$('p').attr('style','margin:0;');
$('span').attr('style','font-family: "Montserrat", sans-serif; font-size:13px; color:#666; line-height:25px;');

$(document).ready(function() {
    //PAGINATION PAGE INDEX BLOG
    paginateIndexBlog();
    //définition de la fonction pour l'appliquer récursivement
    function paginateIndexBlog() {
        $('#paginationPost').find('a').on('click', function (e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-index-blog').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute + argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-index-blog').replaceWith(data);
                    // réactive les écouteurs
                    paginateIndexBlog();
                }, error: function () {
                    addFlashMsgIndexBlog('danger', 'Une erreur est survenue')
                }
            })
        });
    }

    // MESSAGE FLASH INDEX BLOG
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgIndexBlog(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgIndexBlog').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }

    //PAGINATION PAGE CATEGORIE BLOG
    paginatePostsCategory();
    //définition de la fonction pour l'appliquer récursivement
    function paginatePostsCategory() {
        $('#paginationPostsCategory').find('a').on('click', function (e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-posts-category').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute + argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-posts-category').replaceWith(data);
                    // réactive les écouteurs
                    paginatePostsCategory();
                }, error: function () {
                    addFlashMsgPostsCategory('danger', 'Une erreur est survenue')
                }
            })
        });
    }

    // MESSAGE FLASH INDEX BLOG
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgPostsCategory(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgPostsCategory').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }

});
