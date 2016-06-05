
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
<br>

<div align="center"><span class="btitle">{DBSQL_VERSION}</span></div><br><br>
<span class="textbody">{SERVER_UPTIME}</span><div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	<!-- START: fieldcol -->
	    <td class=tdmenu>{fieldcol:NAME}</td>
	<!-- END: fieldcol -->
	  </tr>

	<!-- START: recordrow -->
	  <tr>
		<!-- START: recordrow:recordcol -->
	    <td class=tdtext{recordrow:recordcol:CSS}>{recordrow:recordcol:VALUE}</td>
		<!-- END: recordrow:recordcol -->
	  </tr>
	<!-- END: recordrow -->
	</table>        
    </td>
  </tr>
</table>
</div>