/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.removePlugins = 'about,pagebreak,smiley,widget,uploadwidget,uploadimage,forms,flash,find';
	config.extraPlugins = [  "base64image", "base64pdf", "bootstrapTabs" ] ;
	config.allowedContent = true ;
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    }
