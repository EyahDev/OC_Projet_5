$(document).ready(function() {
// Ecoute des boutons de paginations
    $('#paginationMyObservations').find('a').on('click', function (e) {
        e.preventDefault();

        var $a = $(this);
        var url = $a.attr('href');
        // récupère l'argument passer en get
        var argGet = url.substring(url.lastIndexOf('?'));
        // récupère la route pour la pagination
        var urlNewRoute = $('#paginationMyObservations').attr('url');
        // concatene la route et l'argument
        var newUrl = urlNewRoute + argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // recharge le tableau
                $('.pagination-table-my-observations').replaceWith(data);
                // réactive les écouteurs
                paginateMyObservations();
            }, error: function () {
                addFlashMsgFaq('danger', 'Une erreur est survenue')
            }
        })
    });

    function paginateMyObservations() {
        $('#paginationMyObservations').find('a').on('click', function (e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('#paginationMyObservations').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute + argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-my-observations').replaceWith(data);
                    // réactive les écouteurs
                    paginateMyObservations();
                }, error: function () {
                    addFlashMsgFaq('danger', 'Une erreur est survenue')
                }
            })
        });
    }
});
