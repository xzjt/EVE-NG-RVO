/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.removePlugins = 'about,pagebreak,smiley,forms,flash,find';
	config.extraPlugins = [  "base64pdf", "bootstrapTabs", 'image2' ] ;
	config.allowedContent = true ;
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    }
