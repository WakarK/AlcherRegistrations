<?php
require 'connect.php';
include 'admin_logout.php';

	if(isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']))
		{
		echo '<section><a href="admin.php"><button class="logout_button">Admin Home</button></a>';
		echo '<form action="admin_logout.php" method="POST"><input class="logout_button" type="submit" value="Logout" name="signout"></form><br>';
		echo '<br><br><br>';
		
			echo '<table border=1 id="admin_table">
			<tr id="table_headings">
			<td>COMPETITION</td>
			<td>TEAM-ID</td>
			<td>MEMBERS</td>
			<td>NAMES</td>
			<td>PHONE NUMBERS</td>;
			</tr>';
			
			$query= "SELECT * FROM `registrations` LEFT JOIN `competitions` ON `registrations`.`comp_id`= `competitions`.`index` ORDER BY `competitions`.`name`"; //default query
			
			if(isset($_GET['team_id']) && !empty($_GET['team_id'])){
				
				$query= "SELECT * FROM `registrations` LEFT JOIN `competitions` ON `registrations`.`comp_id`= `competitions`.`index` WHERE `registrations`.`team_id`= '".$_GET['team_id']."' ORDER BY `competitions`.`name`";
			}
	
			if($query_run= mysql_query($query)){
			while($query_row= mysql_fetch_assoc($query_run)){
				$members= explode(",",$query_row['members']);
				for($i=0;$i<sizeof($members);$i++){
					$members[$i]= 'ALCHER-'.(1000+$members[$i]);
				}
				$mem_list= implode("<br> " ,$members);
				$members=explode(",",$query_row['members']);
				echo'
				<tr>
					<td>'.$query_row['name'].'</td>
					<td><a href="users_admin.php?team_id='.$query_row['team_id'].'" target="_blank">TEAM-'.(1000+$query_row['team_id']).'</a></td>
					<td>'.$mem_list.'</td>
					<td>';
					for($i=0;$i<sizeof($members);$i++){
						
						$query_users = "SELECT * FROM `users` WHERE `index`='".$members[$i]."'";
						if($query_users_run=mysql_query($query_users)){
							while($query_users_row=mysql_fetch_assoc($query_users_run)){
								echo $query_users_row['first_name'].' '.$query_users_row['last_name'];
								echo '<br>';
							}
						}
					}
					echo '</td>
					<td>';
					for($i=0;$i<sizeof($members);$i++){
						$query_users = "SELECT * FROM `users` WHERE `index`='".$members[$i]."'";
						if($query_users_run=mysql_query($query_users)){
							while($query_users_row=mysql_fetch_assoc($query_users_run)){
								echo $query_users_row['phone'];
								echo '<br>';
							}
						}
					}
					echo '</td>
					</tr>';
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