/* JS validation functions here */

function ValidateEmail(email)
	{
		AtPos = email.indexOf("@")
		StopPos = email.lastIndexOf(".")
		to_return = false;
		
		if (email == "") {
			to_return = true;
		}
		
		if (AtPos == -1 || StopPos == -1) {
			to_return = true;
		}
		
		if (StopPos < AtPos) {
			to_return = true;
		}
		
		if (StopPos - AtPos == 1) {
			to_return = true;
		}
		
		return to_return;
	}
	
	function doValidate(frm){
		var error = 0;
		var fname = document.getElementById("firstname").value;
		var lname = document.getElementById("lastname").value;
		var email = document.getElementById("email").value;
		if(fname == ''){
			document.getElementById('lbl_fname').innerHTML = 'Required';
			error = 1;
		}else{
			document.getElementById('lbl_fname').innerHTML = '';
			error = 0;
		}
		
		if(lname == ''){
			document.getElementById('lbl_lname').innerHTML = 'Required';
			error = 1;
		}else{
			document.getElementById('lbl_lname').innerHTML = '';
			error = 0;
		}
		
		if(email == ''){
			document.getElementById('lbl_email').innerHTML = 'Required';
			error = 1;
		}else{
			document.getElementById('lbl_email').innerHTML = '';
			error = 0;
		}
		
		if(ValidateEmail(email))
		{
			document.getElementById("lbl_email").innerHTML="e.g (someone@asd.com)";
			error = 1;
		}else{
			document.getElementById("lbl_email").innerHTML="";
		}
		
		
		if(error == 0){
			return true;	
		}else{
			return false;
		}
		
	}

