$(document).ready(function () {
    /*----------------------------------------COLLAPSE GERER LES CATEGORIES------------------------------------------*/

    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgManageCategories(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgManageCategories').html(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlashMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlashMsg, 5000);
        }
    }
    // PAGINATION
    paginateCategories();

    //fonction permettant de traiter la pagination
    function paginateCategories() {
        $('#paginationCategories').find('a').on('click', function(e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-table-categories').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute+argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-categories').replaceWith(data);
                    // réactive les écouteurs
                    removeCategory();
                    editCategory();
                    paginateCategories();
                }, error: function() {
                    addFlashMsgManageCategories('danger', 'Une erreur est survenue')
                }
            })
        });
    }
    // RECHARGEMENT

    // fonction permettant de recharger le tableau apres ajout ou modification d'une categories
    function reloadCategoriesTableAfterAddingOrModifying() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationCategories').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-categories').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-categories').replaceWith(data);
                // relance les écouteurs d'evenement
                removeCategory();
                editCategory();
                paginateCategories();
            }
        })
    }

    //fonction permettant de recharger le tableau après la suppression d'une categorie
    function reloadCategoriesTableAfterRemoving() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationCategories').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // change le numéro de page si la suppression vide le tableau de la page courrante
        if ($('.table-line-categories').length == 1 && parseInt(currentPageNb) > 1) {
            var prevPage = parseInt(currentPageNb)-1;
            argGet = '?page='+prevPage.toString();
        }
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-categories').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-categories').replaceWith(data);
                // relance les écouteurs d'evenement
                removeCategory();
                editCategory();
                paginateCategories();
            }
        })
    }

// SUPPRESSION DE LA CATEGORIE
    removeCategory();

    // fonction permettant de traiter la suppression d'une categorie
    function removeCategory() {
        $('.btn-rm-category').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // recharge le tableau apres suppression
                    reloadCategoriesTableAfterRemoving();
                    reloadWritePost();
                    // ajoute le message flash
                    addFlashMsgManageCategories('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManageCategories('danger', "Une erreur est survenue")
                }
            })
        });
    }

    // EDITION D'UNE CATEGORIE
    editCategory();

    // fonction permettant de traiter la modification d'une categorie
    function editCategory() {
        $('.btn-edit-category').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale d'edition
                    $a.parent().prepend(data);
                    $('#update_category_photoPath').css('display', 'none');
                    $('#update_category_save').css('margin-top', '10px');
                    $a.css('margin-right', '30px');
                    // affiche la modale d'édition
                    $a.prev().modal('show');
                    // en cas de fermeture  de la modale sans modification
                    $a.prev().on('hidden.bs.modal', function (e) {
                        // supprime la modale d'édition
                        $a.prev().replaceWith('');
                    });
                    // A la soumission du formulaire d'édition
                    $('form[name="update_category"]').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            success: function (data, text, jqxhr) {
                                $('.img-category-edit').replaceWith(data);
                                $('.img-category-edit').css("margin-bottom", '10px');
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Catégorie mise à jour</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 5000);
                                // a la fermeture de la modale
                                $a.prev().on('hidden.bs.modal', function (e) {
                                    // efface la modale d'édition
                                    $a.prev().replaceWith('');
                                    addFlashMsgManageCategories('success', 'Catégorie mise à jour');
                                    // recharge le tableau de catégories et le formulaire de redaction d'un article
                                    reloadCategoriesTableAfterAddingOrModifying();
                                    reloadWritePost();
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-faq').off('click', edit);
                                });
                                $a.prev().modal('hide');
                            },
                            error: function (jqxhr) {
                                var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 15000);
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsgManageCategories('danger', "Une erreur est survenue")
                }
            });
        });
    }

    //CREATION D'UNE CATEGORIE
    createCategory();
    //définition de la fonction pour l'appliquer récursivement
    function createCategory() {
        $('form[name="create_category"]').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $('.btn-user-create-category').attr('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data){
                    reloadCategoriesTableAfterAddingOrModifying();
                    reloadWritePost();
                    $('#create_category_name').val('');
                    $('#create_category_photoPath').val('');
                    addFlashMsgManageCategories('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManageCategories('danger', jqxhr.responseText);

                }
            })
        })
    }
    /*----------------------------------------COLLAPSE ECRIRE UN ARTICLES--------------------------------------------*/
    //CREATION RAPIDE D'UNE CATEGORIE
    createCategoryQuickly();

    function createCategoryQuickly() {
        $('form[name="create_category_quickly"]').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $('.btn-user-create-category-quickly').attr('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data){
                    $('#create_category_quickly_name').val('');
                    $('#create_category_quickly_photoPath').val('');
                    // ajoute un message flash
                    var appendCode = '<div class="flash-msg alert alert-success">Catégorie ajoutée</div>';
                    $form.parent().prepend(appendCode);
                    // efface le message flash apres 5 secondes
                    function removeFlashMsg(){
                        $('.flash-msg').replaceWith("");
                    }
                    setTimeout(removeFlashMsg, 5000);
                    // a la fermeture de la modale
                    $('#quickCategoryModal').on('hidden.bs.modal', function (e) {
                        reloadWritePost();
                        reloadCategoriesTableAfterAddingOrModifying();
                    });
                },
                error: function (jqxhr) {
                    // ajoute un message flash
                    var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                    $form.parent().prepend(appendCode);
                    // efface le message flash apres 15 secondes
                    function removeFlashMsg(){
                        $('.flash-msg').replaceWith("");
                    }
                    setTimeout(removeFlashMsg, 15000);
                }
            })
        })
    }

    //CREATION D'UN ARTICLE
    writePost();

    function writePost() {
        $('form[name="write_post"]').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $('.btn-user-write-post').attr('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data){
                    reloadWritePost();
                    reloadPostsTableAfterAddingOrModifying();
                    // ajoute un message flash
                    var appendCode = '<div class="flash-msg alert alert-success">'+data+'</div>';
                    $('#flashMsgWritePost').prepend(appendCode);
                    // efface le message flash apres 5 secondes
                    function removeFlashMsg(){
                        $('.flash-msg').replaceWith("");
                    }
                    setTimeout(removeFlashMsg, 5000);
                },
                error: function (jqxhr) {
                    // ajoute un message flash
                    var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                    $('#flashMsgWritePost').prepend(appendCode);
                    // efface le message flash apres 15 secondes
                    function removeFlashMsg(){
                        $('.flash-msg').replaceWith("");
                    }
                    setTimeout(removeFlashMsg, 15000);
                }
            })
        })
    }

    /* RECHARGEMENT */

    // fonction permettant de recharger le tableau apres ajout ou modification d'une categories
    function reloadWritePost() {
        // récupère la route
        var url = $('#collapseWritePostContent').attr('url');
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                tinymce.remove();
                // actualise le contenu du collapse writePost
                $('#collapseWritePostContent').replaceWith(data);
                $('#write_post_imagePath').css('display', 'none');
                $('#write_post_content_ifr').css('height', '300');
                // relance tinyMCE au chargement de la page
                tinymce.init({
                    selector: 'textarea.tinyMCE',
                    setup: function (editor) {
                        editor.on('change', function (e) {
                            editor.save();
                        });
                    }
                });
                // relance les écouteurs d'evenement
                createCategoryQuickly();
                writePost();
            }
        })
    }

    /*----------------------------------------COLLAPSE GERER LES ARTICLES-------------------------------------------*/

    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgManagePosts(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgManagePosts').html(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlashMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlashMsg, 5000);
        }
    }
    // PAGINATION
    paginatePosts();

    //fonction permettant de traiter la pagination
    function paginatePosts() {
        $('#paginationPosts').find('a').on('click', function(e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-table-posts').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute+argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-posts').replaceWith(data);
                    // réactive les écouteurs
                    removePost();
                    editPost();
                    paginatePosts();
                }, error: function() {
                    addFlashMsgManagePosts('danger', 'Une erreur est survenue')
                }
            })
        });
    }
    // RECHARGEMENT

    // fonction permettant de recharger le tableau apres ajout ou modification d'une categories
    function reloadPostsTableAfterAddingOrModifying() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationPosts').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-posts').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-posts').replaceWith(data);
                // relance les écouteurs d'evenement
                removePost();
                editPost();
                paginatePosts();
            }
        })
    }

    //fonction permettant de recharger le tableau après la suppression d'une categorie
    function reloadPostsTableAfterRemoving() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationPosts').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // change le numéro de page si la suppression vide le tableau de la page courrante
        if ($('.table-line-posts').length == 1 && parseInt(currentPageNb) > 1) {
            var prevPage = parseInt(currentPageNb)-1;
            argGet = '?page='+prevPage.toString();
        }
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-posts').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-posts').replaceWith(data);
                // relance les écouteurs d'evenement
                removePost();
                editPost();
                paginatePosts();
            }
        })
    }

    // SUPPRESSION D'UN ARTICLE
    removePost();

    // fonction permettant de traiter la suppression d'un article
    function removePost() {
        $('.btn-rm-post').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // recharge le tableau apres suppression
                    reloadPostsTableAfterRemoving();
                    // ajoute le message flash
                    addFlashMsgManagePosts('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManagePosts('danger', "Une erreur est survenue")
                }
            })
        });
    }

    // EDITION D'UN ARTICLE
    editPost();

    // fonction permettant de traiter la modification d'un article
    function editPost() {
        $('.btn-edit-post').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale d'edition
                    $a.parent().prepend(data);
                    $('#update_post_imagePath').css('display', 'none');
                    $('#update_post_save').css('margin-top', '10px');
                    $a.css('margin-right', '30px');
                    // relance tinyMCE au chargement de la page
                    tinymce.init({
                        selector: 'textarea.tinyMCE',
                        setup: function (editor) {
                            editor.on('change', function (e) {
                                editor.save();
                            });
                        }
                    });
                    $('#update_post_content_ifr').css('height', '300');
                    // affiche la modale d'édition
                    $a.prev().modal('show');
                    // en cas de fermeture  de la modale sans modification
                    $a.prev().on('hidden.bs.modal', function (e) {
                        tinymce.remove();
                        // supprime la modale d'édition
                        $a.prev().replaceWith('');
                    });
                    // A la soumission du formulaire d'édition
                    $('form[name="update_post"]').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: new FormData(this),
                            processData: false,
                            contentType: false,
                            success: function (data, text, jqxhr) {
                                $('.img-post-edit').replaceWith(data);
                                $('.img-post-edit').parent().css('margin-bottom', '10px');
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Article mis à jour</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 5000);

                                // a la fermeture de la modale
                                $a.prev().on('hidden.bs.modal', function (e) {
                                    tinymce.remove();
                                    // efface la modale d'édition
                                    $a.prev().replaceWith('');
                                    // modifie la ligne du tableau qui vient d'etre modifié
                                    reloadPostsTableAfterAddingOrModifying();
                                    reloadWritePost();
                                    //affiche un message flash de confirmation
                                    addFlashMsgManagePosts('success', "Article mis à jour.");
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-post').off('click', edit);
                                });
                                // ferme la modale
                                $a.prev().modal('hide');
                            },
                            error: function (jqxhr) {
                                var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlashMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlashMsg, 15000);
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsgManagePosts('danger', "Une erreur est survenue")
                }
            });
        });
    }

    /*------------------------------------COLLAPSE MODERATION DES COMMENTAIRES---------------------------------------*/

    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgCommentsModeration(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgCommentsModeration').html(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlashMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlashMsg, 5000);
        }
    }

    // PAGINATION
    paginateComments();

    //fonction permettant de traiter la pagination
    function paginateComments() {
        $('#paginationCommentsModeration').find('a').on('click', function(e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-table-comments-moderation').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute+argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-comments-moderation').replaceWith(data);
                    // réactive les écouteurs
                    // removeComment();
                    // approuvecomment();
                    paginateComments();
                }, error: function() {
                    addFlashMsgCommentsModeration('danger', 'Une erreur est survenue')
                }
            })
        });
    }
    // RECHARGEMENT

    //fonction permettant de recharger le tableau après la suppression d'une categorie
    function reloadCommentsTable() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationCommentsModeration').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // change le numéro de page si la suppression vide le tableau de la page courrante
        if ($('.table-line-posts').length == 1 && parseInt(currentPageNb) > 1) {
            var prevPage = parseInt(currentPageNb)-1;
            argGet = '?page='+prevPage.toString();
        }
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-comments-moderation').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-comments-moderation').replaceWith(data);
                // relance les écouteurs d'evenement
                removeComment();
                approuveComment();
                paginateComments();
            },
            error: function() {
                addFlashMsgCommentsModeration('danger', 'Une erreur est survenue')
            }
        })
    }

    // SUPPRESSION D'UN COMMENTAIRE
    removeComment();

    // fonction permettant de traiter la suppression d'un article
    function removeComment() {
        $('.btn-delete-comment').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // recharge le tableau apres suppression
                    reloadCommentsTable();
                    // ajoute le message flash
                    addFlashMsgCommentsModeration('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgCommentsModeration('danger', "Une erreur est survenue")
                }
            })
        });
    }

    // SUPPRESSION D'UN COMMENTAIRE
    approuveComment();

    // fonction permettant de traiter la suppression d'un article
    function approuveComment() {
        $('.btn-approuve-comment').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // recharge le tableau apres suppression
                    reloadCommentsTable();
                    // ajoute le message flash
                    addFlashMsgCommentsModeration('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgCommentsModeration('danger', "Une erreur est survenue")
                }
            })
        });
    }
});
