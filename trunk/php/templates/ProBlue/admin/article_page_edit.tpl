
<!-- wysiwyg editor -->
<script language="javascript" type="text/javascript">
	var editor_path				= "wysiwyg/";
	var editor_site_url			= "{SITE_URL}";
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
	    <td class=tdtext1 width="22%">{L_PAGE_ORDER}:</td>
	    <td class=tdtext2 width="78%"><input class=form type="text" name="page_order" value="{PAGE_ORDER}" size="5"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%" valign="top">{L_TITLE}:</td>
	    <td class=tdtext2 width="78%">
			<input class=form type="text" id="title" name="title" value="{TITLE}" size="60">
			<!-- START IF: wysiwyg_title -->
			<script language="javascript" type="text/javascript">editor_create(document.EDITFORM, 'title', 'simple', '550', '25');</script>
			<!-- END IF: wysiwyg_title -->
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext2 width="100%" colspan="2">
			<br>{L_CONTENT_DETAIL}:<br>
			<textarea class=form rows="18" id="content_detail" name="content_detail" cols="70">{CONTENT_DETAIL}</textarea>
			<script language="javascript" type="text/javascript">editor_create(document.EDITFORM, 'content_detail', 'full', '100%');</script>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_AUTHOR}</td>
	    <td class=tdtext2 width="78%"><input class=form type="text" name="author" value="{AUTHOR}" size="40"></td>
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

	radio_list("{ENABLED}",the_form.enabled);
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
