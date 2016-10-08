<?php
session_start();
include 'connect.php';
	if(isset($_POST['module_id']) && !empty($_POST['module_id']) && isset($_POST['type']) && isset($_POST['details']) && !empty($_POST['details']) && isset($_POST['comp_name']) && !empty($_POST['comp_name']))
	{
		$module=mysql_real_escape_string($_POST['module_id']);
		$type=mysql_real_escape_string($_POST['type']);
		$details=mysql_real_escape_string($_POST['details']);
		$comp_name=mysql_real_escape_string($_POST['comp_name']);
		$min='';
		$max='';
		if($type==1){
			$min= $_POST['min'];
			$max= $_POST['max'];
		}
			$query= "INSERT INTO `competitions` (`index`, `module_id`,`type`,`details`,`name`, `min`, `max`) VALUES ('', '".$module."', '".$type."', '".$details."', '".$comp_name."', '".$min."', '".$max."')";
			if($query_run= mysql_query($query)){
			
			}
			header('Location: comp_admin.php');	
	}			
	else{						
	echo 'Please Go Back and fill all fields';
}
?>		