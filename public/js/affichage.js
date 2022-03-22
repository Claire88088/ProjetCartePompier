$(document).ready(function() {

    // Passes la page en plein écran
    function fullScreen(element){
        if(element.requestFullScreen){
            element.requestFullScreen();
        } else if (element.webkitRequestFullScreen){
            element.webkitRequestFullScreen();
        } else if (element.mozRequestFullScreen){
            element.mozRequestFullScreen();
        }
    };

    $('#fullScreen').css("cursor", "pointer")
    // Au clique du bouton "Plein écran"
    $('#fullScreen').on('click', function(e) {
        // Si la fenêtre est en plein écran
        if (document.fullscreenElement) {
            document.exitFullscreen()
            $(e.target).removeClass("material-icons")
            e.target.innerHTML = "Plein écran"
            e.target.css("font-size", "35px")
        // Si ce n'est pas le cas
        } else {
            fullScreen(document.body);
            $(e.target).addClass("material-icons")
            e.target.innerHTML = "close"
        }
    });


    // Animation du display des calques avec leurs éléments
    var divCalquesList = $('.listCalqueElem')
    var divTypesElementCalque = $('.typeElementCalque')
    for (let i = 0; i < divCalquesList.length; i++) {

        divCalquesList[i].addEventListener("mouseover", function (event) {
            divTypesElementCalque[i].style.display = 'block'
        })
        divTypesElementCalque[i].addEventListener("mouseover", function (event) {
            divTypesElementCalque[i].style.display = 'block'
        })
        divCalquesList[i].addEventListener("mouseout", function (event) {
            divTypesElementCalque[i].style.display = 'none'
        })
        divTypesElementCalque[i].addEventListener("mouseout", function (event) {
            divTypesElementCalque[i].style.display = 'none'
        })
    }

    // Permet au champs d'upload d'afficher le nom du ficher donné.
    $('.custom-file-input').on('change', function (event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
    });

    // Cache dans les formulaires la latitude et la longitude
    $("fieldset.form-group").css("display", "none");

    //---------------------------------------------------------------------------------------------------
    var urlAddElement = window.location.pathname.includes("add-element");
    var urlEditElement = window.location.pathname.includes("edit-element");

    if (urlAddElement || urlEditElement) {
        // Récupère le nom du formulaire [1] de la page
        let formName = document.querySelectorAll("form")[1].name;

        // affichage du select avec les choix d'icones
        // Récupère le select des icones comprenant les options
        let selectIcone = document.getElementById(formName + "_icone");

        // boucle sur le select pour avoir chaque option
        for (let i = 0; i < selectIcone.length; i++) {
            let option = selectIcone[i];
            let valOption = selectIcone[i].label;

            let unicode = option.attributes[1].value
            // construction du chemin vers les icones
            let cheminIcone = "/MarkersIcons/" + valOption;

            // set l'attribut nécessaire pour afficher les images par la suite
            option.setAttribute('data-imagesrc', cheminIcone)
            option.setAttribute('data-description', unicode)

            // set le nom de l'icone a partir du lien
            let iconeName = option.childNodes[0].textContent
            let splitIconeName = iconeName.split("-")[1].split('.')[0]
            option.innerHTML = splitIconeName
        }

        // Fonction jQuery UI pour reconstruire un select option en div et y inclure des images
        $('#' + formName + '_icone').ddslick({
            onSelected: function (selectedData) {
                // remet le nom sur le input icone pour qu'il repasse dans le formulaire avec l'id de l'icone selectionnée
                $("input.dd-selected-value").attr("name", formName + "[icone]")
                $(".dd-selected-image").attr("unicode", selectedData.selectedData.description)
            }
        });

        // INFORMATION UTILISATEUR si on ne clique pas sur la carte pour choisir un point lors de la création d'un nouvel élément
        $('#' + formName + '_ajouter').click(function(e) {
            $('#position').removeClass('alert-danger');
            if (!$('#' + formName + '_coordonnees_longitude')[0].value) {
                $('#position').addClass('alert-danger');
            }
        });


        if (urlEditElement) {
            // Affiche les noms des photos et liens dans leur champs correspondant, par défaut symfony les cache.
            let divPhoto = $('.photo');
            let divlien = $('.lien');

            let photoNom = divPhoto[0].attributes[1].value;
            let lienNom = divlien[0].attributes[1].value;

            if (photoNom) {
                let uploadPhoto = document.getElementById(formName + "_photo");
                uploadPhoto.nextSibling.textContent = photoNom
            }
            if (lienNom) {
                let uploadLien = document.getElementById(formName + "_lien");
                uploadLien.nextSibling.textContent = lienNom
            }

            $("fieldset").css("margin", "17px 0px 17px 0px")
            $(".custom-file-label").css("border-radius", "unset")
            $(".listElt-icon").css("padding", "6px")
            $(".listElt-icon").css("border-radius", "0px 10px 10px 0px")

        }
    }
});
