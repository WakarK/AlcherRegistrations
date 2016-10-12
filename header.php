<?php
require 'connect.php';
session_start();

include 'main_functions.php';
include 'logout.php';
$current_path = basename($_SERVER['SCRIPT_NAME'], '?' . $_SERVER['QUERY_STRING']);

echo '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset=UTF-8">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,900italic,900,700italic,700,500italic,500,400italic,300italic,300,100italic,100" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="css/main.css" />
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" />
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<script src="jquery/dist/jquery.min.js"></script>
<link rel="shortcut icon" href="images/logo_shortcut.png" />
<meta name="description" content="Register now for competitions happening during Alcheringa, IIT Guwahati - one of the largest college cultural festivals in India.">
<meta name="keywords" content="registrations,cultural,festival,india,competitions,iit,guwahati">

<title>Registrations | Alcheringa, IIT Guwahati</title>
';

echo '

	<link type="text/css" rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css_icons/css/font-awesome.min.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/header.js"></script>

</head>
<body>
<div id="header_bar" style="height:57px;">
<div id="header_container" style="text-align: center;">
	<a href="index.php"><div id="header_logo" style="text-align:left;width:50px;margin-top:12px;color:black">Home</div></a>
	<a href="http://alcheringa.in">
	<img src="http://www.alcheringa.in/images/alcher%20lady.png" style="width: 4%;">
	<img style="width:22%;" src="http://www.alcheringa.in/images/alcheringa%20white.png"></a>
';

if(user_login_status()==1){
	echo '
	<div id="header_dropdown" style="margin-top:11px;color:black">
	<div id="header_dropdown_button" class="header_bar_button dropdown_close_prevent" style="color:black;"><img class="user_account_pic" src="'.$_SESSION['user_photo'].'" />'.$_SESSION['first_name'].'</div>
	<div id="header_dropdown_block" class="right_dropdown" hidden style="text-align:left;">
		<a href="registrations.php"><div><i class="fa fa-cog"></i>My Registrations</div></a>';
		
		echo '<div>
		
			<form method="POST">
				<i class="fa fa-power-off"></i><input type="submit" name="log_out" value="Log Out" />
			</form>
		</div>
	</div>
	</div>
	';
}
else{
	if($current_path!='index.php'){
		header('Location: index.php');
	}
}
echo '</div>
</div>';

echo '<div id="big_container">';

if(isset($_POST['phone_prompt']) && !empty($_POST['phone_prompt']) && isset($_POST['college_name']) && !empty($_POST['college_name'])){ 
	$phone_prompt= $_POST['phone_prompt'];
	$team= $_POST['college_name'];
	$query_new= "UPDATE `users` SET `phone`= ".$phone_prompt.", `team`= '".$team."' WHERE `index`='".$_SESSION['user_id']."'";
		if($query_run_new= mysql_query($query_new)){
			//new user updated
			echo '<div class="common_block green"><i class="fa fa-check-circle"></i>Thank you. You can now register for any competition happening during Alcheringa.</div>';
		}
		else{
			echo '<div class="common_block red"><i class="fa fa-times-circle"></i>There was a problem. Please try again.</div>';
		}
}
if(isset($_POST['team_name']) && !empty($_POST['team_name'])){
	$team_name= $_POST['team_name'];
	$query_new= "UPDATE `users` SET `team`= '".$team_name."' WHERE `index`='".$_SESSION['user_id']."'";
		if($query_run_new= mysql_query($query_new)){
			//new user updated
			echo '<div class="common_block green"><i class="fa fa-check-circle"></i>Thank you. You can now register for any competition happening during Alcheringa.</div>';
		}
		else{
			echo '<div class="common_block red"><i class="fa fa-times-circle"></i>There was a problem. Please try again.</div>';
		}
}
if(user_login_status()==1){
	if(user_phone_number()==''){
		echo '
		<div id="phone_box_container">
			<div class="common_white_block" id="phone_prompt_box">Thank you for showing your interest in Alcheringa. Just these two fields and then you can register for as many competitions as you wish.
				<form method="POST">
					<input type="text" name="phone_prompt" placeholder="10 Digit Mobile Number" pattern="\d{10}" title="10 Digit Number" autofocus required/>
					<input type="text" name="college_name" placeholder="College/Institute/Team Name" required/>
					<input type="submit" class="solid_button" value="Submit"/>
				</form>
			</div>
		</div>';
	}
	else{
		if(user_team_name()==''){
			echo '
			<div id="phone_box_container">
			<div class="common_white_block" id="phone_prompt_box">Thank you for showing your interest in Alcheringa. Just this one field and then you can register for as many competitions as you wish.
				<form method="POST">
					<input type="text" name="team_name" placeholder="College/Institute/Team Name" required/>
					<input type="submit" class="solid_button" value="Submit"/>
				</form>
			</div>
		</div>';
		}
	}
}
?>
                            
                            