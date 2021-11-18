/**
 * Renvoie les coordonnées GPS d'une adresse
 * @param address
 * @param postCode
 * @returns {Promise<*>}
 */
async function coordFromAddressWithFetch(address, postCode) {
    let formatedAddress = address.replace(/ /g, '+');
    const response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${formatedAddress}&postcode=${postCode}`);

    const rep = await response.json();
    let coord = rep.features[0].geometry.coordinates;
    return coord;
}