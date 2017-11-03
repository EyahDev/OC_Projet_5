$(document).ready(function () {
    // traite la soumission du formulaire de contact
    $('form[name="contact_us"]').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this);
        var url = $('.btn-contact-us').attr('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: $form.serialize(),
            success: function (data){
                addFlashMsgContactUs('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgContactUs('danger', jqxhr.responseText);

            }
        })
    });
    // fonction permettant d'ajouter un message flash dynamiquement avec un message
    // et une couleur (couleur bootstrap (danger,primary...)) variables
    function addFlashMsgContactUs(type, message) {
        // construit le html pour le message flash
        var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
        // ajoute le message flash dans la div dédiée
        $('#flashMsgContactUs').html(appendCode);
        if(type != 'danger') {
            // efface le message flash apres 5 secondes
            function removeFlagMsg(){
                $('.flash-msg').replaceWith("");
            }
            setTimeout(removeFlagMsg, 5000);
        }
    }
});

