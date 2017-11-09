$(document).ready(function() {

    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgUserManagement(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgUserManagement').append(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }
    function reloadTableUserManagement() {
        // récupère le num de la page courante
        var currentPageNb = $('#paginationUsersManagement').find('.current').text();
        if (currentPageNb == '') {
            currentPageNb = '1';
        }
        // crée l'argument pour la requete Get
        var argGet = '?page='+currentPageNb;
        // récupère la route pour la pagination
        var urlNewRoute = $('.pagination-table-users-management').attr('url');
        // concatene la route et l'argument get
        var newUrl = urlNewRoute+argGet;
        $.ajax({
            type: 'GET',
            url: newUrl,
            success: function (data) {
                // actualise le tableau
                $('.pagination-table-users-management').replaceWith(data);
                // relance les écouteurs d'evenement
                disableAccount();
                enableAccount();
                changeRole();
                paginateUsersManagement();
            }
        })
    }
    // DESACTIVER COMPTE
    disableAccount();
    function disableAccount() {
        $('.btn-disable-account').on('click', function (e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
                    //recharge le tableau
                    reloadTableUserManagement();
                    // ajoute le message flash
                    addFlashMsgUserManagement('success', data);

                },
                error: function (jqxhr) {
                    addFlashMsgUserManagement('danger', jqxhr.responseText)
                }
            })
        });
    }

    // REACTIVER COMPTE
    enableAccount()
    function enableAccount() {
        $('.btn-enable-account').on('click', function (e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
                    //recharge le tableau
                    reloadTableUserManagement();
                    // ajoute le message flash
                    addFlashMsgUserManagement('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsgUserManagement('danger', jqxhr.responseText)
                }
            })
        });
    }

    // CHANGER ROLE
    changeRole();

    // fonction permettant de réactiver la modification du role après qu'elle ait déjà été faite
    function changeRole() {
        $('.btn-change-role-modal').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale
                    $a.parent().prepend(data);
                    $a.css('margin-right', '30px');
                    // affiche la modale
                    $a.prev().modal('show');
                    // en cas de fermeture  de la modale sans modification
                    $a.prev().on('hidden.bs.modal', function (e) {
                        // supprime la modale
                        $a.prev().replaceWith('');
                    });
                    // A la soumission du formulaire de changement de role
                    $('form[name="change_role"]').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: $form.serialize(),
                            success: function (data) {
                                // ajoute un message flash
                                var appendCode = '<div class="flash-msg alert alert-success">Role modifié</div>';
                                $form.parent().prepend(appendCode);
                                // efface le message flash apres 5 secondes
                                function removeFlagMsg(){
                                    $('.flash-msg').replaceWith("");
                                }
                                setTimeout(removeFlagMsg, 5000);
                                // a la fermeture de la modale
                                $a.prev().on('hidden.bs.modal', function (e) {
                                    // efface la modale
                                    $a.prev().replaceWith('');
                                    //recharge le tableau
                                    reloadTableUserManagement()
                                });
                            },
                            error: function (jqxhr) {
                                addFlashMsgUserManagement('danger', jqxhr.responseText);
                            }
                        })
                    })

                },
                error: function() {
                    addFlashMsg('danger', "Une erreur est survenue")
                }
            });
        });
    }

    //PAGINATION
    paginateUsersManagement();

    //fonction permettant de traiter la pagination
    function paginateUsersManagement() {
        $('#paginationUsersManagement').find('a').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            // récupère l'argument passer en get
            var argGet = url.substring(url.lastIndexOf('?'));
            // récupère la route pour la pagination
            var urlNewRoute = $('.pagination-table-users-management').attr('url');
            // concatene la route et l'argument
            var newUrl = urlNewRoute+argGet;
            $.ajax({
                type: 'GET',
                url: newUrl,
                success: function (data) {
                    // recharge le tableau
                    $('.pagination-table-users-management').replaceWith(data);
                    // réactive les écouteurs
                    enableAccount();
                    disableAccount();
                    changeRole();
                    paginateUsersManagement();
                }, error: function() {
                    addFlashMsgUserManagement('danger', 'Une erreur est survenue')
                }
            })
        });
    }
});
