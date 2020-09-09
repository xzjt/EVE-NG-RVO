CKEDITOR.dialog.add("base64pdfDialog", function(editor){
	return {
        title:          'EVE-NG PDF plugin',
	minWidth : 400,
	minHeight : 200,
	onShow: function () {
		t = this;
	},
	onOk: function () {
		var fileI = t.getContentElement("pdfMain", "file");
		try { n = fileI.getInputElement().$; } catch(e) { n = null; }
                if(n && "files" in n && n.files && n.files.length > 0 && n.files[0]) {
                if("type" in n.files[0] && !n.files[0].type.match("application/pdf")) return;
                                if(!FileReader) return;
                                var fr = new FileReader();
				fr.readAsDataURL(n.files[0]);
                                fr.onload = (function(f) { return function(e) {
					var pdfdata =e.target.result.replace(/.*,/,'');
					editor.setData ('<div style="widht:100%;height: 100%;"><iframe width="100%"  height="100%" id="MyPdf"></iframe></div> <script> \n'+
							            'var pdfbase64String = "'+ pdfdata +'"; \n'+
        						   	    'var pdfbyteCharacters = atob(pdfbase64String);\n'+
        						   	    'var pdfbyteNumbers = new Array(pdfbyteCharacters.length);\n'+
        						    	    'for (let i = 0; i < pdfbyteCharacters.length; i++) { \n'+
							            '	pdfbyteNumbers[i] = pdfbyteCharacters.charCodeAt(i);\n'+
        						   	    '}\n'+
        						   	    'var pdfbyteArray = new Uint8Array(pdfbyteNumbers);\n'+
        						   	    'var pdfblob = new Blob([pdfbyteArray], {type: \'application/pdf\'})\n'+
        						   	    'var pdfblobUrl = URL.createObjectURL(pdfblob);\n'+
        						   	    'document.getElementById(\'MyPdf\').setAttribute(\'src\', pdfblobUrl);\n'+
							       '</script>') ;
                                }; })(n.files[0]);
                        }
	},
	contents: [
			{
				id: "pdfMain",
				label: "PDF Uploader",
				title:      'PDF Uploader',
				elements : [
					{   type: "file",
					    id:   "file",
				 	    label: "Choose PDF File to import....</br></br>This will replace the content of the current Task.</br></br></hr>"
					}
				]
			}
		 ]
	};
});
