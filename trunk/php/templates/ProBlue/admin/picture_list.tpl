
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
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="20">
		<select class="form" name="fstatus">
			<option value="-1">- - {L_STATUS} - -</option>
			<option value="{SYS_ENABLED}">{L_ENABLED}</option>
			<option value="{SYS_DISABLED}">{L_DISABLED}</option>
			<option value="{SYS_SHOWING}">{L_SHOWING}</option>
			<option value="{SYS_WAITING}">{L_WAITING}</option>
		</select>
		<select class=form name="fcat_id">
			<option value="0">- - - - - - {L_SHOW_PAGE} - - - - - -</option>
			<option value="-1">{L_HOME_PAGE}</option>
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
	    <td width="20%">{L_PICTURE}</td>
	    <td>{L_CONTENT}</td>
	    <td width="12%" align="center">{L_DATE}</td>
	    <td width="10%" align="center">{L_STATUS}</td>
	    <td width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: picturerow -->
	  <tr class="{picturerow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="picture_ids[{picturerow:ID}]" value="{picturerow:ID}"></td>
	    <td align="center"><a href="javascript: open_window('{picturerow:U_VIEW}', 400, 350);">{picturerow:PIC_THUMB}</a></td>
	    <td><div align="justify"><span class="{picturerow:CSS}">{picturerow:CONTENT}</span></div></td>
	    <td align="center"><span class="{picturerow:CSS}">{picturerow:DATE}</span></td>
	    <td align="center"><span class="{picturerow:CSS}">{picturerow:STATUS}</span></td>
	    <td align="center">{picturerow:U_EDIT}</td>
	  </tr>
	<!-- END: picturerow -->

	<!-- START NOIF: picturerow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="6" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: picturerow -->
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
	select_list("{FCAT_ID}",filter_form.fcat_id);

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
