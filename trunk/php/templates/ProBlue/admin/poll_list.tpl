
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
<form name="FILTERFORM" method="POST" action="{S_ACTION_FILTER}">
 <tr>
  <td class=tdtext width="100%" align="right">
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="20">
		<select class=form name="fstatus">
			<option value="">- {L_STATUS} -</option>
			<option value="enabled">{L_ENABLED}</option>
			<option value="disabled">{L_DISABLED}</option>
			<option value="waiting">{L_WAITING}</option>
			<option value="showing">{L_SHOWING}</option>
			<option value="expired">{L_EXPIRED}</option>
		</select>
		<select class=form name="fcat_id">
			<option value="0">- - - - - - {L_SHOW_PAGE} - - - - - -</option>
			<option value="-1">{L_HOME_PAGE}</option>
			<!-- START: catrow -->
			<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
			<!-- END: catrow -->
		</select>
		
		<select class=form name="fsort_by">
			<option value="poll_order">{L_SEARCH_ORDER}</option>
			<option value="question">{L_QUESTION}</option>
			<option value="total_hits">{L_HITS}</option>
		</select>
		<select class=form name="fsort_order">
			<option value="asc">{L_ASC}</option>
			<option value="desc" selected>{L_DESC}</option>
		</select>
		<input class=submit type="submit" name="submit" value="{L_BUTTON_SEARCH}">  
  </td>
 </tr>
 <tr><td height="30" valign="top" class=tdtext>{U_ADD} {U_RESYNC}</td></tr>
</form>
</table> 

<div align="center"><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_UPDATE} {U_DELETE}</td>
    <td class=tdtext valign="bottom" align="right">{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="3%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td width="8%" align="center">{L_ORDER}</td>
	    <td>{L_QUESTION}</td>
	    <td width="13%" align="center">{L_DATE}</td>
	    <td width="10%" align="center">{L_HITS}</td>
	    <td width="10%" align="center">{L_STATUS}</td>
	    <td width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: pollrow -->
	  <tr class="{pollrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="poll_ids[{pollrow:ID}]" value="{pollrow:ID}"></td>
	    <td align="center"><input class=form type="text" name="poll_orders[{pollrow:ID}]" value="{pollrow:ORDER}" size="5" style="width: 40px"></td>
	    <td><a class="{pollrow:CSS}" href="javascript: open_window('{pollrow:U_VIEW}', 500, 350);" title="{QUESTION_DESC}">{pollrow:QUESTION}</a></td>
	    <td align="center"><span class="{pollrow:CSS}">{pollrow:DATE}</span></td>
	    <td align="center"><span class="{pollrow:CSS}">{pollrow:HITS}</span></td>
	    <td align="center"><span class="{pollrow:CSS}">{pollrow:STATUS}</span></td>
	    <td align="center">{pollrow:U_EDIT}</td>
	  </tr>
	<!-- END: pollrow -->
	<!-- START NOIF: pollrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="6" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: pollrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_UPDATE} {U_DELETE}</td>
    <td class=tdtext valign="bottom" align="right">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;
	
	select_list("{FSTATUS}", filter_form.fstatus);
	select_list("{FCAT_ID}", filter_form.fcat_id);
	select_list("{FSOFT_BY}", filter_form.fsort_by);
	select_list("{FSOFT_ORDER}", filter_form.fsort_order);
	
	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}

	function updateForm2(the_url){
		the_form.action = the_url;
		the_form.submit();
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
