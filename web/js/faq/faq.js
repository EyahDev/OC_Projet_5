$(document).ready(function() {
    // fonction permettant d'ajouter une ligne au tableau de FAQ après la soumission du formulaire d'ajout
    function addFaq(data, form) {
        console.log(data);
        $('#tableFaq').children('tbody').append(data);
        $('.btn-add-faq').fadeIn( "fast", function() {
        });
        form.replaceWith('');
    }
    // fonction permettant d'ajouter le traitement de la suppression apres un ajout
    // ou une modification d'une question/réponse
    function removeFaq() {
        $('.btn-rm-faq').on('click', function(e) {
            e.preventDefault();
            var $a = $(this);
            var url = $a.attr('href');
            $.ajax({
                type:'GET',
                url: url,
                success: function (data) {
                    // efface la ligne du tableau correspondant à la question/réponse supprimée
                    $a.parentsUntil('tbody').replaceWith("");
                    addFlashMsg('success', data);
                },
                error: function (jqxhr) {
                    addFlashMsg('danger', "Une erreur est survenue")
                }
            })
        });
    }

    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsg(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgFaq').append(appendCode);
        // efface le message flash apres 5 secondes
        function removeFlagMsg(){
            $('.flash-msg').replaceWith("");
        }
        setTimeout(removeFlagMsg, 5000);
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
                $a.parent().removeClass("mx-auto");
                $a.parent().prepend(data);
                $a.hide();
                $('form[name="new_faq"]').on('submit', function(e){
                    e.preventDefault();
                    var $form = $(this);
                    url = $a.attr('href');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $form.serialize(),
                        success: function (data, text, jqxhr) {
                            addFaq(data, $form);
                            addFlashMsg('success', "Question ajoutée");
                            removeFaq();
                        },
                        error: function() {
                            addFlashMsg('danger', "Le formulaire comporte des erreurs (la question et la réponse doivent comporter au moins 2 caractères)")
                        }
                    })
                })

            },
            error: function() {
                addFlashMsg('danger', "Une erreur est survenue")
            }
        });
    });
    // SUPPRESSION DE LA QUESTION/REPONSE
    $('.btn-rm-faq').on('click', function(e) {
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        $.ajax({
            type:'GET',
            url: url,
            success: function (data) {
                //supprime la ligne de la question/réponse
                $a.parentsUntil('tbody').replaceWith("");
                addFlashMsg('success', data);
            },
            error: function (jqxhr) {

            }
        })
    });
    // EDITION D'UNE QUESTION/REPONSE
    $('.btn-edit-faq').on('click', function(e){
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        var faqId = $a.attr('faqid');
        $.ajax({
            type: 'GET',
            url: url,
            success: function(data) {
                $('.show').children().children().children('.modal-body').append(data);
                $('form[name="edit_faq"]').on('submit', function(e){
                    e.preventDefault();
                    var $form = $(this);
                    url = $a.attr('href');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $form.serialize(),
                        success: function (data, text, jqxhr) {
                            var appendCode = '<div class="flash-msg alert alert-success">Question modifiée</div>';
                            $form.parent().prepend(appendCode);
                            // efface le message flash apres 5 secondes
                            function removeFlagMsg(){
                                $('.flash-msg').replaceWith("");
                            }
                            setTimeout(removeFlagMsg, 5000);
                            $form.parentsUntil('.card-body').on('hidden.bs.modal', function (e) {
                                $form.replaceWith("");
                                $a.parentsUntil('tbody').replaceWith(data);
                                removeFaq();
                            });
                        },
                        error: function (jqxhr) {
                            var appendCode = '<div class="flash-msg alert alert-danger">Le formulaire comporte des erreurs (la question et la réponse doivent comporter au moins 2 caractères)</div>';
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
                addFlashMsg('danger', "Une erreur est survenue")
            }
        });
    });
});
