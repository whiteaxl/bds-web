
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

<div align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_ADD} {U_RESYNC} {U_MOVE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu>{L_GROUP_NAME}</td>
	    <td class=tdmenu align="center">{L_LEVEL}</td>
	    <td class=tdmenu align="center">{L_USER_COUNTER}</td>
	    <td class=tdmenu align="center">{L_PERM}</td>
	    <td class=tdmenu align="center">{L_EDIT}</td>
	    <td class=tdmenu align="center">{L_DELETE}</td>
	  </tr>
	<!-- START: grouprow -->
	  <tr>
	    <td class=tdtext1><span class="title2">{grouprow:NAME}</span></td>
	    <td class=tdtext2 align="center">{grouprow:LEVEL}</td>
	    <td class=tdtext1 align="center">{grouprow:U_USER_COUNTER}</td>
	    <td class=tdtext2 align="center">{grouprow:U_PERM}</td>
	    <td class=tdtext1 align="center">{grouprow:U_EDIT}</td>
	    <td class=tdtext2 align="center">{grouprow:U_DEL}</td>
	  </tr>
	<!-- END: grouprow -->
	</table>        
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top">{U_ADD} {U_RESYNC} {U_MOVE}&nbsp;</td>
    <td class=tdtext valign="top" align="right">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
function deleteGroup(user_counter){
	if (user_counter > 0){
		return true;
	}	
	if ( confirm('{L_DELETE_CONFIRM}') ){
		return true;
	}
	return false;
}
</script>