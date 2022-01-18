// Création de la carte avec un fond de carte OSM centrée sur chatellerault
let myMap = L.map('mapid').setView([46.816487, 0.548146], 13);

L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 1,
    maxZoom: 20
}).addTo(myMap);

// Icone d'un feu,
var iconFeu = L.icon({
    iconUrl: '../MarkersIcons/icons8-gaz-24.png',
    iconSize: [35, 39]
});

// Marqueurs de ville dans cities
var littleton = L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.');
var littleton2 = L.marker([39.61, 40.02]).bindPopup('Test');
var cities = L.layerGroup([littleton, littleton2]);


// Création d'un calque
var overlayMapsT = [];
var overlayMapsO = {};
var city = [];

// Test de création d'un tableau de points pour layers.
var keys = Object.keys(cities._layers);
for (let i=0; i < keys.length; i++) {

    city[i] = cities._layers[keys[i]];

}

var clq = document.querySelectorAll('.calques');
for (let i = 0; i < clq.length; i++) {
    clqs = clq[i].attributes[1].value;
    overlayMapsT[i] = clqs;
    overlayMapsO[overlayMapsT[i]] = cities;
}

L.control.layers(null, overlayMapsO /* affichage des calques en continu, { collapsed:false } */).addTo(myMap);

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

// Recherche
const apiKey = "AAPK0ee63466d5eb4011b7e5a901086f02affTxglD9L_jLQVyX8dX6eIwNyVBIlFsfE4_Xq4enRxRLVGgBDBkZ5tDkOP-M_cf5W";
const searchControl = L.esri.Geocoding.geosearch({
    // Position de la barre de recherche
    position: "topleft",
    placeholder: "Entrez une adresse à rechercher",
    useMapBounds: true,
    providers: [L.esri.Geocoding.arcgisOnlineProvider({
        countries: "FR",
        apikey: apiKey,
        nearby: {
            lat: 46.816487,
            lng: 0.548146
        },
    })]
}).addTo(myMap);

const results = L.layerGroup().addTo(myMap);

searchControl.on("results", (data) => {
    results.clearLayers();
    for (let i = data.results.length - 1; i >= 0; i--) {
        const lngLatString = `${Math.round(data.results[i].latlng.lng * 100000)/100000}, ${Math.round(data.results[i].latlng.lat * 100000)/100000}`;
        const marker = L.marker(data.results[i].latlng, {icon: iconFeu});
        marker.bindPopup(`<b>${lngLatString}</b><p>${data.results[i].properties.LongLabel}</p>`)
        results.addLayer(marker);
        marker.openPopup();
    }
})

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
            var inputLat = document.getElementById(formName+'_coordonnees_latitude');
            inputLat.setAttribute("value", jp.features[0].geometry.coordinates[1]);

            // idem
            var inputLong = document.getElementById(formName+'_coordonnees_longitude');
            inputLong.setAttribute("value", jp.features[0].geometry.coordinates[0]);
        });
        req.send(data);

        newMarker.bindPopup(newPopup).addTo(myMap);

    }
    myMap.on("click", addMarker);

}

// Permet au champs d'upload d'afficher le nom du ficher donné.
$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});

/*
// Remplis la liste d'icones.
let divIcones = document.querySelectorAll('.icones');
let ulDropDownIcones = document.getElementById("dropdown-ul");
let whiteB = document.getElementById("dropdownMenuButton")
whiteB.style.background = "white";
whiteB.style.color = "black"

for (let i = 0; i < divIcones.length; i++) {
    divIconeValeur = divIcones[i].attributes[1].value;
    cheminIcone = "/MarkersIcons/"+divIconeValeur;

    let liIcone = document.createElement("li");
    let imgIcone = document.createElement("img");
    let hr = document.createElement("hr")

    hr.style.margin = "0";

    liIcone.setAttribute('id', "liIconeId"+i)
    liIcone.style.textAlign = "center";
    liIcone.style.padding = "10px 0px 10px 0px"
    imgIcone.setAttribute('src', cheminIcone);

    ulDropDownIcones.appendChild(liIcone);
    liIcone.appendChild(imgIcone);

    if (i !== divIcones.length-1 ) {
        liIcone.insertAdjacentElement("afterend", hr)
    }
}

$("#dropdown-ul").children('li').hover(function() {
    $(this).css("background-color", "lightgrey")
}, function() {
    $(this).css("background-color", "white")
});
*/

