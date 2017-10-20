$(document).ready(function() {
    // AJOUT QUESTION/REPONSE
    $('.btn-add-faq').on('click', function(e){
        e.preventDefault();
        var $a = $(this);
        var url = $a.attr('href');
        $.ajax({
            type: 'GET',
            url: url,
            success: function(data) {
                $a.parent().prepend(data);
                $a.hide();

            }
        });
    })
});
