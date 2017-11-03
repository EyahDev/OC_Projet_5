// $(document).ready(function(){
//     var prout = $('a').attr('aria-expanded');
//
//     $('.collapsed').click(function() {
//         if(prout === true) {
//             $(this).css('color', 'red');
//         }
//         else{
//             $(this).css('color', 'blue');
//         }
//     });
// });

// $('.collapsed').on({
//     'click': function() {
//         var prout = ($(this).attr('aria-expanded'));
//             if(prout == true){
//                 $(this).css('color', 'red');
//             }
//         else{
//                 $(this).css('color', 'blue');
//             }
//     }
// });

// var nouvellesIcones = {
//     header : 'ui-icon-plus',
//     headerSelected : 'ui-icon-minus'
// }; // on définit un objet contenant nos nouvelles icônes
//
// $('#accordionUserAndPro').collapsed({
//     icons : nouvellesIcones // on passe cette objet au paramètre icons
// });