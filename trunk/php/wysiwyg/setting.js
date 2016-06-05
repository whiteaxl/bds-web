<!--
/* =============================================================== *\
|		Module name: WYSIWYG Editor									|
|		Module version: 2.0											|
|																	|
\* =============================================================== */

// Configuration --------------------------
var editor_lang_dir			= 'english/';
var editor_lang_direction	= ''; // "blank" or "RTL"
//var editor_button_paragraph	= new Array('Normal', 'Heading 1', 'Heading 2', 'Heading 3', 'Heading 4', 'Heading 5', 'Heading 6', 'Address', 'Formatted');
var editor_button_paragraph	= new Array('H1:: Heading 1', 'H2:: Heading 2', 'H3:: Heading 3', 'H4:: Heading 4', 'H5:: Heading 5', 'H6:: Heading 6', 'PRE:: Preformatted', 'P:: Normal');
var editor_button_font		= new Array('Arial', 'Courier New', 'Verdana', 'Tahoma', 'Times New Roman');
var editor_button_size		= new Array(1, 2, 3, 4, 5, 6, 7);
//-----------------------------------------

//Browser identification
editor_browser		= navigator.userAgent.toLowerCase();
var editor_is_ie	= ((editor_browser.indexOf("msie") != -1) && (editor_browser.indexOf("opera") == -1));
var editor_is_opera	= (editor_browser.indexOf("opera") != -1);
var editor_is_gecko	= (navigator.product == "Gecko");

-->