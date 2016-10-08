<?php
if(isset($_GET['email']) && !empty($_GET['email'])){
	require '../connect.php';
	$query= "SELECT * FROM `users` WHERE `email`= '".mysql_real_escape_string($_GET['email'])."' AND `password`!=''";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)==1){
				echo 1;
			}
			else{
				echo 0;
			}
		}
}
?>