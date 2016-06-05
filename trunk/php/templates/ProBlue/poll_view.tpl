<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITE_NAME} {BROWSER_NAVIGATOR}</title>
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
</head>
<body style="margin: 5px">

<table width="100%" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_blockblue.gif" class="backGX">
  <tr> 
    <td width="12" valign="top"><img src="{TEMPLATE_PATH}/images/left_blockblue2.gif" width="12" height="16"></td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td align="center" valign="top"> 
            <table height="16" border="0" cellpadding="0" cellspacing="0" background="{TEMPLATE_PATH}/images/bg_titleblock.gif" class="backGX">
              <tr> 
                <td width="3" bgcolor="#FFFFFF"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                <td width="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                <td class="catnav" nowrap align="center">{L_POLL}</td>
                <td width="10"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
                <td width="3" bgcolor="#FFFFFF"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    <td width="12" align="right" valign="top"><img src="{TEMPLATE_PATH}/images/right_blockblue.gif" width="9" height="16"></td>
  </tr>
  <tr><td height="8"><img src="{TEMPLATE_PATH}/images/spacer.gif" width="1" height="1"></td></tr>
</table>

<br>
<table border="0" cellpadding="2" cellspacing="1" class="mainText">
  <tr>
    <td colspan="2"><span class="mtitle">{QUESTION}</span><br>({L_TOTAL_HITS}: {TOTAL_HITS})</td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
<!-- START: optionrow -->
  <tr>
    <td valign="top" nowrap>{optionrow:TITLE}: &nbsp;</td>
    <td valign="top" nowrap>

		<table class="mainText" width="300" border="0" cellpadding="0" cellspacing="0" style="border-color: #111111; border-collapse: collapse">
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