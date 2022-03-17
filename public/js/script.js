$(document).ready(function(){
    // taille des icones :
    let iconeHauteur = 50;
    let iconeLargeur = 50;

    // 1. CREATION DE LA CARTE avec un fond de carte OSM centrée sur la commune par défaut---------------------------------
    let defaultLatAndLongElt = $('.defaultLatAndLong');
    let defaultLatAndLong = JSON.parse(defaultLatAndLongElt[0].attributes[1].value);
    let defaultLat = defaultLatAndLong[0];
    let defaultLong = defaultLatAndLong[1];

    let myMap = L.map('mapid', {
        center: [defaultLat, defaultLong],
        zoom: 13
    });

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20
    }).addTo(myMap);


    // 2. AFFICHAGE DES ELEMENTS EXISTANTS sur les calques---------------------------------------------------------------------
    // récupération des éléments à afficher (transmis via Twig)
    var eltsToShowElt = $('.allEltsToShow');
    var calqueList = $('.calquesNomsList');


    // Animation du display des calques avec leurs éléments
    var divCalquesList = $('.listCalqueElem')
    var divTypesElementCalque = $('.typeElementCalque')
    for (let i=0; i < divCalquesList.length; i++) {

        divCalquesList[i].addEventListener("mouseover", function (event) {
            divTypesElementCalque[i].style.display = 'block'
        })
        divTypesElementCalque[i].addEventListener("mouseover", function (event) {
            divTypesElementCalque[i].style.display = 'block'
        })
        divCalquesList[i].addEventListener("mouseout", function (event) {
            divTypesElementCalque[i].style.display = 'none'
        })
        divTypesElementCalque[i].addEventListener("mouseout", function (event) {
            divTypesElementCalque[i].style.display = 'none'
        })
    }

    // ajout du système de gestion de l'affichage (calques et éléments) (permet aussi de récupérer les données d'affichage des calques : groupes de marqueurs et clusters)
    let affichageCalquesTab = addGestionAffichage(eltsToShowElt, calqueList, myMap);


    //--------------------------------------------------------------------
    // 3. STYLISATION DE L'"ICONE" de gestion des calques
    var bCalques = document.getElementsByClassName('leaflet-control-layers-overlays')[0];

    var titreCalque = document.createElement('label');
    titreCalque.style.textAlign = 'center';
    titreCalque.style.fontSize = '.9rem';
    titreCalque.appendChild(document.createTextNode('Affichage des calques'));

    var ligne = document.createElement('hr');
    ligne.style.margin = 'auto';
    titreCalque.appendChild(ligne);

    bCalques.prepend(titreCalque);

    //----------------------------------------------------
    // 4. RECHERCHE D'UNE ADRESSE
    // Pour la première commune selectionnée
    let formCommune = document.getElementById("form_commune");

    // Par défaut :
    let communeLat = defaultLat;
    let communeLong = defaultLong;

    // on créé le form de l'API et on recherche l'adresse
    searchAddress(myMap, communeLat, communeLong);

    // Au changement de commune dans la liste
    formCommune.addEventListener('change', event => {
        let geocoderControl = document.getElementsByClassName('geocoder-control leaflet-control')[0];
        geocoderControl.parentNode.removeChild(geocoderControl);

        let selectedCommune = formCommune.options[formCommune.selectedIndex];
        communeLat = selectedCommune.getAttribute('latitude')
        communeLong = selectedCommune.getAttribute('longitude')
        searchAddress(myMap, communeLat, communeLong);
    });

    //----------------------------------------------------
    // Permet au champs d'upload d'afficher le nom du ficher donné.
    $('.custom-file-input').on('change', function (event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });

    //----------------------------------------------------
    // 5. AJOUT D'UN NOUVEAU MARQUEUR
    // Fonction d'ajout d'un marqueur uniquement a une url précise.
    var newMarker;
    var urlAddElement = window.location.pathname.substr(0,16) === "/map/add-element";
    var urlEditElement = window.location.pathname.substr(0,17) === "/map/edit-element";
    if (urlAddElement || urlEditElement) {
        // on adapte le zoom
        myMap.setZoom(18);

        // on affiche le calque (groupes de marqueurs et cluster) sur lequel on veut créer un point
        let calqueNomElt = $('.calqueNom');
        let calqueNom = calqueNomElt[0].attributes[1].value;

        let markersGroupTab = affichageCalquesTab[0];
        let clustersTab = affichageCalquesTab[1];

        markersGroupTab[calqueNom].addTo(myMap);
        myMap.addLayer(clustersTab[calqueNom]);

        //----------------------------------------------------
        // Récupère le nom du formulaire [1] de la page
        let formName = document.querySelectorAll("form")[1].name;
        // Récupère le select des icones comprenant les options
        let selectIcone = document.getElementById(formName + "_icone");

        // boucle sur le select pour avoir chaque option
        for (let i = 0; i < selectIcone.length; i++) {
            let option = selectIcone[i];
            let valOption = selectIcone[i].label;

            let unicode = option.attributes[1].value
            // construction du chemin vers les icones
            let cheminIcone = "/MarkersIcons/" + valOption;

            // set l'attribut nécessaire pour afficher les images par la suite
            option.setAttribute('data-imagesrc', cheminIcone)
            option.setAttribute('data-description', unicode)

            // set le nom de l'icone a partir du lien
            let iconeName = option.childNodes[0].textContent
            let splitIconeName = iconeName.split("-")[2].split('.')[0]
            option.innerHTML = splitIconeName
        }

        // Fonction jQuery UI pour reconstruire un select option en div et y inclure des images
        $('#' + formName + '_icone').ddslick({
            onSelected: function (selectedData) {
                // remet le nom sur le input icone pour qu'il repasse dans le formulaire avec l'id de l'icone selectionnée
                $("input.dd-selected-value").attr("name", formName + "[icone]")
                $(".dd-selected-image").attr("unicode", selectedData.selectedData.description)
            }
        });

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

                // Création des url pour les icones et pour les fontfaces
                let urlISplit = iconeLien.split('/')[2].split('-');
                let urlISplit2 = urlISplit[2].split('.')

                let urlFontFace = "/MarkersIcons/" + urlISplit[1] + "-" + urlISplit2[0]
                console.log(iconeLien, iconeUnicode)

                // Création d'un objet font-face correspondant à l'icone choisie dans la liste
                let font = new FontFace("fontello", 'url(\'..' + urlFontFace + '.woff\') format(\'woff\')');
                font.load().then(function (loadedFont) {
                    if (document.fonts.has(loadedFont)) {
                        document.fonts.delete(loadedFont);
                    }
                    document.fonts.add(loadedFont);
                }).catch(function (error) {
                    console.log(error)
                });

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
                    iconeLien = $('.dd-selected-image').attr('src');
                    iconeUnicode = $('.dd-selected-image').attr('unicode')

                    urlISplit = iconeLien.split('/')[2].split('-');
                    urlISplit2 = urlISplit[2].split('.')

                    urlFontFace = "/MarkersIcons/" + urlISplit[1] + "-" + urlISplit2[0]

                    let font = new FontFace("fontello", 'url(\'..' + urlFontFace + '.woff\') format(\'woff\')');
                    font.load().then(function (loadedFont) {
                        if (document.fonts.has(loadedFont)) {
                            document.fonts.delete(loadedFont);
                        }
                        document.fonts.add(loadedFont);
                    }).catch(function (error) {
                        console.log(error)
                    });

                    let currentCouleur = inputCouleur.attributes[11].value
                    console.log(currentCouleur)

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
                    inputLat.setAttribute("value", jp.features[0].geometry.coordinates[1]);

                    // idem
                    var inputLong = document.getElementById(formName + '_coordonnees_longitude');
                    inputLong.setAttribute("value", jp.features[0].geometry.coordinates[0]);

                    $('#loader').hide();
                });
                req.send(data);

                newMarker.bindPopup(newPopup);

            }
            myMap.on("click", addMarker);
        }
        if (urlEditElement) {
            let currentUrl = window.location.href
            // Obligation de parsé sinon on récupère un string qui n'est donc pas comparable à l'id de l'élément qui lui est un int.
            let idCurrentElement = currentUrl.split('-')[2]

            let Element = document.getElementById(idCurrentElement)
            let currentCouleurElement = Element.attributes[2].value.split(":")[1].split(';')[0]

            $("#" + formName + "_couleur")[0].value = currentCouleurElement

            $("#" + formName + "_couleur").on('input', function () {
                let inputCouleurVal = this.value
                Element.style.color = inputCouleurVal
            })

            $('.dd-option').on('click', function () {
                iconeLien = $('.dd-selected-image').attr('src');
                // On va rechercher le nouvel unicode
                iconeUnicode = $('.dd-selected-image').attr('unicode')

                urlISplit = iconeLien.split('/')[2].split('-');
                urlISplit2 = urlISplit[2].split('.')

                urlFontFace = "/MarkersIcons/" + urlISplit[1] + "-" + urlISplit2[0]

                let font = new FontFace("fontello", 'url(\'..' + urlFontFace + '.woff\') format(\'woff\')');
                font.load().then(function (loadedFont) {
                    document.fonts.add(loadedFont);
                }).catch(function (error) {
                    console.log(error)
                });

                // on l'applique avec le innerHTML (non faisable avec textContent ou innerText car ces propriétés ne parsent pas en contenu HTML et donc l'unicode s'affiche en texte)
                Element.innerHTML = iconeUnicode
            })
        }

        //---------------------------------------------------------------------------------------------
        // 6. INFORMATION UTILISATEUR si on ne clique pas sur la carte pour choisir un point lors de la création d'un nouvel élément
        $('#' + formName + '_ajouter').click(function(e){
            $('#position').removeClass('alert-danger');
            if (!$('#' + formName + '_coordonnees_longitude')[0].value) {
                $('#position').addClass('alert-danger');
            }
        });

        //---------------------------------------------------------------------------------------------
        // Affiche les noms des photos et liens dans leur champs correspondant, par défaut symfony les cache.
        let divPhoto = $('.photo');
        let divlien =  $('.lien');

        let photoNom = divPhoto[0].attributes[1].value;
        let lienNom = divlien[0].attributes[1].value;

        if (photoNom) {
            let uploadPhoto = document.getElementById(formName + "_photo");
            uploadPhoto.nextSibling.textContent = photoNom
        }
        if (lienNom) {
            let uploadLien = document.getElementById(formName + "_lien");
            uploadLien.nextSibling.textContent = lienNom
        }

        //---------------------------------------------------------------------------------------------
        // X. INFORMATION UTILISATEUR si on ne clique pas sur la carte pour choisir un point lors de la création d'un nouvel élément
        $('#' + formName + '_ajouter').click(function(e){
            $('#position').removeClass('alert-danger');
            if (!$('#' + formName + '_coordonnees_longitude')[0].value) {
                $('#position').addClass('alert-danger');
            }
        });
    }
})
