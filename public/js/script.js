$(document).ready(function(){

    // Création de la carte avec un fond de carte OSM centrée sur chatellerault
    // 13 : c'est le zoom entre minZoom et maxZoom (cf après)
    let myMap = L.map('mapid').setView([46.816487, 0.548146], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        maxZoom: 20
    }).addTo(myMap);


    // récupération des éléments à afficher (envoi via Twig)-----------------------

    /**
     * Ajoute un groupe de marqueurs (bilbiothèque Leaflet)
     * créé à partir d'un élément HTML qui a reçu des données de Symfony
     * à un objet qui sera utilisé pour afficher la gestion des calques
     * @param elementsToShowElt élément HTML qui contient les données en attribut
     */
    function createObjetFromElementsToShowElt(elementsToShowElt)
    {
        let eltsToShow = JSON.parse(elementsToShowElt[0].attributes[1].value);
        let calqueId = eltsToShow[0].calqueId;
        let calqueNom = eltsToShow[0].calqueNom;

        let markersTab = [];
        for (let i = 0; i < eltsToShow.length; i++) {
            // création des marqueurs pour chaque élément
            let marker = L.marker([eltsToShow[i].latitude, eltsToShow[i].longitude]).bindPopup(eltsToShow[i].texte);
            markersTab.push(marker);
        }
        let markersGroup = L.layerGroup(markersTab);

        // ajout du couple nom du calque / "groupe de marqueurs à afficher" sur ce calque
        calquesObjet[calqueNom] = markersGroup;
    }
    // récupération des éléments à afficher
    var erEltsToShowElt = $('.erEltsToShow');
    var autoEltsToShowElt = $('.autoEltsToShow');
    var piEltsToShowElt = $('.piEltsToShow');

    //pour ajouter un calque il faut un objet contenant des couples nom du calque / "groupe de marqueurs pour un calque"
    var calquesObjet = {};

    // création des groupes de marqueurs par calque
    createObjetFromElementsToShowElt(erEltsToShowElt);
    createObjetFromElementsToShowElt(autoEltsToShowElt);
    createObjetFromElementsToShowElt(piEltsToShowElt);

    // ajout l'icône de gestion des calques à la carte :
    // cette icône contient la liste des calques (avec les points associés)
    L.control.layers(null, calquesObjet, { collapsed:false }).addTo(myMap);

//----------------------------------------------------------------------
// récupération des noms des calques que l'on a passé via twig
/*var clqsElts = document.querySelectorAll('.calques');
*/
//--------------------------------------------------------------------
// Style : Ajout d'éléments pour simplifier et rendre l'affichage plus compréhensible pour les utilisateurs
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
// Recherche et géocodage
let formCommune = document.getElementById("form_commune");

let selectedCommune = formCommune.options[formCommune.selectedIndex];
let communeLat = selectedCommune.getAttribute('latitude')
let communeLong = selectedCommune.getAttribute('longitude')
searchControl = searchAddress(myMap, communeLat, communeLong);


formCommune.addEventListener('change', event => {
    for (prop in searchControl){prop=null}
    selectedCommune = formCommune.options[formCommune.selectedIndex];
    communeLat = selectedCommune.getAttribute('latitude')
    communeLong = selectedCommune.getAttribute('longitude')
    searchAddress(myMap, communeLat, communeLong);
});



//----------------------------------------------------
// Fonction d'ajout d'un marqueur uniquement a une url précise.
var newMarker;
if (window.location.pathname.substr(0,16) == "/map/add-element") {
    function addMarker(e) {
        // Enlève le dernier marqueur si il y'en a eu un
        if (myMap.hasLayer(newMarker)) {
            myMap.removeLayer(newMarker)
        }

        var tab = [];
        tab = e;

        var lat = tab.latlng.lat;
        var long = tab.latlng.lng;

        // Création d'un marqueur et d'une popup
        newMarker = new L.marker(e.latlng);
        var newPopup = new L.popup();
        const data = null;

        // Requête XHTML pour retourner l'adresse selon des cordonnées GPS (lat et long)
        const req = new XMLHttpRequest();
        req.open("GET", "https://api-adresse.data.gouv.fr/reverse/?lon=" + long + "&lat=" + lat + "");
        req.addEventListener("load", function () {
            var jp = JSON.parse(req.responseText);
            let popupContenu = jp.features[0].properties.label;
            newPopup.setContent(popupContenu);

            // Récupération du nom du formulaire parce que l'id des champs lat et long changent en fonction du form
            let formName = document.getElementsByTagName("form")[1].name;

            // Récupères le champs de latitude et le remplis au avec la latitude du point (au clique)
            var inputLat = document.getElementById(formName + '_coordonnees_latitude');
            inputLat.setAttribute("value", jp.features[0].geometry.coordinates[1]);

            // idem
            var inputLong = document.getElementById(formName + '_coordonnees_longitude');
            inputLong.setAttribute("value", jp.features[0].geometry.coordinates[0]);
        });
        req.send(data);

        newMarker.bindPopup(newPopup).addTo(myMap);

    }

    myMap.on("click", addMarker);

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

// boucle sur le select pour avoir chaque options
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
}

//----------------------------------------------------
// fonction de choix des communes

// preChooseCommune();



//-------------------------------------------------------------------------------
// Test affichage des pop up pour les établissements répertoriés
// Normalement on récupère les données en JSON
// let monEr = {
//     id:	1,
//     calque:	"1",
//     type:	"immeuble",
//     latitude: 0.440375,
//     longitude: 46.580224,
//     description:	"description de mon immeuble",
//     photo:	"ceci est une photo",
//     lien:	"../pdf/etat_presence_vierge.pdf" // TODO : mettre dans un emplacement sécurisé
// };
// let monErJSON = JSON.stringify(monEr);
//
// // on créé un marqueur pour l'er
// let erMarker = L.marker([monEr.longitude, monEr.latitude]).bindPopup(`<p>${monEr.photo}</p><p>${monEr.description}</p><a id="lienPdf" href="#">voir le pdf</a><div id="dialog" style="display:none">
//     <div>
//     <iframe src="${monEr.lien}"></iframe>
//     </div>
// </div> `);
// erMarker.addTo(myMap);
//
// let popupElt = document.getElementsByClassName('leaflet-popup');
// console.log('mon pop '+popupElt)
// let lienPdfElt = document.getElementById('lienPdf');
// lienPdfElt.addEventListener('click', e =>{
//     document.getElementById('dialog').dialog();
// })

});