<?php
function valid_date($day, $month, $year){ //check if a date is valid
	$valid_status=0;
	$leap_year=0;
	if($year%4==0){
		$leap_year=1;
		if($year%100==0){
			if($year%400!=0){
				$leap_year=0;
			}
		}
	}
	$month_days= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if($year>1904 && $year<=(date('Y',time())) && $month>0 && $month<13){
		if($day>0 && $day<=$month_days[$month-1]){
			$valid_status=1;
		}
		else if($month==2 && $day==29 && $leap_year==1){
			$valid_status=1;
		}
	}
	return $valid_status;
}
function check_form_status(){  //data validation in form
	$error_message='';
	$req_fields= array('first_name', 'last_name', 'email', 'email_check');
	for($i=0;$i<sizeof($req_fields);$i++){ //required fields
		if(empty($_POST[$req_fields[$i]])){
			$error_message='You did not fill all fields. Please try again.';
			return $error_message;
		}
	}
	if(empty($_POST['password']) && !isset($_SESSION['sign_up_fb_id'])){
		$error_message='You did not fill the password field. Please try again.';
		return $error_message;
	}
	if(!isset($_POST['gender'])){ //gender selected 
		$error_message='You did not select a gender. Please try again.';
		return $error_message;
	}
	else{
		if(($_POST['gender'])!='M' && ($_POST['gender'])!='F'){
			$error_message='You did not select a gender. Please try again.';
			return $error_message;
		}
	}
	if(($_POST['email'])!=($_POST['email_check'])){ //email match
		$error_message='Email IDs did not match. Please try again.';
		return $error_message;
	}
	// if(valid_date(($_POST['day']), ($_POST['month']), ($_POST['year']))==0){ //date of birth valid
		// $error_message='The entered date of birth was not valid. Please try again.';
		// return $error_message;
	// }
	if(strlen($_POST['password'])<8 && !isset($_SESSION['sign_up_fb_id'])){ //password at least 8 characters
		$error_message='Password must have been at least 8 characters long. Please try again.';
		return $error_message;
	}
	return $error_message;
}
function user_login_status(){
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])){
		return 1;
	}
	return 0;
}
function member_user_name($user_id){
	$query= "SELECT * FROM `users` WHERE `index`= '".$user_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['first_name'].' '.$query_row['last_name'];
			}
		}
	}
	return '';
}
function user_phone_number($user_id=NULL){ 
	if(empty($user_id) && $user_id!='0'){ //assign session team id by default
		$user_id=$_SESSION['user_id'];
	}
	$query= "SELECT `phone` FROM `users` WHERE `index`= '".$user_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['phone'];
			}
		}
	}
	return '';
}
function user_team_name($user_id=NULL){ 
	if(empty($user_id) && $user_id!='0'){ //assign session team id by default
		$user_id=$_SESSION['user_id'];
	}
	$query= "SELECT `team` FROM `users` WHERE `index`= '".$user_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['team'];
			}
		}
	}
	return '';
}
function team_admin($team_id=NULL){ 
	if(empty($team_id) && $team_id!='0'){ //assign session team id by default
		$team_id=$_SESSION['team_id'];
	}
	$query= "SELECT `admin` FROM `teams` WHERE `index`= '".$team_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['admin'];
			}
		}
	}
	return '';
}
function current_team(){
	$query= "SELECT `index` FROM `teams` WHERE `admin`= '".$_SESSION['user_id']."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['index'];
			}
		}
	}
	return '';
}
function competition_name($comp_id){ 
	$query= "SELECT `name` FROM `competitions` WHERE `index`= '".$comp_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['name'];
			}
		}
	}
	return '';
}
function module_name($mod_id){ 
	$query= "SELECT `module_name` FROM `modules` WHERE `index`= '".$mod_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['module_name'];
			}
		}
	}
	return '';
}
function members_count(){
	$query= "SELECT * FROM `members` WHERE `team_id`= '".$_SESSION['team_id']."'";
	if($query_run= mysql_query($query)){
		return((mysql_num_rows($query_run))+1);
	}
	return 0;
}
function registrations_count(){
	$query= "SELECT * FROM `registrations` WHERE `team_id`= '".$_SESSION['team_id']."'";
	if($query_run= mysql_query($query)){
		return((mysql_num_rows($query_run)));
	}
	return 0;
}
function create_session_variables($s_user_id, $s_user_email, $s_first_name, $s_middle_name, $s_last_name, $s_fb_id, $s_gender){
	$_SESSION['user_id']= $s_user_id;
	$_SESSION['email']= $s_user_email;
	$_SESSION['team_id']= current_team();
	$_SESSION['user_name']= $s_first_name.' '.$s_last_name;
	$_SESSION['first_name']= $s_first_name;
	if(!empty($s_middle_name)){
		$_SESSION['user_name']= $s_first_name.' '.$s_middle_name.' '.$s_last_name;
	}
	$_SESSION['fb_id']= $s_fb_id;
	$_SESSION['gender']= $s_gender;
	if(!empty($s_fb_id)){
		$_SESSION['user_photo']='http://graph.facebook.com/'.$s_fb_id.'/picture'; //use facebook dp if user is logged in via facebook
	}
	else{
		$_SESSION['user_photo']= 'images/default_pic.png'; //default blank photo
	}
}
function kill_temp_variables(){
	if(isset($_SESSION['sign_up_fb_id']))
		{
			unset($_SESSION['sign_up_fb_id']);
		}
}
function redirect_user($user_email){
	$redirect_url= redirect_user_url($user_email);
	kill_temp_variables();
	header('Location: '.$redirect_url);
	die();
}

function redirect_user_url($user_email){
	$redirect_url='team.php';
	if(user_login_status()==0){
		$query_s= "SELECT * FROM `users` WHERE `email`= '".$user_email."'"; //create session variables
		if($query_run_s= mysql_query($query_s)){
			while($query_row_s= mysql_fetch_assoc($query_run_s)){ 
				create_session_variables($query_row_s['index'], $query_row_s['email'], $query_row_s['first_name'], $query_row_s['middle_name'], $query_row_s['last_name'], $query_row_s['fb_id'], $query_row_s['gender']);
			}
		}
	}
	if(user_login_status()==1){
		$redirect_url='team.php';
	}
	return $redirect_url;
}
?>