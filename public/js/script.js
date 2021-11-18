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

console.log(cities)
console.log(city)

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

/* Test
var buttonAddCalque = document.getElementById("addCalque")
var clicked = false
buttonAddCalque.addEventListener("click", function() {
    clicked = true
    if (clicked == true) {
        var zoneAddCalque = document.getElementById("calque");
        buttonAddCalque.appendChild(zoneAddCalque);
    }
});
*/
for (let i = 0; i < clq.length; i++) {
    clqs = clq[i].attributes[1].value;
    var test = document.getElementById(clqs);

    test.addEventListener('click', function() {
        if (test.attributes[1].value = clqs) {
            console.log('hey')
        }
    })
}


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
});

