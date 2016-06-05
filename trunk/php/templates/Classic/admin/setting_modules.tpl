
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
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btn_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_SYSTEM}">{L_TAB_SYSTEM}</a> &nbsp;</td>
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_03.gif" width="4" height="20" alt=""></td>
			<td width="2"></td>
	
			<td width="4"><img src="{TEMPLATE_PATH}/images/admin/tabmenu_01.gif" width="4" height="20" alt=""></td>
			<td style="background-image: url({TEMPLATE_PATH}/images/admin/tabmenu_btnhover_bg.gif);" height="20" align="center">&nbsp; <a class="topmenu" href="{U_TAB_MODULES}"><strong>{L_TAB_MODULES}</strong></a> &nbsp;</td>
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
	    <td class=tdmenusub width="100%" colspan="2" align="center">{L_POLL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_POLL_OPTIONS}</td>
	    <td class=tdtext2 width="60%" valign="top"><input class=form type="text" name="poll_options" value="{POLL_OPTIONS}" size="10"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TIME_REVOTE}<br><span class="date">{L_TIME_REVOTE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="time_revote" value="{TIME_REVOTE}" size="10"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub width="100%" colspan="2" align="center">{L_ARTICLE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_USE_COMMENT}<br><span class="date">{L_USE_COMMENT_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input type="radio" name="use_comment" value="1" checked> {L_YES} &nbsp; <input type="radio" name="use_comment" value="0"> {L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_WYSIWYG_TITLE}<br><span class="date">{L_WYSIWYG_TITLE_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input type="radio" name="enable_article_wysiwyg_title" value="1"> {L_YES} &nbsp; <input type="radio" name="enable_article_wysiwyg_title" value="0" checked> {L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_MENUCAT_LEVEL}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="menucat_level" value="{MENUCAT_LEVEL}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT_COLS}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cat_cols" value="{CAT_COLS}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT_ARTICLE_INDEX}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="cat_article_index" value="{CAT_ARTICLE_INDEX}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_HOT_INDEX}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="hot_index" value="{HOT_INDEX}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_HOT_COLS}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="hot_cols" value="{HOT_COLS}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LIMIT_HOME_NEWS}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="limit_home_news" value="{LIMIT_HOME_NEWS}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LIMIT_HOME_NEWS_NEXT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="limit_home_news_next" value="{LIMIT_HOME_NEWS_NEXT}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LIMIT_HOME_HOT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="limit_home_hot" value="{LIMIT_HOME_HOT}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_LIMIT_HOME_COMMENT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="limit_home_comment" value="{LIMIT_HOME_COMMENT}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NEWSPIC_RAND_LIMIT}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="newspic_rand_limit" value="{NEWSPIC_RAND_LIMIT}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NEWSPIC_RAND_TIME}</td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="newspic_rand_time" value="{NEWSPIC_RAND_TIME}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdmenusub width="100%" colspan="2" align="center">{L_ARTICLE_IMAGE}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_THUMB_LARGE}</td>
	    <td class=tdtext2 valign="top">
			{L_MAX_WIDTH}: <input class=form type="text" name="thumb_large_max_width" value="{THUMB_LARGE_MAX_WIDTH}" size="5"> &nbsp;
			{L_MAX_HEIGHT}: <input class=form type="text" name="thumb_large_max_height" value="{THUMB_LARGE_MAX_HEIGHT}" size="5"> (pixels)
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_THUMB_SMALL}</td>
	    <td class=tdtext2 valign="top">
			{L_MAX_WIDTH}: <input class=form type="text" name="thumb_small_max_width" value="{THUMB_SMALL_MAX_WIDTH}" size="5"> &nbsp;
			{L_MAX_HEIGHT}: <input class=form type="text" name="thumb_small_max_height" value="{THUMB_SMALL_MAX_HEIGHT}" size="5"> (pixels)
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_THUMB_ICON}</td>
	    <td class=tdtext2 valign="top">
			{L_MAX_WIDTH}: <input class=form type="text" name="thumb_icon_max_width" value="{THUMB_ICON_MAX_WIDTH}" size="5"> &nbsp;
			{L_MAX_HEIGHT}: <input class=form type="text" name="thumb_icon_max_height" value="{THUMB_ICON_MAX_HEIGHT}" size="5"> (pixels)
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NEWSPIC_THUMB}</td>
	    <td class=tdtext2 valign="top">
			{L_MAX_WIDTH}: <input class=form type="text" name="newspic_thumb_max_width" value="{NEWSPIC_THUMB_MAX_WIDTH}" size="5"> &nbsp;
			{L_MAX_HEIGHT}: <input class=form type="text" name="newspic_thumb_max_height" value="{NEWSPIC_THUMB_MAX_HEIGHT}" size="5"> (pixels)
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NEWSPIC_FULL}</td>
	    <td class=tdtext2 valign="top">
			{L_MAX_WIDTH}: <input class=form type="text" name="newspic_full_max_width" value="{NEWSPIC_FULL_MAX_WIDTH}" size="5"> &nbsp;
			{L_MAX_HEIGHT}: <input class=form type="text" name="newspic_full_max_height" value="{NEWSPIC_FULL_MAX_HEIGHT}" size="5"> (pixels)
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

	radio_list("{USE_COMMENT}", the_form.use_comment);
	radio_list("{ENABLE_ARTICLE_WYSIWYG_TITLE}", the_form.enable_article_wysiwyg_title);
</script>