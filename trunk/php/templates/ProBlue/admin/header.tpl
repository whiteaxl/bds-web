<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITE_NAME} - {L_ADMIN_CONTROL}</title>
<link href="{TEMPLATE_PATH}/admin/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
.submit {
	background-image: url({TEMPLATE_PATH}/images/admin/bg_button.gif);
	border-right: #7D8184 1px solid;
	border-left: #7D8184 1px solid;
	border-top: #7D8184 1px solid;
	border-bottom: #7D8184 1px solid;
	height: 20px;
	cursor: hand;
	color: #3F3E3D;
	font-size: 12px;
	font-weight: bold;
}
.submitS {
	background-image: url({TEMPLATE_PATH}/images/admin/bg_button.gif);
	border-right: #7D8184 1px solid;
	border-left: #7D8184 1px solid;
	border-top: #7D8184 1px solid;
	border-bottom: #7D8184 1px solid;
	height: 18px;
	cursor: hand;
	color: #3F3E3D;
	font-size: 11px;
	font-weight: bold;
}
</style>
<script language="javascript" type="text/javascript" src="./jslib/cms.js"></script>
<script language="javascript">
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
//				the_cells[i].setAttribute('background', '{TEMPLATE_PATH}/images/admin/bg_hover.gif', 0);
//				the_cells[i].setAttribute('bgcolor', '#dfe0e0', 0);
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
</script>
<script type="text/javascript" src="./fckeditor/fckeditor.js"></script>
</head>

<body style="margin-top: 2px; margin-bottom: 2px; margin-left: 6px; margin-right: 6px;">
