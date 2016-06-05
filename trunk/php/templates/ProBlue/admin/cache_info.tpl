
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

<br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2" align="center">{L_CACHE_HTML}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_HTML_FILES}</td>
	    <td class=tdtext2 width="60%" valign="top">{HTML_FILES}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_HTML_SIZE}</td>
	    <td class=tdtext2 valign="top">{HTML_SIZE}</td>
	  </tr>
	  <tr>
	    <td class=tdmenu width="100%" colspan="2" align="center">{L_CACHE_PHP}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PHP_FILES}</td>
	    <td class=tdtext2 valign="top">{PHP_FILES}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PHP_SIZE}</td>
	    <td class=tdtext2 valign="top">{PHP_SIZE}</td>
	  </tr>
	  <tr>
	    <td class=tdmenu width="100%" colspan="2" align="center">{L_CACHE_TOTAL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_TOTAL_FILES}</td>
	    <td class=tdtext2 width="60%" valign="top">{TOTAL_FILES}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TOTAL_SIZE}</td>
	    <td class=tdtext2 valign="top">{TOTAL_SIZE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="submit" value="{L_CLEAN}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>

