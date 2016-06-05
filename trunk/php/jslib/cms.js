<!--

// Newspicture Module Begin
//=====================================
function newspic_display(pic_thumb, pic_full, the_width, the_height, content){
	var str_output	= '<center><a onclick="javascript: return openImage(\''+ pic_full +'\', '+ the_width +', '+ the_height +');" href="#">'+ pic_thumb +'<'+'/a></center>';
	str_output		+= '<br>'+ content;
	document.getElementById('div_NewsPicture').innerHTML	= str_output;
}

function newspic_change(){
	newspic_start++;
	if ( newspic_start >= newspic_total ){
		newspic_start	= 0;
	}

	eval(newspic_info[newspic_start]);
	T_NewsPic	= setTimeout("newspic_change()", newspic_rand_time);
}

function newspic_pause_start(imgID, flag, t_name, template_path){
	if ( newspic_total < 2 ){
		return false;
	}

	var the_img		= document.getElementById(imgID);
	if ( flag == 'pause' ){
		eval("clearTimeout("+ t_name +");");
		the_img.src		= template_path + "/images/ajax_loading_pause.gif";
	}
	else{
		eval(t_name +' = setTimeout("newspic_change()", newspic_rand_time);');
		the_img.src		= template_path + "/images/ajax_loading.gif";
	}
	return true;
}
//=====================================
// Newspicture Module End


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

function check_uncheck(the_form,the_value){
	var count = the_form.elements.length;

	for (i=0;i<count;i++){
			the_form.elements[i].checked = the_value;
	}
}

function change_src(imgname,the_src){
	if (the_src != ""){	
		imgname.src = the_src;
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
	top_val		= (screen.height - height)/2 - 30;
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

function openImage(the_img, the_width, the_height)
{
	winDef = 'status=no,resizable=yes,scrollbars=no,toolbar=no,location=no,fullscreen=no,titlebar=yes, width='+ the_width +', height='+ the_height;
	winDef = winDef.concat(',top=').concat((screen.height - the_height)/2 - 30);
	winDef = winDef.concat(',left=').concat((screen.width - the_width)/2);
	newwin = open('', '_blank', winDef);

	newwin.document.writeln('<html><title>News Picture</title><body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">');
	newwin.document.writeln('<a href="" onClick="window.close(); return false;"><img src="', the_img, '" alt="Close" border=0 align="center"></a>');
	newwin.document.writeln('</body></html>');

	return false;
}

function showDateTime(myDate){
	document.write(myDate);
}

function ietruebody(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
}

function show_tip(obj, event){
	var ie	= document.all;
	var ns6	= (document.getElementById && !document.all) ? 1 : 0;
	if (ie || ns6) {
		var tipobj	= document.all ? document.all[obj] : (document.getElementById ? document.getElementById(obj) : "");
		var curX	= (ns6) ? event.pageX : event.x + ietruebody().scrollLeft;
		var curY	= (ns6) ? event.pageY : event.y + ietruebody().scrollTop;
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

function hide_field_title(the_field, the_title){
	if (the_field.value == the_title){
		the_field.value	= "";
	}
}

function gotoTop(){
	var url		= window.location + "";
	if ( url.indexOf('#') == -1 ){
		window.location		= window.location +"#";
	}
	else{
		window.location		= window.location;
	}
}
-->
