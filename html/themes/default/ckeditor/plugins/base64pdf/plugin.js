CKEDITOR.plugins.add( 'base64pdf', {
    icons: 'pdf',
    init: function( editor ) {
	editor.addCommand("base64pdfDialog", new CKEDITOR.dialogCommand("base64pdfDialog"));
        editor.ui.addButton( 'pdf', {
            label: 'Insert Pdf',
            command: 'base64pdfDialog',
            toolbar: 'insert'
        });
        CKEDITOR.dialog.add("base64pdfDialog", this.path+"dialogs/base64pdf.js");
    }
});
