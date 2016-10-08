<?php

include 'header.php';
echo '<div id="main_block">
		<div id="inside_top_bar">
			<div id="inside_top_bar_container" class="common_block">
				<span class="shift-span"><i class="fa fa-unlock-alt"></i>Password Recovery</span>
			</div>
		</div>';
$show_email_form=1;
if(isset($_GET['email']) && !empty($_GET['email'])){
	$query= "SELECT * FROM `users` WHERE `email`= '".mysql_real_escape_string($_GET['email'])."' AND `password`!=''";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)==1){ //email exists in database
				$show_email_form=0;
				$rec_code= rand(1000000, 9999999); //create a recovery code
				$query_rec= "UPDATE `users` SET `rec_code`= '".$rec_code."' WHERE `email`= '".mysql_real_escape_string($_GET['email'])."'"; //store recovery code in database
					if($query_run_rec= mysql_query($query_rec)){
						$from='no-reply@alcheringa.in';
						$to= mysql_real_escape_string($_GET['email']);
$message= 'Your password recovery code is '.$rec_code.'.

Regards,
Team Alcheringa';
						$subject= 'Alcheringa Password Recovery';
						mail($to, $subject, $message, 'From: '.$from);
						echo '
							<div class="common_white_block">We just sent you a recovery code. Please check your email and enter it here.</div>
							<div class="common_white_block pwd_rec_block">
								<form action="password_recovery_status.php" method="POST">
									<input type="hidden" name="rec_email" value="'.mysql_real_escape_string($_GET['email']).'" />
									<input type="text" name="rec_code" placeholder="Recovery Code" required />
									<input type="password" name="new_password" pattern=".{8,}" title="Minimum 8 characters" placeholder="New Password" required />
									<input type="submit" value="Submit" class="solid_button"/>
								</form>
							</div>';
					}
			}
			else{
				echo '<div class="common_white_block">This email is not registered with us. Please <a href="index.php">sign up</a>.</div>';
			}
		}
}
if($show_email_form==1){
	echo '
		<div class="common_white_block pwd_rec_block">
			<form method="GET">
				<input type="email" name="email" placeholder="Email" required />
				<input type="submit" value="Next" class="solid_button"/>
			</form>
		</div>';
}
?>

<?php include 'footer.php'; ?>