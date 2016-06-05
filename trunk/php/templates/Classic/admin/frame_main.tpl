
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

<br><div align="center"><span class="error">{ERROR_MSG}</span></div><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="94%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu align="center">{L_ONLINE_OVERVIEW}</td>
	  </tr>
	  <tr>
	    <td height="30" class=tdtext2 align="center">{ONLINE_USERS}&nbsp;</td>
	  </tr>
	</table>        
    </td>
  </tr>
</table>


<br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="94%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td colspan="4" class=tdmenu align="center">{L_SYSTEM_OVERVIEW}</td>
	  </tr>
	  <tr>
	    <td colspan="4" class=tdtext2 align="center" height="30">{L_SCRIPT_NAME}: {SCRIPT_NAME}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%">{L_SERVER_TYPE}</td>
	    <td class=tdtext2 width="25%">{SERVER_TYPE}</td>
	    <td class=tdtext1 width="25%">{L_WEB_SERVER}</td>
	    <td class=tdtext2>{WEB_SERVER}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PHP_VERSION}</td>
	    <td class=tdtext2>{PHP_VERSION}</td>
	    <td class=tdtext1>{L_MYSQL_VERSION}</td>
	    <td class=tdtext2>{MYSQL_VERSION}</td>
	  </tr>
	</table>        
    </td>
  </tr>
</table>

<br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="94%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td colspan="4" class=tdmenu align="center">{L_DATA_OVERVIEW}</td>
	  </tr>
	  <!-- START: grouprow -->
	  <tr>
	    <td colspan="4" class=tdtext1>{grouprow:L_GROUP_NAME}:</td>
	  </tr>
		  <!-- START: grouprow:funcrow -->
		  <tr>
			<!-- START: grouprow:funcrow:funccol -->
		    <td class=tdtext1 width="30%">&nbsp; &nbsp; &raquo; <a href="{grouprow:funcrow:funccol:U_FUNC_URL}">{grouprow:funcrow:funccol:L_FUNC_NAME}</a>:</td>
		    <td class=tdtext2 width="20%">{grouprow:funcrow:funccol:COUNTER}</td>
			<!-- END: grouprow:funcrow:funccol -->
		  </tr>
		  <!-- END: grouprow:funcrow -->
	  <!-- END: grouprow -->
	</table>        
    </td>
  </tr>
</table>

<br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="94%">
<form name="NOTEFORM" method="POST" action="{S_ACP_NOTE}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu align="center">{L_ACP_NOTE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 align="center"><textarea class="myform" name="acp_note" cols="50" rows="8" style="width: 90%">{ACP_NOTE}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 align="center"><input class="submit" type="submit" name="admin_note" value="{L_BUTTON_UPDATE}"></td>
	  </tr>
	</table>
    </td>
  </tr>
</form>
</table>
</div>