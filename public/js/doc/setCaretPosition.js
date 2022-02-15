/**
 * Déplace le curseur d'un élément à une position donnée
 * @param elem élément qui contient le curseur à déplacer
 * @param caretPos position à laquelle mettre le curseur
 */
//  function setCaretPosition(elem, caretPos) {
//     if(elem != null) {
//         if(elem.createTextRange) {
//             var range = elem.createTextRange();
//             range.move('character', caretPos);
//             range.select();
//         } else {
//             if(elem.selectionStart) {
//                 elem.focus();
//                 elem.setSelectionRange(caretPos, caretPos); }
//             else elem.focus();
//         }
//     }
// }