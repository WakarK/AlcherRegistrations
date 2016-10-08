		<link rel="stylesheet" type="text/css" href="http://www.alcheringa.in/events/css/event.css">
		<link rel="stylesheet" type="text/css" href="http://alcheringa.in/wave.css">
		<link href='https://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
				<style type="text/css">
					.no-js #loader { display: none;  }
				    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
				    .se-pre-con {
				        position: fixed;
				        left: 0px;
				        top: 0px;
				        width: 100%;
				        height: 100%;
				        z-index: 9999;
				        background: url(http://alcheringa.in/images/Preloader_3.gif) center no-repeat #fff;
		    }
		</style>
		<script>
			//paste this code under head tag or in a seperate js file.
			// Wait for window load
			$(window).load(function() {
				// Animate loader off screen
				$(".se-pre-con").fadeOut("slow");;
			});
		</script>
		<style>
#topNav{
	font-size:16px;
	margin-top: -1.5% !important;
	}
.alchertag{
	margin-left: 46% !important;
	}
</style>
<div class="se-pre-con"></div>
			<nav id="topNav">
			<div>
				<ul class="navleft" style="list-style: outside none none;">
					<li>
						<a href="http://www.alcheringa.in/events" class="wave">EVENTS</a>
					</li>
					<li>
						<a href="http://www.alcheringa.in/concerts" class="wave">CONCERTS</a>
					</li>
					<li>
						<a href="http://www.alcheringa.in/specials" class="wave">SPECIALS</a>
					</li>
					<li>
						<a href="http://www.alcheringa.in/informals" class="wave">GAMESCAPE</a>
					</li>
				</ul>
			</div>
			<div class="alchertag">
		              <a href="http://www.alcheringa.in">
		                <img src="http://www.alcheringa.in/images/alchertag1.png">
		              </a>
		        </div>
			<div>
				<ul class="navright" style="list-style: outside none none;">
					<li>
						<a style="margin-left: 60px;" href="http://www.alcheringa.in/team" class="wave" target="_blank">TEAM</a>
					</li>
					<li>
						<a style="margin-left: 60px;" href="http://www.alcheringa.in/hospitality" class="wave">HOSPITALITY</a>
					</li>
					<li>
						<a style="margin-left: 60px;font-family: Architects Daughter,cursive !important;" href="http://www.alcheringa.in/registrations" class="wave">REGISTRATION</a>
					</li>
					<li>
						<a style="margin-left: 60px;font-family: Architects Daughter,cursive !important;" href="http://www.alcheringa.in/sponsors" class="wave">SPONSORS</a>
					</li>
				</ul>
			</div>
			</nav>
<?php
include 'header.php';
echo '<style type="text/css">
		
		#header_bar{
			display: none;
		}
	</style>';
if(user_login_status()==1){ //redirect user if logged in
	redirect_user($_SESSION['email']);
}
if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['email_check']) && isset($_POST['password'])){ //check sign up form submit
$new_user_request=0;
$temp_req=0;
$update_user_req=0;
$password= hash("sha512", (mysql_real_escape_string($_POST['password'])));
$error_message='';
	if(check_form_status()==''){
		$query= "SELECT * FROM `users` WHERE `email`= '".mysql_real_escape_string($_POST['email'])."'";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)==1){ //if email exists in our database
				while($query_row= mysql_fetch_assoc($query_run)){
					if(!empty($query_row['password'])){ //account exists
						echo 'This email is already registered with us. Please login.';
					}
					else{
						$update_user_req=$query_row['index'];
						$temp_req=1;
					}
				}
			}
			else{
				$temp_req=1;
			}
			if($temp_req==1){
				if(isset($_SESSION['sign_up_fb_id']) && !empty($_SESSION['sign_up_fb_id'])){ //if facebook login is there
					$query_fb_check= "SELECT * FROM `users` WHERE `fb_id`= '".mysql_real_escape_string($_SESSION['sign_up_fb_id'])."'";
						if($query_run_fb_check= mysql_query($query_fb_check)){
							if(mysql_num_rows($query_run_fb_check)==1){ //if facebook id exists in database but email doesn't
								echo 'This facebook account is already registered with us. Please login.';
							}
							else{ //neither facebook id nor email exist in database
								$new_user_request=1;
								$act_password= rand(100000000, 999999999);
								$password= hash("sha512", (mysql_real_escape_string($_POST['password'])));
							}
						}
				}
				else{ //user is logging in via email
					if(mysql_real_escape_string($_POST['ver_code'])==$_SESSION['ver_code']){
						$new_user_request=1;
					}
					else{
						echo 'You did not enter the correct verification code.';
					}
				}
			}
		}
	}
	else{
		echo check_form_status();
	}
if($new_user_request==1){
	$fb_id=NULL;
	$phone=NULL;
	if(!empty($_POST['phone'])){
		$phone= mysql_real_escape_string($_POST['phone']);
	}
	if(isset($_SESSION['sign_up_fb_id']) && !empty($_SESSION['sign_up_fb_id'])){
		$fb_id=$_SESSION['sign_up_fb_id'];
	}
	$time= date("Y-m-d H:i:s");
	if($update_user_req>0){
		$query_new= "UPDATE `users` SET `fb_id`= ".(($fb_id===NULL)?"NULL":"'".$fb_id."'").", `phone`= ".(($phone===NULL)?"NULL":"'".$phone."'").", `password`= '".$password."', `gender`= '".mysql_real_escape_string($_POST['gender'])."', `first_name`='".mysql_real_escape_string($_POST['first_name'])."', `last_name`= '".mysql_real_escape_string($_POST['last_name'])."', `timestamp`='".$time."' WHERE `email`='".mysql_real_escape_string($_POST['email'])."' AND `index`='".$update_user_req."'";
		if($query_run_new= mysql_query($query_new)){
			//new user updated
			mysql_query("INSERT INTO `teams` (`index`, `name`, `admin`) VALUES ('', '', '".$update_user_req."')"); //create team
			redirect_user(mysql_real_escape_string($_POST['email']));
		}
		else{
			echo 'There was a problem. Please try again.';
		}
	}
	else{
		$query_new= "INSERT INTO `users` (`index`, `email`, `fb_id`, `phone`, `password`, `gender`, `first_name`, `middle_name`, `last_name`, `timestamp`) VALUES ('', '".mysql_real_escape_string($_POST['email'])."', ".(($fb_id===NULL)?"NULL":"'".$fb_id."'").", ".(($phone===NULL)?"NULL":"'".$phone."'").", '".$password."', '".mysql_real_escape_string($_POST['gender'])."', '".mysql_real_escape_string($_POST['first_name'])."', '".mysql_real_escape_string($_POST['middle_name'])."', '".mysql_real_escape_string($_POST['last_name'])."', '".$time."')";
		if($query_run_new= mysql_query($query_new)){
			//new user added
			mysql_query("INSERT INTO `teams` (`index`, `name`, `admin`) VALUES ('', '', '".mysql_insert_id()."')"); //create team
			redirect_user(mysql_real_escape_string($_POST['email']));
		}
		else{
			echo 'There was a problem. Please try again.';
		}
	}
}
}
else{
	if(isset($_SESSION['sign_up_fb_id'])){
		unset($_SESSION['sign_up_fb_id']);
	}
	$_SESSION['ver_code']= rand(1000000, 9999999);
}
if(isset($_POST['l_email']) && isset($_POST['l_password'])){ //check log in form submit
	$l_password= hash("sha512", (mysql_real_escape_string($_POST['l_password'])));
	$query= "SELECT * FROM `users` WHERE `email`= '".mysql_real_escape_string($_POST['l_email'])."' AND `password`!=''";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)==1){ //email exists in database
				while($query_row= mysql_fetch_assoc($query_run)){
					if($l_password==$query_row['password']){ //correct password
						redirect_user(mysql_real_escape_string($_POST['l_email']));
					}
					else{
						$l_error_message='The password you entered is incorrect. Please try again (make sure your caps lock is off).';
					}
				}
			}
			else{
				$l_error_message= 'This email is not registered with us. Please Sign Up.';
			}
		echo $l_error_message;
		}
}
?>
<div id="big_login_container">
<div id="free_trial_message">SIGN UP NOW FOR REGISTRATIONS</div>
<div id="log_in_section">
	<form id="log_in_form" method="POST">
		<input type="button" id="fb_connect" value="Connect With Facebook" />
		<div id="main_login_box">
			<input type="email" name="l_email" placeholder="Email" required />
			<input type="password" name="l_password" placeholder="Password" required />
			<input type="submit" value="Log In" class="solid_button" />	
			<div id="forgot_password">Forgot password? <a href="password_recovery.php">Request a new one</a></div>
		</div>
	</form>
</div>
<div id="sign_up_section">
<div id="sign_up_label">
	<span id="email_sign_up">Sign Up with Email</span>
</div>
<div id="already_exists_message" hidden>This email is already registered with us. Please login.</div>
<form id="sign_up_form" method="POST" hidden>
	<div id="error_message"></div>
	<div id="mail_sent_message" hidden>We just sent you a verification code. Check your email & enter it here.</div>
	<input type="text" name="first_name" placeholder="First Name" required />
	<input type="text" name="middle_name" placeholder="Middle Name" hidden />
	<input type="text" name="last_name" placeholder="Last Name" required /><br>
	<input type="email" name="email" placeholder="Email" required />
	<input type="email" name="email_check" placeholder="Re-enter email" required /><br>
	<input type="password" name="password" placeholder="New Password" required />
	<input type="text" name="phone" placeholder="Phone" pattern="\d{10}" title="10 Digit Number"/><br>
	<div id="fb_password_message" hidden>This password can be used to alternatively login via email.</div>
	<input type="radio" name="gender" value="F" /><span>Female</span>
	<input type="radio" name="gender" value="M" /><span>Male</span>
	<input type="text" name="ver_code" placeholder="Email Verification Code" hidden /><br>
	<input class="solid_button" type="submit" value="Sign Up" />
	<div class="clear_both"></div>
</form>
</div>
</div>


<script type="text/javascript" src="js/sign_up.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69366308-1', 'auto');
  ga('send', 'pageview');

</script>
<?php include 'footer.php'; ?>