
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

<table width="100%" style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
<tr><td>
	<table style="height: 20px;" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_05.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btnhover_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu2" href="{U_TAB_WEBSITE}"><strong>{L_TAB_WEBSITE}</strong></a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_07.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_SYSTEM}">{L_TAB_SYSTEM}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_MODULES}">{L_TAB_MODULES}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>

			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_OPENCLOSE}">{L_TAB_OPEN_CLOSE}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
		</tr>
	</table>
</td></tr>
<tr><td height="2"></td></tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenusub width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td width="40%" class=tdtext1>{L_SITE_NAME}</td>
	    <td width="60%" class=tdtext2 valign="top"><input class=form type="text" name="site_name" value="{SITE_NAME}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SITE_SLOGAN}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="site_slogan" value="{SITE_SLOGAN}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SITE_URL}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="site_url" value="{SITE_URL}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SITE_PATH}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="site_path" value="{SITE_PATH}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_SITE_KEYWORDS}</td>
	    <td class=tdtext2 valign="top"><textarea class=form name="site_keywords" cols="60" rows="4">{SITE_KEYWORDS}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_SITE_DESC}</td>
	    <td class=tdtext2 valign="top"><textarea class=form name="site_desc" cols="60" rows="4">{SITE_DESC}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ADMIN_EMAIL}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="admin_email" value="{ADMIN_EMAIL}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LANGUAGE}</td>
	    <td class=tdtext2 valign="top">
			<select class=form name="language">
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
				<!-- START: templaterow -->
				<option value="{templaterow:NAME}">{templaterow:NAME}</option>
				<!-- END: templaterow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TIMEZONE}</td>
	    <td class=tdtext2 valign="top">
			<select class=form name="timezone">
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
				<option value="0" selected>GMT</option>
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
			<a href="javascript:open_window('{U_HELP_TIMEZONE}',420,450);">{L_HELP}</a>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_DATE_FORMAT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="date_format" value="{DATE_FORMAT}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TIME_FORMAT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="time_format" value="{TIME_FORMAT}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_FULL_DATE_TIME_FORMAT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="full_date_time_format" value="{FULL_DATE_TIME_FORMAT}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2">&nbsp;</td>
	  </tr>
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
	the_form = window.document.EDITFORM;

	<!-- START IF: reload -->
	parent.menu.location	= parent.menu.location;
	window.location			= "{U_EDIT_OPTION}";
	<!-- END IF: reload -->

	select_list("{LANGUAGE}", the_form.language);
	select_list("{TEMPLATE}", the_form.template);
	select_list("{TIMEZONE}", the_form.timezone);
</script>