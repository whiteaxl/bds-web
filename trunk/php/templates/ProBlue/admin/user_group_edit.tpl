
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

<br><div align="center"><span class="error">{ERROR_MSG}</span><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="GROUPFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_GROUP_NAME}</td>
	    <td class=tdtext2><input class=form type="text" name="group_name" value="{GROUP_NAME}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_GROUP_DESC}</td>
	    <td class=tdtext2><textarea class=form rows="3" name="group_desc" cols="50">{GROUP_DESC}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LEVEL}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="group_level" value="1">{L_GROUP_ADMIN} <input class=form2 type="radio" name="group_level" value="0" checked>{L_GROUP_NORMAL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><br><input class=submit type="submit" name="submit" value="{L_BUTTON_UPDATE}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form = window.document.GROUPFORM;

	radio_list("{GROUP_LEVEL}", the_form.group_level);
</script>
