// fonction permettant d'ajouter un message flash dynamiquement avec un message
// et une couleur (couleur bootstrap (danger,primary...)) variables
function addFlashMsgAccount(type, message) {
    // construit le html pour le message flash
    var appendCode = '<div class="flash-msg alert alert-'+type+'">'+message+'</div>';
    // ajoute le message flash dans la div dédiée
    $('#flashMsgAccount').html(appendCode);
    if(type != 'danger') {
        // efface le message flash apres 5 secondes
        function removeFlagMsg(){
            $('.flash-msg').replaceWith("");
        }
        setTimeout(removeFlagMsg, 5000);
    }
}
$(document).ready(function () {
    // MODIFICATION DU NOM
    $('form[name="update_user_name"]').on('submit', function (e) {
       e.preventDefault();
       var $form = $(this);
       var url = $('.btn-user-update-name').attr('url');
       $.ajax({
           type: 'POST',
           url: url,
           data: $form.serialize(),
           success: function (data){
               addFlashMsgAccount('success', data);
           },
           error: function (jqxhr) {
               addFlashMsgAccount('danger', jqxhr.responseText);

           }
       })
    });
    //MODIFICATION DU PRENOM
    $('form[name="update_user_firstname"]').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $('.btn-user-update-firstname').attr('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: $form.serialize(),
            success: function (data){
                addFlashMsgAccount('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgAccount('danger', jqxhr.responseText);

            }
        })
    })
    //MODIFICATION DE L'ADRESSE
    $('form[name="update_user_location"]').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $('.btn-user-update-location').attr('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: $form.serialize(),
            success: function (data){
                addFlashMsgAccount('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgAccount('danger', jqxhr.responseText);

            }
        })
    })
    //MODIFICATION DE L'INSCRIPTION A LA NEWSLETTER
    $('form[name="update_user_newsletter"]').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $('.btn-user-update-newsletter').attr('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: $form.serialize(),
            success: function (data){
                addFlashMsgAccount('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgAccount('danger', jqxhr.responseText);

            }
        })
    })
    //MODIFICATION DU MOT DE PASSE
    $('form[name="update_password"]').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var url = $('.btn-user-update-password').attr('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: $form.serialize(),
            success: function (data){
                addFlashMsgAccount('success', data);
            },
            error: function (jqxhr) {
                addFlashMsgAccount('danger', jqxhr.responseText);

            }
        })
    });
    //MODIFICATION DE L'AVATAR
    updateAvatar();
    //définition de la fonction pour l'appliquer récursivement
    function updateAvatar() {
        $('form[name="update_user_avatar"]').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $('.btn-user-update-avatar').attr('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (data){
                    $('.update-avatar-section').replaceWith(data);
                    addFlashMsgAccount('success', 'avatar modifié');
                    updateAvatar();
                },
                error: function (jqxhr) {
                    addFlashMsgAccount('danger', jqxhr.responseText);

                }
            })
        })
    }
});

