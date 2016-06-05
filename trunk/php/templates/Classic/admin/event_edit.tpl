
<!-- wysiwyg editor -->
<script language="javascript" type="text/javascript">
	var editor_path				= "wysiwyg/";
	var editor_url_image_upload	= "{S_IMAGE_UPLOAD}";
</script>
<script src="wysiwyg/setting.js" type="text/javascript"></script>
<script src="wysiwyg/wysiwyg.js" type="text/javascript"></script>
<!-- wysiwyg editor -->

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

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
<input type="hidden" id="used_files" name="used_files" value="{USED_FILES}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_DATE}:</td>
	    <td class=tdtext2 width="78%">
		    <table border="0" cellpadding="0" cellspacing="1" width="100%">
		      <tr>
		        <td width="90%">
					<select class=form size="1" name="day">
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
					&nbsp;
					<select class=form size="1" name="month">
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
					&nbsp;
					<input class=form text="text" name="year" value="{YEAR}" size="4" maxlength="4">
		        </td>
		      </tr>
		    </table>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_TIME}:</td>
	    <td class=tdtext2 width="78%">
			<select class=form size="1" name="from_hour">
		        <option value="-1">- {L_HOUR} -</option> <option value="0">0</option> 
				<option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> 
				<option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> 
				<option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> 
				<option value="13">13</option> <option value="14">14</option> <option value="15">15</option> <option value="16">16</option> 
				<option value="17">17</option> <option value="18">18</option> <option value="19">19</option> <option value="20">20</option> 
				<option value="21">21</option> <option value="22">22</option> <option value="23">23</option>
	        </select>

			<select class=form size="1" name="from_minute">
		        <option value="-1">- {L_MINUTE} -</option> <option value="0">0</option> 
				<option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> 
				<option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> 
				<option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> 
				<option value="13">13</option> <option value="14">14</option> <option value="15">15</option> <option value="16">16</option> 
				<option value="17">17</option> <option value="18">18</option> <option value="19">19</option> <option value="20">20</option> 
				<option value="21">21</option> <option value="22">22</option> <option value="23">23</option> <option value="24">24</option> 
				<option value="25">25</option> <option value="26">26</option> <option value="27">27</option> <option value="28">28</option> 
				<option value="29">29</option> <option value="30">30</option> <option value="31">31</option> <option value="32">32</option> 
				<option value="33">33</option> <option value="34">34</option> <option value="35">35</option> <option value="36">36</option> 
				<option value="37">37</option> <option value="38">38</option> <option value="39">39</option> <option value="40">40</option> 
				<option value="41">41</option> <option value="42">42</option> <option value="43">43</option> <option value="44">44</option> 
				<option value="45">45</option> <option value="46">46</option> <option value="47">47</option> <option value="48">48</option> 
				<option value="49">49</option> <option value="50">50</option> <option value="51">51</option> <option value="52">52</option> 
				<option value="53">53</option> <option value="54">54</option> <option value="55">55</option> <option value="56">56</option> 
				<option value="57">57</option> <option value="58">58</option> <option value="59">59</option>
		        </select>
				
			&nbsp; {L_TO} &nbsp;
			<select class=form size="1" name="to_hour">
		        <option value="-1">- {L_HOUR} -</option> <option value="0">0</option> 
				<option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> 
				<option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> 
				<option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> 
				<option value="13">13</option> <option value="14">14</option> <option value="15">15</option> <option value="16">16</option> 
				<option value="17">17</option> <option value="18">18</option> <option value="19">19</option> <option value="20">20</option> 
				<option value="21">21</option> <option value="22">22</option> <option value="23">23</option>
	        </select>

			<select class=form size="1" name="to_minute">
		        <option value="-1">- {L_MINUTE} -</option> <option value="0">0</option> 
				<option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option> 
				<option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option> 
				<option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option> 
				<option value="13">13</option> <option value="14">14</option> <option value="15">15</option> <option value="16">16</option> 
				<option value="17">17</option> <option value="18">18</option> <option value="19">19</option> <option value="20">20</option> 
				<option value="21">21</option> <option value="22">22</option> <option value="23">23</option> <option value="24">24</option> 
				<option value="25">25</option> <option value="26">26</option> <option value="27">27</option> <option value="28">28</option> 
				<option value="29">29</option> <option value="30">30</option> <option value="31">31</option> <option value="32">32</option> 
				<option value="33">33</option> <option value="34">34</option> <option value="35">35</option> <option value="36">36</option> 
				<option value="37">37</option> <option value="38">38</option> <option value="39">39</option> <option value="40">40</option> 
				<option value="41">41</option> <option value="42">42</option> <option value="43">43</option> <option value="44">44</option> 
				<option value="45">45</option> <option value="46">46</option> <option value="47">47</option> <option value="48">48</option> 
				<option value="49">49</option> <option value="50">50</option> <option value="51">51</option> <option value="52">52</option> 
				<option value="53">53</option> <option value="54">54</option> <option value="55">55</option> <option value="56">56</option> 
				<option value="57">57</option> <option value="58">58</option> <option value="59">59</option>
		    </select>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_TITLE}:</td>
	    <td class=tdtext2 width="78%"><input class=form type="text" name="title" value="{TITLE}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2">
			<br>{L_DETAIL}:<br>
			<textarea class=form rows="15" id="detail" name="detail" cols="70">{DETAIL}</textarea>
			<script language="javascript" type="text/javascript">editor_create(document.EDITFORM, 'detail', 'full', '100%');</script>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_STATUS}</td>
	    <td class=tdtext2 width="78%"><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED}</td>
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

	select_list("{MONTH}", the_form.month);
	select_list("{DAY}", the_form.day);
	select_list("{FROM_HOUR}", the_form.from_hour);
	select_list("{FROM_MINUTE}", the_form.from_minute);
	select_list("{TO_HOUR}", the_form.to_hour);
	select_list("{TO_MINUTE}", the_form.to_minute);

	radio_list("{ENABLED}", the_form.enabled);

	<!-- START IF: addrow -->
	radio_list("{PAGE_TO}",the_form.page_to);
	<!-- END IF: addrow -->

	function add_usedFile(the_file){
		var the_form = window.document.EDITFORM;

		if (the_form.used_files.value != ""){
			the_form.used_files.value += ","+ the_file;
		}
		else{
			the_form.used_files.value += the_file;
		}
	}
</script>
