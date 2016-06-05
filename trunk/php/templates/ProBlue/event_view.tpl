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
                <td class="catnav" nowrap align="center">{L_EVENT}</td>
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

<div align="center"><span class="btitle">{EVENT_DATE}</span><br><br>

<table class="mainText" width="100%" border="0" cellspacing="0" cellpadding="0">
<!-- START: eventrow -->
<tr><td><strong>&raquo;</strong> <span class="date">{eventrow:HOUR}</span> <span class="mtitle">{eventrow:TITLE}</span></td></tr>
<tr><td><div align="justify"><br>{eventrow:DETAIL}</div></td></tr>
<tr><td><br>{eventrow:HR}</td></tr>
<!-- END: eventrow -->
</table>

<span class="mainText">[<a href="javascript: window.close();">{L_CLOSE}</a>]</span><br>
</div>

</body>
</html>