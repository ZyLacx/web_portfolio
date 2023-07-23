CKEDITOR.plugins.add( 'video', {
    icons: 'video',
    
    init: function( editor ) {
        editor.addCommand('video', new CKEDITOR.dialogCommand('videoDialog'));
        editor.ui.addButton( 'Video', {
            label: 'Pridať video',
            command: 'video',
            toolbar: 'insert, 20'
        });

        CKEDITOR.dialog.add( 'videoDialog', this.path + 'dialogs/video.js' );
    }
});
