<?php
if(isset($_POST['log_out'])){ 
	session_destroy();
	header('Location: index.php');
}
?>
