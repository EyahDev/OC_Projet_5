$(document).ready(function () {
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
            var urlNewRoute = $('#paginationCategories').attr('url');
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
                    //editCategory();
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
        var urlNewRoute = $('#paginationCategories').attr('url');
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
        var urlNewRoute = $('#paginationCategories').attr('url');
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

    // SUPPRESSION DE LA QUESTION/REPONSE
    removeCategory();

    // fonction permettant de traiter la suppression d'une question/réponse
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
                    // ajoute le message flash
                    addFlashMsgManageCategories('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgManageCategories('danger', "Une erreur est survenue")
                }
            })
        });
    }

    // EDITION D'UNE QUESTION/REPONSE
    editCategory();

    // fonction permettant de traiter la modification d'une question/réponse
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
                                    // modifie la ligne du tableau qui vient d'etre modifié
                                    reloadCategoriesTableAfterAddingOrModifying();
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-faq').off('click', edit);
                                });
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


});