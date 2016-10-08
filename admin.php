<?php
include 'admin_logout.php';
if(isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']))
{
echo '<section><form action="admin_logout.php" method="POST"><input type="submit" value="LOGOUT" name="signout" /></form>';
	echo '<a href= "comp_admin.php">Add or Remove Competitions</a><br><br>
		<a href= "users_admin.php">View all participants</a><br><br>
		<a href= "reg_admin.php">View all registrations</a><br>
	</section>';
}

else{
if(isset($_POST['pass']) && !empty($_POST['pass']))
{
if($_POST['pass']=="hailalcher")
{
$_SESSION['admin_user_id']= "admin";
header('Location: admin.php');
}
else{
header('Location: admin.php');
}
}
else{
echo '
<form id="login_form" action="admin.php" method="POST">
	Password : <input id="login" autofocus="" type="password" name="pass"><br><br>
	<input type="submit" value="Enter">
</form>';
}
}
?>
<style type= "text/css">
#login_form{
	text-align: center;
	margin-top: 100px;
	padding: 50px;
	background: #eee;
	font-size: 20px;
}
section{
	font-family: Georgia;
	text-align: center;
	margin-top: 100px;
	padding: 50px;
	background: #eee;

}
input{
	padding: 10px 20px;
}
#login{
	padding: 10px 5px;
}
</style>