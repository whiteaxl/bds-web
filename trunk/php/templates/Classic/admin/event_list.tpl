
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
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="25">
		<select class="form" name="fstatus">
			<option value="-1">- {L_STATUS} -</option>
			<option value="{SYS_ENABLED}">{L_ENABLED}</option>
			<option value="{SYS_DISABLED}">{L_DISABLED}</option>
		</select>
		<select class=form size="1" name="fday">
	        <option value="0">- {L_DAY} -</option>
			<option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> 
			<option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> 
			<option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> 
			<option value="13">13</option> <option value="14">14</option> <option value="15">15</option> <option value="16">16</option> 
			<option value="17">17</option> <option value="18">18</option> <option value="19">19</option> <option value="20">20</option> 
			<option value="21">21</option> <option value="22">22</option> <option value="23">23</option> <option value="24">24</option> 
			<option value="25">25</option> <option value="26">26</option> <option value="27">27</option> <option value="28">28</option> 
			<option value="29">29</option> <option value="30">30</option> <option value="31">31</option>
        </select>
			
		<select class=form size="1" name="fmonth">
	        <option value="0">- {L_MONTH} -</option>
	        <option value="1">January</option>
	        <option value="2">February</option>
	        <option value="3">March</option>
	        <option value="4">April</option>
	        <option value="5">May</option>
	        <option value="6">June</option>
	        <option value="7">July</option>
	        <option value="8">August</option>
	        <option value="9">September</option>
	        <option value="10">October</option>
	        <option value="11">November</option>
	        <option value="12">December</option>
        </select>
			
		<input class=form type="text" name="fyear" value="{FYEAR}" size="6" onfocus="javascript: hide_field_title(window.document.FILTERFORM.fyear, '- {L_YEAR} -');">
		<input class=submit type="submit" name="submit" value="{L_SEARCH}">  
  </td>
 </tr>
 <tr>
   <td valign="top" height="30" class=tdtext>{U_ADD}</td>
 </tr>
</form>
</table> 

<div align="center"><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<form name="LISTFORM" method="POST" action="{S_LIST_ACTION}">
	  <tr>
	    <td class=tdmenu width="4%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td class=tdmenu>{L_TITLE}</td>
	    <td class=tdmenu width="15%" align="center">{L_DATE}</td>
	    <td class=tdmenu width="13%" align="center">{L_TIME}</td>
	    <td class=tdmenu width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: eventrow -->
	  <tr>
	    <td class=tdtext1 align="center"><input type="checkbox" name="event_ids[{eventrow:ID}]" value="{eventrow:ID}"></td>
	    <td class=tdtext2><a class="{eventrow:CSS}" href="javascript: open_window('{eventrow:U_VIEW}', 450, 400);">{eventrow:TITLE}</a></td>
	    <td class=tdtext1 align="center"><span class="{eventrow:CSS}">{eventrow:DATE}</span></td>
	    <td class=tdtext2 nowrap><span class="{eventrow:CSS}">{eventrow:TIME}</span></td>
	    <td class=tdtext1 align="center">{eventrow:U_EDIT}</td>
	  </tr>
	<!-- END: eventrow -->

	<!-- START NOIF: eventrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="5" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: eventrow -->
	</form>
	</table>        
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext align="right" valign="top">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
</div>


<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;

	select_list("{FSTATUS}",filter_form.fstatus);
	select_list("{FDAY}",filter_form.fday);
	select_list("{FMONTH}",filter_form.fmonth);

	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}
	
	function deleteForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			if ( confirm('{L_DEL_CONFIRM}') ){
				the_form.action = the_url;
				the_form.submit();
			}
		}
	}
</script>
