
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
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NAME}</td>
	    <td class=tdtext2><input class=form type="text" name="name" value="{NAME}" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL}</td>
	    <td class=tdtext2><input class=form type="text" name="email" value="{EMAIL}" size="40"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="22%">{L_TITLE}</td>
	    <td class=tdtext2 width="78%"><input class=form type="text" name="title" value="{TITLE}" size="70"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CONTENT}</td>
	    <td class=tdtext2><textarea class=form rows="10" name="content" cols="70">{CONTENT}</textarea></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED}</td>
	  </tr>
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
