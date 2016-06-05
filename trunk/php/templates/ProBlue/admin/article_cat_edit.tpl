
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

<DIV class=tooltip id="tip_catcode" style="width: 250px" align="left">{L_CAT_CODE_TIP}</DIV>
<DIV class=tooltip id="tip_searchengine" style="width: 250px" align="left">{L_SEARCH_ENGINE_TIP}</DIV>
<DIV class=tooltip id="tip_cattemplate" style="width: 250px" align="left">{L_CAT_TEMPLATE_TIP}</DIV>
<DIV class=tooltip id="tip_indexdisplay" style="width: 250px" align="left">{L_INDEX_DISPLAY_TIP}</DIV>

<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="EDITFORM" method="POST" action="{S_ACTION}" enctype="multipart/form-data">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="100%" colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	    <td width="30%" class=tdtext1>{L_PARENT_CAT}</td>
	    <td class=tdtext2>
			<select class=form name="parent_id">
				<option value="0">{L_ROOT}</option>
				<!-- START: catrow -->
				<option value="{catrow:ID}">{catrow:PREFIX}{catrow:NAME}</option>
				<!-- END: catrow -->
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT_CODE}</td>
	    <td class=tdtext2><input class=form type="text" name="cat_code" value="{CAT_CODE}" size="25" maxlength="32"> &nbsp; <img onMouseMove="javascript: show_tip('tip_catcode', event);" onMouseOut="javascript: hide_tip('tip_catcode');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT_NAME}</td>
	    <td class=tdtext2><input class=form type="text" name="cat_name" value="{CAT_NAME}" size="50" maxlength="64"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CAT_KEYWORDS}</td>
	    <td class=tdtext2><input type="text" class=form name="cat_keywords" size="70" value="{CAT_KEYWORDS}" maxlength="255"> &nbsp; <img onMouseMove="javascript: show_tip('tip_searchengine', event);" onMouseOut="javascript: hide_tip('tip_searchengine');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_CAT_DESC}</td>
	    <td class=tdtext2><input type="text" class=form name="cat_desc" size="70" value="{CAT_DESC}" maxlength="255"> &nbsp; <img onMouseMove="javascript: show_tip('tip_searchengine', event);" onMouseOut="javascript: hide_tip('tip_searchengine');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1 valign="top">{L_REDIRECT_URL}</td>
	    <td class=tdtext2><input type="text" class=form name="redirect_url" size="70" value="{REDIRECT_URL}"> <span class="date">({L_OPTIONAL})</span></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_CAT_TEMPLATE}</td>
	    <td class=tdtext2>
			<select class="form" name="cat_template">
				<!-- START: templaterow -->
				<option value="{templaterow:DIR}">{templaterow:PATH}</option>
				<!-- END: templaterow -->
			</select>
			 &nbsp; <img onMouseMove="javascript: show_tip('tip_cattemplate', event);" onMouseOut="javascript: hide_tip('tip_cattemplate');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0">
		</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_INDEX_DISPLAY}</td>
	    <td class=tdtext2><input type="radio" name="index_display" value="1" checked>{L_YES} &nbsp; <input type="radio" name="index_display" value="0">{L_NO} &nbsp; &nbsp; <img onMouseMove="javascript: show_tip('tip_indexdisplay', event);" onMouseOut="javascript: hide_tip('tip_indexdisplay');" src="{TEMPLATE_PATH}/images/admin/icon_help.gif" border="0" align=absbottom></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_STATUS}</td>
	    <td class=tdtext2><input type="radio" name="enabled" value="1" checked>{L_ENABLED} &nbsp; <input type="radio" name="enabled" value="0">{L_DISABLED}</td>
	  </tr>
	  <!-- START IF: addrow -->
	  <tr>
	    <td class=tdtext1>{L_PAGE_TO}</td>
	    <td class=tdtext2><input class=form2 type="radio" name="page_to" value="pagelist" checked>{L_PAGE_LIST} &nbsp; <input class=form2 type="radio" name="page_to" value="pageadd">{L_PAGE_ADD}</td>
	  </tr>
	  <!-- END IF: addrow -->
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

	select_list("{PARENT_ID}",the_form.parent_id);
	select_list("{CAT_TEMPLATE}",the_form.cat_template);
	radio_list("{INDEX_DISPLAY}",the_form.index_display);
	radio_list("{ENABLED}",the_form.enabled);
	<!-- START IF: addrow -->
	radio_list("{PAGETO}",the_form.page_to);
	<!-- END IF: addrow -->

</script>
