
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
 <tr style="height: 25px;">
  <td class=tdtext>{U_ADD}</td>
  <td class=tdtext align="right">
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
</form>
</table> 

<div align="center"><br><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<form name="LISTFORM" method="POST" action="{S_LIST_ACTION}">
	  <tr class="tdmenu">
	    <td width="4%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td>{L_TITLE}</td>
	    <td width="20%" align="center">{L_LAST_SENT}</td>
	    <td width="15%" align="center">{L_SEND}</td>
	    <td width="10%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: issuerow -->
	  <tr class="{issuerow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="issue_ids[{issuerow:ID}]" value="{issuerow:ID}"></td>
	    <td><a class="{issuerow:CSS}" href="javascript: open_window('{issuerow:U_VIEW}', 550, 450);" title="{L_VIEW_DESC}">{issuerow:TITLE}</a></td>
	    <td align="center"><span class="{issuerow:CSS}">{issuerow:LAST_SENT}</span></td>
	    <td align="center">{issuerow:U_SEND}</td>
	    <td align="center">{issuerow:U_EDIT}</td>
	  </tr>
	<!-- END: issuerow -->

	<!-- START NOIF: issuerow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="5" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: issuerow -->
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

	select_list("{FSTATUS}", filter_form.fstatus);
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
