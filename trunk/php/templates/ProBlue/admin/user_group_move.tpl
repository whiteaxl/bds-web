
<div align="center">
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

<br><br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="MOVEFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr valign="top">
	    <td width="50%" class=tdtext1 align="center">
		
			<table border="0" cellpadding="0">
			<tr><td>
				{L_SOURCE_GROUPS}:<br>
				<select class=form name="source_id[]" size="10" multiple>
					<!-- START: grouprow -->
					<option value="{grouprow:ID}">{grouprow:NAME} ({grouprow:USER_COUNTER})</option>
					<!-- END: grouprow -->
				</select>
			</td></tr></table>
			
	    </td>
	    <td width="50%" class=tdtext2 align="center">
		
			<table border="0" cellpadding="0">
			<tr><td>
				{L_DEST_GROUP}:<br>
				<select class=form name="dest_id">
					<!-- START: grouprow -->
					<option value="{grouprow:ID}">{grouprow:NAME}</option>
					<!-- END: grouprow -->
				</select>
			</td></tr>
			</table>
			
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="updatesm" value="{L_MOVE_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>
