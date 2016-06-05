
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
<table width="400" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_PUBLISH_RSS} {U_REMOVE_RSS}&nbsp;</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="400">
<form name="LISTFORM" method="POST" action="{S_UPDATE_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="10%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td align="center">{L_CATEGORY}</td>
	  </tr>
	  <tr class="tdtext1" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="rss_codes[0]" value="{RSS_CODE_LATEST}"></td>
	    <td>&laquo; {L_LATEST_ARTICLES} {RSS_LATEST_ARTICLES} &raquo;</td>
	  </tr>
	  <!-- START: catrow -->
	  <tr class="{catrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="cat_ids[{catrow:ID}]" value="{catrow:ID}"></td>
	    <td>{catrow:PREFIX}{catrow:NAME} {catrow:RSS}</td>
	  </tr>
	  <!-- END: catrow -->
	  <!-- START NOIF: catrow -->
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2" align="center">&nbsp;</td>
	  </tr>
	  <!-- END NOIF: catrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="400" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top">{U_PUBLISH_RSS} {U_REMOVE_RSS}&nbsp;</td>
  </tr>
</table>
<br>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;

	function updateForm(the_url){
		the_form.action = the_url;
		the_form.submit();
	}
</script>