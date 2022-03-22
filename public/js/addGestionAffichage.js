/**
 * Ajoute :
 * 1. le système de gestion de l'affichage des calques (et des éléments)
 * 2. la fonctionnalité "cluster" de marqueurs par calque
 * @param elementsToShowElt élément HTML qui contient les éléments à afficher en attribut
 * @param calquesList [] élément HTML dequi contient les noms des calques en attribut
 * @param myMap objet Leaflet L.map carte sur laquelle on affiche
 * @return affichageCalquesTab [] tableau qui contient le tableau des groupes de marqueurs (markersGroup) et le tableau des clusters (clustersTab)
 */
function addGestionAffichage(elementsToShowElt, calquesList, myMap)
{
    let affichageCalquesTab = [];
    let markersGroupTab = [];

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
        markersTabTab[escapeHtml(calquesNoms[i])] = [];
        clustersTab[calquesNoms[i]] = L.markerClusterGroup();
    }

    // Si true, un utilisateur est connecté, si false, non.
    let divIsConnected = $('.isConnected');
    let isConnected = divIsConnected[0].attributes[1].value;

    let divRole = $('.role');
    let role = divRole[0].attributes[1].value;

    // on vérifie qu'il y a des éléments à afficher
    if (eltsToShow.length > 0) {

        // on parcourt les éléments
        for (let i = 0; i < eltsToShow.length; i++) {

            let idElement = eltsToShow[i].idElement
            let couleur = eltsToShow[i].couleur
            let unicode = eltsToShow[i].unicode
            let latitude = eltsToShow[i].latitude
            let longitude = eltsToShow[i].longitude

            let urlISplit = eltsToShow[i].lienIcone.split('-');
            let urlFontFace = "/MarkersIcons/" + urlISplit[1].split('.')[0]

            font = new FontFace("fontello", 'url(\'..' + urlFontFace + '.woff\') format(\'woff\')');

            font.load().then(function(loadedFont)
            {
                document.fonts.add(loadedFont);
            }).catch(function(error) {
                console.log(error)
            });

            let eltAndIcone = L.marker([latitude, longitude], {
                idElement: idElement,
                icon: L.divIcon({
                    html: "<i id="+idElement+" class='demo-icon' style='color:"+couleur+";'>"+unicode+"</i>",
                    iconSize: [iconeLargeur, iconeHauteur],
                    iconAnchor: [iconeLargeur/2,iconeHauteur],
                    popupAnchor: [0, -32]
                })
            }); //.addTo(myMap); on affiche aucun élément par défaut sur la carte

            // Mise en forme des popups : 2 types de pop up en fonction de l'affichage : au survol ou au clic
            // contenu de la pop up au survol :
            let nom = eltsToShow[i].nom;
            let hoverPopupContent = "" + escapeHtml(nom);

            // contenu de la pop up au clic :
            let typeElement = eltsToShow[i].typeElementNom;
            let clickPopupContent = "<strong>" + escapeHtml(nom) + "</strong> (type : " + escapeHtml(typeElement) + ")</br>";

            let texte = eltsToShow[i].texte;
            if (texte) {
                clickPopupContent += "Description : " + escapeHtml(texte) +  "</br>";
            }

            let dateDeb = eltsToShow[i].dateDeb
            if (dateDeb) {
                let dateDebInJs = new Date(dateDeb["date"]);
                clickPopupContent += "Date de début : " + dateDebInJs.toLocaleDateString() + "</br>";
            }

            let dateFin = eltsToShow[i].dateFin
            if (dateFin) {
                let dateFinInJs = new Date(dateFin["date"]);
                clickPopupContent += "Date de fin : " + dateFinInJs.toLocaleDateString() + "</br>";
            }

            let photo = eltsToShow[i].photo
            let lien = eltsToShow[i].lien

            if (photo === null && lien === null) {
                if (isConnected && role === "ROLE_ADMIN") {
                    clickPopupContent +=
                        '</br>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; text-align: center;"><button id="modification' + eltsToShow[i].idElement + '" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier</button></div>'
                        + '<div style="flex:auto; text-align: center;"><button id="suppression' + eltsToShow[i].idElement + '" class="btn suppression" style="font-size: 12px; padding:5px;">Supprimer</button></div>'
                        + '</div>'
                }
            } else if (photo !== null && lien === null) {
                if (isConnected && role === "ROLE_ADMIN") {
                    clickPopupContent +=
                        '</br>'
                        + '<div>'
                        + '<div style="margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                        + '</div>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; text-align: center;"><button id="modification' + eltsToShow[i].idElement + '" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier</button></div>'
                        + '<div style="flex:auto; text-align: center;"><button id="suppression' + eltsToShow[i].idElement + '" class=" btn suppression" style="font-size: 12px; padding:5px;">Supprimer</button></div>'
                        + '</div>'
                } else {
                    clickPopupContent +=
                        '</br>'
                        + '<div>'
                        + '<div style="margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                        + '</div>'
                }
            } else if (lien !== null && photo === null) {
                if (isConnected && role === "ROLE_ADMIN") {
                    clickPopupContent +=
                        '</br>'
                        + '<div>'
                        + '<div style="margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                        + '</div>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; text-align: center;"><button id="modification' + eltsToShow[i].idElement + '" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier</button></div>'
                        + '<div style="flex:auto; text-align: center;"><button id="suppression' + eltsToShow[i].idElement + '" class="btn suppression" style="font-size: 12px; padding:5px;">Supprimer</button></div>'
                        + '</div>'
                } else {
                    clickPopupContent +=
                        '</br>'
                        + '<div>'
                        + '<div style="margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                        + '</div>'
                }
            } else {
                if (isConnected && role === "ROLE_ADMIN") {
                    clickPopupContent +=
                        '</br>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                        + '<div style="flex:auto; margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                        + '</div>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; text-align: center;"><button id="modification'+eltsToShow[i].idElement+'" class="btn-primary btn" style="font-size: 12px; padding:5px;">Modifier</button></div>'
                        + '<div style="flex:auto; text-align: center;"><button id="suppression'+eltsToShow[i].idElement+'" class="btn suppression" style="font-size: 12px; padding:5px;">Supprimer</button></div>'
                        + '</div>'
                } else {
                    clickPopupContent +=
                        '</br>'
                        + '<div style="display: flex;">'
                        + '<div style="flex:auto; margin-bottom: 10px;"><a id="photo" photo="../uploads/photos/' + photo + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir la photo</a></div>'
                        + '<div style="flex:auto; margin-bottom: 10px;"><a id="lien" lien="../uploads/pdf/' + lien + '" role="button" data-toggle="modal" data-target="#modalAffichage">voir le pdf</a></div>'
                        + '</div>'
                }
            }

            // création des pop-ups
            let hoverPopup = new L.popup();
            hoverPopup.setContent(hoverPopupContent);

            let clickPopup = new L.popup();
            clickPopup.setContent(clickPopupContent);

            // ajout de la popup aux Elements/Icones
            eltAndIcone.bindPopup(clickPopup);

            // gestion de l'affichage des pop up
            eltAndIcone.on('mouseover', function(e) {
                this.unbindPopup();
                this.bindPopup(hoverPopup).openPopup()
                setTimeout(function () {
                    myMap.closePopup(hoverPopup)
                }, 2000)
            })

            eltAndIcone.on('click', function() {
                this.unbindPopup();
                this.bindPopup(clickPopup).openPopup();
            })

            // gestion de la modification / suppression des éléments
            $(document).on("click", "#modification"+eltsToShow[i].idElement+"", function () {
                let idElement = parseInt(eltAndIcone.options.idElement)
                document.location.replace("http://127.0.0.1:8000/map/edit-element-"+idElement+"");
            })

            $(document).on("click", "#suppression"+eltsToShow[i].idElement+"", function () {
                let idElement = parseInt(eltAndIcone.options.idElement)
                ConfirmDelete()
                document.location.replace("http://127.0.0.1:8000/map/delete-element-"+idElement+"");
            })

            // on veut afficher les éléments par calque
            for (let j = 0; j < calquesNoms.length; j++) {
                // si le nom du calque correspond à celui sur lequel est l'élément
                if (calquesNoms[j] === eltsToShow[i].calqueNom) {
                    // on ajoute le marqueur au tableau des marqueurs (pour affichage sur le calque)
                    markersTabTab[calquesNoms[j]].push(eltAndIcone);

                    // on ajoute le marqueur au tableau des clusters
                    clustersTab[calquesNoms[j]].addLayer(eltAndIcone);
                    break;
                }
            }
        }
    }

    //pour ajouter un calque au système de gestion : il faut un objet contenant des couples nom du calque / "groupe de marqueurs pour un calque"
    var calquesWithGroupsObjet = {};

    // on parcourt le tableau de tableau de marqueurs pour créer les "groupes de marqueurs" par calque
    for (let key in markersTabTab) {
        let markersGroup = L.layerGroup(markersTabTab[key]);

        // ajout du groupe au tableau de groupes de marqueurs pour retour
        markersGroupTab[key] = markersGroup;

        // ajout du couple nom du calque / "groupe de marqueurs à afficher" sur le calque
        calquesWithGroupsObjet[key] = markersGroup;
    }

    // on ajoute les données au tableau de retour
    affichageCalquesTab.push(markersGroupTab, clustersTab);

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

    function ConfirmDelete()
    {
        var x = confirm("Etes-vous sur de vouloir supprimer ?");
        if (x)
            return true;
        else
            return false;
    }

    return affichageCalquesTab;
}
