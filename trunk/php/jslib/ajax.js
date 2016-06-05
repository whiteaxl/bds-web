//Create http object
function ajaxCreateHTTP(){
	try{
		xmlhttp	= window.XMLHttpRequest ? new XMLHttpRequest(): new ActiveXObject("Microsoft.XMLHTTP");
	}
	catch (e){
		//Browser doesn't support ajax
		xmlhttp	= false;
	}
	return xmlhttp;
}

//Load url and get content
function ajaxLoadURL(the_url, the_method, post_vars, div_output, waiting_type, output_type){
	//Create http object
	var xmlhttp	= ajaxCreateHTTP();
	if ( !xmlhttp ){
		return false;
	}

	//Handle status changes
	xmlhttp.onreadystatechange	= function(){
		ajaxHandleResponse(div_output, waiting_type, output_type);
	}

	//Prevent caching
	the_date	= new Date();
	randCode	= the_date.getTime() +"_"+ (Math.round(Math.random() * 100));
	the_url		+= (the_url.indexOf('?') != -1) ? '&ajaxRand='+ randCode : '?ajaxRand='+ randCode;

	//Open takes in the HTTP method and url.
	xmlhttp.open(the_method, the_url);

	//Send the request.
	//If this is a POST request we would have sent post variables: send("name=aleem&gender=male)
	//Moz is fine with just send(); but IE expects a value here, hence we do send(null);
	if ( the_method == 'POST' ){
		xmlhttp.send(post_vars);
	}
	else{
		xmlhttp.send(null);
	}
	return true;
}

function ajaxHandleResponse(div_output, waiting_type, output_type){
	//If the readyState code is 4 (Completed) and http status is 200 (OK) we go ahead and get the responseText
	if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)){
		if (output_type == 1){
			//Call js function
			if (xmlhttp.responseText != ""){
				eval(xmlhttp.responseText);
			}
		}
		else{
			//Display result
			if ( div_output != "" ){
				document.getElementById(div_output).innerHTML	= xmlhttp.responseText;
			}
		}
	}
	else{
		switch (waiting_type){
			case 1:
					//Display waiting text in div_output
					document.getElementById(div_output).innerHTML	= "Loading.....";
					break;
			case 2:
					//Display waiting text at the right top of page
					break;
			default:
					//Not display waiting screen
		}
	}
}

