<!--
/* =============================================================== *\
|		Module name: WYSIWYG Editor									|
|		Module version: 2.1											|
\* =============================================================== */

//Config ----------------------------------------------------------
var editor_url_color		= editor_path + 'popup/popup_color.html';
var editor_url_image		= editor_path + 'popup/popup_insert_image.html';
var editor_url_table		= editor_path + 'popup/popup_insert_table.html';
var editor_url_row			= editor_path + 'popup/popup_insert_row.html';
var editor_url_col			= editor_path + 'popup/popup_insert_col.html';
var editor_url_mergecell	= editor_path + 'popup/popup_merge_cell.html';
var editor_url_find			= editor_path + 'popup/popup_find_text.html';
var editor_url_image		= editor_path + 'popup/popup_insert_image.html';
var editor_url_weblink		= editor_path + 'popup/popup_insert_weblink.html';

var editor_menu_full		= new Array(
	'paragraph', 'font_type', 'font_size', 'viewhtml', 'nextline',
	'selectall', 'cut', 'copy', 'past', 'separator', 'undo', 'redo', 'separator', 'find', 'insertimg', 'insertlink', 'removelink', 'separator', 'inserttable', 'insertrow', 'removerow', 'insertcol', 'removecol', 'mergecell', 'unmergecell', 'nextline',
	'bold', 'italic', 'underline', 'strike', 'separator', 'sub', 'sup', 'separator', 'fontcolor', 'bgcolor', 'removeformat', 'separator', 'left', 'center', 'right', 'justify', 'separator', 'numlist', 'bulletlist', 'increase', 'decrease', 'separator', 'dirLTR', 'dirRTL', 'hr'
);
var editor_menu_medium		= new Array(
	'undo', 'redo', 'separator', 'find', 'insertlink', 'removelink', 'separator', 'inserttable', 'insertrow', 'removerow', 'insertcol', 'removecol', 'mergecell', 'unmergecell', 'separator', 'viewhtml', 'nextline',
	'bold', 'italic', 'underline', 'strike', 'separator', 'fontcolor', 'bgcolor', 'removeformat', 'separator', 'left', 'center', 'right', 'justify', 'separator', 'numlist', 'bulletlist', 'increase', 'decrease', 'separator', 'dirLTR', 'dirRTL', 'hr'
);
var editor_menu_simple	= new Array(
	'bold', 'italic', 'underline', 'strike', 'separator', 'fontcolor', 'bgcolor', 'removeformat', 'separator', 'left', 'center', 'right', 'justify', 'separator', 'numlist', 'bulletlist', 'increase', 'decrease', 'separator', 'dirLTR', 'dirRTL', 'separator', 'viewhtml'
);
var editor_button_list	= {
//						function											image			Tool tip	Enabled
	'selectall': 		['editor_formatText("_htmlID_", "SelectAll")', 'selectall.gif', editor_lang['select_all'], 1],
	'cut': 				['editor_formatText("_htmlID_", "Cut")', 'cut.gif', editor_lang['cut'], 1],
	'copy': 			['editor_formatText("_htmlID_", "Copy")', 'copy.gif', editor_lang['copy'], 1],
	'past': 			['editor_formatText("_htmlID_", "Paste")', 'paste.gif', editor_lang['paste'], 1],
	'undo': 			['editor_formatText("_htmlID_", "Undo")', 'undo.gif', editor_lang['undo'], 1],
	'redo': 			['editor_formatText("_htmlID_", "Redo")', 'redo.gif', editor_lang['redo'], 1],
	'find': 			['editor_findText("_htmlID_")', 'find.gif', editor_lang['find'], 1],
	'insertimg': 		['editor_insertImage("_htmlID_")', 'image.gif', editor_lang['insert_image'], 1],
	'insertlink':		['editor_insertWebLink("_htmlID_")', 'wlink.gif', editor_lang['insert_weblink'], 1],
	'removelink':		['editor_removeWebLink("_htmlID_")', 'removewlink.gif', editor_lang['remove_weblink'], 1],
	'inserttable':		['editor_insertTable("_htmlID_")', 'table.gif', editor_lang['insert_table'], 1],
	'insertrow':		['editor_insertRow("_htmlID_")', 'insrow.gif', editor_lang['insert_row'], 1],
	'removerow':		['editor_removeRow("_htmlID_")', 'removerow.gif', editor_lang['remove_row'], 1],
	'insertcol':		['editor_insertCol("_htmlID_")', 'inscol.gif', editor_lang['insert_col'], 1],
	'removecol':		['editor_removeCol("_htmlID_")', 'removecol.gif', editor_lang['remove_col'], 1],
	'insertcell':		['editor_insertCell("_htmlID_")', 'inscell.gif', editor_lang['insert_cell'], 1],
	'removecell':		['editor_removeCell("_htmlID_")', 'removecell.gif', editor_lang['remove_cell'], 1],
	'mergecell':		['editor_mergeCell("_htmlID_")', 'mergecell.gif', editor_lang['merge_cell'], 1],
	'unmergecell':		['editor_unmergeCell("_htmlID_")', 'unmergecell.gif', editor_lang['unmerge_cell'], 1],
	'bold': 			['editor_formatText("_htmlID_", "Bold")', 'bold.gif', editor_lang['bold'], 1],
	'italic': 			['editor_formatText("_htmlID_", "Italic")', 'italic.gif', editor_lang['italic'], 1],
	'underline': 		['editor_formatText("_htmlID_", "Underline")', 'underline.gif', editor_lang['underline'], 1],
	'strike': 			['editor_formatText("_htmlID_", "Strikethrough")', 'strike.gif', editor_lang['strike'], 1],
	'sub': 				['editor_formatText("_htmlID_", "Subscript")', 'subscript.gif', editor_lang['sub'], 1],
	'sup': 				['editor_formatText("_htmlID_", "Superscript")', 'superscript.gif', editor_lang['sup'], 1],
	'fontcolor': 		['editor_fontColor("_htmlID_")', 'color.gif', editor_lang['font_color'], 1],
	'bgcolor': 			['editor_bgColor("_htmlID_")', 'bgcolor.gif', editor_lang['bg_color'], 1],
	'removeformat':		['editor_removeFormat("_htmlID_")', 'removeformat.gif', editor_lang['remove_format'], 1],
	'left': 			['editor_formatText("_htmlID_", "justifyLeft")', 'aleft.gif', editor_lang['justify_left'], 1],
	'center': 			['editor_formatText("_htmlID_", "justifyCenter")', 'acenter.gif', editor_lang['justify_center'], 1],
	'right':			['editor_formatText("_htmlID_", "justifyRight")', 'aright.gif', editor_lang['justify_right'], 1],
	'justify': 			['editor_formatText("_htmlID_", "justifyFull")', 'ajustify.gif', editor_lang['justify_full'], 1],
	'numlist': 			['editor_formatText("_htmlID_", "insertOrderedlist")', 'nlist.gif', editor_lang['num_list'], 1],
	'bulletlist': 		['editor_formatText("_htmlID_", "insertUnorderedlist")', 'blist.gif', editor_lang['bullet_list'], 1],
	'increase': 		['editor_formatText("_htmlID_", "Indent")', 'iright.gif', editor_lang['increase'], 1],
	'decrease': 		['editor_formatText("_htmlID_", "Outdent")', 'ileft.gif', editor_lang['decrease'], 1],
	'dirLTR': 			['editor_formatLTR("_htmlID_")', 'dir_ltr.gif', editor_lang['dir_ltr'], 1],
	'dirRTL': 			['editor_formatRTL("_htmlID_")', 'dir_rtl.gif', editor_lang['dir_rtl'], 1],
	'hr': 				['editor_insertHR("_htmlID_")', 'hr.gif', editor_lang['hr'], 1]
};
//-------------------------------------------------------------------

var editor_area_info		= new Array(); //Textarea data
var editor_areatype_info	= new Array(); //Textarea type: full, simple,...
var editor_form_info		= new Array(); //Form data
var editor_mode_info		= new Array();
//var editor_htmlID			= ""; //Current html ID
var editor_counter			= 0;
var editor_popup_menu		= editor_is_ie ? window.createPopup() : null;
var editor_onload_replace	= false;

function editor_create(formObj, areaID, areaType, areaWidth, areaHeight){
	//Check client browser
/*
	var browser = parseFloat(navigator.appVersion.split("MSIE")[1]);
	if ( !browser || (browser <= 5) ) {
		document.write(editor_lang['error_create_editor']);
		return false;
	}
*/

	areaObj		= document.getElementById(areaID);
	htmlID		= editor_create_htmlID(areaID);

	//Add this area to global area data
	editor_area_info.push(areaID);
	editor_areatype_info.push(areaType);
	editor_form_info.push(formObj);

	//Get area dimension -------------------
	if ( !areaWidth ){
		if (areaObj.style.width){
			areaWidth	= areaObj.style.width;
		}
		else if (areaObj.cols){
			areaWidth	= areaObj.cols*8 + 22;
		}
		else{
			areaWidth	= '100%';
		}
	}

	if ( !areaHeight ){
		if (areaObj.style.height){
			areaHeight	= areaObj.style.height;
		}
		else if (areaObj.rows){
			areaHeight	= areaObj.rows*17;
		}
		else{
			areaHeight	= '350';
		}
	}
	//---------------------------------------

	//Hide area
	areaObj.style.display	= 'none';

	//Show editor menu
	editor_setmenu(areaWidth, areaID, htmlID, editor_counter, areaType);
	editor_counter++;

	//Show editor area
	td_padding	= editor_is_ie ? '1px' : '3px';
	document.writeln('<table width="'+ areaWidth +'" cellspacing="0" cellpadding="0" border="0"><tr><td style="padding-right: '+ td_padding +';">');
	document.writeln('<iframe frameborder=0 id="'+ htmlID +'" name="'+ htmlID +'" width="'+ areaWidth +'" height="'+ areaHeight +'" style="border: #3c9197 solid 1px; background-color: #FFFFFF;"></iframe>');
	document.writeln('</td></tr></table>');

	//Body onload
	if ( !editor_onload_replace ){
		if ( window.onload ){
			editor_onload_original	= window.onload;
		}
		window.onload	= new Function("editor_onload()");
		editor_onload_replace	= true;
	}
	return true;
}

//Body onload
function editor_onload(){
	editor_onload_original();

	var flag_forms	= ",";
	var direction	= (editor_lang_direction != "") ? "direction: "+ editor_lang_direction +"; " : "";

	//Load all editors
	for (i=0; i<editor_area_info.length; i++){
		htmlID		= editor_create_htmlID(editor_area_info[i]);
		areaObj		= document.getElementById(editor_area_info[i]);
		formObj		= editor_form_info[i];

		myDoc	= frames[htmlID].document.open("text/html","replace");
		myDoc.write("<HTML><HEAD><base href='"+ editor_site_url +"'><style type='text/css' rel='stylesheet'>body {"+ direction +"margin-left: 5px; margin-right: 5px; margin-top: 3px; margin-bottom: 3px; border: 0px;}</style></HEAD><BODY contentEditable=true></BODY></HTML>");
		myDoc.close();

		//Design mode
		document.getElementById(htmlID).contentWindow.document.designMode = "on";
//		document.getElementById(htmlID).contentDocument.designMode = "on";

		//Get area value and set WYSIWYG value
		editor_get_content(areaObj, htmlID);
	
		//Update content when submit
//		editor_area_info[editor_area_info.length]	= areaID;

		//Form submit event
		if ( flag_forms.indexOf(","+ formObj.name +",") == -1 ){
			try { // IE
				formObj.attachEvent('onsubmit', function(){ editor_update_content();} );
			}
			catch (e){ //Firefox
//				formObj.addEventListener('onSubmit', function(){ editor_update_content();}, false) ;
				formObj.onsubmit	= new Function("editor_update_content()");
			}
			flag_forms	+= formObj.name +",";
		}

		//Right click event on editor
		editor_mode_info[htmlID]	= 1;// 1: HTML Mode; 0: Text Mode
		frames[htmlID].document.oncontextmenu	= new Function("return editor_context_menu(frames['"+ htmlID +"'].event, '"+ htmlID +"');");
	}
}

//Original body onload
function editor_onload_original(){
}

//Get frameID from textareaID
function editor_create_htmlID(areaID){
	htmlID	= '_editor_'+ areaID;
	return htmlID;
}

//Set menu for editor
function editor_setmenu(areaWidth, areaID, htmlID, edt_counter, areaType){
	menu_info	= (areaType == 'full') ? editor_menu_full : ((areaType == 'medium') ? editor_menu_medium : editor_menu_simple);

	//Global table
	document.writeln('<table width="'+ areaWidth +'" cellpadding="0" cellspacing="0">');
//	document.writeln('<tr><td height="5"><img src="'+ editor_path +'images/space.gif" border="0"></td>');
	document.writeln('<tr><td width="100%" class="editor_menubar" style="background-image: url('+ editor_path +'images/menu_bg.gif)" nowrap>');

	//One row table
	document.writeln('<table cellpadding="0" cellspacing="0" border="0">');
	document.writeln('<tr>');

	menu_length		=	menu_info.length;
	for (i=0; i<menu_length; i++){
		switch (menu_info[i]){
			case "paragraph":
				//Paragraph
				document.writeln('<td class="editor_button"><select size="1" id="edt_paragraph_'+ edt_counter +'" name=""edt_paragraph_'+ edt_counter +'" onchange="javascript: editor_formatText(\''+ htmlID +'\', \'formatBlock\', false, this[this.selectedIndex].value); this.selectedIndex=0;">');
				document.writeln('<option>'+ editor_lang['menu_paragraph'] +'</option>');	
				para_length	= editor_button_paragraph.length;
				for (j=0; j<para_length; j++){
					para_info	= editor_button_paragraph[j].split(":: ");
					para_title	= para_info[1] ? para_info[1] : para_info[0];
					para_value	= !editor_is_ie ? para_info[0] : para_title;
					document.writeln('<option value="'+ para_value +'">'+ para_title +'</option>');
				}
				document.writeln('</select></td>');
				break;
			case "font_type":
				//Font
				document.writeln('<td class="editor_button"><select size="1" id="edt_font_type_'+ edt_counter +'" name="edt_font_type_'+ edt_counter +'" onchange="javascript:editor_formatText(\''+ htmlID +'\', \'fontname\', false, this[this.selectedIndex].value); this.selectedIndex=0;">');
				document.writeln('<option>'+ editor_lang['menu_font'] +'</option>');
				font_length	= editor_button_font.length;
				for (j=0; j<font_length; j++){
					document.writeln('<option value="'+ editor_button_font[j] +'">'+ editor_button_font[j] +'</option>');
				}
				document.writeln('</select></td>');
				break;
			case "font_size":
				//Font size
				document.writeln('<td class="editor_button"><select size="1" id="edt_font_size_'+ edt_counter +'" name="edt_font_size_'+ edt_counter +'" onchange="javascript:editor_formatText(\''+ htmlID +'\', \'fontsize\',false,this[this.selectedIndex].value); this.selectedIndex=0;">');
				document.writeln('<option>'+ editor_lang['menu_size'] +'</option>');
				size_length	= editor_button_size.length;
				for (j=0; j<size_length; j++){
					document.writeln('<option value="'+ editor_button_size[j] +'">'+ editor_button_size[j] +'</option>');
				}
				document.writeln('</select></td>');
				break;
			case "viewhtml":
				//change Mode
//				document.writeln('<td class="editor_button" nowrap><input type="checkbox" name="edt_viewHTML_'+ edt_counter +'" onclick="javascript: editor_HTMLMode(\''+ htmlID +'\', '+ edt_counter +', this.checked, \''+ areaType +'\');"><font size="2">HTML</font></td>');
				//Randome name for prevent caching
				document.writeln('<td class="editor_button" nowrap><input type="checkbox" name="edt_viewHTML_'+ edt_counter +'_'+ Math.round(Math.random() * 100) +'" onclick="javascript: editor_HTMLMode(\''+ htmlID +'\', '+ edt_counter +', this.checked, \''+ areaType +'\');"><font size="2">HTML</font></td>');
				break;
			case "nextline":
				//One row table
				document.writeln('</tr></table>');
				//Global table
				document.writeln('</td></tr>');

				//Global table
				document.writeln('<tr><td width="100%" class="editor_menubar" style="background-image: url('+ editor_path +'images/menu_bg.gif)" nowrap>');
				//One row table
				document.writeln('<table cellpadding="0" cellspacing="0" border="0">');
				document.writeln('<tr>');
				break;
			case "separator":
//				document.writeln('<td class="editor_sep"><img src="'+ editor_path +'images/space.gif" width="1" border="0"></td>');
				document.writeln('<td><img src="'+ editor_path +'images/separator.gif" border="0"></td>');
				break;
			default:
				//Display search/replace button in Mozilla
				if ( (menu_info[i] == 'find') && !editor_is_ie ){
					break;
				}

				key		= menu_info[i];
				func	= editor_button_list[key][0].replace("_areaID_", areaID);
				func	= func.replace("_htmlID_", htmlID);
				if ( editor_button_list[key][3] ){
//					document.writeln('<span class="editor_button" id="edt_'+ menu_info[i] +'_'+ edt_counter +'" onmouseOver="editor_mouseOver(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseOut="editor_mouseOut(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseDown="editor_mouseDown(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseUp="editor_mouseUp(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" TITLE="'+ editor_button_list[key][2] +'" onclick=\'javascript: if (this.disabled == false){ '+ func +' }\'> <img id="imgedt_'+ menu_info[i] +'_'+ edt_counter +'" src="'+ editor_path +'images/'+ editor_button_list[key][1] +'" valign="absbottom"></span> ');
					document.writeln('<td class="editor_button" id="edt_'+ menu_info[i] +'_'+ edt_counter +'" onmouseOver="editor_mouseOver(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseOut="editor_mouseOut(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseDown="editor_mouseDown(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" onmouseUp="editor_mouseUp(\'edt_'+ menu_info[i] +'_'+ edt_counter +'\')" TITLE="'+ editor_button_list[key][2] +'" onclick=\'javascript: if (this.className != "editor_button_disabled"){ '+ func +' }\'> <img id="imgedt_'+ menu_info[i] +'_'+ edt_counter +'" src="'+ editor_path +'images/'+ editor_button_list[key][1] +'" valign="absbottom"></td> ');
				}
		}
	}

	//One row table
	document.writeln('</tr></table>');

	//Global table
	document.writeln('</td></tr>');
	document.writeln('</table>');
}

function editor_button_changemode(type, edt_counter, areaType){
	if ( type == 'enabled' ){
		btn_class		= 'editor_button';
		img_class		= 'editor_imgbtn';
		btn_disabled	= false;
	}
	else{
		btn_class		= 'editor_button_disabled';
		img_class		= 'editor_imgbtn_disabled';
		btn_disabled	= true;
	}

	menu_info		= (areaType == 'full') ? editor_menu_full : editor_menu_simple;
	menu_length		=	menu_info.length;
	for (i=0; i<menu_length; i++){
		if ( (menu_info[i] != 'separator') && (menu_info[i] != 'nextline') && (menu_info[i] != 'viewhtml') ){
			try {
				if ( (menu_info[i] != 'paragraph') && (menu_info[i] != 'font_type') && (menu_info[i] != 'font_size') ){
					document.getElementById('edt_'+ menu_info[i] +'_'+ edt_counter).className		= btn_class;
					document.getElementById('imgedt_'+ menu_info[i] +'_'+ edt_counter).className	= img_class;
//					document.getElementById('imgedt_'+ menu_info[i] +'_'+ edt_counter).disabled		= btn_disabled;
				}
				document.getElementById('edt_'+ menu_info[i] +'_'+ edt_counter).disabled			= btn_disabled;
			}
			catch (e){ }
		}
	}
}

function editor_get_content(areaObj, htmlID){
/*
	if (document.readyState != 'complete'){
		setTimeout(function(){ editor_get_content(areaObj, htmlID);}, 250);
		return false;
	}
*/
	try{
//		if (frames[htmlID].document.body.innerHTML == ""){
//			frames[htmlID].document.body.innerHTML = areaObj.value;
//		}
		if ( (frames[htmlID].document.body.innerHTML == "") || (frames[htmlID].document.body.innerHTML == "<br>") ){
			frames[htmlID].document.body.innerHTML = areaObj.value;
		}
		else{
			frames[htmlID].document.body.innerHTML += areaObj.value;
		}
	}
	catch (e) {
		setTimeout(function(){ editor_get_content(areaObj, htmlID);}, 250);
	}

//	return true;
}

function editor_update_content(){
	area_count	= editor_area_info.length;
	for (i=0; i<area_count; i++){
		areaID	= editor_area_info[i];
		htmlID	= "_editor_"+ areaID;
		if ( editor_mode_info[htmlID] == 0 ){
			//Source Code mode
			editor_HTMLMode(htmlID, i, false, editor_areatype_info[i])			
		}
		document.all[areaID].value	= frames[htmlID].document.body.innerHTML;
	}
}

function editor_mouseOver(the_span){
	if ( (document.getElementById(the_span).disabled == undefined) || (document.getElementById(the_span).disabled == false) ){
		document.getElementById(the_span).className			= 'editor_buttonOver';
		document.getElementById('img'+ the_span).className	= 'editor_imgbtn';
	}
}
function editor_mouseOut(the_span){
	if ( (document.getElementById(the_span).disabled == undefined) || (document.getElementById(the_span).disabled == false) ){
		document.getElementById(the_span).className			= 'editor_button';
		document.getElementById('img'+ the_span).className	= 'editor_imgbtn';
	}
}

function editor_mouseDown(the_span){
	if ( (document.getElementById(the_span).disabled == undefined) || (document.getElementById(the_span).disabled == false) ){
		document.getElementById(the_span).className			= 'editor_buttonDown';
		document.getElementById('img'+ the_span).className	= 'editor_imgbtnDown';
	}
}
function editor_mouseUp(the_span){
	if ( (document.getElementById(the_span).disabled == undefined) || (document.getElementById(the_span).disabled == false) ){
		document.getElementById(the_span).className			= 'editor_buttonOver';
		document.getElementById('img'+ the_span).className	= 'editor_imgbtn';
	}
}

function editor_formatText(htmlID, sCmd, bUI, vVal){
	frames[htmlID].focus();
	if (vVal == null){
//		frames[htmlID].document.execCommand(sCmd);
//		document.getElementById(htmlID).contentWindow.document.execCommand(sCmd);
		try {
			document.getElementById(htmlID).contentWindow.document.execCommand(sCmd, false, vVal);
		}
		catch (e){
			//Can not Cut, Copy, Paste in Firefox
			switch (sCmd){
				case "Cut":
						alert(editor_lang['error_firefox_cut']);
						break;
				case "Copy":
						alert(editor_lang['error_firefox_copy']);
						break;
				case "Paste":
						alert(editor_lang['error_firefox_paste']);
			}
		}
	}
	else{
//		frames[htmlID].document.execCommand(sCmd,bUI,vVal);
		document.getElementById(htmlID).contentWindow.document.execCommand(sCmd,bUI,vVal);
	}
	frames[htmlID].focus();
}

function editor_HTMLMode(htmlID, edt_counter, the_check, areaType){
	if (the_check){
		editor_mode_info[htmlID]	= 0;//Text mode
		if ( editor_is_ie ){
			frames[htmlID].document.body.innerText		= frames[htmlID].document.body.innerHTML;
		}
		else{
			frames[htmlID].document.body.textContent	= frames[htmlID].document.body.innerHTML;
		}
		editor_button_changemode('disabled', edt_counter, areaType);
	}
	else{
		editor_mode_info[htmlID]	= 1;//HTML mode
		if ( editor_is_ie ){
			frames[htmlID].document.body.innerHTML = frames[htmlID].document.body.innerText;
		}
		else{
			frames[htmlID].document.body.innerHTML = frames[htmlID].document.body.textContent;
		}
		editor_button_changemode('enabled', edt_counter, areaType);
	}
	frames[htmlID].focus();
}

function editor_formatLTR(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		editor_formatText(htmlID, "BlockDirLTR");
	}
	else{
		//Other browsers
		var editorSel	= document.getElementById(htmlID).contentWindow.getSelection();
		var editorElm	= editor_getSelectedElement(editorSel);
		if ( editorElm.dir ){
			editorElm.removeAttribute("dir");
		}
		else{
			editorElm.dir = "ltr";
		}
	}
}

function editor_formatRTL(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		editor_formatText(htmlID, "BlockDirRTL");
	}
	else{
		//Other browsers
		var editorSel	= document.getElementById(htmlID).contentWindow.getSelection();
		var editorElm	= editor_getSelectedElement(editorSel);
		if ( editorElm.dir ){
			editorElm.removeAttribute("dir");
		}
		else{
			editorElm.dir = "rtl";
		}
	}
}

function editor_insertHR(htmlID){
//	insertHTML("<HR>");
	editor_formatText(htmlID, "InsertHorizontalRule", false, "");
}

function editor_fontColor(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		var popup = editor_openDialog(editor_url_color, 291, 167);
		if (popup != null) editor_formatText(htmlID, 'forecolor', false, popup);
	}
	else{
		//Other browsers
		editor_open_window(editor_url_color + "?"+ htmlID +".forecolor", 291, 167);
	}
}

function editor_bgColor(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		var popup = editor_openDialog(editor_url_color, 291, 167);
		if (popup != null) editor_formatText(htmlID, 'backcolor', false, popup);
	}
	else{
		//Other browsers
		editor_open_window(editor_url_color + "?"+ htmlID +".hilitecolor", 291, 167);
	}
}

function editor_openDialog(the_url, the_width, the_height){
	return showModalDialog(the_url, self, "status:no; scroll:no; resizable:yes; help:no; dialogWidth:"+ the_width +"px; dialogHeight:"+ the_height +"px");
}

function editor_openModelessDialog(the_url, the_width, the_height){
	showModelessDialog(the_url, self, "status:no; scroll:no; resizable:yes; help:no; dialogWidth:"+ the_width +"px; dialogHeight:"+ the_height +"px");
}

function editor_insertHTML(htmlID, the_msg){
	frames[htmlID].focus();

	if ( editor_is_ie ){
		//Internet explorer
		var temp	= frames[htmlID].document.selection.createRange();	
		temp.pasteHTML(the_msg);
	}
	else{
		//Other browsers
		var random_string	= "insert_html_" + Math.round(Math.random()*100000000);
		var pat				= new RegExp("<[^<]*" + random_string + "[^>]*>");
		document.getElementById(htmlID).contentWindow.document.execCommand("insertimage", false, random_string);
		frames[htmlID].document.body.innerHTML	= frames[htmlID].document.body.innerHTML.replace(pat, the_msg);
	}

	frames[htmlID].focus();
}

function editor_getValue(){
	var modHTML = window.document.viewHTML.checked;

	if (viewHTML)
		return Content.document.body.innerText;
	else{
		return Content.document.body.innerHTML;
	}
}

function editor_insertWebLink(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		var popup = editor_formatText(htmlID, "CreateLink", true, "");
		if (popup != null){
			editor_formatText(htmlID, 'CreateLink', false, popup);
		}
	}
	else{
		//Other browsers
		editor_open_window(editor_url_weblink + "?"+ htmlID, 323, 50);
	}
}

function editor_removeWebLink(htmlID){
	editor_formatText(htmlID, "UnLink");
}

function editor_removeFormat(htmlID){
	editor_formatText(htmlID, "RemoveFormat", false, null);
}

function editor_findText(htmlID){
//	editor_htmlID	= htmlID;
	var popup_url	= editor_url_find + "?"+ htmlID;

	if ( editor_is_ie ){
		//Internet explorer
		var popup = editor_openDialog(popup_url, 340, 150);
	}
	else{
		//Other browsers
		editor_open_window(popup_url, 340, 150);
	}
}

function editor_get_htmlID(){
	return editor_htmlID;
}

function editor_get_imageURL(){
	return editor_url_image_upload;
}

//Insert image
function editor_insertImage(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
//		var popup = editor_openModelessDialog(editor_url_image, 430, 336);
		var popup = editor_openDialog(editor_url_image, 430, 290);
		if (popup != null){
			editor_insertHTML(htmlID, popup);
		}	
	}
	else{
		//Other browsers
		editor_open_window(editor_url_image + "?"+ htmlID, 432, 302);
	}
}

//Open popup
function editor_open_window(the_page, width, height){
	var left	= screen.availWidth/2 - width/2;
	var top		= screen.availHeight/2 - height/2;
	var myWin = window.open(the_page,'_Editor',"dependent=1, toolbar=0, location=0, status=0, menubar=0, scrollbars=0, resizable=0, width="+ width +", height="+ height +", top="+ top +", left="+ left);
	myWin.focus();
}

//Context menu - Format text
function editor_checkFormatEnabled(the_event, htmlID){
	if ( frames[htmlID].document.queryCommandEnabled(the_event) ){
		return 'onclick="parent.editor_formatText(\''+ htmlID +'\', \''+ the_event +'\'); parent.editor_popup_menu.hide();"';
	}
	return 'disabled';
}

//Display context menu
function editor_context_menu(the_event, htmlID){
	if (editor_mode_info[htmlID] == 0){
		return true;
	}

	var the_width	= 160;
	var the_height	= 127;
	var str_menu	= '';

	//Header
	str_menu		+= '<html><head><style type="text/css" rel="stylesheet">';
	str_menu		+= 'body {margin:0px;border:0px}';
	str_menu		+= '.tblborder {BORDER-TOP: #6C9BB9 1px solid; BORDER-LEFT: #6C9BB9 1px solid; BORDER-BOTTOM: #6C9BB9 1px solid; BORDER-RIGHT: #6C9BB9 1px solid; BACKGROUND-COLOR: #FFFFFF;}';
	str_menu		+= '.tdtext1 {BACKGROUND-COLOR: #B4D8ED; COLOR: #000000; FONT-SIZE: 12px; cursor: default;}';
	str_menu		+= '.tdtext2 {BACKGROUND-COLOR: #EAF3F5; COLOR: #000000; FONT-SIZE: 12px; cursor: default;}';
	str_menu		+= '</style></head>';
	//Body
	str_menu		+= '<body topmargin=0 leftmargin=0 scroll="no" onContextMenu="event.returnValue=false;">';
	str_menu		+= '<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">';
	str_menu		+= '<tr>';
	str_menu		+= '  <td width="100%">';
	str_menu		+= '	<table border="0" cellpadding="2" cellspacing="1" width="100%">';

	//Format
	str_menu		+= '		<tr><td width="15" class="tdtext1">&nbsp;</td><td '+ editor_checkFormatEnabled('Cut', htmlID) +' class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['cut'] +'</td></tr>';
	str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td '+ editor_checkFormatEnabled('Copy', htmlID) +' class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['copy'] +'</td></tr>';
	str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td '+ editor_checkFormatEnabled('Paste', htmlID) +' class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['paste'] +'</td></tr>';
	str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td '+ editor_checkFormatEnabled('Delete', htmlID) +' class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['delete'] +'</td></tr>';
	str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td '+ editor_checkFormatEnabled('SelectAll', htmlID) +' class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['select_all'] +'</td></tr>';

	//Table
	if ( editor_isInTable(htmlID) ){
		str_menu		+= '		<tr><td height="1" colspan="2" bgcolor="#6C9BB9"></td></tr>';
//		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_tableProperties(\'\', \''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">Table Properties</td></tr>';
//		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_cellProperties(\'\', \''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">Cell Properties</td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_insertRow(\''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['insert_row'] +'</td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_removeRow(\''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['remove_row'] +'</td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_insertCol(\''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['insert_col'] +'</td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_removeCol(\''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['remove_col'] +'</td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_mergeCol(\'\', \''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['merge_cell'] +'</td></tr>';
		the_height		+= 102;
	}
	if ( editor_isControlSelected(htmlID, "TABLE") ){
//		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_tableProperties(\'\', \''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">Table Properties</td></tr>';
	}

	//Image
	if ( editor_isControlSelected(htmlID, "IMG") ){
		str_menu		+= '		<tr><td height="1" colspan="2" bgcolor="#6C9BB9"></td></tr>';
		str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['image_prop'] +'</td></tr>';
	}

	str_menu		+= '		<tr><td height="1" colspan="2" bgcolor="#6C9BB9"></td></tr>';
	str_menu		+= '		<tr><td class="tdtext1">&nbsp;</td><td onclick="parent.editor_findText(\''+ htmlID +'\'); parent.editor_popup_menu.hide();" class="tdtext2" onMouseOver="this.className=\'tdtext1\';" onMouseOut="this.className=\'tdtext2\';">'+ editor_lang['find'] +'</td></tr>';
	str_menu		+= '		</table>';
	str_menu		+= '    </td>';
	str_menu		+= '  </tr>';
	str_menu		+= '</table>';
	str_menu		+= '</body></html>';

	editor_popup_menu.document.open();
	editor_popup_menu.document.write(str_menu);
	editor_popup_menu.document.close();

	editor_popup_menu.show(the_event.clientX, the_event.clientY, the_width, the_height, frames[htmlID].document.body);
	return false;
}

function editor_isControlSelected(htmlID, tag_name){
	if ( tag_name ){
		if (frames[htmlID].document.selection.type == "Control") {
			var oControlRange	= frames[htmlID].document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == tag_name) {
				return true;
			}	
		}
	}
	return false;
}

function editor_isTagSelected(htmlID, tag_name){
	var sel		= frames[htmlID].document.selection.createRange();
	sel.type	= frames[htmlID].document.selection.type;
	if (sel.type != "Control"){
		var oBody		= frames[htmlID].document.body;
		var aAllEl		= oBody.getElementsByTagName(tag_name);
		var aSelEl		= new Array();
		var oRngTemp	= oBody.createTextRange();

		for (i=0; i<aAllEl.length; i++){
			oRngTemp.moveToElementText(aAllEl(i));
			if (sel.inRange(oRngTemp)){
				aSelEl[aSelEl.length] = aAllEl[i];
			}
			else{
				if ( ((sel.compareEndPoints("StartToEnd", oRngTemp) < 0) && (sel.compareEndPoints("StartToStart", oRngTemp) > 0)) || ((sel.compareEndPoints("EndToStart", oRngTemp) > 0) && (sel.compareEndPoints("EndToEnd", oRngTemp) < 0)) ){
					aSelEl[aSelEl.length] = aAllEl[i];
				}
			}
		}

		if (aSelEl.length > 0){
			return true;
		}
	}
	return false;
}

//Table ----------------------------------------------------------------
function editor_insertTable(htmlID){
	if ( editor_is_ie ){
		//Internet explorer
		var popup = editor_openDialog(editor_url_table, 318, 142);
		if (popup != null){
			editor_insertHTML(htmlID, popup);
		}
	}
	else{
		//Other browsers
		editor_open_window(editor_url_table + "?"+ htmlID, 318, 135);
	}
}

//Insert table row
function editor_insertRow(htmlID){
	if ( editor_isInTable(htmlID) ){
		if ( editor_is_ie ){
			//Internet explorer
			var popup = editor_openDialog(editor_url_row, 260, 139);
			if ( (popup != null) && (popup != 0) ){
				editor_insertRow_detail(htmlID, popup);
			}
		}
		else{
			//Other browsers
			editor_open_window(editor_url_row + "?"+ htmlID, 260, 135);
		}
	}
}

//Do insert table row
function editor_insertRow_detail(htmlID, row_number){
	if ( row_number > 0 ){
		insertType	= 'above';
//		row_number	= row_number;
	}
	else{
		insertType	= 'below';
		row_number	= 0 - row_number;
	}

	table_info	= editor_getTable(htmlID);
	row_index	= 0;
	cell_count	= table_info['row'].childNodes.length;

	the_row		= table_info['row'];
	while ( the_row ){
		if (the_row.tagName == "TR") {
			row_index++
			the_row = the_row.previousSibling;
		}			
	}
	if ( insertType == 'above' ){
		row_index--;
	}
	
	for (k=0; k<row_number; k++){
		//Insert row
		new_row	= table_info['table'].insertRow(row_index);
		
		//Insert cells
		for (i = 0; i < cell_count; i++) {
			new_cell = new_row.appendChild(frames[htmlID].document.createElement("TD"));

			if (table_info['cell'].colSpan) {
				new_cell.colSpan = table_info['row'].childNodes[i].colSpan;
			}
			new_cell.width		= table_info['row'].childNodes[i].width;
			new_cell.innerHTML	= '&nbsp;';
		}
		row_index++;
	}
}

//Remove table row
function editor_removeRow(htmlID){
	if ( editor_isInTable(htmlID) ){
		table_info	= editor_getTable(htmlID);
		the_row		= table_info['row'];
		row_index	= 0;
		while ( the_row ){
			if (the_row.tagName == "TR") {
				row_index++
				the_row = the_row.previousSibling;
			}			
		}
		row_index--;
	
		table_info['table'].deleteRow(row_index);
	}
}

//Insert table column
function editor_insertCol(htmlID){
	if ( editor_isInTable(htmlID) ){
		if ( editor_is_ie ){
			//Internet explorer
			var popup = editor_openDialog(editor_url_col, 260, 139);
			if ( (popup != null) && (popup != 0) ){
				editor_insertCol_detail(htmlID, popup);
			}
		}
		else{
			//Other browsers
			editor_open_window(editor_url_col + "?"+ htmlID, 260, 135);
		}
	}
}

//Do insert table column
function editor_insertCol_detail(htmlID, col_number){
	if ( col_number > 0 ){
		insertType	= 'left';
//		col_number	= col_number;
	}
	else{
		insertType	= 'right';
		col_number	= 0 - col_number;
	}

	table_info	= editor_getTable(htmlID);
	cell_index	= table_info['cell'].cellIndex;

	for (k=0; k<col_number; k++){
		editor_insertCol_process(htmlID, table_info['table'], cell_index, insertType);
	}
}

//Start insert column
function editor_insertCol_process(htmlID, tblObj, cell_index, insertType){
	cell_count	= tblObj.childNodes.length;

	for (i=0; i<cell_count; i++){
		if (tblObj.childNodes[i].tagName == 'TR'){
			cell	= tblObj.childNodes[i].childNodes[cell_index];
			if ( !cell ){
				break;
			}

			new_cell	= frames[htmlID].document.createElement("TD");
			if ( insertType == 'left' ){
				cell.parentNode.insertBefore(new_cell, cell);
			}
			else{
				cell.parentNode.insertBefore(new_cell, cell.nextSibling);
			}
			new_cell.innerHTML	= '&nbsp;';
		}
		else{
			//Search TR
			editor_insertCol_process(htmlID, tblObj.childNodes[i], cell_index, insertType);
		}
	}
}

function editor_removeCol(htmlID){
	if ( editor_isInTable(htmlID) ){
		table_info	= editor_getTable(htmlID);
		cell_index	= table_info['cell'].cellIndex;

		editor_removeCol_process(htmlID, table_info['table'], table_info['row'], cell_index);
	}
}

function editor_removeCol_process(htmlID, tblObj, rowObj, cell_index){
	cell_count	= tblObj.childNodes.length;

	for (i=0; i<cell_count; i++){
		if (tblObj.childNodes[i].tagName == 'TR'){
			cell	= tblObj.childNodes[i].childNodes[cell_index];
			if ( !cell ){
				break;
			}

			//Check for rowspan
			if (cell.rowSpan > 1) {
				i += (cell.rowSpan - 1);
			}
			if (cell.colSpan < 2) { 
				tblObj.childNodes[i].removeChild(cell);
			}
			else {
				cell.colSpan -= 1;
			}
		}
		else{
			//Search TR
			editor_removeCol_process(htmlID, tblObj.childNodes[i], rowObj, cell_index);
		}
	}
}

function editor_mergeCell(htmlID){
	if ( editor_isInTable(htmlID) ){
		if ( editor_is_ie ){
			//Internet explorer
			var popup = editor_openDialog(editor_url_mergecell, 260, 113);
			if ( (popup != null) && (popup != 0) ){
				editor_mergeCell_process(htmlID, popup);
			}
		}
		else{
			//Other browsers
			editor_open_window(editor_url_mergecell + "?"+ htmlID, 260, 110);
		}
	}
	frames[htmlID].focus();
}

function editor_mergeCell_process(htmlID, cell_pos){
	mergeType	= (cell_pos == 'right') ? 'right' : 'below';
	table_info	= editor_getTable(htmlID);

	if ( mergeType == 'right' ){//Merge cell right
		if ( !table_info['cell'].nextSibling ){
			alert(editor_lang['error_table_cell_right']);
			return;
		}
		
		//Can not merge rows with different rowspans
		if (table_info['cell'].rowSpan != table_info['cell'].nextSibling.rowSpan) {
			alert(editor_lang['error_table_cell_merge_row']);
			return;
		}

		if (table_info['cell'].nextSibling.innerHTML.toLowerCase() != frames[htmlID].tdInners) {
			if (table_info['cell'].innerHTML.toLowerCase() == frames[htmlID].tdInners) {
				table_info['cell'].innerHTML = table_info['cell'].nextSibling.innerHTML;
			}
			else {
				table_info['cell'].innerHTML += table_info['cell'].nextSibling.innerHTML;
			}
		}
		table_info['cell'].colSpan	+= table_info['cell'].nextSibling.colSpan;
		table_info['row'].removeChild(table_info['cell'].nextSibling);
	}
	else{//Merge cell below
		row_count	= table_info['table'].getElementsByTagName('TR').length;
		topRowIndex	= table_info['row'].rowIndex;

		if ( !table_info['row'].nextSibling ) {
			alert(editor_lang['error_table_cell_below']);
			return;
		}
		if (row_count - (topRowIndex + table_info['cell'].rowSpan) <= 0) {
			alert(editor_lang['error_table_cell_merge_col']);
			return;
		}
		
		bottomCell	= table_info['row'].parentNode.childNodes[topRowIndex + table_info['cell'].rowSpan].childNodes[table_info['cell'].cellIndex];
		bottomRow	= table_info['row'].parentNode.childNodes[topRowIndex + table_info['cell'].rowSpan];

		// don't allow merging rows with different colspans
		if (table_info['cell'].colSpan != bottomCell.colSpan) {
			alert(editor_lang['error_table_cell_merge_col']);
			return;
		}

		if (bottomCell.innerHTML.toLowerCase() != frames[htmlID].tdInners) {
			if (table_info['cell'].innerHTML.toLowerCase() == frames[htmlID].tdInners) {
				table_info['cell'].innerHTML	= bottomCell.innerHTML
			}
			else {
				table_info['cell'].innerHTML	+= bottomCell.innerHTML
			}
		}
		table_info['cell'].rowSpan	+= bottomCell.rowSpan;
		table_info['cell'].nextSibling;
		bottomRow.removeChild(bottomCell);
	}
}

function editor_unmergeCell(htmlID){
	if ( editor_isInTable(htmlID) ){
		table_info	= editor_getTable(htmlID);
		if ( (table_info['cell'].colSpan >= 2) && (table_info['cell'].rowSpan < 2) ){
			//Unmerge cell right		
			table_info['cell'].colSpan	= table_info['cell'].colSpan - 1;
			newCell				= table_info['cell'].parentNode.insertBefore(frames[htmlID].document.createElement("TD"), table_info['cell'].nextSibling)
			newCell.rowSpan 	= table_info['cell'].rowSpan;
			newCell.innerHTML	= '&nbsp;';
/*
			if (table_info['cell'].colSpan < 2) {
				table_info['cell'].width	= (table_info['cell'].width.replace('%','')/2) + '%';
				newCell.width	= table_info['cell'].width;
			}
			else{
				newWidth				= table_info['cell'].width.replace('%','')/table_info['cell'].colSpan;
				table_info['cell'].width = (newwidth*(table_info['cell'].colSpan-1)) + '%'
				newCell.width = newWidth + '%';
			}
*/
		}
		else if ( (table_info['cell'].rowSpan >= 2) && (table_info['cell'].colSpan < 2) ){
			//Unmerge cell below
			topRowIndex			= table_info['cell'].parentNode.rowIndex;
			newCell				= table_info['row'].parentNode.childNodes[topRowIndex + table_info['cell'].rowSpan - 1].appendChild(frames[htmlID].document.createElement("TD"));
			newCell.width		= table_info['cell'].width;
			newCell.colSpan		= table_info['cell'].colSpan;
			newCell.innerHTML	= '&nbsp;';
			table_info['cell'].rowSpan -= 1;
		}
		frames[htmlID].focus();
	}
}

function editor_isInTable(htmlID){
	if ( editor_is_ie ){
		var the_td	= frames[htmlID].document.selection.createRange().parentElement();
	}
	else{
		var editorSel	= frames[htmlID].getSelection();
		var the_td		= editor_getSelectedElement(editorSel);
	}

	while ( (the_td.tagName != "TD") && (the_td.tagName != "BODY") && (the_td.tagName != "HTML") ) {
		the_td	= editor_is_ie ? the_td.parentElement : the_td.parentNode;
	}
	if (the_td.tagName == "TD"){
		return true;
	}
	return false;
}

//Mozilla range methods
function editor_getSelectedElement(editorSel){
	var range	= editorSel.getRangeAt(0);
	var node	= range.startContainer;

	if (node.nodeType == Node.ELEMENT_NODE){
		if (range.startOffset >= node.childNodes.length){
			return node;
		}
		node	= node.childNodes[range.startOffset];
	}

	if (node.nodeType == Node.TEXT_NODE){    
		if (node.nodeValue.length == range.startOffset){
			var elem	= node.nextSibling;
			if (elem && elem.nodeType == Node.ELEMENT_NODE){
				if ( (range.endContainer.nodeType == Node.TEXT_NODE) && (range.endContainer.nodeValue.length == range.endOffset) ){
					if (elem == range.endContainer.parentNode){
						return elem;
					}
				}
			}
		}
		while ( (node != null) && (node.nodeType != Node.ELEMENT_NODE) ){
			node	= node.parentNode;
		}
	}    
	return node;
}

function editor_getTable(htmlID){
	if ( editor_is_ie ){
		var the_tag		= frames[htmlID].document.selection.createRange().parentElement();
	}
	else{
		var editorSel	= frames[htmlID].getSelection();
		var the_tag		= editor_getSelectedElement(editorSel);
	}
	table_info	= new Array();

	table_info['cell']		= the_tag;
	while ( (table_info['cell'].tagName != "TD") && (table_info['cell'].tagName != "BODY") && (table_info['cell'].tagName != "HTML") ) {
		table_info['cell'] = editor_is_ie ? table_info['cell'].parentElement : table_info['cell'].parentNode;
	}

	table_info['row'] = table_info['cell'];
	while ( (table_info['row'].tagName != "TR") && (table_info['row'].tagName != "BODY") && (table_info['row'].tagName != "HTML") ) {
		table_info['row'] = editor_is_ie ? table_info['row'].parentElement : table_info['row'].parentNode;
	}

	table_info['table'] = table_info['row'];
	while ( (table_info['table'].tagName != "TABLE") && (table_info['table'].tagName != "BODY") && (table_info['table'].tagName != "HTML") ) {
		table_info['table'] = editor_is_ie ? table_info['table'].parentElement : table_info['table'].parentNode;
	}

	return table_info;
}
//----------------------------------------------------------------------

-->