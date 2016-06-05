
<table width="100%" style="height: 32px;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="23"><img src="{TEMPLATE_PATH}/images/admin/adminmain_01.gif" width="23" height="32" alt=""></td>
		<td width="30"><img src="{TEMPLATE_PATH}/images/admin/adminmain_02.gif" width="30" height="32" alt=""></td>
		<td width="67"><img src="{TEMPLATE_PATH}/images/admin/adminmain_03.gif" width="67" height="32" alt=""></td>
		<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmain_04.gif);" align="center" class="pagetitle">{L_PAGE_TITLE}</td>
		<td width="66"><img src="{TEMPLATE_PATH}/images/admin/adminmain_05.gif" width="66" height="32" alt=""></td>
		<td width="31"><img src="{TEMPLATE_PATH}/images/admin/adminmain_06.gif" width="31" height="32" alt=""></td>
		<td width="27"><img src="{TEMPLATE_PATH}/images/admin/adminmain_07.gif" width="27" height="32" alt=""></td>
	</tr>
</table>

<br><div align="center">{ERROR_MSG}<br><br>

<table width="100%" style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
<tr><td>
	<table style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_05.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu2" href="{U_TAB_WEBSITE}">{L_TAB_WEBSITE}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_07.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_SYSTEM}">{L_TAB_SYSTEM}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_MODULES}">{L_TAB_MODULES}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>

			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btnhover_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_OPENCLOSE}"><strong>{L_TAB_OPEN_CLOSE}</strong></a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
		</tr>
	</table>
</td></tr>
<tr><td height="2"></td></tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_WEBSITE_CLOSE}?<br><span class="date">{L_WEBSITE_CLOSE_DESC}</span></td>
	    <td class=tdtext2 width="60%" valign="top"><input type="radio" name="website_close" value="1">{L_YES} <input type="radio" name="website_close" value="0" checked>{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_WEBSITE_CLOSE_MESSAGE}:</td>
	    <td class=tdtext2 valign="top"><textarea class=form name="website_close_message" cols="60" rows="7">{WEBSITE_CLOSE_MESSAGE}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	the_form = window.document.EDITFORM;

	radio_list("{WEBSITE_CLOSE}", the_form.website_close);
</script>