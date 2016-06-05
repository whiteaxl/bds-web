
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

<br><br><div align="center">
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_ADD} {U_UPDATE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="8%" align="center">{L_ORDER}</td>
	    <td>{L_TITLE}</td>
	    <td width="25%" align="center">{L_TYPE}</td>
	    <td width="25%" align="center">{L_REQUIRED}</td>
	    <td width="8%" align="center">{L_EDIT}</td>
	    <td width="8%" align="center">{L_DELETE}</td>
	  </tr>
	<!-- START: fieldrow -->
	  <tr class="{fieldrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input class=form type="text" name="field_orders[{fieldrow:ID}]" value="{fieldrow:ORDER}" size="2"></td>
	    <td>{fieldrow:TITLE}</td>
	    <td align="center"><span class="date">{fieldrow:TYPE}</span></td>
	    <td align="center">{fieldrow:REQUIRED}</td>
	    <td align="center">{fieldrow:U_EDIT}</td>
	    <td align="center">{fieldrow:U_DEL}</td>
	  </tr>
	<!-- END: fieldrow -->

	<!-- START NOIF: fieldrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="6" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: fieldrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top">{U_ADD} {U_UPDATE}&nbsp;</td>
    <td class=tdtext valign="top" align="right">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form = window.document.LISTFORM;

	function updateForm(the_url){
		the_form.action = the_url;
		the_form.submit();
	}
</script>