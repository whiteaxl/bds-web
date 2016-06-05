<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITE_NAME} - {L_TELLFRIEND}</title>
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
</head>
<body style="margin-top: 5px; margin-bottom: 5px; margin-left: 5px; margin-right: 5px;">

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
                <td class="catnav" nowrap align="center">{L_TELLFRIEND}</td>
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

<div align="center">
<font color="#ff0000">{MSG}</font>
<form method="POST" name="TELLFRIEND" action="{S_ACTION}" onSubmit="javascript: return checkForm(this);">
<input type="hidden" name="act" value="send">
<input type="hidden" name="u_referer" value="{U_REFERER}">
<table width="420" border="0" cellspacing="0" cellpadding="5">
<tr><td class="tdtext1">
	<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr><td class="tdtext2">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr class="tdtext2"><td>{L_YOUR_NAME}:</td><td><input class="myForm" name="your_name" value="{YOUR_NAME}" size="40"></td></tr>
		<tr class="tdtext2"><td>{L_YOUR_EMAIL}:</td><td><input class="myForm" name="your_email" value="{YOUR_EMAIL}" size="40"></td></tr>
		<tr class="tdtext2"><td>{L_FRIEND_NAME}:</td><td><input class="myForm" name="friend_name" value="{FRIEND_NAME}" size="40"></td></tr>
		<tr class="tdtext2"><td>{L_FRIEND_EMAIL}:</td><td><input class="myForm" name="friend_email" value="{FRIEND_EMAIL}" size="40"></td></tr>
		<tr class="tdtext2"><td valign="top">{L_MESSAGE}:</td><td><textarea class="myForm" name="message" cols="60" rows="10">{MESSAGE}</textarea></td></tr>
		<tr class="tdtext2"><td colspan="2" align="center"><input class="mySubmit" type="submit" name="sm" value="{L_BUTTON}"></td></tr>
		</table>
	</td></tr>
	</table>
</td></tr>
</table>
</form>
</div>

<script language="javascript" type="text/javascript">
function checkForm(the_form){
	var your_name		= the_form.your_name.value;
	var your_email		= the_form.your_email.value;
	var friend_name		= the_form.friend_name.value;
	var friend_email	= the_form.friend_email.value;
	var message			= the_form.message.value;
	
	if ( (your_name == "") || (your_email == "") || (friend_name == "") || (friend_email == "") || (message == "") ){
		alert("{L_ERROR_NOT_FULL}");
		return false;
	}

	if ( (check_email(your_email) == false) || (check_email(friend_email) == false) ){
		alert("{L_ERROR_EMAIL}");
		return false;
	}

	return true;
}

function check_email(the_email){
	var the_at    = the_email.indexOf("@");
	var the_dot   = the_email.indexOf(".");
	var the_space = the_email.indexOf(" ");

	if ( (the_at!=-1)&&(the_at!=0)&&(the_dot!=-1)&&(the_dot>the_at + 1)&&(the_dot<the_email.length-1)&&(the_space==-1) ){
		return true;
	}
	return false;
}
</script>

</div>
</body>
</html>