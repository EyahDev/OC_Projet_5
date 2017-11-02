$(document).ready(function() {

    // MESSAGE FLASH
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgFaq(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgFaq').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }

    // AJOUT QUESTION/REPONSE
    $('.btn-add-faq').on('click', function(e){
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        $.ajax({
            type: 'GET',
            url: url,
            success: function(data) {
                // modifie la div contenant le bouton ajouter une question
                $a.parent().removeClass("mx-auto");
                $a.parent().prepend(data);
                $a.hide();
                // a la soumission du formulaire
                $('form[name="new_faq"]').on('submit', function(e){
                    e.preventDefault();
                    var $form = $(this);
                    url = $a.attr('href');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $form.serialize(),
                        success: function (data, text, jqxhr) {
                            $('.btn-add-faq').fadeIn( "fast", function() {
                            });
                            $form.replaceWith('');
                            reloadTableFaqAfterAddingOrModifying();
                            addFlashMsgFaq('success', "Question ajoutée");
                            // retire l'ecoute de l'évenement clic sur un bouton edit
                            $('.btn-edit-faq').off('click');
                            removeFaq();
                            editFaq();
                        },
                        error: function (jqxhr) {
                            addFlashMsgFaq('danger', jqxhr.responseText);
                        }
                    })
                })

            },
            error: function() {
                addFlashMsgFaq('danger', "Une erreur est survenue")
            }
        });
    });

    // EDITION D'UNE QUESTION/REPONSE
    editFaq();

    // fonction permettant de traiter la modification d'une question/réponse
    function editFaq() {
        $('.btn-edit-faq').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale d'edition
                    $a.parent().prepend(data);
                    // affiche la modale d'édition
                    $a.prev().modal('show');
                    // en cas de fermeture  de la modale sans modification
                    $a.prev().on('hidden.bs.modal', function (e) {
                        // supprime la modale d'édition
                        $a.prev().replaceWith('');
                    });
                    // A la soumission du formulaire d'édition
                    $('form[name="edit_faq"]').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: $form.serialize(),
                            success: function (data, text, jqxhr) {
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Question modifiée</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlagMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlagMsg, 5000);
                                // a la fermeture de la modale
                                $a.prev().on('hidden.bs.modal', function (e) {
                                    // efface la modale d'édition
                                    $a.prev().replaceWith('');
                                    // modifie la ligne du tableau qui vient d'etre modifié
                                    reloadTableFaqAfterAddingOrModifying();
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-faq').off('click', edit);
                                    removeFaq();
                                    editFaq();
                                });
                            },
                            error: function (jqxhr) {
                                var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlagMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlagMsg, 5000);
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsgFaq('danger', "Une erreur est survenue")
                }
            });
        });
    }

    // fonction permettant de recharger le tableau apres ajout ou modification d'une question / réponse
    function reloadTableFaqAfterAddingOrModifying() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationFaq').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // récupère la route pour la pagination
        var urlNewRoute = $('#paginationFaq').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-faq').replaceWith(data);
                // relance les écouteurs d'evenement
                removeFaq();
                editFaq();
                paginateFaq();
            }
        })
    }

    // SUPPRESSION DE LA QUESTION/REPONSE
    removeFaq();

    // fonction permettant de traiter la suppression d'une question/réponse
    function removeFaq() {
        $('.btn-rm-faq').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // recharge le tableau apres suppression
                    reloadTableFaqAfterRemoving();
                    // ajoute le message flash
                    addFlashMsgFaq('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgFaq('danger', "Une erreur est survenue")
                }
            })
        });
    }

    //fonction permettant de recharger le tableau après la suppression d'une question / réponse
    function reloadTableFaqAfterRemoving() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationFaq').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // change le numéro de page si la suppression vide le tableau de la page courrante
        if ($('.table-line-faq').length == 1 && parseInt(currentPageNb) > 1) {
            var prevPage = parseInt(currentPageNb)-1;
            argGet = '?page='+prevPage.toString();
        }
        // récupère la route pour la pagination
        var urlNewRoute = $('#paginationFaq').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-faq').replaceWith(data);
                // relance les écouteurs d'evenement
                removeFaq();
                editFaq();
                paginateFaq();
            }
        })
    }

    // PAGINATION
    paginateFaq();

    //fonction permettant de traiter la pagination
    function paginateFaq() {
        $('#paginationFaq').find('a').on('click', function(e) {
            e.preventDefault();

            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('#paginationFaq').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute+argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-faq').replaceWith(data);
                    // réactive les écouteurs
                    removeFaq();
                    editFaq();
                    paginateFaq();
                }, error: function() {
                    addFlashMsgFaq('danger', 'Une erreur est survenue')
                }
            })
        });
    }
});


