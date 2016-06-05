
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

<table border="0" cellpadding="1" cellspacing="0" width="100%">
<form name="FILTERFORM" method="POST" action="{S_FILTER_ACTION}">
 <tr style="height: 25px;">
  <td class="tdtext">{U_ADD}</td>
  <td class=tdtext align="right">
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="25">
		<input class=submit type="submit" name="submit" value="{L_SEARCH}">  
  </td>
 </tr>
</form>
</table>

<div align="center"><br><br> 
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom">{U_DELETE} {U_RESYNC} {U_MOVE}&nbsp;</td>
    <td class=tdtext align="right" valign="bottom">{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu width="5%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td class=tdmenu>{L_TITLE}</td>
	    <td width="15%" class=tdmenu align="center">{L_ARTICLES}</td>
	    <td width="10%" class=tdmenu align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: topicrow -->
	  <tr>
	    <td class=tdtext1 align="center"><input type="checkbox" name="topic_ids[{topicrow:ID}]" value="{topicrow:ID}"></td>
	    <td class=tdtext2>{topicrow:TITLE}</td>
	    <td class=tdtext1 align="center">{topicrow:ARTICLE_COUNTER}</td>
	    <td class=tdtext2 align="center">{topicrow:U_EDIT}</td>
	  </tr>
	<!-- END: topicrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top">{U_DELETE} {U_RESYNC} {U_MOVE}&nbsp;</td>
    <td class=tdtext align="right" valign="top">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form = window.document.LISTFORM;

	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}
	
	function deleteForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			if ( confirm('{L_DEL_CONFIRM}') ){
				the_form.action = the_url;
				the_form.submit();
			}
		}
	}
</script>

