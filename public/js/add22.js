/**
 * Ajoute un groupe de marqueurs (bilbiothèque Leaflet)
 * créé à partir d'un élément HTML qui a reçu des données de Symfony
 * à un objet qui sera utilisé pour afficher la gestion des calques
 * @param elementsToShowElt élément HTML qui contient les données en attribut
 */
function add22(calquesWithGroupsObjet, elementsToShowElt, calquesList)
{
    let eltsToShow = JSON.parse(elementsToShowElt[0].attributes[1].value);
    let calquesNoms = JSON.parse(calquesList[0].attributes[1].value);

    // on parcourt les noms des calques pour créer autant de tableaux de marqueurs et de groupes de marqueurs qu'il y a de calques
    let markersTabTab = [];
    for (let i = 0; i < calquesNoms.length; i++) {
        markersTabTab[calquesNoms[i]] = [];
    }

    // on vérifie qu'il y a des éléments à afficher
    if (eltsToShow.length > 0) {
        // on parcourt les éléments
        for (let i = 0; i < eltsToShow.length; i++) {

            // on veut afficher les éléments par calque
            // on parcourt la liste des noms des calques
            for (let j = 0; j < calquesNoms.length; j++) {

                // si le nom du calque correspond à celui sur lequel est l'élément
                if (calquesNoms[j] === eltsToShow[i].calqueNom) {

                    // on créé l'icone de l'élément
                    var eltIcone = L.icon({
                        iconUrl: `../MarkersIcons/${eltsToShow[i].lienIcone}`,
                        iconSize: [35, 39]
                    });

                    // Mise en forme de la popup
                    let texte = eltsToShow[i].texte
                    let typeElement = eltsToShow[i].typeElementNom
                    let popupContenu = "Description: " + texte + "</br>" +
                        "Type d'élement: " + typeElement + "</br>";

                    let photo = eltsToShow[i].photo
                    let lien = eltsToShow[i].lien

                    if (photo === null && lien === null) {
                    } else if (photo !== null && lien === null) {
                        popupContenu += "</br>" + photo
                    } else if (lien !== null && photo === null) {
                        popupContenu += "</br>" + lien
                    } else {
                        popupContenu +=
                            '</br>'
                            + '<div style="display: flex;">'
                            + '<div style="flex:auto;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                            + '<div style="flex:auto;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                            + '</div>'
                    }

                    let popupPoints = new L.popup();
                    popupPoints.setContent(popupContenu);

                    // Coordonnnées
                    let latitude = eltsToShow[i].latitude
                    let longitude = eltsToShow[i].longitude

                    // création des marqueurs pour chaque élément
                    let marker = L.marker([latitude, longitude], {icon: eltIcone}).bindPopup(popupPoints);
                    markersTabTab[calquesNoms[j]].push(marker);
                    break;
                }
            }
        }


        $("#header-content").append('<div class="modal fade" id="modalAffichage" tabIndex="-1" role="dialog" aria-labelledby="modalAffichageLabel" aria-hidden="true">'
            + '<div class="modal-dialog" style="max-width: 50%; height: 95%;" role="document">'
            + '<div class="modal-content" style="height: 100%;">'
            + '<div class="modal-header">'
            + '<h5 class="modal-title" id="modalAffichageLabel">Affichage du fichier</h5>'
            + '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
            + '<span aria-hidden="true">&times;</span>'
            + '</button>'
            + '</div>'
            + '<div id="photoOuLien" style="height: 100%;">'

            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>');


        $(document).on("click", "#photo", function () {
            let photoNode = document.getElementById("photoOuLien");
            if (photoNode.hasChildNodes()) {
                while (photoNode.firstChild) {
                    photoNode.removeChild(photoNode.lastChild);
                }
            }
            let photo = $("#photo").attr("photo")

            $("#photoOuLien").append("<img style='width:85%; height: 92%' src=" + photo + ">")
        })

        $(document).on("click", "#lien", function () {
            let lienNode = document.getElementById("photoOuLien");
            if (lienNode.hasChildNodes()) {
                while (lienNode.firstChild) {
                    lienNode.removeChild(lienNode.lastChild);
                }
            }
            let lien = $("#lien").attr("lien")
            $("#photoOuLien").append("<iframe id='iframePdf' style='width:95%;' src=" + lien + "></iframe>")
            let iframePdf = $("#iframePdf")
            iframePdf.css("height", $("#photoOuLien").height() + "%")
        })

        // on parcourt le tableau de tableau de marqueurs pour créer les groupes de marqueurs par calques
        for (let key in markersTabTab) {
            let markersGroup = L.layerGroup(markersTabTab[key]);

            // ajout du couple nom du calque / "groupe de marqueurs à afficher" sur le calque
            calquesWithGroupsObjet[key] = markersGroup;
        }
    }
}
