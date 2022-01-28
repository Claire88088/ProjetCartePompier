/**
 * échappe les caractères HTML d'une chaine de caractères
 * @param string unsafe Chaine contenant du HTML
 * @return string {*} Chaine avec le HTML échappé
 */
function escapeHtml(unsafe)
{
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}