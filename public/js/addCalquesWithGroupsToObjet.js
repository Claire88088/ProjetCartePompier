/**
 * Ajoute un groupe de marqueurs (bilbiothèque Leaflet)
 * créé à partir d'un élément HTML qui a reçu des données de Symfony
 * à un objet qui sera utilisé pour afficher la gestion des calques
 * @param elementsToShowElt élément HTML qui contient les données en attribut
 */
function addCalquesWithGroupsToObjet(calquesWithGroupsObjet, elementsToShowElt)
{
    let eltsToShow = JSON.parse(elementsToShowElt[0].attributes[1].value);
    let calqueNom = eltsToShow[0].calqueNom;

    let markersTab = [];
    for (let i = 0; i < eltsToShow.length; i++) {
        // création des icones pour chaque élément
        var eltIcone = L.icon({
            iconUrl: `../MarkersIcons/${eltsToShow[i].lienIcone}`,
            iconSize: [35, 39]
        });

        // création des marqueurs pour chaque élément
        let marker = L.marker([eltsToShow[i].latitude, eltsToShow[i].longitude], {icon: eltIcone}).bindPopup(eltsToShow[i].texte);
        markersTab.push(marker);
    }
    let markersGroup = L.layerGroup(markersTab);

    // ajout du couple nom du calque / "groupe de marqueurs à afficher" sur ce calque
    calquesWithGroupsObjet[calqueNom] = markersGroup;
}