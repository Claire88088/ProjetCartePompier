$(document).ready(function(){
    // taille des icones :
    let iconeHauteur = 50;
    let iconeLargeur = 50;

    // 1. CREATION DE LA CARTE avec un fond de carte OSM centrée sur chatellerault---------------------------------
    let myMap = L.map('mapid', {
        center: [46.816487, 0.548146],
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

    // ajout du système de gestion de l'affichage (calques et éléments)
    addGestionAffichage(eltsToShowElt, calqueList, myMap);


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

    // Par défaut : la commune sélectionnée est Châtellerault
    let communeLat = 46.8156700185;
    let communeLong = 0.552598976936;

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
    // 5. AJOUT D'UN NOUVEAU MARQUEUR
    // Fonction d'ajout d'un marqueur uniquement a une url précise.
    var newMarker;
    var urlAddElement = window.location.pathname.substr(0,16) == "/map/add-element";
    var urlEditElement = window.location.pathname.substr(0,17) == "/map/edit-element";
    if (urlAddElement || urlEditElement) {
        // on adapte le zoom
        myMap.setZoom(18);

        if (urlAddElement) {
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

                // récupération de l'icône choisie
                var iconeLien = $('.dd-selected-image').attr('src');
                var newIcon = L.icon({
                    iconUrl: `..${iconeLien}`,
                    iconSize: [iconeLargeur, iconeHauteur],
                    iconAnchor: [iconeLargeur/2,iconeHauteur]
                });

                // Création et ajout à la carte d'un marqueur avec l'icône choisie
                newMarker = new L.marker(e.latlng, {icon: newIcon}).addTo(myMap);

                // si l'utilisateur choisit une autre icône
                $('.dd-option').on('click', function () {
                    // récupération de l'icône choisie
                    var iconeLien = $('.dd-selected-image').attr('src');
                    var newIcon = L.icon({
                        iconUrl: `..${iconeLien}`,
                        iconSize: [iconeLargeur, iconeHauteur],
                        iconAnchor: [iconeLargeur/2,iconeHauteur]
                    });

                    if (myMap.hasLayer(newMarker)) {
                        myMap.removeLayer(newMarker)
                        // Création d'un nouveau marqueur avec les anciennes coordonnées mais la nouvelle icone
                        newMarker = new L.marker(e.latlng, {icon: newIcon}).addTo(myMap);
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
        //----------------------------------------------------
        // Permet au champs d'upload d'afficher le nom du ficher donné.
        $('.custom-file-input').on('change', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });

        //----------------------------------------------------
        // Cache dans les formulaires la latitude et la longitude
        $("fieldset.form-group").css("display", "none");

        //----------------------------------------------------
        // Récupère le nom du formulaire [1] de la page
        let formName = document.querySelectorAll("form")[1].name;
        // Récupère le select des icones comprenant les options
        let selectIcone = document.getElementById(formName + "_icone");

        // boucle sur le select pour avoir chaque option
        for (let i = 0; i < selectIcone.length; i++) {
            let option = selectIcone[i];
            let valOption = selectIcone[i].label;
            // construction du chemin vers les icones
            cheminIcone = "/MarkersIcons/" + valOption;
            // set l'attribut nécessaire pour afficher les images par la suite
            option.setAttribute('data-imagesrc', cheminIcone)

            // set le nom de l'icone a partir du lien
            let iconeName = option.childNodes[0].textContent
            let splitIconeName = iconeName.split("-")[1]
            option.innerHTML = splitIconeName
        }

        // Fonction jQuery UI pour reconstruire un select option en div et y inclure des images
        $('#' + formName + '_icone').ddslick({
            onSelected: function (selectedData) {
                // remet le nom sur le input icone pour qu'il repasse dans le formulaire avec l'id de l'icone selectionnée
                $("input.dd-selected-value").attr("name", formName + "[icone]")
            }
        });

        //---------------------------------------------------------------------------------------------
        // X. INFORMATION UTILISATEUR si on ne clique pas sur la carte pour choisir un point lors de la création d'un nouvel élément
        $('#' + formName + '_ajouter').click(function(e){
            $('#position').removeClass('alert-danger');
            if (!$('#' + formName + '_coordonnees_longitude')[0].value) {
                $('#position').addClass('alert-danger');
            }
        });
    }
});