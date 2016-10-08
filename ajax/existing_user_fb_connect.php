<?php
if(isset($_POST['connect_fb_id']) && !empty($_POST['connect_fb_id']))
{
	session_start();
	require '../connect.php';
	require '../php_functions/book_main.php';
	echo existing_user_fb_connect(mysql_real_escape_string($_POST['connect_fb_id']));
}
?>