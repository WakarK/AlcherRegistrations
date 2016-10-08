<?php
session_start();
include '../connect.php';
if(isset($_POST['sign_up_fb_id']) && !empty($_POST['sign_up_fb_id']))
{
	echo $_SESSION['sign_up_fb_id']= mysql_real_escape_string($_POST['sign_up_fb_id']);
}
?>