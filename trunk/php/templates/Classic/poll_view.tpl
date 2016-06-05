<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITENAME} {BROWSER_NAVIGATOR}</title>
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
</head>
<body style="margin: 5px">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr><td>
		<table width="100%" border="1" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" style="border-color: #77A7AD; border-collapse: collapse">
		<tr><td class="tdmtop" align="center"><span class="pagenav">{L_POLL}</span>&nbsp;</td></tr>
		</table>
  </td></tr>
  <tr><td height="10"></td></tr>
</table>

<br>
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
						<tr>
						<td width="100%"></td>
						</tr>
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

</body>
</html>