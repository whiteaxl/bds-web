
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
	<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="25">
	<select class="form" name="fstatus">
		<option value="-1">- - {L_STATUS} - -</option>
		<option value="{SYS_ENABLED}">{L_ENABLED}</option>
		<option value="{SYS_DISABLED}">{L_DISABLED}</option>
	</select>
	<select class=form name="fcat_id">
		<option value="0">- - - - - {L_CATS} - - - - -</option>
		<!-- START: catrow -->
		<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
		<!-- END: catrow -->
	</select>
	<input class="submit" type="submit" name="smSearch" value="{L_SEARCH}">  
  </td>
 </tr>
 <tr>
   <td valign="top" height="30" class=tdtext valign="top">{U_ADD} {U_MOVE_CAT} {U_IMPORT} {U_EXPORT}</td>
 </tr>
</form>
</table> 

<br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<form name="LISTFORM" method="POST" action="{S_LIST_ACTION}">
	  <tr class="tdmenu">
	    <td width="4%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td width="35%">{L_NAME}</td>
	    <td>{L_EMAIL}</td>
	    <td width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: emailrow -->
	  <tr class="{emailrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="newslt_ids[{emailrow:ID}]" value="{emailrow:ID}"></td>
	    <td><span class="{emailrow:CSS}">{emailrow:NAME}&nbsp;</span></td>
	    <td><span class="{emailrow:CSS}">{emailrow:EMAIL}</span></td>
	    <td align="center">{emailrow:U_EDIT}</td>
	  </tr>
	<!-- END: emailrow -->
	</form>
	</table>        
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext align="right" valign="top">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
</div>


<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;

	select_list("{FSTATUS}",filter_form.fstatus);
	select_list("{FCAT_ID}", filter_form.fcat_id);

	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}
	
	function deleteForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			if ( confirm('{L_DEL_CONFIRM}') ){
				the_form.action = the_url;
				the_form.submit();
			}
		}
	}
</script>
