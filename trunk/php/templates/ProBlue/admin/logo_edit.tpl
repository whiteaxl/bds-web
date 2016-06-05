
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

<br><div align="center">{ERROR_MSG}<br><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%" valign="top">{L_LOGO}:</td>
	    <td class=tdtext2 width="80%">{LOGO_IMG}
			<input type="radio" name="logo_type" value="local"> {L_LOCAL} <input class=form type="file" name="logo_local" size="40" onclick="javascript:radio_list('local',window.document.EDITFORM.logo_type);"><br>
			<input type="radio" name="logo_type" value="remote"> {L_REMOTE} <input class=form type="text" name="logo_remote" value="{LOGO_REMOTE}" size="50" onclick="javascript:radio_list('remote',window.document.EDITFORM.logo_type);"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%" valign="top">{L_LOGO_SIZE}:</td>
	    <td class=tdtext2 width="80%">{L_WIDTH} <input class=form type="text" name="logo_width" value="{LOGO_WIDTH}" size="3"> x {L_HEIGHT} <input class=form type="text" name="logo_height" value="{LOGO_HEIGHT}" size="3"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%">{L_SITE_NAME}:</td>
	    <td class=tdtext2 width="80%"><input class=form type="text" name="site_name" value="{SITE_NAME}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="20%">{L_SITE_URL}:</td>
	    <td class=tdtext2 width="80%"><input class=form type="text" name="site_url" value="{SITE_URL}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="25%" valign="top">{L_START_DATE}:</td>
	    <td class=tdtext2 width="75%">
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
		      <tr>
		        <td class=tdtext width="15%">{L_DATE}:</td>
		        <td width="85%">
					<select class=form size="1" name="start_month">
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
			
					<select class=form size="1" name="start_day">
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
						
					<select class=form size="1" name="start_year">
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
				</td>
		      </tr>
		      <tr>
		        <td class=tdtext width="15%">{L_TIME}:</td>
		        <td width="85%"><input class=form type="text" name="start_time" value="{START_TIME}" size="4"> <span class="textbody">{L_TIME_EXPLAIN}</span></td>
		      </tr>
		    </table>    
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="25%" valign="top">{L_END_DATE}:</td>
	    <td class=tdtext2 width="75%"><input type="radio" name="end_date" value="never" checked>{L_NEVER}<br>
			<input type="radio" name="end_date" value="past_future">{L_PAST_FUTURE}:
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
		      <tr>
		        <td class=tdtext width="15%">{L_DATE}:</td>
		        <td width="85%">
					<select class=form size="1" name="end_month">
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
						
					<select class=form size="1" name="end_day">
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
						
					<select class=form size="1" name="end_year">
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
				</td>
		      </tr>
		      <tr>
		        <td class=tdtext width="15%">{L_TIME}:</td>
		        <td width="85%"><input class=form type="text" name="end_time" value="{END_TIME}" size="4"> <span class="textbody">{L_TIME_EXPLAIN}</span></td>
		      </tr>
		    </table>    
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LOGO_POS}:</td>
	    <td class=tdtext2>
				<select class=form name="logo_pos" style="width: 200px">
					<option value="{LOGO_POS_LEFT}">{L_LOGO_POS_LEFT}</option>
					<option value="{LOGO_POS_RIGHT}">{L_LOGO_POS_RIGHT}</option>
				</select>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="25%" valign="top">{L_LOGO_PAGES}:</td>
	    <td class=tdtext2 width="75%">
			<select class=form name="logo_pages" size="10" multiple style="width: 200px">
				<option value="-1">--------- {L_ALL} ---------</option>	
				<!-- START: webpagerow -->
				<option value="{webpagerow:CODE}">{webpagerow:NAME}</option>
				<!-- END: webpagerow -->

				<option value="">::::: {L_ARTICLE_PAGES} :::::</option>	
				<!-- START: catrow -->
				<option value="C_{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}:</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="1" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="0">{L_DISABLED}</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.EDITFORM;

	select_list("{START_MONTH}", the_form.start_month);
	select_list("{START_DAY}", the_form.start_day);
	select_list("{START_YEAR}", the_form.start_year);

	radio_list("{END_DATE}", the_form.end_date);
	select_list("{END_MONTH}", the_form.end_month);
	select_list("{END_DAY}", the_form.end_day);
	select_list("{END_YEAR}", the_form.end_year);

	select_list("{LOGO_POS}", the_form.logo_pos);
	the_form.logo_pages.name	= "logo_pages[]";
	<!-- START: logopagerow -->
	select_list("{logopagerow:CODE}", the_form.logo_pages);
	<!-- END: logopagerow -->

	radio_list("{ENABLED}", the_form.enabled);
	<!-- START IF: addrow -->
	radio_list("{PAGETO}",the_form.page_to);
	<!-- END IF: addrow -->
</script>
