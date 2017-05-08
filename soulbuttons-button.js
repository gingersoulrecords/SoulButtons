( function() {
    tinymce.PluginManager.add( 'soulbuttons', function( editor, url ) {
        editor.addButton( 'soulbuttons_shortcode', {
            image: soulbuttons.icon,
            onclick: function() {
                var win = editor.windowManager.open( {
                    title: soulbuttons.texts.add_dialog_title,
                    bodyType: 'tabpanel',
                    body: soulbuttons.editor,
                    onsubmit: function( e ) {
                        var result = win.toJSON();
                        var atts = '';
                        var content = '';
                        for ( var key in result ) {
                            if ( !result[key] ) {
                                continue;
                            }
                            if ( 'content' === key ) {
                              content = result[key];
                              continue;
                            }
                            atts += ' ' + key  + '="' + result[key] + '"';
                        }
                        editor.insertContent( '[soulbutton' + atts + ']' + content + '[/soulbutton] ' );
                    }
                } );
            }
        } );
    } );
} )();
