<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Website Name - Admin Control Panel</title>
<link href="admin_file/login.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="admin_file/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="admin_file/jquery-ui-1.8.17.custom.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function(){
			$("#wapper").show('drop', {direction: "left"}, 1500);
			$("#wapper").draggable();
		}, 300);

		setTimeout(function(){
			if ( $('#username').val() == '' ){
				$('#username').focus();
			}
		}, 2000);
	});
</script>

<script type="text/javascript" src="admin_file/ajax.js"></script>
<script type="text/javascript" src="admin_file/cms.js"></script>
</head>

<body>

<div align="center" class="overallBox">
	<span class="errorMsg">{ERROR_MSG}</span>

	<div id="wapper" style="" class="ui-draggable">
		<div id="wapper1">
			<div class="content">
				<form class="loginForm" name="LOGINFORM" method="POST" target="_top" action="{S_LOGIN_ACTION}" onsubmit="javascript: return check_login(this);">
<input type="hidden" name="number_id" value="{NUMBER_ID}">
				<ul>
					<li style="height:105px">&nbsp;</li>
					<li class="r1"><span class="left" style="width:108px">Username</span> <input type="text" name="username" id="username" value="" style="width: 185px;"></li>
					<li class="r2"><span class="left" style="width:108px">Password</span> <input type="password" name="password" id="password" style="width: 185px;"></li>
					<li class="r3"><span class="left" style="width:108px">Security code</span>
						<span class="left"><input type="text" name="number_value" id="number_value" style="width:75px;"></span>
						<span class="right">{IMAGE_NUMBER}</span>
					</li>
					<li class="r4"><input type="submit" class="btnLogin" name="smLogin" value="    Login   "></li>
					<li class="r5">
						<a href="{U_FORGOT_PASS}">Forgot Password</a>
						<span class="right2"><label>Remember &nbsp;<input type="checkbox" name="remember" id="remember" value="1"></label></span>
					</li>
					<li class="r6">Powered by TEDIN<br>Copyright © 2009-2016 by <a class="copyright" href="http://www.tedin.com.vn/">tedin.com.vn</a></li>
				</ul>
				</form>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function check_login(the_form){
		if (the_form.username.value == ""){
			window.alert('Please enter username!');
			the_form.username.focus();
			return false;
		}

		if (the_form.password.value == ""){
			window.alert('Password is invalid!');
			the_form.password.focus();
			return false;
		}

		if (the_form.number_value.value == ""){
			window.alert('Please complete all required fields!');
			the_form.number_value.focus();
			return false;
		}

		$("#wapper").hide('drop', {direction: "right"}, 1500);

		setTimeout(function(){
			document.LOGINFORM.submit();
		}, 1500);

		return false;
		//return true;
	}
</script>



</body></html>