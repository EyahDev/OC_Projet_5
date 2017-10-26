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

    // DESACTIVER COMPTE
    $('.btn-disable-account').on('click', function(e) {
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        $.ajax({
            type:'GET',
            url: url,
            success: function (data) {
                // efface la ligne du tableau correspondant à la question/réponse supprimée
                $a.parentsUntil('tbody').children('.btn-enable-account-modal').show();
                $a.parentsUntil('tbody').children('.btn-disable-account-modal').hide();
                // ajoute le message flash
                addFlashMsgUserManagement('success', data);

            },
            error: function (jqxhr) {
                addFlashMsgUserManagement('danger', jqxhr.responseText)
            }
        })
    });

    // REACTIVER COMPTE
    $('.btn-enable-account').on('click', function(e) {
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        $.ajax({
            type:'GET',
            url: url,
            success: function (data) {
                // efface la ligne du tableau correspondant à la question/réponse supprimée
                $a.parentsUntil('tbody').children('.btn-enable-account-modal').hide();
                $a.parentsUntil('tbody').children('.btn-disable-account-modal').show();
                // ajoute le message flash
                addFlashMsgUserManagement('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgUserManagement('danger', jqxhr.responseText)
            }
        })
    });

    // CHANGER ROLE
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
                                // modifie l'affichage du role qui vient d'etre modifié
                                var newRole = '<div class="col-sm-5 role-name">'+data+'</div>';
                                $a.parent().prev().replaceWith(newRole);
                                // retire l'ecoute de l'évenement clic sur un bouton modifier role
                                $('.btn-change-role-modal').off('click', edit);
                                changeRole();
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
                                    // modifie l'affichage du role qui vient d'etre modifié
                                    var newRole = '<div class="col-sm-5 role-name">'+data+'</div>';
                                    $a.parent().prev().replaceWith(newRole);
                                    // retire l'ecoute de l'évenement clic sur un bouton modifier role
                                    $('.btn-change-role-modal').off('click', edit);
                                    changeRole();
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
});
