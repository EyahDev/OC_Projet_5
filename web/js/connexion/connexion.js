$(document).ready(function () {
    // CHANGER ROLE
    login();

    // fonction permettant de réactiver la modification du role après qu'elle ait déjà été faite
    function login() {
        $('.btn-connexion-modal').on('click', function edit(e){
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type: 'GET',
                url: url,
                success: function(data) {
                    // ajoute la modale
                    $('body').prepend(data);
                    // affiche la modale
                    $('#connexionModal').modal('show');
                   // en cas de fermeture  de la modale sans modification
                    $('#connexionModal').on('hidden.bs.modal', function (e) {
                        // supprime la modale
                        $('#connexionModal').replaceWith('');
                    });
                    // A la soumission du formulaire de changement de role
                    $('#connexion-form').on('submit', function(e){
                        e.preventDefault();
                        var $form = $(this);
                        url = $a.attr('href');
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: $form.serialize(),
                            success: function () {
                                location.reload(true);
                            },
                            error: function (jqxhr) {
                                if(jqxhr.status === 500) {
                                    $('.flash-msg-cnx').replaceWith('<div class="alert alert-danger justify-content-center flash-msg-cnx">Compte désactivé, contactez l\'administrateur pour plus d\'informations.</div>');
                                }else {
                                    $('.flash-msg-cnx').replaceWith(jqxhr.responseText);
                                }
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
