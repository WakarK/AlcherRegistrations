<?php

include 'header.php';

if(isset($_POST['rec_code']) && !empty($_POST['rec_code']) && isset($_POST['rec_email']) && !empty($_POST['rec_email']) && isset($_POST['new_password']) && !empty($_POST['new_password'])){
	$query= "SELECT * FROM `users` WHERE `email`= '".mysql_real_escape_string($_POST['rec_email'])."' AND `rec_code`= '".mysql_real_escape_string($_POST['rec_code'])."'";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)==1){ //correct recovery code
				if(strlen(mysql_real_escape_string($_POST['new_password']))>=8){ 
					$password= hash("sha512", (mysql_real_escape_string($_POST['new_password'])));
					$query_rec= "UPDATE  `users` SET  `password` =  '".$password."',`rec_code` =  '' WHERE  `email` ='".mysql_real_escape_string($_POST['rec_email'])."'";
						if($query_run_rec= mysql_query($query_rec)){
							redirect_user(mysql_real_escape_string($_POST['rec_email'])); //update password and log in
						}
						else{
							echo '<div class="common_white_block">There was a problem. Please <a href="index.php">try again.</a></div>';
						}
				}
				else{
					echo '<div class="common_white_block">Password must have been at least 8 characters long. Please <a href="password_recovery.php">try again.</a></div>';
				}
			}
			else{
				echo '<div class="common_white_block">You did not provide the correct recovery code. Please <a href="password_recovery.php">try again</a>.</div>';
			}
		}
}
else{
	header('Location: index.php');
	die();
}
?>

<?php include 'footer.php'; ?>