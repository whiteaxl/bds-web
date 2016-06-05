<table width="100%" style="height: 32px;" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="23"><img src="{TEMPLATE_PATH}/images/admin/adminmain_01.gif" width="23" height="32" alt=""></td>
		<td width="67"><img src="{TEMPLATE_PATH}/images/admin/adminmain_03.gif" width="67" height="32" alt=""></td>
		<td width="99%" style="background-image: url({TEMPLATE_PATH}/images/admin/adminmain_04.gif);" align="center" class="pagetitle">{L_PAGE_TITLE}</td>
		<td width="66"><img src="{TEMPLATE_PATH}/images/admin/adminmain_05.gif" width="66" height="32" alt=""></td>
		<td width="27"><img src="{TEMPLATE_PATH}/images/admin/adminmain_07.gif" width="27" height="32" alt=""></td>
	</tr>
</table>
<br><br>

<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<tr><td class="title">{TITLE}</td></tr>
	<tr><td class="date">{DATE}</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><div align="justify">{CONTENT} {CONTENT_URL}</div></td></tr>
	<tr><td align="right" class="author">&nbsp;{AUTHOR}</td></tr>
	<!-- START IF: pagerow -->
	<tr><td align="center">
			<br>
			<table border=0 cellspacing=0 cellpadding=1>
			<form name="PAGEFORM" method="POST" action="{S_PAGE_ACTION}">
			<tr><td>{L_PAGE}:
				<select name="page_id" onchange="javacript: document.PAGEFORM.submit();">
				<!-- START: pagerow -->
				<option value="{pagerow:ID}">{pagerow:TITLE}</option>
				<!-- END: pagerow -->
				</select>
				<input type="submit" name="sm" value="{L_GO}">
			</td></tr>
			</form>
			</table>
			<br>
			<script language="javascript" type="text/javascript">
				select_list("{PAGE_ID}", document.PAGEFORM.page_id);
			</script>
	</td></tr>
	<!-- END IF: pagerow -->
	<tr><td align="center"><br>[<a href="javascript: window.close();">{L_CLOSE}</a>]</td></tr>
</table>

