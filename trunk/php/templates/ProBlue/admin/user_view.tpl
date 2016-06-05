
<table width="100%" style="height: 32px;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="23"><img src="{TEMPLATE_PATH}/images/admin/adminmain_01.gif" width="23" height="32" alt=""></td>
		<td width="67"><img src="{TEMPLATE_PATH}/images/admin/adminmain_03.gif" width="67" height="32" alt=""></td>
		<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmain_04.gif);" align="center" class="pagetitle">{L_PAGE_TITLE}</td>
		<td width="66"><img src="{TEMPLATE_PATH}/images/admin/adminmain_05.gif" width="66" height="32" alt=""></td>
		<td width="27"><img src="{TEMPLATE_PATH}/images/admin/adminmain_07.gif" width="27" height="32" alt=""></td>
	</tr>
</table>
<br><br>

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_USERNAME}:</td>
	    <td class=tdtext2><strong>{USERNAME}</strong></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL}:</td>
	    <td class=tdtext2>{EMAIL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_IN_GROUP}:</td>
	    <td class=tdtext2>{USER_GROUPS}</td>
	  </tr>
	<!-- START: fieldrow -->
	  <tr>
	    <td class=tdtext1>{fieldrow:TITLE}:</td>
	    <td class=tdtext2>{fieldrow:VALUE}</td>
	  </tr>
	<!-- END: fieldrow -->
	</table>        
    </td>
  </tr>
</table>
<br>
<table border="0" cellpadding="1">
  <tr><td align="center">[<a href="javascript: window.close();">{L_CLOSE}</a>]</td></tr>
</table>
</div>