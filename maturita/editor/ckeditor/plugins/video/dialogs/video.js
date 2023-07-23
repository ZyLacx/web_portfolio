CKEDITOR.dialog.add('videoDialog', function (editor){
    return {
        title: 'Pridať súbor na stiahnutie',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'file-add',
                label: 'Pridať video',
                elements: [{
                    type: 'text',
                    id: 'url',
                    label: 'URL videa',
                    validate: CKEDITOR.dialog.validate.notEmpty('Pole URL súboru nemôže byť prázdne')
                },{
                    type: 'button',
                    id: 'browse',
                    style: 'display:inline-block;margin-top:14px;',
                    align: 'center',
                    label: editor.lang.common.browseServer,
                    filebrowser: {action:"Browse",target:"file-add:url",url:'functions/upload.php?video=true'}
                },{
                    type: 'checkbox',
                    id: 'controls',
                    label: 'Povoliť ovládacie prvky'
                },{
                    type: 'checkbox',
                    id: 'autoplay',
                    label: 'Opakovať prehrávanie'
                },{
                    type: 'checkbox',
                    id: 'muted',
                    label: 'Stlmiť zvuk'
                }]
            }
        ],
        onOk: function () {
            var dialog = this;
            var muted = dialog.getValueOf('file-add', 'muted');
            var autoplay = dialog.getValueOf('file-add', 'autoplay');
            var controls = dialog.getValueOf('file-add', 'controls');

            var video = new CKEDITOR.dom.element('video');
            video.setStyle('width', '100%');
            video.setStyle('height', 'auto');

            if(muted)
                video.setAttribute('muted');
            if(autoplay)
                video.setAttribute('autoplay');
            if(controls)
                video.setAttribute('controls');
            
            var fileURL = dialog.getValueOf('file-add', 'url');

            var source = new CKEDITOR.dom.element('source');
            source.setAttribute('src', fileURL);

            video.append(source);

            // if(dialog.getValueOf('file-add', 'filetext') == undefined || dialog.getValueOf('file-add', 'filetext') == ""){
            //     title = URLarray[URLarray.length - 1];
            // }
            // else{        
            //     title = dialog.getValueOf('file-add', 'filetext');
            // }
            

            // let div = new CKEDITOR.dom.element( 'div' );
            // div.addClass('download-div');
            
            // let a = new CKEDITOR.dom.element('a');

            // a.setAttributes({
            //     'href': fileURL,
            //     'download': ''
            // });

            // a.appendText(title);
            // div.append(a);

            editor.insertElement(video);
        }

    };
});