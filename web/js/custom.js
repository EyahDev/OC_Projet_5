//Chargement de TinyMCE pour le dashboard
tinymce.init({
    selector: 'textarea.tinyMCE',
    setup: function (editor) {
        editor.on('change', function (e) {
            editor.save();
        });
    }
});