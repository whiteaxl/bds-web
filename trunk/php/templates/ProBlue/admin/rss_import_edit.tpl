
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
<DIV class=tooltip id="tip_article_type" style="width: 200px" align="left">{L_ARTICLE_TYPE_TIP}</DIV>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu colspan="2">{L_RSS_IMPORT_BASICS}:</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%">{L_RSS_TITLE}:</td>
	    <td class=tdtext2 width="60%"><input class=form type="text" name="rss_title" value="{RSS_TITLE}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_URL}:<br><span class="date">{L_RSS_URL_DESC}</span></td>
	    <td class=tdtext2><input class=form type="text" name="rss_url" value="{RSS_URL}" size="50"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_IMPORT_PERGO}:<br><span class="date">{L_RSS_IMPORT_PERGO_DESC}</span></td>
	    <td class=tdtext2><input class=form type="text" name="rss_import_pergo" value="{RSS_IMPORT_PERGO}" size="10"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_CONVERT_CHARSET}:<br><span class="date">{L_RSS_CONVERT_CHARSET_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="rss_convert_charset" value="1" checked>{L_YES} &nbsp; &nbsp;<input type="radio" name="rss_convert_charset" value="0">{L_NO}</td>
	  </tr>

	  <tr>
	    <td class=tdmenu colspan="2">{L_RSS_IMPORT_AUTH}:</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_REQUIRE_AUTH}:<br><span class="date">{L_RSS_REQUIRE_AUTH_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="rss_auth" value="1">{L_YES} &nbsp; &nbsp;<input type="radio" name="rss_auth" value="0" checked>{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_AUTH_USER}:</td>
	    <td class=tdtext2><input class=form type="text" name="rss_auth_user" value="{RSS_AUTH_USER}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_RSS_AUTH_PASS}:</td>
	    <td class=tdtext2><input class=form type="text" name="rss_auth_pass" value="{RSS_AUTH_PASS}" size="30"></td>
	  </tr>

	  <tr>
	    <td class=tdmenu colspan="2">{L_RSS_IMPORT_CONTENTS}:</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_CAT}:<br><span class="date">{L_ARTICLE_CAT_DESC}</span></td>
	    <td class=tdtext2>
			<select class=form name="article_cat_id" size=1>
				<option value="0"> - - - - - - {L_CHOOSE} - - - - - - </option>
				<!-- START: catrow -->
				<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_TYPE}:</td>
	    <td class=tdtext2>
			<input type="radio" name="article_type" value="{SYS_ARTICLE_FULL}" checked>{L_ARTICLE_TYPE_FULL} &nbsp; &nbsp;
			<input type="radio" name="article_type" value="{SYS_ARTICLE_SUMMARY}">{L_ARTICLE_TYPE_SUMMARY} &nbsp; &nbsp;
			<input type="radio" name="article_type" value="{SYS_ARTICLE_LINK}">{L_ARTICLE_TYPE_LINK}
			&nbsp; <img onMouseMove="javascript: show_tip('tip_article_type', event);" onMouseOut="javascript: hide_tip('tip_article_type');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_REMOVE_HTML}:<br><span class="date">{L_ARTICLE_REMOVE_HTML_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="article_remove_html" value="1" checked>{L_YES} &nbsp; &nbsp;<input type="radio" name="article_remove_html" value="0">{L_NO}</td>
	  </tr>
<!--
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_SHOW_LINK}:<br><span class="date">{L_ARTICLE_SHOW_LINK_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="article_show_link" value="1">{L_YES} &nbsp; &nbsp;<input type="radio" name="article_show_link" value="0" checked>{L_NO}</td>
	  </tr>
-->
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_AUTHOR}:<br><span class="date">{L_ARTICLE_AUTHOR_DESC}</span></td>
	    <td class=tdtext2><input class=form type="text" name="article_author" value="{ARTICLE_AUTHOR}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_USERNAME}:<br><span class="date">{L_ARTICLE_USERNAME_DESC}</span></td>
	    <td class=tdtext2><input class=form type="text" name="article_username" value="{ARTICLE_USERNAME}" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_USERPOST_INCREASE}:<br><span class="date">{L_ARTICLE_USERPOST_INCREASE_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="article_userpost_increase" value="1" checked>{L_YES} &nbsp; &nbsp;<input type="radio" name="article_userpost_increase" value="0">{L_NO}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_ARTICLE_STATUS}:<br><span class="date">{L_ARTICLE_STATUS_DESC}</span></td>
	    <td class=tdtext2><input type="radio" name="article_enabled" value="1" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="article_enabled" value="0">{L_DISABLED}</td>
	  </tr>

	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext2 colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <tr>
	    <td class=tdtext2 colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 colspan="2" align="center"><input class=submit type="submit" name="submit" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.EDITFORM;

	radio_list("{RSS_CONVERT_CHARSET}", the_form.rss_convert_charset);
	radio_list("{RSS_AUTH}", the_form.rss_auth);
	select_list("{ARTICLE_CAT_ID}",the_form.article_cat_id);
	radio_list("{ARTICLE_TYPE}",the_form.article_type);
	radio_list("{ARTICLE_REMOVE_HTML}", the_form.article_remove_html);
//	radio_list("{ARTICLE_SHOW_LINK}", the_form.article_show_link);
	radio_list("{ARTICLE_USERPOST_INCREASE}", the_form.article_userpost_increase);
	radio_list("{ARTICLE_ENABLED}", the_form.article_enabled);

	<!-- START IF: addrow -->
	radio_list("{PAGETO}",the_form.page_to);
	<!-- END IF: addrow -->
</script>
