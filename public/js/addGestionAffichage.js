/**
 * Ajoute :
 * 1. le système de gestion de l'affichage des calques (et des éléments)
 * 2. la fonctionnalité "cluster" de marqueurs par calque
 * @param elementsToShowElt élément HTML qui contient les éléments à afficher en attribut
 * @param calquesList [] élément HTML dequi contient les noms des calques en attribut
 * @param myMap objet Leaflet L.map carte sur laquelle on affiche
 */
function addGestionAffichage(elementsToShowElt, calquesList, myMap)
{
    // taille des icones :
    let iconeHauteur = 50;
    let iconeLargeur = 50;

    // traitement des données
    let eltsToShow = JSON.parse(elementsToShowElt[0].attributes[1].value);
    let calquesNoms = JSON.parse(calquesList[0].attributes[1].value);

    // on créé autant de tableaux et de clusters de marqueurs qu'il y a de calques
    let markersTabTab = []; //contiendra les tableaux de marqueurs à afficher par calque
    let clustersTab = []; // contiendra les "clusters" de marqueurs par calque
    for (let i = 0; i < calquesNoms.length; i++) {
        markersTabTab[calquesNoms[i]] = [];
        clustersTab[calquesNoms[i]] = L.markerClusterGroup();
    }

    // on vérifie qu'il y a des éléments à afficher
    if (eltsToShow.length > 0) {

        // on parcourt les éléments
        for (let i = 0; i < eltsToShow.length; i++) {

            // on créé l'icone de l'élément
            var eltIcone = L.icon({
                iconUrl: `../MarkersIcons/${eltsToShow[i].lienIcone}`,
                iconSize: [iconeLargeur, iconeHauteur]
            });

            // Mise en forme de la popup
            let texte = eltsToShow[i].texte
            let typeElement = eltsToShow[i].typeElementNom
            let popupContenu = "Description: " + texte + "</br>" +
                "Type d'élement: " + typeElement + "</br>";

            let photo = eltsToShow[i].photo
            let lien = eltsToShow[i].lien

            if (photo === null && lien === null) {
                popupContenu +=
                    '</br>'
                    + '<div style="text-align: center;"><button id="modification'+eltsToShow[i].idElement+'" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier cet élement</button></div>'
            } else if (photo !== null && lien === null) {
                popupContenu +=
                    '</br>'
                    + '<div>'
                    + '<div style="margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                    + '<div style="text-align: center;"><button id="modification'+eltsToShow[i].idElement+'" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier cet élement</button></div>'
                    + '</div>';
            } else if (lien !== null && photo === null) {
                popupContenu +=
                    '</br>'
                    + '<div>'
                    + '<div style="margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                    + '<div id="modification" style="text-align: center;"><button id="modification'+eltsToShow[i].idElement+'" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier cet élement</button></div>'
                    + '</div>';
            } else {
                popupContenu +=
                    '</br>'
                    + '<div style="display: flex;">'
                    + '<div style="flex:auto; margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                    + '<div style="flex:auto; margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                    + '</div>'
                    + '<div style="text-align: center;"><button id="modification'+eltsToShow[i].idElement+'" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier cet élement</button></div>';

            }

            let popupPoints = new L.popup();
            popupPoints.setContent(popupContenu);

            // Coordonnnées
            let latitude = eltsToShow[i].latitude
            let longitude = eltsToShow[i].longitude

            // création des marqueurs pour chaque élément
            let marker = L.marker([latitude, longitude], {idElement: eltsToShow[i].idElement, icon: eltIcone}).bindPopup(popupPoints);

            $(document).on("click", "#modification"+eltsToShow[i].idElement+"", function () {
                let idElement = marker.options.idElement
                $.ajax({
                    url: '/map/edit-element-'+idElement+'',
                    type: 'GET',
                    success: function(){
                        document.location.replace("http://127.0.0.1:8000/map/edit-element-"+idElement+"");
                    }
                });
            })

            // on veut afficher les éléments par calque
            for (let j = 0; j < calquesNoms.length; j++) {
                // si le nom du calque correspond à celui sur lequel est l'élément
                if (calquesNoms[j] === eltsToShow[i].calqueNom) {
                    // on ajoute le marqueur au tableau des marqueurs (pour affichage sur le calque)
                    markersTabTab[calquesNoms[j]].push(marker);

                    // on ajoute le marqueur au tableau des clusters
                    clustersTab[calquesNoms[j]].addLayer(marker);
                    break;
                }
            }
        }

        //pour ajouter un calque au système de gestion : il faut un objet contenant des couples nom du calque / "groupe de marqueurs pour un calque"
        var calquesWithGroupsObjet = {};

        // on parcourt le tableau de tableau de marqueurs pour créer les "groupes de marqueurs" par calque
        for (let key in markersTabTab) {
            let markersGroup = L.layerGroup(markersTabTab[key]);

            //markersGroup.addTo(myMap); // ajoute les marqueurs des calques "par défaut"
            // todo : si on ajoute les marqueurs par défaut il faut aussi ajouter les clusters liés

            // ajout du couple nom du calque / "groupe de marqueurs à afficher" sur le calque
            calquesWithGroupsObjet[key] = markersGroup;
        }

        // créé l'"icône" et le système de gestion de l'affichage des calques à la carte
        L.control.layers(null, calquesWithGroupsObjet, { collapsed:false }).addTo(myMap);

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

        // affichage de la photo
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

        // affichage du pdf
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

        // activation / inactivation des "clusters"
        $(document).on("click", ".leaflet-control-layers-selector", function(e) {
            // on doit enlever l'espace en début de string qui a été ajouté automatiquement
            var calqueNom = e.target.nextElementSibling.textContent.trim();

            if(this.checked) {
                // on ajoute le cluster à la carte
                myMap.addLayer(clustersTab[calqueNom]);
            } else {
                myMap.removeLayer(clustersTab[calqueNom]);
            }
        });

    }
}