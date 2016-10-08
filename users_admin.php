<?php
require 'connect.php';
include 'admin_logout.php';

function team_admin($team_id){ 
	$query= "SELECT `admin` FROM `teams` WHERE `index`= '".$team_id."'";
	if($query_run= mysql_query($query)){
		if(mysql_num_rows($query_run)==1){
			while($query_row= mysql_fetch_assoc($query_run)){
				return $query_row['admin'];
			}
		}
	}
	return '';
}

	if(isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']))
		{
		echo '<section><a href="admin.php"><button class="logout_button">Admin Home</button></a>';
		echo '<form action="admin_logout.php" method="POST"><input class="logout_button" type="submit" value="Logout" name="signout"></form><br>';
		echo '<br><br><br>';
		
			echo '<table border=1 id="admin_table">
			<tr id="table_headings">
			<td>ALCHER-ID</td>
			<td>NAME</td>
			<td>TYPE</td>
			<td>SEX</td>
			<td>PHONE</td>
			<td>TEAM ID</td>
			<td>TEAM NAME</td>
			<td>EMAIL</td>';
			echo '</tr>';
			
			$query= "SELECT * FROM `users` ORDER BY `index` DESC"; //default query
			
			if(isset($_GET['team_id']) && !empty($_GET['team_id'])){
				
				$query= "SELECT *, `users`.`index` AS 'index' FROM `users` LEFT JOIN `members` ON `users`.`index`= `members`.`user_id` WHERE `members`.`team_id`= '".$_GET['team_id']."' OR `users`.`index`= '".team_admin($_GET['team_id'])."' ORDER BY `users`.`index` DESC";
				echo '<div id="team_id_title">TEAM-'.(1000+$_GET['team_id']).' - <a href="reg_admin.php?team_id='.$_GET['team_id'].'" target="_blank">View registrations</a></div><br>';
			}
			else{
				echo '<div id="team_id_title"><a href="?leaders=1" target="_blank">Show only team leaders</a></div><br>';
			}
			if(isset($_GET['leaders']) && !empty($_GET['leaders'])){
				$query= "SELECT * FROM `users` WHERE `password`!='' ORDER BY `index` DESC"; 
			}
			if($query_run= mysql_query($query)){
			while($query_row= mysql_fetch_assoc($query_run)){
				$fb_link='';
				$type='N';
				$team_id='';
				if(!empty($query_row['fb_id'])){
					$fb_link='https://www.facebook.com/'.$query_row['fb_id'];
				}
				if(!empty($query_row['password'])){
					$type='R';
				}
				$query_t= "SELECT `index` FROM `teams` WHERE `admin`= '".$query_row['index']."'";
				if($query_run_t= mysql_query($query_t)){
					if(mysql_num_rows($query_run_t)==1){
						while($query_row_t= mysql_fetch_assoc($query_run_t)){
							$team_id= $query_row_t['index'];
						}
					}
				}
				echo'
				<tr>
					<td>ALCHER-'.(1000+$query_row['index']).'</td>
					<td>';
					if($fb_link!=''){
						echo '<a href="'.$fb_link.'" target="_blank">'.htmlentities($query_row['first_name']).' '.htmlentities($query_row['last_name']).'</a>';
					}
					else{
						echo htmlentities($query_row['first_name']).' '.htmlentities($query_row['last_name']);
					}
					echo '</td>
					<td>'.$type.'</td>
					<td>'.htmlentities($query_row['gender']).'</td>
					<td>'.htmlentities($query_row['phone']).'</td>
					<td>';
					if($team_id>0){
						echo '<a href="?team_id='.$team_id.'" target="_blank">TEAM-'.(1000+$team_id).'</a>';
					}
					
					echo '</td><td>'.htmlentities($query_row['team']).'</td>
					<td>'.htmlentities($query_row['email']).'</td>';
				echo '</tr>';
			}
			
		}
		echo '</table></section>';
	}
	else{
	  header('Location: admin.php');
	}
 ?>
<style type="text/css">
*{
	margin: 0;
	padding: 0;
}
body{
	background: #eee;
}
#team_id_title{
	text-align: left;
}
section{
	font-family: Calibri;
	
	text-align: center;
	padding: 40px;
	text-align: center;

}
#small_image{
	width: 100px;
}
#admin_table{
	background: white;
	font-size: 16px;
	border: 1px solid #777;
	border-collapse: collapse;
	
}
#admin_table td{
	padding: 5px 10px;
}
.option{
	width: 100px;
}
.logout_button{
	float: left;
	padding: 15px 20px;
}
#table_headings{
	text-align: center;
	background: #eee;
}
#add_comp_button, #delete_comp_button{
	padding: 3px;
}
input[name="min"], input[name="max"]{
	width: 50px;
	margin-top: 5px;
}
</style>