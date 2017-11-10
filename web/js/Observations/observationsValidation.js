$(document).ready(function() {
    //PAGINATION
    paginateObservationsValidation();
    //définition de la fonction pour l'appliquer récursivement
    function paginateObservationsValidation() {
        $('#paginationObservationsValidation').find('a').on('click', function (e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-table-observations-validation').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute + argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-observations-validation').replaceWith(data);
                    // réactive les écouteurs
                    paginateObservationsValidation();
                }, error: function () {
                    addFlashMsgObservationsValidation('danger', 'Une erreur est survenue')
                }
            })
        });
    }

    // MESSAGE FLASH
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgObservationsValidation(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgObservationsValidation').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }

});