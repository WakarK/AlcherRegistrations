<?php
session_start();
include '../connect.php';
if(isset($_GET['email']) && !empty($_GET['email']))
{
$from='no-reply@alcheringa.in';
$to= mysql_real_escape_string($_GET['email']);
$message= 'Your verification code is '.$_SESSION['ver_code'].'.
Regards,
Team Alcheringa';
$subject= 'Verification Code';
mail($to, $subject, $message, 'From: '.$from);
}
?>