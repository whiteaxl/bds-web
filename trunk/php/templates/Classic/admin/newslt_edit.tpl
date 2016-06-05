
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
<form name="EDITFORM" method="POST" action="{S_ACTION}" onsubmit="javascript:getValues();" enctype="multipart/form-data">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT}:</td>
	    <td class=tdtext2>
			<select class=form name="cat_id" size=1>
				<option value="0"> - - - - - - {L_CHOOSE} - - - - - - </option>
				<!-- START: catrow -->
				<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1 width="25%">&nbsp;</td>
	    <td class=tdtext2 width="75%">
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class=tdtext1 width="40%"><strong>{L_NAME}</strong></td>
				<td width="15">&nbsp;</td>
				<td class=tdtext1><strong>{L_EMAIL} *</strong></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_EMAIL} 1:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="names[1]" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="emails[1]" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL} 2:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="names[2]" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="emails[2]" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL} 3:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="names[3]" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="emails[3]" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL} 4:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="names[4]" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="emails[4]" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_EMAIL} 5:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="names[5]" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="emails[5]" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
	  <!-- START IF: editrow -->
	  <tr>
	    <td class=tdtext1>{L_EMAIL}:</td>
	    <td class=tdtext2>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="40%"><input class=form type="text" name="name" value="{NAME}" size="27"></td>
				<td width="15">&nbsp;</td>
				<td><input class=form type="text" name="email" value="{EMAIL}" size="40"></td>
			</tr>
			</table>
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="{SYS_ENABLED}" checked>{L_ENABLED} &nbsp; &nbsp;<input type="radio" name="enabled" value="{SYS_DISABLED}">{L_DISABLED}</td>
	  </tr>
	  <!-- END IF: editrow -->
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

	select_list("{CAT_ID}",the_form.cat_id);
	radio_list("{ENABLED}",the_form.enabled);
	<!-- START IF: addrow -->
	radio_list("{PAGE_TO}",the_form.page_to);
	<!-- END IF: addrow -->
</script>
