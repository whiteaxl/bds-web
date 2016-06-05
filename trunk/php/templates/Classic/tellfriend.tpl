<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<base href="{SITE_URL}">
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset={L_CHARSET}">
<title>{SITENAME} - {L_TELLFRIEND}</title>
<link href="{TEMPLATE_PATH}/style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#E1EBF1" style="margin-top: 5px; margin-bottom: 5px; margin-left: 5px; margin-right: 5px;">
<div align="center">

<div align="center"><font color="#ff0000">{MSG}</font></div>
<form method="POST" name="TELLFRIEND" action="{S_ACTION}" onSubmit="javascript: return checkForm(this);">
<input type="hidden" name="act" value="send">
<input type="hidden" name="u_referer" value="{U_REFERER}">
<table border="0" cellspacing="0" cellpadding="2">
<tr><td>{L_YOUR_NAME}:<br><input class="form" name="your_name" value="{YOUR_NAME}" size="40"></td></tr>
<tr><td>{L_YOUR_EMAIL}:<br><input class="form" name="your_email" value="{YOUR_EMAIL}" size="40"></td></tr>
<tr><td>{L_FRIEND_NAME}:<br><input class="form" name="friend_name" value="{FRIEND_NAME}" size="40"></td></tr>
<tr><td>{L_FRIEND_EMAIL}:<br><input class="form" name="friend_email" value="{FRIEND_EMAIL}" size="40"></td></tr>
<tr><td>{L_MESSAGE}:<br><textarea class="form" name="message" cols="40" rows="10">{MESSAGE}</textarea></td></tr>
<tr><td align="center"><input class="button" type="submit" name="sm" value="{L_BUTTON}"></td></tr>
</table>
</form>

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