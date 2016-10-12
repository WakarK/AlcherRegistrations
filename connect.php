<?php ob_start();
if(!@mysql_connect('localhost', 'root', '') || !@mysql_select_db('alcher_reg')){
		die('There was a problem. Please try again.');
	}
?>
                            