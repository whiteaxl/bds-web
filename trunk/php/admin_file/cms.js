<!--

function refreshImage(the_img, the_url){
	//Prevent cache problem
	if ( the_url.indexOf('?') ){
		the_url	+= "&randCode=" + new Date().getTime();
	}
	else{
		the_url	+= "?randCode=" + new Date().getTime();
	}

	the_img.src		= the_url;
	return false;
}
function setBackground(the_row, the_event){
	if (typeof(document.getElementsByTagName) != 'undefined') {
		the_cells = the_row.getElementsByTagName('td');
	}
	else if (typeof(the_row.cells) != 'undefined') {
		the_cells = the_row.cells;
	}
	else {
		return false;
	}

	count	= the_cells.length;
	for (i=0; i<count; i++){
		if ( the_event == 'over' ){
//			the_cells[i].setAttribute('background', '{TEMPLATE_PATH}/images/bg_hover.gif', 0);
			the_cells[i].setAttribute('bgcolor', '#ffffce', 0);
		}
		else if ( the_event == 'out' ){
			the_cells[i].setAttribute('bgcolor', '', 0);
		}
		else if ( the_event == 'click' ){
			the_cells[i].setAttribute('bgcolor', '#dfe0e0', 0);
		}
	}
}

function chooseRecord(the_row, record_id){
	if ( document.getElementById(record_id).checked == true ){
		document.getElementById(record_id).checked	= false;
	}
	else{
		document.getElementById(record_id).checked	= true;
	}
}

function is_checked_item(the_form){
	var count = the_form.elements.length;

	for (i=0;i<count;i++){
		if ( (the_form.elements[i].type == 'checkbox') && (the_form.elements[i].checked == true) ){
			return true;
		}
	}
	return false;
}

function check_all(the_form,the_value){
	var count = the_form.elements.length;

	for (i=0;i<count;i++){
		the_form.elements[i].checked = the_value;
	}
}

function check_block(the_form, the_value, item_id){
	var count = the_form.elements.length;

	for (i=0;i<count;i++){
		if ( the_form.elements[i].name.indexOf(item_id) != -1 ){
			the_form.elements[i].checked = the_value;
		}
	}
}

function select_list(the_value,the_list){
	var option_count = the_list.options.length;

	for (i=0;i<option_count;i++){
		if (the_value==the_list.options[i].value){
			the_list.options[i].selected	= true;
			return true;
		}
	}

	return false;
}

function radio_list(the_value,the_list){
	var name_count = the_list.length;

	for (i=0;i<name_count;i++){
		if (the_value==the_list[i].value){
			the_list[i].checked=true;
			break;
		}
	}
}	

function get_radio_value(the_list){
	var name_count = the_list.length;

	for (i=0;i<name_count;i++){
		if (the_list[i].checked == true){
			return the_list[i].value;
		}
	}
	return 0;
}


function open_window(the_url, width, height){
	top_val		= (screen.height - height)/2 - 40;
	if (top_val < 0){ top_val	= 0; }
	left_val	= (screen.width - width)/2;

	var new_win = window.open(the_url, "", "toolbar=0,location=0,status=1,menubar=0,scrollbars=1,resizable=1,width="+ width +",height="+ height +", top="+ top_val +",left="+ left_val);
}

function del_confirm(msg) {
	question = confirm(msg)
	if (question != "0"){
		return true;
	}
	return false;
}

function ietruebody(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function show_tip(obj, event){
	var ie	= document.all;
	var ns6	= (document.getElementById && !document.all) ? 1 : 0;
	if (ie || ns6) {
		var tipobj	= document.all ? document.all[obj] : (document.getElementById ? document.getElementById(obj) : "");
		//var curX	= (ns6) ? event.pageX : event.x + ietruebody().scrollLeft;
		//var curY	= (ns6) ? event.pageY : event.y + ietruebody().scrollTop;
		var curX	= (ns6) ? event.pageX : event.x;
		var curY	= (ns6) ? event.pageY : event.y;

		var rightedge	= (ie && !window.opera) ? ietruebody().clientWidth-event.clientX : window.innerWidth - event.clientX - 16;
		var bottomedge	= (ie && !window.opera) ? ietruebody().clientHeight-event.clientY : window.innerHeight - event.clientY;
		var leftedge	= -1000;

		if ( rightedge < tipobj.offsetWidth ){
			tipobj.style.left	= ie ? ietruebody().scrollLeft + event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset + event.clientX-tipobj.offsetWidth + "px";
		}
		else if (curX<leftedge){
			tipobj.style.left	= "5px";
		}
		else tipobj.style.left	= curX + "px";

		if ( bottomedge < tipobj.offsetHeight ){
			tipobj.style.top	= ie ? ietruebody().scrollTop + event.clientY-tipobj.offsetHeight+16+"px" : window.pageYOffset + event.clientY-tipobj.offsetHeight-16 + "px";
		}
		else{
			tipobj.style.top	= curY + 16 + "px";
			tipobj.style.visibility	= "visible"
			document.body.style.cursor	= "help";
		}
	}
}

function hide_tip(obj){
	var ie	= document.all;
	var ns6	= (document.getElementById && !document.all) ? 1 : 0;
	if (ie || ns6) {
		var tipobj	= document.all ? document.all[obj] : (document.getElementById ? document.getElementById(obj) : "");
		tipobj.style.visibility	= "hidden";
		tipobj.style.left		= "-1000px";
		document.body.style.cursor	= "auto";
	}
}

function hide_title(the_field, the_title){
	if (the_field.value == the_title){
		the_field.value	= "";
	}
}

function show_title(the_field, the_title){
	if (the_field.value == ""){
		the_field.value	= the_title;
	}
}

function get_frame_index(frame_name){
	var count	= parent.frames.length;
	for (var i=0; i<count; i++){
		if ( parent.frames[i].name == frame_name ){
			return i;
		}
	}
	return -1;
}

function is_firefox(){
	if ( navigator.userAgent.indexOf("Firefox")!= -1 ){
		return true;
	}
	return false;
}
-->
