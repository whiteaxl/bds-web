
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
  <td class=tdtext align="right">
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="25">
		<select class=form name="flogo_pos">
			<option value="-1">-- {L_LOGO_POS} --</option>
			<option value="{LOGO_POS_LEFT}">{L_LOGO_POS_LEFT}</option>
			<option value="{LOGO_POS_RIGHT}">{L_LOGO_POS_RIGHT}</option>
		</select>
		<select class=form name="fstatus">
			<option value="">-- {L_STATUS} --</option>
			<option value="enabled">{L_ENABLED}</option>
			<option value="disabled">{L_DISABLED}</option>
			<option value="waiting">{L_WAITING}</option>
			<option value="showing">{L_SHOWING}</option>
			<option value="expired">{L_EXPIRED}</option>
		</select>
		<select class=form name="fsort_by">
			<option value="logo_order">{L_SEARCH_ORDER}</option>
			<option value="site_name">{L_LOGO}</option>
			<option value="hits">{L_HITS}</option>
		</select>
		<select class=form name="fsort_order">
			<option value="asc">{L_ASC}</option>
			<option value="desc">{L_DESC}</option>
		</select>
		<input class=submit type="submit" name="submit" value="{L_BUTTON_SEARCH}">  
  </td>
 </tr>
 <tr>
  <td height="30" valign="top" class=tdtext>{U_ADD} {U_RESYNC}</td>
 </tr>
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
<form name="LISTFORM" method="POST" action="">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="3%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td width="8%" align="center">{L_ORDER}</td>
	    <td align="center">{L_LOGO}</td>
	    <td width="15%" align="center">{L_LOGO_POS}</td>
	    <td width="15%" align="center">{L_DATE}</td>
	    <td width="15%" align="center">{L_STATUS}</td>
	    <td width="10%" align="center">{L_HITS}</td>
	    <td width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: logorow -->
	  <tr class="{logorow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="logo_ids[{logorow:ID}]" value="{logorow:ID}"></td>
	    <td align="center"><input class=form type="text" name="logo_orders[{logorow:ID}]" value="{logorow:ORDER}" size="3"></td>
	    <td align="center">{logorow:IMG}<br><span class="{logorow:CSS}">{logorow:SITE_NAME}</span></td>
	    <td align="center"><span class="{logorow:CSS}">{logorow:LOGO_POS}</span></td>
	    <td align="center"><span class="{logorow:CSS}">{logorow:DATE}</span></td>
	    <td align="center"><span class="{logorow:CSS}">{logorow:STATUS}</span></td>
	    <td align="center"><span class="{logorow:CSS}">{logorow:HITS}</span></td>
	    <td align="center">{logorow:U_EDIT}</td>
	  </tr>
	<!-- END: logorow -->

	<!-- START NOIF: logorow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="8" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: logorow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_ENABLE} {U_DISABLE} {U_UPDATE} {U_DELETE}</td>
    <td class=tdtext valign="top" align="right">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;
	
	select_list("{FLOGO_POS}", filter_form.flogo_pos);
	select_list("{FSTATUS}", filter_form.fstatus);
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

