
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

<table border="0" cellpadding="1" cellspacing="0" width="100%">
<form name="FILTERFORM" method="POST" action="{S_FILTER_ACTION}">
 <tr>
  <td height="25" class=tdtext align="right">
		{L_IP}: <input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="20">
		<select class=form name="ffunc_name">
			<option value="">- - - - {L_FUNCTION} - - - -</option>
			<option value="menu_general_login">{L_FUNC_LOGIN}</option>
			<option value="menu_general_logout">{L_FUNC_LOGOUT}</option>
			<!-- START: funcrow -->
			<option value="{funcrow:NAME}">{funcrow:FULLNAME}</option>
			<!-- END: funcrow -->
		</select>
		<select class=form name="ffunc_action">
			<option value="">- {L_ACTION} -</option>
			<option value="log_login">{L_LOGIN}</option>
			<option value="log_logout">{L_LOGOUT}</option>
			<option value="log_add">{L_ADD}</option>
			<option value="log_edit">{L_EDIT}</option>
			<option value="log_del">{L_DEL}</option>
			<option value="log_move">{L_MOVE}</option>
			<option value="log_enable">{L_ENABLE}</option>
			<option value="log_disable">{L_DISABLE}</option>
		</select>
		<select class=form name="fuser_id">
			<option value="">- - - {L_USER} - - -</option>
			<!-- START: userrow -->
			<option value="{userrow:ID}">{userrow:NAME}</option>
			<!-- END: userrow -->
		</select>
		<input class="submit" type="submit" name="smSearch" value="{L_SEARCH}">  
  </td>
 </tr>
</form>
</table> 

<br><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_EXPORT_LOG} &nbsp; &nbsp; {U_DEL_ALL}</td>
    <td class=tdtext valign="bottom" align="right">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="20%" align="center">{L_LOG_TIME}</td>
	    <td align="center">{L_FUNCTION}</td>
	    <td width="15%" align="center">{L_ACTION}</td>
	    <td width="15%" align="center">{L_RECORD}</td>
	    <td width="15%" align="center">{L_USER}</td>
	    <td width="15%" align="center">{L_IP}</td>
	  </tr>
	<!-- START: logrow -->
	  <tr class="{logrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td>{logrow:LOG_TIME}</td>
	    <td><a href="{logrow:U_FUNCTION}">{logrow:FUNC_NAME}</a></td>
	    <td align="center"><a href="{logrow:U_ACTION}">{logrow:ACTION}</a></td>
	    <td align="center">{logrow:RECORD}</td>
	    <td align="center"><a href="{logrow:U_USER}">{logrow:USERNAME}</a></td>
	    <td align="center"><a href="{logrow:U_IP}">{logrow:USER_IP}</a></td>
	  </tr>
	<!-- END: logrow -->
	</table>        
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_EXPORT_LOG} &nbsp;&nbsp; {U_DEL_ALL}</td>
    <td class=tdtext align="right" valign="top">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var	filter_form	= window.document.FILTERFORM;
			
	select_list("{FFUNC_NAME}",filter_form.ffunc_name);
	select_list("{FFUNC_ACTION}",filter_form.ffunc_action);
	select_list("{FUSER_ID}",filter_form.fuser_id);
				
	function del_confirm(counter) {
		question = confirm("{L_DEL_ALL} ?")
		if (question != "0"){
			return true;
		}
		return false;
	}
</script>
