
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

<div align="center"><br><span class="error">{ERROR_MSG}&nbsp;</span><br><br>

<DIV class=tooltip id="tip_cat" style="width: 200px" align="left">{L_CAT_TIP}</DIV>
<DIV class=tooltip id="tip_posttime" style="width: 200px" align="left">{L_POST_TIME_TIP}</DIV>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" onsubmit="javascript:getValues();" enctype="multipart/form-data">
<input type="hidden" id="used_files" name="used_files" value="{USED_FILES}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_PIC_THUMB}:</td>
	    <td class=tdtext2>
			<!-- START IF: picthumb -->
			{PIC_THUMB} <input type="checkbox" name="remove_thumb" value="1" {REMOVE_THUMB_CHECK}> {L_REMOVE}<br>
			<!-- END IF: picthumb -->
			<input class=form type="file" name="pic_thumb" size="47"> {L_THUMB_SIZE}
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_PIC_FULL}:</td>
	    <td class=tdtext2>
			<!-- START IF: picfull -->
			{PIC_FULL} <input type="checkbox" name="remove_full" value="1" {REMOVE_FULL_CHECK}> {L_REMOVE}<br>
			<!-- END IF: picfull -->
			<input class=form type="file" name="pic_full" size="47"> {L_FULL_SIZE}
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CONTENT}:</td>
		<td class=tdtext2><textarea class=form name="content" cols="60" rows="8">{CONTENT}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CAT}:</td>
	    <td class=tdtext2>
			<select class=form name="cat_id" size="15" multiple>
				<option value="-1">{L_HOME_PAGE}</option>
				<!-- START: catrow -->
				<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
			<img onMouseMove="javascript: show_tip('tip_cat', event);" onMouseOut="javascript: hide_tip('tip_cat');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0" align=absMiddle>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_POST_TIME}:</td>
	    <td class=tdtext2>
		    <table border="0" cellpadding="0" cellspacing="1" width="100%">
		      <tr>
		        <td width="10%"><span class="textbody">{L_DATE}:</span></td>
		        <td width="90%">
					<select class=form size="1" name="month">
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
						
					<select class=form size="1" name="day">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
					</select>
						
					<select class=form size="1" name="year">
						<option value="2000">2000</option>
						<option value="2001">2001</option>
						<option value="2002">2002</option>
						<option value="2003">2003</option>
						<option value="2004">2004</option>
						<option value="2005">2005</option>
						<option value="2006">2006</option>
						<option value="2007">2007</option>
						<option value="2008">2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011">2011</option>
						<option value="2012">2012</option>
						<option value="2013">2013</option>
						<option value="2014">2014</option>
						<option value="2015">2015</option>
						<option value="2016">2016</option>
						<option value="2017">2017</option>
						<option value="2018">2018</option>
						<option value="2019">2019</option>
						<option value="2020">2020</option>
					</select>
					<img onMouseMove="javascript: show_tip('tip_posttime', event);" onMouseOut="javascript: hide_tip('tip_posttime');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0" align=absMiddle>
				</td>
		      </tr>
		      <tr>
		        <td width="10%"><span class="textbody">{L_TIME}:</span></td>
		        <td width="90%"><input class=form type="text" name="time" value="{TIME}" size="4"> <span class="date">{L_TIME_EXPLAIN}</span></td>
		      </tr>
		    </table>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED}</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>
<br>

<script language="javascript" type="text/javascript">
	var the_form = window.document.EDITFORM;

	the_form.cat_id.name	= "cat_id[]";
	<!-- START: catvalrow -->
	select_list("{catvalrow:ID}", the_form.cat_id);
	<!-- END: catvalrow -->

	select_list("{MONTH}",the_form.month);
	select_list("{DAY}",the_form.day);
	select_list("{YEAR}",the_form.year);

	radio_list("{ENABLED}",the_form.enabled);

	<!-- START IF: addrow -->
	radio_list("{PAGE_TO}",the_form.page_to);
	<!-- END IF: addrow -->
</script>
