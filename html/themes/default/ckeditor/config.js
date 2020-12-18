/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:
    config.removePlugins = 'about,pagebreak,smiley,forms,flash,find';
    config.extraPlugins = ["base64pdf", "bootstrapTabs"];
    config.allowedContent = true;
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    //  Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
    config.toolbar = [{
            name: 'styles',
            items: ['Styles', 'Format', 'Font', 'FontSize']
        },
        {
            name: 'undo',
            items: ['Undo', 'Redo']
        },
        {
            name: 'find',
            items: ['Find', 'Replace']
        },
        '/',
        {
            name: 'clipboard',
            items: ['Cut', 'Copy', 'Paste']
        },
        {
            name: 'list',
            items: ['NumberedList', 'BulletedList']
        },
        {
            name: 'editing',
            groups: ['selection', 'spellchecker'],
            items: ['SelectAll', '-', 'Scayt']
        },
        {
            name: 'forms',
            items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton']
        },
        {
            name: 'insert',
            items: ['Image', 'SpecialChar']
        },
        {
            name: 'blocks',
            items: ['Blockquote']
        },
        {
            name: 'indent',
            items: ['Outdent', 'Indent']
        },
        {
            name: 'bidi',
            items: ['BidiLtr', 'BidiRtl']
        },
        '/',
        {
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
        },
        {
            name: 'colors',
            items: ['TextColor', 'BGColor']
        },
        {
            name: 'cleanup',
            items: ['CopyFormatting', 'RemoveFormat']
        },
        {
            name: 'align',
            items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
        },
        {
            name: 'links',
            items: ['Link', 'Unlink']
        }
    ];
}