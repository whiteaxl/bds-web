
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

<div align="center"><br>
<table class="tblborder" cellpadding="0" cellspacing="1" width="80%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
		<tr><td class="tdmenu" align="center">{L_ARTICLE}</td></tr>
		<tr><td class="tdtext1" align="center"><span class="title">{ARTICLE_TITLE}</span></td></tr>
	</table>        
    </td>
  </tr>
</table>
<br></div>

<table border="0" cellpadding="1" cellspacing="0" width="100%">
<form name="FILTERFORM" method="POST" action="{S_FILTER_ACTION}">
 <tr>
  <td height="45" valign="top" class=tdtext>{U_ADD} {U_UPDATE} {U_RESYNC}</td>
  <td height="45" valign="top" class=tdtext align="right">
		<select class="form" name="fstatus">
			<option value="-1">- - {L_STATUS} - -</option>
			<option value="{SYS_ENABLED}">{L_ENABLED}</option>
			<option value="{SYS_DISABLED}">{L_DISABLED}</option>
		</select>
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="20">
		<input class=submit type="submit" name="submit" value="{L_SEARCH}">  
  </td>
 </tr>
</form>
</table> 

<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext valign="bottom" align="right">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>

<div align="center">
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	<form name="LISTFORM" method="POST" action="{S_LIST_ACTION}">
	  <tr>
	    <td class=tdmenu width="4%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td class=tdmenu width="8%" align="center">{L_ORDER}</td>
	    <td class=tdmenu>{L_TITLE}</td>
	    <td class=tdmenu width="7%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: artpagerow -->
	  <tr>
	    <td class=tdtext1 align="center"><input type="checkbox" name="ids[{artpagerow:ID}]" value="{artpagerow:ID}"></td>
	    <td class=tdtext2 align="center"><input class="form" type="text" name="page_orders[{artpagerow:ID}]" value="{artpagerow:ORDER}" size="4" style="width: 40px"></td>
	    <td class=tdtext1><a class="{artpagerow:CSS}" href="javascript: open_window('{artpagerow:U_VIEW}', 550, 450);">{artpagerow:TITLE}</a></td>
	    <td class=tdtext2 align="center">{artpagerow:U_EDIT}</td>
	  </tr>
	<!-- END: artpagerow -->

	<!-- START NOIF: artpagerow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="4" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: artpagerow -->
	</form>
	</table>        
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_ENABLE} {U_DISABLE} {U_DELETE}&nbsp;</td>
    <td class=tdtext align="right" valign="top">&nbsp;{PAGE_OUT}</td>
  </tr>
</table>
</div>


<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
	var filter_form	= window.document.FILTERFORM;

	select_list("{FSTATUS}", filter_form.fstatus);

	function updateForm(the_url){
		if ( !is_checked_item(the_form) ){
			alert('{L_CHOOSE_ITEM}');
		}
		else{
			the_form.action = the_url;
			the_form.submit();
		}
	}

	function updateForm2(the_url){
		the_form.action = the_url;
		the_form.submit();
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
