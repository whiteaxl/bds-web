
<script language="javascript" type="text/javascript">
	function check_login(the_form){
		if (the_form.username.value == ""){
			window.alert('{L_ENTER_USERNAME}');
			the_form.username.focus();
			return false;
		}

		if (the_form.password.value == ""){
			window.alert('{L_ENTER_PASSWORD}');
			the_form.password.focus();
			return false;
		}

		return true;		
	}
</script>

<br><br><div align="center"><span class="errorMsg">{ERROR_MSG}</span><br><br>
<table width="350" class="tblborder" cellpadding="0" cellspacing="0">
<form name="LOGINFORM" method="POST" target="_top" action="{S_LOGIN_ACTION}" onsubmit="javascript: return check_login(this);">
<input type="hidden" name="number_id" value="{NUMBER_ID}">
  <tr>
    <td width="100%">  
	<table border="0" cellpadding="2" cellspacing="1" width="100%">
	  <tr>
	    <td class=tdmenu colspan="2" align="center">{L_ADMIN_CONTROL}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_USERNAME}:</td>
	    <td class=tdtext2><input class=form type="text" name="username" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_PASSWORD}:</td>
	    <td class=tdtext2><input class=form type="password" name="password" size="30"></td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_NUMBER}</td>
	    <td class=tdtext2><input class="form" type="text" name="number_value" size="10"> {IMAGE_NUMBER}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1>{L_REMEMBER}?</td>
	    <td class=tdtext2><input type="checkbox" name="remember" value="1"> {L_YES}</td>
	  </tr>
	  <tr>
	    <td class=tdtext1 align="center" colspan="2"><input class=submit type="submit" name="submit" value="{L_LOGIN_BUTTON}"></td>
	  </tr>
	</table>        
    </td>
  </tr>
</form>
</table>

<br>[<a href="{U_FORGOT_PASS}">{L_FORGOT_PASS}</a>]<br>
</div>
