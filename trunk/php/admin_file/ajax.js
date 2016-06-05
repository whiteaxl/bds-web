
function ajaxCreateHTTP(div_output, callback){
	try{
		var xmlhttp	= window.XMLHttpRequest ? new XMLHttpRequest(): new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (e){
		return false;
	}

	// The xmlhttp object triggers an event everytime the status changes
	xmlhttp.onreadystatechange	= function(){
		if ( (xmlhttp.readyState == 4) && (xmlhttp.status == 200) ){
			// xmlhttp.responseText object contains the response.
			if (div_output != ""){
				document.getElementById(div_output).innerHTML	= xmlhttp.responseText;
			}
			if ( callback != "" ){
				eval(callback);
			}
		}	
	}

	return xmlhttp;
}

function ajaxLoadURL(the_url, div_output, callback){
	//if ( div_output != "" ){
	//	document.getElementById(div_output).innerHTML	= '<img src="images/loading.gif" border="0" title="Đang tải trang">';
	//}

	//Create HTTP Object
	var xmlhttp	= ajaxCreateHTTP(div_output, callback);

	//Prevent cache problem
	if ( the_url.indexOf('?') ){
		the_url	+= "&ajaxRandCode=" + new Date().getTime();
	}
	else{
		the_url	+= "?ajaxRandCode=" + new Date().getTime();
	}

	// Open takes in the HTTP method and url.
	xmlhttp.open("GET", the_url);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

	// Send the request.
	// If this is a POST request we would have sent post variables: send("name=aleem&gender=male)
	// Moz is fine with just send(); but IE expects a value here, hence we do send(null);
	xmlhttp.send(null);
	return false;
}

function ajaxPostURL(the_url, div_output, post_vars){
	//Create HTTP Object
	var xmlhttp	= ajaxCreateHTTP(div_output);

	//Prevent cache problem
	if ( the_url.indexOf('?') ){
		the_url	+= "&ajaxRandCode=" + new Date().getTime();
	}
	else{
		the_url	+= "?ajaxRandCode=" + new Date().getTime();
	}

	// Open takes in the HTTP method and url.
	xmlhttp.open("POST", the_url);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	// Send the request.
	// If this is a POST request we would have sent post variables: send("name=aleem&gender=male)
	// Moz is fine with just send(); but IE expects a value here, hence we do send(null);
	xmlhttp.send(post_vars);
	return false;
}

function ajaxGetFormVars(the_form, form_vars, field_name){
	for (i = 0; i < the_form.length; i++) {
		if ( (field_name == "") || (the_form.elements[i].name.indexOf(field_name) != -1) ){
			//Field type
			if (the_form.elements[i].type == 'checkbox'){
				var field_value	= the_form.elements[i].checked;
			}
//			else if (the_form.elements[i].type == 'select-one'){
//				var field_value	= the_form.elements[i].options[the_form.elements[i].selectedIndex];
//			}
			else{
				var field_value	= the_form.elements[i].value;
			}

			if ( form_vars != "" ){
				form_vars	+= "&";
			}
			form_vars	+= the_form.elements[i].name +"="+ field_value;
		}
	}	
	return form_vars;
}
