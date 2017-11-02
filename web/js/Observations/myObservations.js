$(document).ready(function() {
    //PAGINATION
    paginateMyObservations();
    //définition de la fonction pour l'appliquer récursivement
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
                    editCurrentUserObservations();
                    paginateMyObservations();
                }, error: function () {
                    addFlashMsgMyObservations('danger', 'Une erreur est survenue')
                }
            })
        });
    }

    // EDITION D'UNE OBSERVATION
    editCurrentUserObservations();

    // fonction permettant de traiter la modification d'une question/réponse
    function editCurrentUserObservations() {
        $('.btn-edit-current-user-observation').on('click', function edit(e){
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
                    $('form[name="modif_observation_by_observer"]').on('submit', function(e){
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
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Observation modifiée</div>';
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
                                    reloadTableMyObservations();
                                    // retire l'ecoute de l'évenement clic sur un bouton edit
                                    $('.btn-edit-current-user-observation').off('click', edit);
                                   editCurrentUserObservations();
                                });
                            },
                            error: function (jqxhr) {
                                var appendCode = '<div class="flash-msg alert alert-danger">'+jqxhr.responseText+'</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlagMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsgMyObservations('danger', "Une erreur est survenue")
                }
            });
        });
    }

    // fonction permettant de recharger le tableau  modification d'une observation
    function reloadTableMyObservations() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationMyObservations').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // récupère la route pour la pagination
        var urlNewRoute = $('#paginationMyObservations').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-my-observations').replaceWith(data);
                // relance les écouteurs d'evenement
                editCurrentUserObservations();
                paginateMyObservations();
            }
        })
    }

    // MESSAGE FLASH
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgMyObservations(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgMyObservations').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }

});

