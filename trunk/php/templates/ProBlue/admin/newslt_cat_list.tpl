
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
<form name="MODEFORM" method="POST" action="{S_MODE_ACTION}">
  <tr>
    <td class="tdtext" valign="top">{U_ADD}</td>
	<td align="right">
		<select name="view_mode" onchange="javascript: document.MODEFORM.submit();">
			<option value="normal">{L_NORMAL}</option>
			<option value="expand">{L_EXPAND}</option>
		</select>
	</td>
  </tr>
</form>
</table> 

<br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_UPDATE} {U_RESYNC} {U_MOVE}&nbsp;</td>
    <td class=tdtext align="right" valign="bottom">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="{S_UPDATE_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="7%" align="center">{L_ORDER}</td>
	    <td>{L_NAME}</td>
		<!-- START IF: normalmode -->
	    <td width="15%" align="center" nowrap>{L_CHILDS}</td>
		<!-- END IF: normalmode -->
	    <td width="15%" align="center">{L_EMAILS}</td>
	    <td width="8%" align="center">{L_EDIT}</td>
	    <td width="8%" align="center">{L_DELETE}</td>
	  </tr>
	<!-- START: parenthave -->
	  <tr>
	    <td class=tdtext1 colspan="9"><a href="{U_LIST_CAT}"><img src="{TEMPLATE_PATH}/images/admin/bullet_2.gif" border="0" alt=""></a> <b>{parenthave:NAME}</b></td>
	  </tr>
	<!-- END: parenthave -->
	<!-- START: catrow -->
	  <tr class="{catrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input class=form type="text" name="cat_orders[{catrow:ID}]" value="{catrow:ORDER}" size="5" style="width: 30px" maxlength="3"></td>
	    <td><span class="{catrow:CSS}">{catrow:PREFIX}{catrow:NAME}</span></td>
		<!-- START IF: normalmode -->
	    <td align="center"><span class="{catrow:CSS}">{catrow:CHILDREN_COUNTER}</span></td>
		<!-- END IF: normalmode -->
	    <td align="center"><span class="{catrow:CSS}">{catrow:EMAIL_COUNTER}</span></td>
	    <td align="center">{catrow:U_EDIT}</td>
	    <td align="center">{catrow:U_DEL}</td>
	  </tr>
	<!-- END: catrow -->
	<!-- START NOIF: catrow -->
	  <tr>
	    <td class=tdtext2 width="100%" colspan="6" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: catrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_UPDATE} {U_RESYNC} {U_MOVE}</td>
    <td class=tdtext align="right" valign="top">{PAGE_OUT}</td>
  </tr>
</table>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var mode_form	= window.document.MODEFORM;

	select_list("{VIEW_MODE}", mode_form.view_mode);

	function updateForm(the_url){
		the_form.action = the_url;
		the_form.submit();
	}
</script>