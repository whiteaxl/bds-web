
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
		</select>
		<select class=form name="fgroup_id">
			<option value="0">---- {L_GROUP} ----</option>
			<!-- START: grouprow -->
			<option value="{grouprow:ID}">{grouprow:NAME}</option>
			<!-- END: grouprow -->
		</select>
			
		<select class=form name="fsort_by">
			<option value="username">{L_USERNAME}</option>
			<option value="user_email">{L_EMAIL}</option>
		</select>
		<select class=form name="fsort_order">
			<option value="asc">{L_ASC}</option>
			<option value="desc">{L_DESC}</option>
		</select>
		<input class=submit type="submit" name="submit" value="{L_BUTTON_SEARCH}">  
  </td>
 </tr>
 <tr><td height="30" valign="top">{U_ADD} {U_RESYNC}</td></tr>
</form>
</table> 

<div align="center"><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_ENABLE} {U_DISABLE} {U_DELETE}</td>
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
	    <td>{L_USERNAME}</td>
	    <td width="19%">{L_GROUP}</td>
	    <td width="15%" align="center">{L_ARTICLES}</td>
	    <td width="20%" align="center">{L_LAST_LOGIN}</td>
	    <td width="15%" align="center">{L_ONLINE}</td>
	    <td width="8%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: userrow -->
	  <tr class="{userrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center">{userrow:CHECKBOX}</td>
	    <td><a class="{userrow:CSS}" href="javascript: open_window('{userrow:U_VIEW}', 450, 350);">{userrow:USERNAME}</a></td>
	    <td><span class="{userrow:CSS}">{userrow:GROUPS}</span></td>
	    <td align="center"><span class="{userrow:CSS}">{userrow:ARTICLE_COUNTER}</span></td>
	    <td align="center"><span class="{userrow:CSS}">{userrow:LAST_LOGIN}</span></td>
	    <td align="center"><span class="{userrow:CSS}">{userrow:ONLINE}</span></td>
	    <td align="center">{userrow:U_EDIT}</td>
	  </tr>
	<!-- END: userrow -->
	</table>        
    </td>
  </tr>
  </form>
</table>

<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top">{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext valign="top" align="right">{PAGE_OUT}</td>
  </tr>
</table>

</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;

	select_list("{FSTATUS}", filter_form.fstatus);
	select_list("{FGROUP_ID}", filter_form.fgroup_id);
	select_list("{FSORT_BY}", filter_form.fsort_by);
	select_list("{FSORT_ORDER}", filter_form.fsort_order);

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
