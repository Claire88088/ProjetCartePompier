// Création de la carte avec un fond de carte OSM centrée sur chatellerault
// 13 : c'est le zoom entre minZoom et maxZoom (cf après)
let myMap = L.map('mapid').setView([46.816487, 0.548146], 13);

L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 1,
    maxZoom: 20
}).addTo(myMap);

//---------------------------------------------------------------
// Icone d'un feu,
var iconFeu = L.icon({
    iconUrl: '../MarkersIcons/icons8-gaz-24.png',
    iconSize: [35, 39]
});


//-----------------------------------------------------------------------
// affichage d'un calque
// on créé des marqueurs
var littleton = L.marker([46.580224, 0.340375]).bindPopup('This is Littleton, CO.');
var littleton2 = L.marker([46.580224, 0.341485]).bindPopup('Test');
// on créé un "groupe de marqueurs pour un calque" cad un ensemble de marqueurs qui appartiendront au même calque
var citiesGroup = L.layerGroup([littleton, littleton2]); // groupe de marqueurs à afficher ensuite dans un calque


// récupération des données à afficher avec des marqueurs sur le calque correspondant (test avec envoi des données via Twig = via des div)
// on a besoin au minimum des lat et long des points à afficher pour créer des marqueurs
var autorouteElts = document.querySelectorAll('.elementsAutoroute');

let marqueursTab = [];

// on créé les marqueurs correspondant
for (let i = 0; i < autorouteElts.length; i++) {
    latlongAutoElt = autorouteElts[i].attributes[1].value;
    //console.log(latlongAutoElt.split(" "));

    marqueurTest = L.marker(latlongAutoElt.split(" ")).bindPopup('marqueur issu de la BD').addTo(myMap);
    marqueursTab.push(marqueurTest);
}

// on créé un "groupe de marqueurs pour un calque"
let testGroup = L.layerGroup(marqueursTab);

// // Création d'un calque
// var overlayMapsT = [];
// var overlayMapsO = {};
// var city = [];

// // Test de création d'un tableau de points pour layers.
// var keys = Object.keys(cities._layers);
// for (let i=0; i < keys.length; i++) {
//
//     city[i] = cities._layers[keys[i]];
//
// }

// var clq = document.querySelectorAll('.calques');
// for (let i = 0; i < clq.length; i++) {
//     clqs = clq[i].attributes[1].value;
//     overlayMapsT[i] = clqs;
//     overlayMapsO[overlayMapsT[i]] = cities;
// }
//
// L.control.layers(null, overlayMapsO /* affichage des calques en continu, { collapsed:false } */).addTo(myMap);

//----------------------------------------------------------------------
// récupération des noms des calques que l'on a passé via twig
var clqsElts = document.querySelectorAll('.calques');

//pour ajouter un calque il faut un objet contenant des couples nom du calque / "groupe de marqueurs pour un calque"
var calquesTab = [];
var calquesObjet = {};

for (let i = 0; i < clqsElts.length; i++) {
    nomClq = clqsElts[i].attributes[1].value;

    calquesTab[i] = nomClq;
    calquesObjet[calquesTab[i]] = testGroup;
    //overlayMapsT[i] = nomClq; // tableau de noms de calques
    //overlayMapsO[overlayMapsT[i]] = citiesGroup; // objet qui contient : pour chaque calque -> un groupe de villes associé
}

// pour ajouter l'icône de gestion des calques à la carte :
// cette icône contient la liste des calques (avec les points associés)
//L.control.layers(null, overlayMapsO /* affichage des calques en continu, { collapsed:false } */).addTo(myMap);
L.control.layers(null, calquesObjet /* affichage des calques en continu, { collapsed:false } */).addTo(myMap);


//--------------------------------------------------------------------
// Ajout d'éléments pour simplifier et rendre l'affichage plus compréhensible pour les utilisateurs
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
searchAddress(myMap);


// Test de récupération de données envoyées en JSON par l'appli
// on récupère les données envoyées à l'url 'http://127.0.0.1:8000/testJson' via la méthode envoiEnJSON du MapController
fetch(`http://127.0.0.1:8000/testJson`).then(function(response) {
    response.text().then(function(text) {
        //console.log(text);
    });
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
            let formName = document.getElementsByTagName("form")[0].name;

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


// Permet au champs d'upload d'afficher le nom du ficher donné.
    $('.custom-file-input').on('change', function (event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });

// Cache dans les formulaires la latitude et la longitude
    $("fieldset.form-group").css("display", "none");

// Récupère le nom du formulaire courant
    let formName = document.getElementsByTagName("form")[0].name;
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

preChooseCommune();

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