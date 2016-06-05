
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
 <tr>
  <td height="25" class=tdtext align="right">
		{L_DATE_FROM}: <input class=form type="text" name="fdate_from" value="{FDATE_FROM}" size="8">
		{L_DATE_TO}: <input class=form type="text" name="fdate_to" value="{FDATE_TO}" size="8">
		<input class=submit type="submit" name="submit" value="{L_BUTTON_VIEW}">  
  </td>
 </tr>
</form>
</table> 

<div align="center"><br><span class="title">{L_REPORT_TITLE}</span><br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="20%" align="center">{L_MONTHS}</td>
	    <td class=tdmenu align="center">{L_VISITORS}</td>
	  </tr>
	  <!-- START: statrow -->
	  <tr>
	    <td class=tdtext1 align="right">{statrow:MONTH} {statrow:YEAR}</td>
	    <td class=tdtext2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-color: #111111; border-collapse: collapse">
			<tr>
				<td width="75%">
					<table bgcolor="#F1F8F9" border="1" cellpadding="0" cellspacing="0" style="border-color: #111111; border-collapse: collapse; height: 13px;" width="100%">
					<tr>
						<td width="100%">				
							<table style="height: 11px; background-image: url({TEMPLATE_PATH}/images/admin/stat.gif);" width="{statrow:PERCENT}" style="height: 11px;" border="0" cellpadding="0" cellspacing="0">
							<tr><td width="100%"></td></tr>
							</table>				
						</td>
					</tr>
					</table>
				</td>
				<td nowrap>&nbsp;{statrow:PERCENT} [{statrow:VISITORS}]</td>
			</tr>
			</table>
		</td>
	  </tr>
	  <!-- END: statrow -->
	  <!-- START IF: statrow -->
	  <tr>
	    <td class=tdtext2 colspan="2" align="center">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 colspan="2" align="center"><strong>{L_TOTAL_VISITORS}: &nbsp;{TOTAL_VISITORS}</strong></td>
	  </tr>
	  <!-- END IF: statrow -->

	  <!-- START NOIF: statrow -->
	  <tr>
	    <td class=tdtext1 colspan="2">&nbsp;</td>
	  </tr>
	  <!-- END NOIF: statrow -->
	</table>        
    </td>
  </tr>
</table>
</div>
