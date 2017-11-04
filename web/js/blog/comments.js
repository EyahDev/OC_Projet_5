$(document).ready(function() {
    // MESSAGE FLASH FORM NEW COMMENT
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgNewComment(type, message) {
        // construit le html pour le message flash
        var replaceCode = '<div id="flashMsgNewComment"><div class="flash-msg alert alert-'+type+'">'+message+'</div></div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgNewComment').replaceWith(replaceCode);
        // efface le message flash apres 5 secondes
        function removeFlagMsg(){
            $('.flash-msg').replaceWith("");
        }
        if(type != 'danger') {
            setTimeout(removeFlagMsg, 5000);
        }
    }
    // MESSAGE FLASH FORM NEW COMMENT
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgReplyComment(type, message) {
        // construit le html pour le message flash
        var replaceCode = '<div id="flashMsgReplyComment"><div class="flash-msg alert alert-'+type+'">'+message+'</div></div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgReplyComment').replaceWith(replaceCode);
        // efface le message flash apres 5 secondes
        function removeFlagMsg(){
            $('.flash-msg').replaceWith("");
        }
        if(type != 'danger') {
            setTimeout(removeFlagMsg, 5000);
        } else {
            setTimeout(removeFlagMsg,15000);
        }
    }
    //RECHARGER LA LISTE DE COMMENTAIRE
    function reloadComments() {
        //récupère l'url pour recharger la list de commentaire
        var url = $('#commentsList').attr('url');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('#commentsList').replaceWith(data);
                // réactive les écouteurs
                flagComment();
                replyToComment();
            },
            error: function () {
                console.log('erreur de rechargement des commentaires')
            }
        })

    }
    // ECOUTE DES BOUTONS ANNULER
    function cancelBtnComment(){
        // au clic sur le bouton annuler
        $('.cancel-btn').on('click', function (e) {
            // empeche l'action prévue sur le bouton annuler
            e.preventDefault();
            // affiche les bouton répondre
            $('.btn-reply').fadeIn("fast", function () {
                // Animation complete
            });
            // affiche le bouton Commenter
            $('#createComm').fadeIn("fast", function () {
                // Animation complete
            });
            // supprime les bouton annuler
            $('.cancel-btn').replaceWith("");
            // supprime le formulaire de commentaire
            $('form[name="reply_comment"]').replaceWith("");
            // supprime les formulaires de reponse
            $('form[name="new_comment"]').replaceWith("");
        })
    }
    // AJOUT D'UN COMMENTAIRE
    addNewComment();
    function addNewComment() {
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
                    // masque les boutons répondre
                    $(".btn-reply").hide();
                    // masque le bouton commenter
                    $("#createComm").hide();
                    // supprime le formulaire de commentaire
                    $('form[name="reply_comment"]').replaceWith("");
                    // ajoute apres le formulaire un bouton annuler pour réafficher les autres bouton de la page
                    $('.btn-new-comment-section').prepend('<button class="cancel-btn btn">Annuler</button>');
                    // au clic sur le bouton annuler
                    cancelBtnComment();
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
                            success: function (data) {
                                // recharge la liste de commentaires
                                reloadComments();
                                addFlashMsgNewComment('success', data);
                                // affiche le bouton commentaire
                                $('#createComm').fadeIn("fast", function () {
                                    // Animation complete
                                });
                                // supprime le bouton annuler
                                $('.cancel-btn').replaceWith("");
                                // supprime le formulaire de commentaire
                                $('form[name="new_comment"]').replaceWith("");
                            },
                            // en cas d'erreur
                            error: function (jqxhr) {
                                addFlashMsgNewComment('danger', jqxhr.responseText);
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
        });
    }
    //REPONSE A UN COMMENTAIRE
    replyToComment();
    function replyToComment() {
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
                    $a.parentsUntil('#commentsList').append(data);
                    // ajoute apres le formulaire un bouton annuler pour réafficher les autres bouton de la page
                    $a.parentsUntil('#commentsList').append('<button class="cancel-btn btn">Annuler</button>');
                    // masque les boutons répondre
                    $(".btn-reply").hide();
                    // masque le bouton commenter
                    $("#createComm").hide();
                    // supprime le formulaire de commentaire
                    $('form[name="new_comment"]').replaceWith("");
                    // au clic sur le bouton annuler
                    cancelBtnComment();
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
        });
    }
    // SIGNALEMENT
    flagComment();
    function flagComment() {
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
    }
});
