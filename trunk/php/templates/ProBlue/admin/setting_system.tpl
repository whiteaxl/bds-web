
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
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu2" href="{U_TAB_WEBSITE}">{L_TAB_WEBSITE}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_07.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btnhover_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_SYSTEM}"><strong>{L_TAB_SYSTEM}</strong></a> &nbsp;</td>
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
	    <td class=tdtext1>{L_ITEMS_PER_PAGE}<br><span class="date">{L_ITEMS_PER_PAGE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="items_per_page" value="{ITEMS_PER_PAGE}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_IMAGE_TYPE}<br><span class="date">{L_IMAGE_TYPE_DESC}</span></td>
	    <td class=tdtext2 width="60%" valign="top"><input class=form type="text" name="image_type" value="{IMAGE_TYPE}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_IMAGE_MAX_SIZE}<br><span class="date">{L_IMAGE_MAX_SIZE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="image_max_size" value="{IMAGE_MAX_SIZE}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_TIME_LOGIN}<br><span class="date">{L_TIME_LOGIN_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="time_login" value="{TIME_LOGIN}" size="60"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_COOKIE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_COOKIE_DOMAIN}<br><span class="date">{L_COOKIE_DOMAIN_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cookie_domain" value="{COOKIE_DOMAIN}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_COOKIE_PATH}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cookie_path" value="{COOKIE_PATH}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_COOKIE_TIME} <span class="date">{L_COOKIE_TIME_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cookie_time" value="{COOKIE_TIME}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_SMTP}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SMTP_HOST}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="smtp_host" value="{SMTP_HOST}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SMTP_USERNAME}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="smtp_username" value="{SMTP_USERNAME}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SMTP_PASSWORD} <span class="date">{L_HIDDEN_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="smtp_password" value="{SMTP_PASSWORD}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_FTP}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_FTP_HOST}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="ftp_host" value="{FTP_HOST}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_FTP_PORT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="ftp_port" value="{FTP_PORT}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_FTP_USERNAME}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="ftp_username" value="{FTP_USERNAME}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_FTP_PASSWORD} <span class="date">{L_HIDDEN_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="ftp_password" value="{FTP_PASSWORD}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_LOG}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LOG}</td>
	    <td class=tdtext2 valign="top"><input type="radio" name="log_save" value="1" checked>{L_TURN_ON} &nbsp; <input type="radio" name="log_save" value="0">{L_TURN_OFF}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LOG_DAYS}<br><span class="date">{L_LOG_DAYS_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="log_days" value="{LOG_DAYS}" size="15"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_CACHE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CACHE}<br><span class="date">{L_CACHE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input type="radio" name="cache_enabled" value="1" checked>{L_TURN_ON} &nbsp; <input type="radio" name="cache_enabled" value="0">{L_TURN_OFF}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CACHE_EXPIRE} <span class="date">{L_CACHE_EXPIRE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cache_expire" value="{CACHE_EXPIRE}" size="15"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub colspan="2"><span style="width: 40%"></span>{L_SHORT_URL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_SHORT_URL}<br><span class="date">{L_SHORT_URL_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input type="radio" name="short_url_enabled" value="1" checked>{L_TURN_ON} &nbsp; <input type="radio" name="short_url_enabled" value="0">{L_TURN_OFF}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_URL_SEP}<br><span class="date">{L_URL_SEP_DESC}</span></td>
	    <td class=tdtext2 valign="top">
			<select class="form" name="short_url_sep">
				<option value="/">/</option>
				<option value=".">.</option>
				<option value="_">_</option>
				<option value="-">-</option>
				<option value="^">^</option>
				<option value="~">~</option>
			</select>
		</td>
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

	radio_list("{LOG_SAVE}", the_form.log_save);
	radio_list("{CACHE_ENABLED}", the_form.cache_enabled);
	radio_list("{SHORT_URL_ENABLED}", the_form.short_url_enabled);
	select_list("{URL_SEP}", the_form.short_url_sep);
</script>