
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

<div align="center"><br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="DELFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">{L_DELETE_CONFIRM}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="35%">{L_GROUP_NAME}:</td>
	    <td class=tdtext2 width="65%"><strong>{GROUP_NAME}</strong></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_GROUP_USERS}:</td>
	    <td class=tdtext2><strong>{USER_COUNTER}</strong></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_DELETE_MOVE}</td>
	    <td class=tdtext2>
			<input type="radio" name="del_user" value="1" checked> <span class="textbody">{L_DELETE_USERS}<br>
			<input type="radio" name="del_user" value="0">
			{L_MOVE_USERS}
			<select class=form name="group_id" onchange="javascript: radio_list(0, window.document.DELFORM.del_user)">
				<!-- START: grouprow -->
				<option value="{grouprow:ID}">{grouprow:NAME}</option>
				<!-- END: grouprow -->
			</select>
			</span>
	    </td>
	  </tr>
	  <tr>
	    <td class="tdtext1">&nbsp;</td><td class=tdtext2><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>