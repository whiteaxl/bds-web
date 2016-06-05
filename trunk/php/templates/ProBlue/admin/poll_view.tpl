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

<div align="center">
<table border="0" cellpadding="2" cellspacing="1">
  <tr>
    <td colspan="2" align="center"><span class="title">{QUESTION}</span><br>({L_TOTAL_HITS}: {TOTAL_HITS})</td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
<!-- START: optionrow -->
  <tr>
    <td valign="top" nowrap>{optionrow:TITLE}: &nbsp;</td>
    <td valign="top" nowrap>

		<table width="300" border="0" cellpadding="0" cellspacing="0" style="border-color: #111111; border-collapse: collapse">
		<tr>
			<td width="70%">
				<table bgcolor="#F1F8F9" border="1" cellpadding="0" cellspacing="0" style="border-color: #111111; border-collapse: collapse; height: 13px;" width="100%">
				<tr>
					<td width="100%">				
						<table style="height: 11px; background-image: url({TEMPLATE_PATH}/images/poll.gif);" width="{optionrow:PERCENT}" border="0" cellpadding="0" cellspacing="0">
							<tr><td width="100%"></td></tr>
						</table>				
					</td>
				</tr>
				</table>
			</td>
			<td nowrap>&nbsp;{optionrow:PERCENT} [{optionrow:HITS}]</td>
		</tr>
		</table>
	</td>
  </tr>
<!-- END: optionrow -->
</table>

<br><br>[<a href="javascript: window.close();">{L_CLOSE}</a>]
</div>