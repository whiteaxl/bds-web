
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

<br><div align="center"><span class="error">{ERROR_MSG}</span><br><br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="FIELDFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_TITLE}:</td>
	    <td class=tdtext2 width="50%"><input class=form type="text" name="ftitle" value="{FTITLE}" size="35" maxlength="128"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_DESC}:</td>
	    <td class=tdtext2 width="50%"><input class=form type="text" name="fdesc" value="{FDESC}" size="35" maxlength="255"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_TYPE}:</td>
	    <td class=tdtext2 width="50%">
			<select class=form size="1" name="ftype">
				<option value="textinput">{L_TEXTINPUT}</option>
				<option value="textarea">{L_TEXTAREA}</option>
				<option value="dropdown">{L_DROPDOWN}</option>
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_SIZE}:<br><span class=date>{L_FIELD_SIZE_DESC}</span></td>
	    <td class=tdtext2 width="50%" valign="top"><input class=form type="text" name="fsize" value="{FSIZE}" size="35"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_MAX_INPUT}:<br><span class=date>{L_MAX_INPUT_DESC}</span></td>
	    <td class=tdtext2 width="50%" valign="top"><input class=form type="text" name="fmaxinput" value="{FMAXINPUT}" size="35"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_CONTENT}:<br><span class=date>{L_FIELD_CONTENT_DESC}</span></td>
	    <td class=tdtext2 width="50%" valign="top"><textarea class=form rows="4" name="fcontent" cols="35">{FCONTENT}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%">{L_FIELD_REQUIRED}?<br><span class="date">{L_FIELD_REQUIRED_DESC}</span></td>
	    <td class=tdtext2 width="50%">
			<input type="radio" name="frequired" value="1">{L_YES}
			<input type="radio" name="frequired" value="0" checked>{L_NO}</td>
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
	the_form = window.document.FIELDFORM;
	
	select_list("{FTYPE}",the_form.ftype);	
	radio_list("{FREQUIRED}",the_form.frequired);
</script>
