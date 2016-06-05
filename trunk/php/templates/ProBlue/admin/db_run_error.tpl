
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

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="RUNQUERYFORM" method="POST" action="{S_RUN_SQL}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" align="center">{L_RUN_QUERY}</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" align="center"><span class="date">{L_RUN_NOTE}</span><br><textarea class=form name="sql" cols="70" rows="6">{SQL_VALUE}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" align="center"><input class=submit type=submit name="submit" value="{L_RUN}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>

<br><span class="menu">{L_SQL_ERROR}</span><br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%"><pre>{SQL_RESULT}</pre></td>
	  </tr>
	</table>        
    </td>
  </tr>
</table>
</div>