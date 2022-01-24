/**
 * permet de "préchoisir" une commune grâce à une liste déroulante
 * on utilise la valeur dans le formulaire de l'API ESRI pour pouvoir utiliser la suggestion d'adresse
 */
//  function preChooseCommune() {
//     // on récupère la valeur de la commune sélectionnée par défaut
//     let communeElt = document.getElementById('form_commune');
//
//     // Cas 1 : on met la valeur au moment du chargement de la page
//     // on la met dans l'input du formulaire de l'API
//     let formAPIElt = document.getElementsByClassName('geocoder-control-input')[0];
//     insererChoixDansFormRecherche(formAPIElt, communeElt);
//
//     // Cas 2 : on met la valeur quand on change le choix de la commune
//     communeElt.addEventListener('change', event => {
//         insererChoixDansFormRecherche(formAPIElt, communeElt);
//     });
//
//     // Cas 3 : on met la valeur quand on n'est plus sur le champ du formulaire de l'API
//     formAPIElt.addEventListener('focusout', event => {
//         let commune = ` ${communeElt.options[communeElt.selectedIndex].text} `;
//         formAPIElt.value = commune;
//     });
//
//     formAPIElt.addEventListener('click', event => {
//         // on met le curseur au début du input (avant le code postal et le nom de la commune)
//         setCaretPosition(formAPIElt, 0);
//     });
//
//     // TODO on garde le focus si on essaye de changer mais qu'on choisit la même valeur finalement
// }