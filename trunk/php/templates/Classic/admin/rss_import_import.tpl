
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
	    <td class=tdmenu colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="40%" valign="top">{L_RSS_STREAMS}:</td>
	    <td class=tdtext2 width="60%">
			<select id="rss_ids[]" name="rss_ids[]" size="10" multiple style="width: 100%">
				<!-- START: rssrow -->
				<option value="{rssrow:ID}">{rssrow:TITLE} ({rssrow:ARTICLES})</option>
				<!-- END: rssrow -->
			</select>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_IMPORT_NUMBERS}:<br><span class="date">{L_IMPORT_NUMBER_DESC}</span></td>
	    <td class=tdtext2 valign="top"><input class=form type="text" name="import_number" value="{IMPORT_NUMBER}" size="15"></td>
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
	<!-- START: rss_value -->
	select_list("{rss_value:ID}", document.getElementById('rss_ids[]'));
	<!-- END: rss_value -->
</script>
