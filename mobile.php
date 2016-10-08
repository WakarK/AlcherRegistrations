<?php

include 'header1.php';
echo '<div id="main_block">';

if(isset($_POST['member_list']) && !empty($_POST['member_list']) && isset($_POST['comp_id'])){
	if(user_phone_number()=='' || user_team_name()==''){
		echo '<div class="common_block red"><i class="fa fa-times-circle"></i>Please provide your contact number and college name above before registering for any competition.</div>';
	}
	else{
		$reg_count= sizeof($_POST['member_list']);
		$min=0;
		$max=0;
		$query= "SELECT * FROM `competitions` WHERE `index`='".mysql_real_escape_string($_POST['comp_id'])."'";
			if($query_run= mysql_query($query)){ 
				while($query_row= mysql_fetch_assoc($query_run)){
					if($query_row['type']==0){
						$min=1;
						$max=1;
					}
					else{
						$min= $query_row['min'];
						$max= $query_row['max'];
					}
				}
			}
		if($reg_count>=$min && $reg_count<=$max){
			
			$members= implode("," ,$_POST['member_list']);
			$time= date("Y-m-d H:i:s");
			$query_new= "INSERT INTO `registrations` (`index`, `comp_id`, `team_id`, `members`, `timestamp`) VALUES ('', '".mysql_real_escape_string($_POST['comp_id'])."', '".$_SESSION['team_id']."', '".$members."', '".$time."')";
				if($query_run_new= mysql_query($query_new)){
					echo '<div class="common_block green"><i class="fa fa-check-circle"></i>Your registration was successful.</div>';
				}
				else{
					echo '<div class="common_block red"><i class="fa fa-times-circle"></i>There was a problem. Please try again.</div>';
				}
		}
		else{
			echo '<div class="common_block red"><i class="fa fa-times-circle"></i>Your registration could not be completed. Please check the <b>team size</b> of the competition.</div>';
		}
	}
}

	echo '<div class="common_block module_list_block">
		<select name="module_list">
			<option value="0">All Modules</option>';
			$query= "SELECT * FROM `modules` ORDER BY `module_name`";
			if($query_run= mysql_query($query)){ 
				while($query_row= mysql_fetch_assoc($query_run)){
					echo '<option value="'.$query_row['index'].'">'.$query_row['module_name'].'</option>';
				}
			}
		echo '</select>
	</div>';
	
	$reg_html='<div class="comp_reg_user"><input type="checkbox" name="member_list[]" value="'.$_SESSION['user_id'].'"/>'.$_SESSION['user_name'].'</div>';
	$query= "SELECT * FROM `members` WHERE `team_id`= '".$_SESSION['team_id']."'";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)>0){ 
				while($query_row= mysql_fetch_assoc($query_run)){
					$reg_html.='<div class="comp_reg_user"><input type="checkbox" name="member_list[]" value="'.$query_row['user_id'].'">'.member_user_name($query_row['user_id']).'</div>';
				}
			}
		}

$query= "SELECT *, `competitions`.`index` AS 'index' FROM `competitions` LEFT JOIN `modules` ON `competitions`.`module_id`= `modules`.`index` ORDER BY `module_name`";
		if($query_run= mysql_query($query)){
				while($query_row= mysql_fetch_assoc($query_run)){
					echo '<div class="common_white_block competitions_block" data-module="'.$query_row['module_id'].'">
						<span class="comp_display_name">'.$query_row['name'].'</span>
						<span class="comp_mod_name">'.$query_row['module_name'].'</span>
						<div class="comp_details">';
							$comp_text='Solo';
							if($query_row['type']==1){
								$comp_text='Group | '.$query_row['min'].'-'.$query_row['max'].' Members';							
							}
							echo $comp_text;
							echo '
							<button class="solid_button"><a href="http://alcheringa.in/registrations" target="_blank">Register</a></button>
							<button class="outline_button rules_drop">Rules</button>
							<div class="comp_rules">'.nl2br($query_row['details']).'</div>
							<div class="comp_register">
								<div class="common_block"><b>Check the team member(s) you want to register for this competition</b></div>
								<form method="POST">
									<input type="hidden" name="comp_id" value="'.$query_row['index'].'"/>
									'.$reg_html.'
									<div class="clear_both"></div>
									<input type="submit" class="solid_button" value="Submit & Register"/>
								</form>
							</div>
						</div>		
					</div>';
				}
		}
	
echo '</div>
';
include 'right_panel.php';
?>