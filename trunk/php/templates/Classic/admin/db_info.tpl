
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

<div align="center"><span class="btitle">{DBSQL_VERSION}</span><br><br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu>{L_TABLE}</td>
	    <td class=tdmenu align="center">{L_ROWS}</td>
	    <td class=tdmenu align="center">{L_DATA_SIZE}</td>
	    <td class=tdmenu align="center">{L_MAX_DATA_SIZE}</td>
	    <td class=tdmenu align="center">{L_INDEX_SIZE}</td>
	    <td class=tdmenu align="center">{L_CREATE_TIME}</td>
	    <td class=tdmenu align="center">{L_UPDATE_TIME}</td>
	  </tr>
	<!-- START: tablerow -->
	  <tr>
	    <td class=tdtext1><span class="title2">{tablerow:NAME}</span></td>
	    <td class=tdtext2 align="center">{tablerow:ROWS}</td>
	    <td class=tdtext1 align="center"><span class="date">{tablerow:DTSIZE}</span></td>
	    <td class=tdtext2 align="center"><span class="date">{tablerow:MAXDTSIZE}</span></td>
	    <td class=tdtext1 align="center"><span class="date">{tablerow:INDEXSIZE}</span></td>
	    <td class=tdtext2 align="center"><span class="date">{tablerow:CREATETIME}</span></td>
	    <td class=tdtext1 align="center"><span class="date">{tablerow:UPDATETIME}</span></td>
	  </tr>
	<!-- END: tablerow -->
	</table>        
    </td>
  </tr>
</table>
</div>
&nbsp;<span class="menu">{TOTAL_SIZE}</span><br>

<!-- START: permrunsql -->
<div align="center"><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="RUNQUERYFORM" method="POST" action="{S_RUN_SQL}" onsubmit="javascript: return checkSQL();">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" align="center">{L_RUN_QUERY}</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" align="center"><span class="date">{L_RUN_NOTE}</span><br><textarea class=form name="sql" cols="70" rows="6"></textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" align="center"><input class=submit type=submit name="submit" value="{L_RUN}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= document.RUNQUERYFORM;
	
	function checkSQL(){
		if (the_form.sql.value == ''){
			alert("{L_ERROR_NOT_SQL}");
			the_form.sql.focus();
			return false;
		}
		return true;
	}
</script>

<!-- END: permrunsql -->