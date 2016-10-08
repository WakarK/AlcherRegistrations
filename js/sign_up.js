function valid_date(day, month, year){ //check if a date is valid
	var valid_status=0;
	var leap_year=0;
	if(year%4==0){
		leap_year=1;
		if(year%100==0){
			if(year%400!=0){
				leap_year=0;
			}
		}
	}
	var month_days=[31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	if(year>1904 && year<=(new Date().getFullYear()) && month>0 && month<13){
		if(day>0 && day<=month_days[month-1]){
			valid_status=1;
		}
		else if(month==2 && day==29 && leap_year==1){
			valid_status=1;
		}
	}
	return valid_status;
}
function check_form_status(){  //data validation in form
	var error_message='';
	var req_fields= ['first_name', 'last_name', 'email', 'email_check', 'password'];
	for(var i=0;i<req_fields.length;i++){ //required fields
		if(($("input[name="+req_fields[i]+"]").val())==''){
			error_message='Please fill all fields.';
			return error_message;
		}
	}
	// if(($("select[name='year']").val())==0 || ($("select[name='year']").val())==0 || ($("select[name='year']").val())==0){ //date of birth selected
		// error_message='Please select a date of birth.';
		// return error_message;
	// } 
	if(($("input[name='gender']:checked").val())!='M' && ($("input[name='gender']:checked").val())!='F'){ //gender selected 
		error_message='Please select a gender.';
		return error_message;
	}
	if(($("input[name='email']").val())!=($("input[name='email_check']").val())){ //email match
		error_message='Email IDs do not match.';
		return error_message;
	}
	// if(valid_date(($("select[name='day']").val()), ($("select[name='month']").val()), ($("select[name='year']").val()))==0){ //date of birth valid
		// error_message='The entered date of birth is not valid.';
		// return error_message;
	// }
	if(($("input[name='password']").val().length)<8){ //password at least 8 characters
		error_message='Password must be at least 8 characters long.';
		return error_message;
	}
	return error_message;
}
$(document).ready(function(){	
	var fb_signup_email='';
	$('#fb_connect').click(function(){ //fb connect
		FB.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
		FB.login(function(response) {
		  if (response.status === 'connected'){
			statusChangeCallback(response, 1);
		  } 
		}, {scope: 'public_profile,email,user_friends'});
	});
	
	$('#email_sign_up').click(function(){ //sign up with email
		$('#sign_up_label').fadeOut(200, function(){
			$('#sign_up_form').slideDown(300);
		});
	});
	$("#sign_up_form input[type='submit']").click(function(e){
		if(check_form_status()!=''){
			e.preventDefault();
			$('#error_message').text(check_form_status());
		}
		else{
			if($('#mail_sent_message').is(":visible")){ //if verification code has been sent
				if(($("input[name='ver_code']").val())==''){
					e.preventDefault();
					$('#error_message').text('Please enter a verification code.').show();
				}
			}
			else{
				e.preventDefault();
				$("#sign_up_form input[type='submit']").attr('disabled','disabled');
				$.ajax({ //check if email exists in database
					url: 'ajax/check_existing_email.php',
					data: 'email='+($("input[name='email']").val()),
					success: function(data){
						$('#sign_up_form *').hide();
						$('#sign_up_form').append('<div class="clear_both"></div>');
						if(data==1){
							$('#already_exists_message').fadeIn('slow'); 
						}
						else{
							if($("input[name='email']").val()!=fb_signup_email){
								$.ajax({ //email verification code
									url: 'ajax/mail_ver_code.php',
									data: 'email='+($("input[name='email']").val()),
									success: function(data){
										$("input[name='ver_code']").next('br').show();
										$('#mail_sent_message').fadeIn('slow');
										$("input[name='ver_code']").fadeIn('slow');
										$("#sign_up_form input[type='submit']").removeAttr('disabled').fadeIn('slow');		
									}
								});
							}
							else{
								$("#sign_up_form").submit();
							}
						}
					}
				});
			}
		}	
	});
	
	function statusChangeCallback(response, sign_up_check) {
	sign_up_check = (typeof sign_up_check === "undefined") ? "0" : sign_up_check;	
		if (response.status === 'connected') {
		 FB.api('/me', function(response) {
		 console.log(response);
			$.ajax({ 
				type: "POST",
				url: 'ajax_fb_login.php',
				data: 'log_in_fb_id='+(response.id),
				success: function(data){
					if(data.trim().length>1){ //user login successful
						window.location.href= data;
					}
					else{
						if(sign_up_check==1){
							FB.api('/me', function(response) {	
							$('#sign_up_form input[name="first_name"]').val(response.first_name);
							$('#sign_up_form input[name="last_name"]').val(response.last_name);
							$('#sign_up_form input[name="email"]').val(response.email);
							$('#sign_up_form input[name="email_check"]').val(response.email);
							fb_signup_email= response.email;
							if(response.gender=='male'){
								$('#sign_up_form input[name="gender"][value="M"]').prop('checked', 'true');
							}
							else{
								$('#sign_up_form input[name="gender"][value="F"]').prop('checked', 'true');
							}
							$.ajax({ 
								type: "POST",
								url: 'ajax/set_fb_sign_up.php',
								data: 'sign_up_fb_id='+(response.id),
								success: function(data){
									$("#sign_up_form").submit();
								}
							});
							});
						}
					}
				}
			});
		});
		} 
	}
});