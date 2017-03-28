( function() {
    tinymce.PluginManager.add( 'soulbuttons', function( editor, url ) {
        editor.addButton( 'soulbuttons_shortcode', {
            image: soulbuttons.icon,
            onclick: function() {
                var win = editor.windowManager.open( {
                    title: soulbuttons.texts.add_dialog_title,
                    bodyType: 'tabpanel',
                    body: [{
                        type: 'form',
                        title: soulbuttons.texts.main_label,
                        items: [
                        {
                            type: 'textbox',
                            name: 'link',
                            value: '#',
                            label: soulbuttons.texts.link_label,
                        },
                        {
                            type: 'textbox',
                            name: 'content',
                            value: '',
                            label: soulbuttons.texts.text_label,
                        },
                        {
                            type: 'listbox',
                            name: 'style',
                            label: soulbuttons.texts.style_label,
                            values: soulbuttons.texts.style_options,
                        }
                        ],
                    },{
                        type: 'form',
                        title: soulbuttons.texts.advanced_label,
                        items: [
                          {
                            type: 'listbox',
                            name: 'align',
                            label: soulbuttons.texts.align_label,
                            values: soulbuttons.texts.align_options,
                          },{
                            type: 'textbox',
                            name: 'icon',
                            label: soulbuttons.texts.icon_label,
                            tooltip: soulbuttons.texts.icon_tooltip,
                          },{
                            type: 'listbox',
                            name: 'icon-position',
                            label: soulbuttons.texts.iconpos_label,
                            values: soulbuttons.texts.iconpos_options,
                          },{
                            type: 'textbox',
                            name: 'target',
                            label: soulbuttons.texts.target_label,
                            tooltip: soulbuttons.texts.target_tooltip,
                          },{
                            type: 'listbox',
                            name: 'target-effect',
                            label: soulbuttons.texts.targeteffect_label,
                            values: soulbuttons.texts.targeteffect_options,
                        }
                        ],
                    }],
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
