
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
<form name="EDITFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td width="35%" class=tdtext1 valign="top">{L_GROUP} *</td>
	    <td width="65%" class=tdtext2>
			<select class=form name="group_id" size="4" multiple>
				<!-- START: grouprow -->
				<option value="{grouprow:ID}">{grouprow:NAME}</option>
				<!-- END: grouprow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_USERNAME} *</td>
	    <td class=tdtext2><input class=form type="text" name="username" value="{USERNAME}" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PASS}</td>
	    <td class=tdtext2><input class=form type="password" name="pass" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_VERIFY_PASS}</td>
	    <td class=tdtext2><input class=form type="password" name="verifypass" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL} *</td>
	    <td class=tdtext2><input class=form type="text" name="email" value="{EMAIL}" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LANGUAGE}</td>
	    <td class=tdtext2 valign="top">
			<select class=form name="language">
				<option value="">- - [ {L_DEFAULT} ] - -</option>
				<!-- START: langrow -->
		 		<option value="{langrow:NAME}">{langrow:NAME}</option>
				<!-- END: langrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TEMPLATE}</td>
	    <td class=tdtext2 valign="top">
			<select class=form name="template">
				<option value="">- - [ {L_DEFAULT} ] - -</option>
				<!-- START: templaterow -->
				<option value="{templaterow:NAME}">{templaterow:NAME}</option>
				<!-- END: templaterow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TIMEZONE}</td>
	    <td class=tdtext2>
			<select name="timezone">
				<option value="20" selected>- - [ {L_DEFAULT} ] - -</option>
				<option value="-12">GMT - 12 hours</option>
				<option value="-11">GMT - 11 hours</option>
				<option value="-10">GMT - 10 hours</option>
				<option value="-9">GMT - 9 hours</option>
				<option value="-8">GMT - 8 hours</option>
				<option value="-7">GMT - 7 hours</option>
				<option value="-6">GMT - 6 hours</option>
				<option value="-5">GMT - 5 hours</option>
				<option value="-4">GMT - 4 hours</option>
				<option value="-3.5">GMT - 3.5 hours</option>
				<option value="-3">GMT - 3 hours</option>
				<option value="-2">GMT - 2 hours</option>
				<option value="-1">GMT - 1 hour</option>
				<option value="0">GMT</option>
				<option value="1">GMT + 1 hour</option>
				<option value="2">GMT + 2 hours</option>
				<option value="3">GMT + 3 hours</option>
				<option value="3.5">GMT + 3.5 hours</option>
				<option value="4">GMT + 4 hours</option>
				<option value="4.5">GMT + 4.5 hours</option>
				<option value="5">GMT + 5 hours</option>
				<option value="5.5">GMT + 5.5 hours</option>
				<option value="6">GMT + 6 hours</option>
				<option value="6.5">GMT + 6.5 hours</option>
				<option value="7">GMT + 7 hours</option>
				<option value="8">GMT + 8 hours</option>
				<option value="9">GMT + 9 hours</option>
				<option value="9.5">GMT + 9.5 hours</option>
				<option value="10">GMT + 10 hours</option>
				<option value="11">GMT + 11 hours</option>
				<option value="12">GMT + 12 hours</option>
				</select>
			<a href="javascript: open_window('{U_HELP_TIMEZONE}',420,460);">{L_HELP}</a>
	    </td>
	  </tr>
	<!-- START: fieldrow -->
	  <tr>
	    <td class=tdtext1>{fieldrow:TITLE} {fieldrow:REQUIRED}</td>
	    <td class=tdtext2>{fieldrow:INPUT}</td>
	  </tr>
	<!-- END: fieldrow -->
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="enabled" value="1" checked>{L_ENABLED} &nbsp; <input class=form2 type="radio" name="enabled" value="0">{L_DISABLED}</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <!-- START IF: editrow -->
	  <tr>
	    <td class=tdtext1>{L_SAVE_AS}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="save_as" value="save" checked>{L_SAVE} &nbsp; <input class=form2 type="radio" name="save_as" value="copy">{L_COPY}</td>
	  </tr>
	  <!-- END IF: editrow -->
	<tr><td class="tdtext1">&nbsp;</td><td class="tdtext1"><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td></tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form = window.document.EDITFORM;
	
	the_form.group_id.name = "group_id[]";
	
	<!-- START: groupvalrow -->
	select_list("{groupvalrow:ID}",the_form.group_id);
	<!-- END: groupvalrow -->

	select_list("{LANGUAGE}",the_form.language);
	select_list("{TEMPLATE}",the_form.template);
	select_list("{TIMEZONE}",the_form.timezone);
	radio_list("{ENABLED}",the_form.enabled);
	
	<!-- START IF: addrow -->
	radio_list("{PAGETO}",the_form.page_to);
	<!-- END IF: addrow -->
	
	<!-- START IF: editrow -->
	radio_list("{SAVEAS}",the_form.save_as);
	<!-- END IF: editrow -->

</script>