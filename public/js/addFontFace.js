function iconeFontFace(iconeLien) {
    // Création des url pour les icones et pour les fontfaces
    let urlISplit = iconeLien.split('/')[2].split('-');
    let urlFontFace = "/MarkersIcons/" + urlISplit[1].split('.')[0]

    // Création d'un objet font-face correspondant à l'icone choisie dans la liste
    let font = new FontFace("fontello", 'url(\'..' + urlFontFace + '.woff\') format(\'woff\')');
    font.load().then(function (loadedFont) {
        if (document.fonts.has(loadedFont)) {
            document.fonts.delete(loadedFont);
        }
        document.fonts.add(loadedFont);
    }).catch(function (error) {
        console.log(error)
    });
}