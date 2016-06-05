<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITE_NAME} - {L_ADMIN_CONTROL}</title>
<link href="{TEMPLATE_PATH}/admin/style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="jslib/cms.js"></script>
<style type="text/css">
<!--
.tdmenu {
	BACKGROUND-IMAGE: url("{TEMPLATE_PATH}/images/menubg.gif"); HEIGHT: 22px; COLOR: #326D51; FONT-SIZE: 9pt; FONT-WEIGHT: bold
}
.tdsmenu {
	BACKGROUND-IMAGE: url("{TEMPLATE_PATH}/images/menubg.gif"); HEIGHT: 22px; COLOR: #326D51; FONT-SIZE: 8pt; FONT-WEIGHT: bold
}
-->
</style>

<script language="javascript" type="text/javascript">
	function showHideDiv(div_id){
		the_div		= document.getElementById('DivGroup_'+ div_id);
		the_icon	= document.getElementById('ImgGroup_'+ div_id);
		if (the_div.style.display == "none"){
			the_div.style.display	= "";
			the_icon.src			= "{TEMPLATE_PATH}/images/admin/minus.gif";
		}
		else{
			the_div.style.display	= "none";
			the_icon.src			= "{TEMPLATE_PATH}/images/admin/plus.gif";
		}
	}

	function expandAll() {
		var div_info	= document.getElementsByTagName("DIV");
		var icon_info	= document.getElementsByTagName("IMG");

		//Divs	
		for (i=0; i<div_info.length; i++){
			if (div_info.item(i).id.indexOf('DivGroup_') != -1) {
				div_info.item(i).style.display		= "";
			}
		}
		//Icons
		for (i=0; i<icon_info.length; i++) {
			if (icon_info.item(i).id.indexOf('ImgGroup_') != -1) {
				icon_info.item(i).src	= icon_info.item(i).src.replace('plus', 'minus');
			}
		}
	}
	
	function collapseAll() {
		var div_info	= document.getElementsByTagName("DIV");
		var icon_info	= document.getElementsByTagName("IMG");

		//Divs	
		for (i=0; i<div_info.length; i++){
			if (div_info.item(i).id.indexOf('DivGroup_') != -1) {
				div_info.item(i).style.display		= "none";
			}
		}
		//Icons
		for (i=0; i<icon_info.length; i++) {
			if (icon_info.item(i).id.indexOf('ImgGroup_') != -1) {
				icon_info.item(i).src	= icon_info.item(i).src.replace('minus', 'plus');
			}
		}
	}

	function showHidePanel(){
		var div_01	= document.getElementById('Div_Top01');
		var div_02	= document.getElementById('Div_Top02');

		if (div_01.style.display == "none"){
			//Show the panel
			div_01.style.display = "";
			div_02.style.display = "none";
			parent.document.getElementById('FR_Panel').cols	= "181,*";
			parent.document.getElementById('FR_Panel_Left').csrolling	= "";
		}
		else{
			//Hide the panel
			div_01.style.display = "none";
			div_02.style.display = "";
			parent.document.getElementById('FR_Panel').cols	= "18,*";
			parent.document.getElementById('FR_Panel_Left').csrolling	= "no";
		}
	}
</script>
</head>

<body style="margin: 0px">

<div id="Div_Top01" align="left">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="32" align="left"><img src="{TEMPLATE_PATH}/images/admin/acp.gif" alt="Admin Control Panel"></td>
		<td align="right"><a href="#" onclick="javascript: showHidePanel();"><img src="{TEMPLATE_PATH}/images/admin/hide_panel.gif" alt="{L_HIDE_PANEL}" border="0" hspace="2"></a></td>
	</tr>
</table>

<table width="100%" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_02.gif);" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="2" width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="60" height="13"><img src="{TEMPLATE_PATH}/images/admin/adminmenu_top_1.gif" width="60" height="13" alt=""></td>
				<td width="99%" height="13" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_top_2.gif);"></td>
				<td width="16" height="13"><img src="{TEMPLATE_PATH}/images/admin/adminmenu_top_3.gif" width="16" height="13" alt=""></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="99%">

			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr><td height="50" align="center">
				<a class="stitle" href="{U_ACP_HOME}" target="main">{L_ACP_HOME}</a><br>
				<a class="stitle" href="#" onclick="javascript: expandAll(); return false;">{L_EXPAND_ALL}</a> | <a class="stitle" href="#" onclick="javascript: collapseAll(); return false;">{L_COLLAPSE_ALL}</a>
			</td></tr>
			</table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<!-- START: fgroup -->
				<tr><td colspan="2" height="2" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_03.gif);"></td></tr>
				<tr>
					<td colspan="2" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_04.gif);">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr style="cursor: hand;" onclick="javascript: showHideDiv('{fgroup:ID}'); return false;">
							<td align="left" class="menugroup">{fgroup:NAME}</td>
							<td align="right"><img id="ImgGroup_{fgroup:ID}" src="{TEMPLATE_PATH}/images/admin/minus.gif" border="0" hspace="3"></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2" height="2" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_05.gif);"></td></tr>
				<tr><td>
					<div id="DivGroup_{fgroup:ID}">
					<table cellspacing="0" cellpadding="0" border="0">
					<tr><td height="3"></td></tr>
					<!-- START: fgroup:func -->	
					<tr><td height="20" nowrap style="padding-left: 10px"><img src="{TEMPLATE_PATH}/images/admin/arrow_m.gif" border="0" align="absmiddle"> <a class="menufunc" href="{fgroup:func:U_LINK}" target="{fgroup:func:TARGET}">{fgroup:func:NAME}</a></td></tr>
					<!-- END: fgroup:func -->	
					</table>
					</div>
				</td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<!-- END: fgroup -->
			</table>
			
		</td>
		<td width="4" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_rightcol.gif);"><img src="{TEMPLATE_PATH}/images/admin/space.gif" border="0" width="1"></td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="60" height="13"><img src="{TEMPLATE_PATH}/images/admin/adminmenu_bottom_1.gif" width="60" height="13" alt=""></td>
				<td width="99%" height="13" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmenu_bottom_2.gif);"></td>
				<td width="16" height="13"><img src="{TEMPLATE_PATH}/images/admin/adminmenu_bottom_3.gif" width="16" height="13" alt=""></td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<center>==[ <a class="stitle" href="{U_ONLINE_DOCUMENT}" target="_blank">{L_ONLINE_DOCUMENT}</a> ]==</center>
</div>

<div id="Div_Top02" align="left" style="display: none">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="right"><a href="#" onclick="javascript: showHidePanel();"><img src="{TEMPLATE_PATH}/images/admin/show_panel.gif" alt="{L_SHOW_PANEL}" border="0" vspace="7"></a></td>
	</tr>
</table>
</div>
<br>

</body>
</html>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       