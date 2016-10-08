<?php ob_start();
if(!@mysql_connect('localhost', 'root', '') || !@mysql_select_db('registrations')){
		die('There was a problem. Please try again.');
	}
?>
                            