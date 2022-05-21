$(document).ready(function(){
    // récupération des données stockées dans le navigateur
    let latToCenter = sessionStorage.getItem('latToCenter');
    let longToCenter = sessionStorage.getItem('longToCenter');
    let zoomToPut = sessionStorage.getItem('zoomToPut');
    let calqueWithAddOrEdit = sessionStorage.getItem('calqueWithAddOrEdit');
    let searchZoom = sessionStorage.getItem('searchZoom');

    // taille des icones :
    let iconeHauteur = 50;
    let iconeLargeur = 50;
    let defaultZoom = 13;

    // 1. CREATION DE LA CARTE avec un fond de carte OSM---------------------------------
    // gestion du zoom
    if (latToCenter) {
        var zoom = zoomToPut;
        sessionStorage.removeItem('zoomToPut');
    } else if (searchZoom) {
        var zoom = searchZoom;
        sessionStorage.removeItem('searchZoom');
    } else {
        var zoom = defaultZoom;
    }

    // gestion du centrage de la carte
    if (latToCenter) {
        var centerLat = latToCenter;
        var centerLong = longToCenter;
        sessionStorage.removeItem('latToCenter');
        sessionStorage.removeItem('longToCenter');
    } else {
        let defaultLatAndLongElt = $('.defaultLatAndLong');
        let defaultLatAndLong = JSON.parse(defaultLatAndLongElt[0].attributes[1].value);
        var centerLat = defaultLatAndLong[0];
        var centerLong = defaultLatAndLong[1];
    }

    // création de la carte
    let myMap = L.map('mapid', {
        center: [centerLat, centerLong],
        zoom: zoom
    });

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20
    }).addTo(myMap);


    // 2. AFFICHAGE DES ELEMENTS EXISTANTS sur les calques---------------------------------------------------------------------
    // récupération des éléments à afficher (transmis via Twig)
    let eltsToShowElt = $('.allEltsToShow');
    let eltsToShow = JSON.parse(eltsToShowElt[0].attributes[1].value);
    let calquesList = $('.calquesNomsList');
    let calquesNoms = JSON.parse(calquesList[0].attributes[1].value);

    // ajout du système de gestion de l'affichage (calques et éléments) (permet aussi de récupérer les données d'affichage des calques : groupes de marqueurs et clusters)
    let affichageCalquesTab = addGestionAffichage(eltsToShow, calquesNoms, myMap);
    var markersGroupTab = affichageCalquesTab[0];
    var clustersTab = affichageCalquesTab[1];

    // Si on n'est pas connecté : on affiche par défaut le 1er calque stocké en BDD
    let divIsConnected = $('.isConnected');
    let isConnected = divIsConnected[0].attributes[1].value;
    if (!isConnected) {
        let defaultCalque = calquesNoms[0];
        let clustersTab = affichageCalquesTab[1];
        markersGroupTab[defaultCalque].addTo(myMap);
        myMap.addLayer(clustersTab[defaultCalque]);
    }

    // si on ajoute ou modifie un point
    if (calqueWithAddOrEdit) {
        // on affiche le calque (groupes de marqueurs et cluster) sur lequel on a créé un point
        markersGroupTab[calqueWithAddOrEdit].addTo(myMap);
        myMap.addLayer(clustersTab[calqueWithAddOrEdit]);
        sessionStorage.removeItem('calqueWithAddOrEdit');
    }

    // Affiche le calque sur lequel on veut centrer un élément
    $(".afficheCalqueElem").on("click", function (e) {
        let nomCalqueTypeElem = e.target.attributes[1].value
        let previousCalqueAfficheElt = sessionStorage.getItem('calqueAfficheElt')

        if (previousCalqueAfficheElt) {
            markersGroupTab[previousCalqueAfficheElt].remove(myMap);
            myMap.addLayer(clustersTab[previousCalqueAfficheElt]);
        }

        markersGroupTab[nomCalqueTypeElem].addTo(myMap);
        myMap.addLayer(clustersTab[nomCalqueTypeElem]);
        sessionStorage.setItem('calqueAfficheElt', nomCalqueTypeElem);
        centrePeageNord()
        styleGestionCalques()
    })

    // Centre sur l'élément quand on clique dessus.
    $(".elementClique").css("cursor", "pointer")
    $(".elementClique").on("click", function (e) {
        let latitude =  e.target.attributes[1].value
        let longitude = e.target.attributes[2].value
        myMap.setView([latitude, longitude], 17)
    })

    // Centre sur le péage nord de l'autoroute A10 lors du clique sur le calque "Autoroute"
    function centrePeageNord() {
        let controlCalques = $(".leaflet-control-layers-overlays")
        let checkboxCalque;
        for (let i = 0; i < controlCalques[0].childElementCount; i++) {
            checkboxCalque = controlCalques[0].childNodes[i].childNodes[0].childNodes[0]
            checkboxCalque.addEventListener('change', function () {
                if (this.checked && this.nextSibling.textContent.trim() == "Autoroute") {
                    myMap.setView([46.83533, 0.531051], 17)
                }
            });
        }
    }
    centrePeageNord()

    // 3. STYLISATION DE L'"ICONE" de gestion des calques----------------------------
    function styleGestionCalques() {
        var bCalques = document.getElementsByClassName('leaflet-control-layers-overlays')[0];

        var titreCalque = document.createElement('label');
        titreCalque.style.textAlign = 'center';
        titreCalque.style.fontSize = '.9rem';
        titreCalque.appendChild(document.createTextNode('Affichage des calques'));

        var ligne = document.createElement('hr');
        ligne.style.margin = 'auto';
        titreCalque.appendChild(ligne);

        bCalques.prepend(titreCalque);
    }
    styleGestionCalques()

    // 4. RECHERCHE D'UNE ADRESSE----------------------------------------
    // Pour la première commune selectionnée
    let formCommune = document.getElementById("form_commune");

    // on créé le form de l'API et on recherche l'adresse
    searchAddress(myMap, centerLat, centerLong);

    // Au changement de commune dans la liste
    formCommune.addEventListener('change', event => {
        // raz du zoom
        myMap.setZoom(13);

        let geocoderControl = document.getElementsByClassName('geocoder-control leaflet-control')[0];
        geocoderControl.parentNode.removeChild(geocoderControl);

        let selectedCommune = formCommune.options[formCommune.selectedIndex];
        communeLat = selectedCommune.getAttribute('latitude')
        communeLong = selectedCommune.getAttribute('longitude')
        searchAddress(myMap, communeLat, communeLong);
    });


    // 5. AJOUT D'UN NOUVEAU MARQUEUR-----------------------------------------
    // Fonction d'ajout d'un marqueur uniquement a une url précise.
    var newMarker;

    var urlAddElement = window.location.pathname.includes("add-element");
    var urlEditElement = window.location.pathname.includes("edit-element");

    if (urlAddElement || urlEditElement) {
        // on adapte le zoom
       //myMap.setZoom(17);

        // on affiche le calque (groupes de marqueurs et cluster) sur lequel on veut créer un point
        let calqueNomElt = $('.calqueNom');
        let calqueNom = calqueNomElt[0].attributes[1].value;
        sessionStorage.setItem('calqueWithAddOrEdit', calqueNom);

        let markersGroupTab = affichageCalquesTab[0];
        let clustersTab = affichageCalquesTab[1];

        markersGroupTab[calqueNom].addTo(myMap);
        myMap.addLayer(clustersTab[calqueNom]);

        //----------------------------------------------------
        // Récupère le nom du formulaire [1] de la page
        let formName = document.querySelectorAll("form")[1].name;

        let inputCouleur = document.getElementById(""+formName+"_couleur")
        let inputCouleurVal = inputCouleur.value

        if (urlAddElement) {
            // Ajout marqueur
            function addMarker(e) {
                // Enlève le dernier marqueur si il y'en a eu un
                if (myMap.hasLayer(newMarker)) {
                    myMap.removeLayer(newMarker)
                }

                // enlève l'alerte si il y en avait une
                $('#position').removeClass('alert-danger');

                var tab = [];
                tab = e;

                var lat = tab.latlng.lat;
                var long = tab.latlng.lng;

                // Récupération de l'icône choisie et notamment de son lien
                let iconeLien = $('.dd-selected-image').attr('src');
                let iconeUnicode = $('.dd-selected-image').attr('unicode');

                iconeFontFace(iconeLien)

                // Création et ajout à la carte d'un marqueur avec l'icône choisie
                newMarker = L.marker([lat, long], {
                    icon: L.divIcon({
                        html: "<i class='demo-icon' style='color:"+inputCouleurVal+";'>" + iconeUnicode + "</i>",
                        iconSize: [iconeLargeur, iconeHauteur],
                        iconAnchor: [iconeLargeur / 2, iconeHauteur],
                        popupAnchor: [0, -32]
                    })
                }).addTo(myMap);

                // Changement en temps réel de la couleur de l'icone
                $("#" + formName + "_couleur").on('input', function () {
                    if (myMap.hasLayer(newMarker)) {
                        myMap.removeLayer(newMarker)
                    }
                    inputCouleurVal = this.value
                    newMarker = L.marker([lat, long], {
                        icon: L.divIcon({
                            html: "<i class='demo-icon' style='color:"+inputCouleurVal+";'>" + iconeUnicode + "</i>",
                            iconSize: [iconeLargeur, iconeHauteur],
                            iconAnchor: [iconeLargeur / 2, iconeHauteur],
                            popupAnchor: [0, -32]
                        })
                    }).addTo(myMap);
                });

                // si l'utilisateur choisit une autre icône
                $('.dd-option').on('click', function () {
                    // On recrée une icone avec son lien
                    let iconeLien = $('.dd-selected-image').attr('src');
                    let iconeUnicode = $('.dd-selected-image').attr('unicode');

                    iconeFontFace(iconeLien)

                    let currentCouleur = inputCouleur.attributes[11].value

                    if (myMap.hasLayer(newMarker)) {
                        myMap.removeLayer(newMarker)
                        // Création d'un nouveau marqueur avec les anciennes coordonnées mais la nouvelle icone
                        newMarker = L.marker([lat, long], {
                            icon: L.divIcon({
                                html: "<i class='demo-icon' style='color:"+currentCouleur+";'>" + iconeUnicode + "</i>",
                                iconSize: [iconeLargeur, iconeHauteur],
                                iconAnchor: [iconeLargeur / 2, iconeHauteur],
                                popupAnchor: [0, -32]
                            })
                        }).addTo(myMap);
                    }
                })

                // création d'une popup
                var newPopup = new L.popup();
                const data = null;

                // Requête XHTML pour retourner l'adresse selon des coordonnées GPS (lat et long)
                const req = new XMLHttpRequest();
                req.open("GET", "https://api-adresse.data.gouv.fr/reverse/?lon=" + long + "&lat=" + lat + "");

                req.addEventListener("load", function () {
                    var jp = JSON.parse(req.responseText);
                    let popupContenu = jp.features[0].properties.label;
                    newPopup.setContent(popupContenu);

                    // Récupération du nom du formulaire parce que l'id des champs lat et long changent en fonction du form
                    let formName = document.getElementsByTagName("form")[1].name;

                    // Récupère le champ de latitude et le remplit avec la latitude du point (au clique)
                    var inputLat = document.getElementById(formName + '_coordonnees_latitude');
                    //inputLat.setAttribute("value", jp.features[0].geometry.coordinates[1]);
                    inputLat.setAttribute("value", lat);

                    // idem
                    var inputLong = document.getElementById(formName + '_coordonnees_longitude');
                    //inputLong.setAttribute("value", jp.features[0].geometry.coordinates[0]);
                    inputLong.setAttribute("value", long);

                    $('#loader').hide();
                });
                req.send(data);

                newMarker.bindPopup(newPopup).openPopup();

                // gestion de l'affichage des pop up
                setTimeout(function () {
                    newMarker.closePopup()
                }, 2000)

                // on stocke les coordonnées du nouveau marqueur dans le navigateur
                let currentZoom = myMap.getZoom();
                sessionStorage.setItem('latToCenter', lat);
                sessionStorage.setItem('longToCenter', long);
                sessionStorage.setItem('zoomToPut', currentZoom);
            }
            myMap.on("click", addMarker);
        }

        if (urlEditElement) {
            // on stocke les lat et long de l'élément modifié dans le navigateur (pour pouvoir centrer la carte)
            let editEltLatElt = $('.editEltLat');
            let editEltLongElt = $('.editEltLong');

            let editEltLat = editEltLatElt[0].attributes[1].value;
            let editEltLong = editEltLongElt[0].attributes[1].value;

            sessionStorage.setItem('latToCenter', editEltLat);
            sessionStorage.setItem('longToCenter', editEltLong);
            sessionStorage.setItem('zoomToPut', 18);

            // onrécupère l'id de l'élement
            let currentUrl = window.location.href
            let idCurrentElement = currentUrl.split('-')[2]

            let Element = document.getElementById(idCurrentElement)
            let currentCouleurElement = Element.attributes[2].value.split(":")[1].split(';')[0]

            $("#" + formName + "_couleur")[0].value = currentCouleurElement

            $("#" + formName + "_couleur").on('input', function () {
                let inputCouleurVal = this.value
                Element.style.color = inputCouleurVal
            })

            $('.dd-option').on('click', function () {
                let iconeLien = $('.dd-selected-image').attr('src');
                let iconeUnicode = $('.dd-selected-image').attr('unicode');

                iconeFontFace(iconeLien)

                // on l'applique avec le innerHTML (non faisable avec textContent ou innerText car ces propriétés ne parsent pas en contenu HTML et donc l'unicode s'affiche en texte)
                Element.innerHTML = iconeUnicode
            })
        }
    }
})
