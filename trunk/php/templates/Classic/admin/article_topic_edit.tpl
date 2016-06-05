
<div align="center">
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
<br><span class="error">{ERROR_MSG}&nbsp;</span><br><br>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_TOPIC_TITLE}</td>
	    <td class=tdtext2><input class=form type="text" name="topic_title" value="{TOPIC_TITLE}" size="60" maxlength="128"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_TOPIC_DESC}</td>
	    <td class=tdtext2><textarea class=form name="topic_desc" cols="60" rows="3" maxlength="255">{TOPIC_DESC}</textarea></td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->

	  <!-- START IF: articlerow -->
	  <tr>
	    <td class=tdtext1 valign="top">{L_ARTICLES}</td>
	    <td class=tdtext2>
			<table width="100%" border="0" cellspacing="0" cellpadding="1">
			<tr><td class=tdmenusub><input type="checkbox" name="chkall" value="1" onclick="javascript: check_all(document.EDITFORM, this.checked)"></td><td class=tdmenusub>{L_ARTICLE_TITLE} {L_ARTICLE_REMOVE}</td></tr>
			<!-- START: articlerow -->
			<tr><td><input type="checkbox" name="article_ids[{articlerow:ID}]" value="{articlerow:ID}"></td><td><a href="javascript: open_window('{articlerow:U_VIEW}', 450, 400);">{articlerow:TITLE}</a></td></tr>
			<!-- END: articlerow -->			
			</table>
		
		</td>
	  </tr>
	  <!-- END IF: articlerow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="2" align="center"><input class=submit type="submit" name="updatesm" value="{L_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>
</div>

<script language="javascript" type="text/javascript">
	the_form = window.document.EDITFORM;

	the_form.topic_title.focus();
	radio_list("{PAGETO}",the_form.page_to);

</script>
