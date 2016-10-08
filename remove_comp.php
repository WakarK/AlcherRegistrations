<?php
session_start();
include 'connect.php';

if(isset($_POST['remove_comp'])& !empty($_POST['remove_comp']))
{
$query_del_book= "DELETE FROM `competitions` WHERE `competitions`.`index` = '".$_POST['remove_comp']."'";	
	if($query_run_del_book= mysql_query($query_del_book)){
	
	}
	header('Location: comp_admin.php');
}
?>