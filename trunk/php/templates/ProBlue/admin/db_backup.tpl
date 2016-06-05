
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

<div align="center"><span class="btitle">{DBSQL_VERSION}</span></div><br>

<!-- START IF: displayresult -->
<br><strong>{L_SQL_RESULT}:</strong>
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
<br><br>
<!-- END IF: displayresult -->

<table width="100%" border="0" cellpadding="1" cellspacing="0">
<form name="DBFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_OPTIMIZE} {U_ANALYZE} {U_CHECK} {U_REPAIR}&nbsp;</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="5%" align="center"><input type="checkbox" name="allinput" onclick="javascript:do_check_all(window.document.DBFORM,window.document.DBFORM.allinput.checked);"></td>
	    <td width="55%">{L_TABLE}</td>
	    <td width="20%" align="center">{L_ROWS}</td>
	    <td width="20%" align="center">{L_DATA_SIZE}</td>
	  </tr>
	<!-- START: tablerow -->
	  <tr class="{tablerow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td width="5%" align="center"><input type=checkbox name="tbl[{tablerow:NAME}]" value="{tablerow:NAME}"></td>
	    <td width="55%"><span class="title2">{tablerow:NAME}</span></td>
	    <td width="20%" align="center">{tablerow:ROWS}</td>
	    <td width="20%" align="center"><span class="date">{tablerow:DTSIZE}</span></td>
	  </tr>
	<!-- END: tablerow -->
	</table>        
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="2" width="100%">
  <tr>
    <td width="100%" align="right"><span class="menu">{TOTAL_SIZE}</span>&nbsp;&nbsp;</td>
  </tr>
</table>

<br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2" align="center">{L_BACKUP_TBLS}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%" align="right">{L_STRUCTURE}?&nbsp;</td>
	    <td class=tdtext2 width="50%"><input type="radio" name="structure" value="1" checked>{L_YES} <input type="radio" name="structure" value="0">{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%" align="right">{L_DATA}?&nbsp;</td>
	    <td class=tdtext2 width="50%"><input type="radio" name="data" value="1" checked>{L_YES} <input type="radio" name="data" value="0">{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="50%" align="right">{L_USE_GZIP}?&nbsp;</td>
	    <td class=tdtext2 width="50%"><input type="radio" name="gzip" value="1" checked>{L_YES} <input type="radio" name="gzip" value="0">{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit class=submit type="submit" name="smBackup" value="{L_START_BACKUP}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>


<script language="javascript" type="text/javascript">
	var the_form	= window.document.DBFORM;

	function do_check_all(the_form,check_status){
		var structure = get_radio_value(the_form.structure);
		var data = get_radio_value(the_form.data);
		var gzip = get_radio_value(the_form.gzip);

		check_all(the_form,check_status);

		radio_list(structure,the_form.structure);
		radio_list(data,the_form.data);
		radio_list(gzip,the_form.gzip);
	}

	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}
</script>