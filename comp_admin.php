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
			<td>ID</td>
			<td>MODULE</td>
			<td>COMPETITION NAME</td>
			<td>TYPE</td>
			<td>DETAILS/RULES</td>
			<td>ADMIN</td>';
			echo '</tr>';
			
			echo'<tr>
			<form id="add_comp" action="add_comp.php" method="POST">
				<td></td>
				<td><select name="module_id">';
				
				$query_mod= "SELECT * FROM `modules` ORDER BY `module_name`";
				if($query_run_mod= mysql_query($query_mod)){
					while($query_row_mod= mysql_fetch_assoc($query_run_mod)){
						echo '<option value="'.$query_row_mod['index'].'">'.$query_row_mod['module_name'].'</option>';
					}
				}
				echo '</select></td>
				<td><input name="comp_name" class="option" type="text" placeholder="Name" /></td>
				<td>
				<select name="type">
					<option value="0">Individual</option>
					<option value="1">Team</option>
				</select>
				<input placeholder="Min" name="min" type="text"/>
				<input placeholder="Max" name="max" type="text"/>										
				</td>
				<td><textarea name="details" rows="5" cols="40" placeholder="Rules"></textarea></td>
				<td><input id="add_comp_button" type="submit" value="ADD COMPETITION"></td>
				</form>
			</tr>';
			$query= "SELECT * FROM `competitions` ORDER BY `index` DESC";
			if($query_run= mysql_query($query)){
			while($query_row= mysql_fetch_assoc($query_run)){
				$module_id= $query_row['module_id'];
				$query_name= "SELECT `module_name` FROM `modules` WHERE `index`='".$module_id."'";
				if($query_run_name= mysql_query($query_name)){
					while($query_row_name= mysql_fetch_assoc($query_run_name)){
						$module_name= $query_row_name['module_name'];
					}
					
				}
				
				$module_lim=1;
				if($query_row['type']==1){
					$module_lim= $query_row['min'].'-'.$query_row['max'];
				}
				echo'
				<tr>
				
					<td>'.htmlentities($query_row['index']).'
					<td>'.$module_name.'
					<td>'.htmlentities($query_row['name']).'
					<td>'.$module_lim.'
					<td>'.htmlentities($query_row['details']);
					
					echo'</td>';
				echo '<td></td>';
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