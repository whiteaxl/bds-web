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
		<tr><td class="tdmtop" align="center"><span class="pagenav">{L_EVENT}</span>&nbsp;</td></tr>
		</table>
  </td></tr>
  <tr><td height="10"></td></tr>
</table>

<div align="center"><span class="btitle2">{EVENT_DATE}</span><br><br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<!-- START: eventrow -->
<tr><td>{eventrow:HOUR} <span class="mtitle">{eventrow:TITLE}</span></td></tr>
<tr><td><div align="justify"><br>{eventrow:DETAIL}</div></td></tr>
<tr><td><br>{eventrow:HR}</td></tr>
<!-- END: eventrow -->
</table>

[<a href="javascript: window.close();">{L_CLOSE}</a>]<br>
</div>

</body>
</html>