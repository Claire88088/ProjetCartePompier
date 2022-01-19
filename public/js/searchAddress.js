/**
 * permet de créer un formulaire ESRI (avec fonction suggestion d'adresse),
 * de rechercher cette adresse
 * et d'ajouter un marqueur et une pop up au niveau de l'adresse trouvée
 * @param myMap carte Leaflet
 */
 function searchAddress(myMap) {
    const apiKey = "AAPK0ee63466d5eb4011b7e5a901086f02affTxglD9L_jLQVyX8dX6eIwNyVBIlFsfE4_Xq4enRxRLVGgBDBkZ5tDkOP-M_cf5W";

    // on créé le formulaire de recherche
    const searchControl = L.esri.Geocoding.geosearch({
        // Position de la barre de recherche
        position: "topleft",
        placeholder: "Entrez une adresse à rechercher",
        useMapBounds: false, // permet de filter les résultats à l'aide du cadre de délimitation statique fourni
        providers: [L.esri.Geocoding.arcgisOnlineProvider({ // utilisation du service de géocodage de ArcGIS
            countries: "FR",
            apikey: apiKey,
            categories: 'Address',
            nearby: {
                lat: 46.816487,
                lng: 0.548146
            },
        })]
    }).addTo(myMap);
    
    // on créé un "groupe de marqueurs affichés sur le même calque" qui recevra les résultats de la recherche
    const results = L.layerGroup().addTo(myMap);
    
    // l'événement "results" a lieu quand le geocoder a trouvé des résultats
    searchControl.on("results", (data) => {
        results.clearLayers();
        for (let i = data.results.length - 1; i >= 0; i--) {
            const lngLatString = `${Math.round(data.results[i].latlng.lng * 100000)/100000}, ${Math.round(data.results[i].latlng.lat * 100000)/100000}`;
            // on créé un marqueur pour l'adresse trouvée et on l'affiche via le layerGroup
            const marker = L.marker(data.results[i].latlng, {icon: iconFeu});
            // on affiche dans la pop up uniquement l'adresse et la ville
            let longLabel = data.results[i].properties.LongLabel;
            let shortLabel = longLabel.split(',');
            let label = shortLabel.splice(0, 3);
    
            marker.bindPopup(`<p>${label}</p>`);
            results.addLayer(marker);
            marker.openPopup();
        }
    });   
}



