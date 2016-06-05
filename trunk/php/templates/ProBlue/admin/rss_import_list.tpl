
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
<form name="FILTERFORM" method="POST" action="{S_ACTION_FILTER}">
 <tr>
  <td class=tdtext>{U_ADD} {U_IMPORT_FEEDS} {U_REMOVE_FEEDS}</td>
  <td class=tdtext align="right">
		<input class=form type="text" name="fkeyword" value="{FKEYWORD}" size="20">
		<input class=submit type="submit" name="submit" value="{L_BUTTON_SEARCH}">  
  </td>
 </tr>
</form>
</table> 

<div align="center"><br><span class="error"><strong>{ERROR_MSG}</strong></span><br>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="bottom" nowrap>{U_UPDATE} {U_DELETE} {U_RESYNC}</td>
    <td class=tdtext valign="bottom" align="right">{PAGE_OUT}</td>
  </tr>
</table>
<table class="tblborder" cellpadding="0" cellspacing="1" width="100%">
<form name="LISTFORM" method="POST" action="">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr class="tdmenu">
	    <td width="4%" align="center"><input type="checkbox" name="chkall" onclick="javascript: check_all(window.document.LISTFORM, this.checked);"></td>
	    <td width="8%" align="center">{L_ORDER}</td>
	    <td>{L_TITLE}</td>
	    <td width="17%" align="center">{L_LAST_DATE}</td>
	    <td width="10%" align="center">{L_ARTICLES}</td>
	    <td width="8%" align="center">{L_EDIT}</td>
	  </tr>
	<!-- START: rssrow -->
	  <tr class="{rssrow:BG_CSS}" onMouseOver="setBackground(this, 'over');" onMouseOut="setBackground(this, 'out');">
	    <td align="center"><input type="checkbox" name="rss_ids[{rssrow:ID}]" value="{rssrow:ID}"></td>
	    <td align="center"><input class=form type="text" name="rss_orders[{rssrow:ID}]" value="{rssrow:ORDER}" size="3"></td>
	    <td>
			<span class="{rssrow:CSS}" style="font-size: 11px; font-weight: bold;">{rssrow:TITLE}</span><br>
			<a class="date" href="{rssrow:URL}" target="_blank"><strong>{rssrow:URL}</strong></a><br>
			<span class="date">[<em>{L_AUTO_IMPORT}: <a class="date" href="{rssrow:URL_AUTO_IMPORT}" target="_blank">{rssrow:AUTO_IMPORT_TEXT}</a></em>]</span>
		</td>
	    <td align="center">{rssrow:LAST_DATE}</td>
	    <td align="center">{rssrow:ARTICLES}</td>
	    <td align="center">{rssrow:U_EDIT}</td>
	  </tr>
	<!-- END: rssrow -->

	<!-- START NOIF: rssrow -->
	  <tr>
	    <td class=tdtext1 width="100%" colspan="6" align="center">&nbsp;</td>
	  </tr>
	<!-- END NOIF: rssrow -->
	</table>        
    </td>
  </tr>
</form>
</table>
<table width="100%" border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td class=tdtext valign="top" nowrap>{U_UPDATE} {U_DELETE} {U_RESYNC}</td>
    <td class=tdtext valign="top" align="right">{PAGE_OUT}</td>
  </tr>
</table>
</div>

<script language="javascript" type="text/javascript">
	var the_form	= window.document.LISTFORM;
//	var filter_form	= window.document.FILTERFORM;
	
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

