// création de la carte avec un fond de carte OSM
let myMap = L.map('mapid').setView([46.580224, 0.340375], 13);

L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 1,
    maxZoom: 20
}).addTo(myMap);

var littleton = L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.');
var littleton2 = L.marker([39.61, 40.02]).bindPopup('Test');
var cities = L.layerGroup([littleton, littleton2]);


/* var divAdresse = document.querySelectorAll('.adresses');
var adresses = divAdresse.forEach(function(adrs) {
    adresse = adrs.attributes[1].value
}); */

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

// Géocodage
const apiKey = "AAPK0ee63466d5eb4011b7e5a901086f02affTxglD9L_jLQVyX8dX6eIwNyVBIlFsfE4_Xq4enRxRLVGgBDBkZ5tDkOP-M_cf5W";
const searchControl = L.esri.Geocoding.geosearch({
    position: "topleft",
    placeholder: "Entrez une adresse à rechercher",
    useMapBounds: false,
    providers: [L.esri.Geocoding.arcgisOnlineProvider({
        apikey: apiKey,
        nearby: {
            lat: -33.8688,
            lng: 151.2093
        },
    })]
}).addTo(myMap);

const results = L.layerGroup().addTo(myMap);

searchControl.on("results", (data) => {
    results.clearLayers();
    for (let i = data.results.length - 1; i >= 0; i--) {
        const lngLatString = `${Math.round(data.results[i].latlng.lng * 100000)/100000}, ${Math.round(data.results[i].latlng.lat * 100000)/100000}`;
        const marker = L.marker(data.results[i].latlng);
        marker.bindPopup(`<b>${lngLatString}</b><p>${data.results[i].properties.LongLabel}</p>`)
        results.addLayer(marker);
        marker.openPopup();
    }
})

/*
// Affichage d'un marqueur à partir du formulaire de recherche par adresse
let form = document.getElementById('rechercheForm');

form.addEventListener('submit', event => {
    event.preventDefault();

    let adresseRecherche = document.getElementById('form_adresseRecherche').value;

    let selectElt = document.getElementById('form_commune');
    let codePostal = selectElt.options[selectElt.selectedIndex].value;

    // on cherche les coordonnées GPS correspondant à l'adresse
    coordFromAddressWithFetch(adresseRecherche, codePostal).then(coord => {

        // on affiche l'adresse à l'aide d'un marqueur
        let myMarker = L.marker([coord[1], coord[0]]).addTo(myMap)
    });
});*/

// Fonction d'ajout d'un marqueur uniquement a une url précise.
var newMarker;
if (window.location.pathname == "/map/add-element-1") {
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

            var inputAdresse = document.getElementById('etablissement_repertorie_adresse');
            inputAdresse.setAttribute("value", jp.features[0].properties.name);

            var inputLat = document.getElementById('etablissement_repertorie_latitude');
            inputLat.setAttribute("value", jp.features[0].geometry.coordinates[0]);

            var inputLong = document.getElementById('etablissement_repertorie_longitude');
            inputLong.setAttribute("value", jp.features[0].geometry.coordinates[1]);
        });
        req.send(data);

        newMarker.bindPopup(newPopup).addTo(myMap);

    }
    myMap.on("click", addMarker);

}
