$(document).ready(function() {
    //COMMENTER
    // au clic sur le bouton commenter
    $("#createComm").on('click', function (e) {
        // empeche l'action prévue sur le bouton commenter
        e.preventDefault();
        // conserve dans une variable le bouton
        var $a = $(this);
        // recupere l'url depuis l'attribut href du bouton
        var url = $a.attr('href');
        // appelle la fonction Ajax
        $.ajax({
            // type de requête ici GET
            type: 'GET',
            // url de la requete (prend la valeur de l'url du bouton commenter)
            url: url,
            // en cas de succes de la requete
            success: function (data) {
                //ajoute au début de la div formComm le formulaire
                $('#formComm').prepend(data);
                // ajoute apres le formulaire un bouton annuler pour réafficher les autres bouton de la page
                $('#formComm').append('<button class="cancel-btn btn">Annuler</button>');
                // au clic sur le bouton annuler
                $('.cancel-btn').on('click', function (e) {
                    // empeche l'action prévue sur le bouton annuler
                    e.preventDefault();
                    // affiche les bouton répondre
                    $('.btn-reply').fadeIn( "fast", function() {
                        // Animation complete
                    });
                    // affiche le bouton Commenter
                    $('#createComm').fadeIn( "fast", function() {
                        // Animation complete
                    });
                    // supprime les bouton annuler
                    $('.cancel-btn').replaceWith("");
                    // supprime le formulaire de commentaire
                    $('form[name="reply_comment"]').replaceWith("");
                    // supprime les formulaires de reponse
                    $('form[name="new_comment"]').replaceWith("");
                })
                // a la soumission du formulaire
                $('form').on('submit', function (e) {
                    // empeche l'action prévue sur le bouton save
                    e.preventDefault();
                    // conserve dans une variable le formulaire
                    var $form = $(this);
                    // récupère l'url du bouton Commenter
                    var url = $a.attr('href');
                    // appelle la fonction Ajax
                    $.ajax({
                        // type de requête ici POST
                        type: 'POST',
                        // url de la requete (prend la valeur de l'url du bouton commenter)
                        url: url,
                        // ajoute le formulaire sous forme de chaine de caractères
                        data: $form.serialize(),
                        // en cas de succès
                        success: function (data, text, jqxhr) {
                            // ajoute le commentaire a la fin de la div comment
                            $('#comment').append(jqxhr.responseText);
                            // affiche les bouton répondre
                            $('.btn-reply').fadeIn( "fast", function() {
                                // Animation complete
                            });
                            // affiche le bouton commentaire
                            $('#createComm').fadeIn( "fast", function() {
                                // Animation complete
                            });
                            // supprime le bouton annuler
                            $('.cancel-btn').replaceWith("");
                            // supprime le formulaire de commentaire
                            $('form[name="new_comment"]').replaceWith("");
                        },
                        // en cas d'erreur
                        error: function (jqxhr) {
                            // affiche une alert contenant le message d'erreur
                            // alert(jqxhr.responseText);
                        }
                    });
                })
            },
            // en cas d'erreur
            error: function (jqxhr) {
                // affiche une alert contenant le message d'erreur
                // alert(jqxhr.responseText);
            }
        });
        // masque les boutons répondre
        $(".btn-reply").hide();
        // masque le bouton commenter
        $("#createComm").hide();
        // supprime les formulaire de réponse
        $('form[name="reply_comment"]').replaceWith("");
    });
    // REPONDRE
    // au clic sur un bouton répondre
    $(".btn-reply").on('click', function (e) {
        // empeche l'action prévue sur le bouton répondre
        e.preventDefault();
        // conserve dans une variable le bouton
        var $a = $(this);
        // recupere l'url depuis l'attribut href du bouton
        var url = $a.attr('href');
        // appelle la fonction Ajax
        $.ajax({
            // type de requête ici GET
            type: 'GET',
            // url de la requete (prend la valeur de l'url du bouton commenter)
            url: url,
            // en cas de succes de la requete
            success: function (data) {
                // affiche le formulaire de réponse avant le bouton répondre
                $a.parent().prepend(data);
                // ajoute apres le formulaire un bouton annuler pour réafficher les autres bouton de la page
                $a.parent().append('<button class="cancel-btn btn">Annuler</button>');
                // au clic sur le bouton annuler
                $('.cancel-btn').on('click', function (e) {
                    // empeche l'action prévue sur le bouton annuler
                    e.preventDefault();
                    // affiche les bouton répondre
                    $('.btn-reply').fadeIn( "fast", function() {
                        // Animation complete
                    });
                    // affiche le bouton Commenter
                    $('#createComm').fadeIn( "fast", function() {
                        // Animation complete
                    });
                    // supprime les bouton annuler
                    $('.cancel-btn').replaceWith("");
                    // supprime le formulaire de commentaire
                    $('form[name="reply_comment"]').replaceWith("");
                    // supprime les formulaires de reponse
                    $('form[name="new_comment"]').replaceWith("");
                })
                // a la soumission du formulaire
                $('form').on('submit', function (e) {
                    // empeche l'action prévue sur le bouton répondre
                    e.preventDefault();
                    // conserve dans une variable le formulaire
                    var $form = $(this);
                    // récupère l'url du bouton Commenter
                    var url = $a.attr('href');
                    // appelle la fonction Ajax
                    $.ajax({
                        // type de requête ici POST
                        type: 'POST',
                        // url de la requete (prend la valeur de l'url du bouton commenter)
                        url: url,
                        // ajoute le formulaire sous forme de chaine de caractères
                        data: $form.serialize(),
                        // en cas de succès
                        success: function (data, text, jqxhr) {
                            // ajoute la réponse a la suite des autres réponses (apres le commentaire parent)
                            $('form[name="reply_comment"]').parent().prev().append(jqxhr.responseText);
                            //supprime le formulaire de réponse
                            $('form[name="reply_comment"]').replaceWith("");
                            // affiche les boutons réponses
                            $('.btn-reply').fadeIn( "fast", function() {
                                // Animation complete
                            });
                            // affiche le bouton commenter
                            $('#createComm').fadeIn( "fast", function() {
                                // Animation complete
                            });
                            // supprime le bouton annuler
                            $('.cancel-btn').replaceWith("");
                        },
                        // en cas d'erreur
                        error: function (jqxhr) {
                            // affiche une alert contenant le message d'erreur
                            // alert(jqxhr.responseText);
                        }
                    });
                })
            },
            // en cas d'erreur
            error: function (jqxhr) {
                // affiche une alert contenant le message d'erreur
                // alert(jqxhr.responseText);
            }
        });
        // masque les boutons répondre
        $(".btn-reply").hide();
        // masque le bouton commenter
        $("#createComm").hide();
        // supprime le formulaire de commentaire
        $('form[name="new_comment"]').replaceWith("");
    });
    // SIGNALER
    //  auclic sur un bouton signaler
    $(".btn-flag").on('click', function (e) {
        // empeche l'action prévue sur le bouton répondre
        e.preventDefault();
        // conserve dans une variable le bouton
        var $a = $(this);
        // recupere l'url depuis l'attribut href du bouton
        var url = $a.attr('href');
        // appelle la fonction Ajax
        $.ajax({
            // type de requête ici GET
            type: 'GET',
            // url de la requete (prend la valeur de l'url du bouton commenter)
            url: url,
            // en cas de succes de la requete
            success: function (data) {
                // affiche le formulaire de réponse avant le bouton répondre
                $a.parent().prepend(data);
                // retire le message flash après 5 secondes
                function removeFlagMsg(){
                    $('.flag-msg').replaceWith("");
                }
                setTimeout(removeFlagMsg, 5000);
            },
            // en cas d'erreur
            error: function (jqxhr) {
                // affiche une alert contenant le message d'erreur
                // alert(jqxhr.responseText);
            }
        });
    });
});
