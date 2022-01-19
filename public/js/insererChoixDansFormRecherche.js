/**
 * Insérer le choix de la commune (choisi au niveau de la liste déroulante)
 * dans le formulaire de recherche de l'API avec postionnement du curseur au début du champ
 * @param formEPIElt élément HTML qui est le formulaire de recherche
 */
 function insererChoixDansFormRecherche(formEPIElt) {
    let commune = ` ${communeElt.options[communeElt.selectedIndex].text} `;
    formAPIElt.value = commune;
    formAPIElt.focus();
    // on met le curseur au début du input (avant le code postal et le nom de la commune)
    setCaretPosition(formAPIElt, 0);
}