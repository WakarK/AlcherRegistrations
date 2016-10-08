<?php
session_start();
require 'connect.php';
require 'main_functions.php';

if(isset($_POST['log_in_fb_id']) && !empty($_POST['log_in_fb_id'])){
	$query= "SELECT * FROM `users` WHERE `fb_id`= '".mysql_real_escape_string($_POST['log_in_fb_id'])."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){	
				$user_redirect_url= redirect_user_url($query_row['email']);
				echo $user_redirect_url;
			}
		}
	}
}
?>