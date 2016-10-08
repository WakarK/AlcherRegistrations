<?php

include 'header.php';
include 'left_panel.php';
echo '<div id="main_block">';

if(isset($_POST['member_list']) && !empty($_POST['member_list']) && isset($_POST['comp_id'])){
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
		$query_new= "UPDATE `registrations` SET `members`='".$members."' WHERE `comp_id`='".mysql_real_escape_string($_POST['comp_id'])."' AND `index`='".mysql_real_escape_string($_POST['reg_id'])."' AND `team_id`= '".$_SESSION['team_id']."'";
			if($query_run_new= mysql_query($query_new)){
				echo '<div class="common_block green"><i class="fa fa-check-circle"></i>Your registration update was successful.</div>';
			}
			else{
				echo '<div class="common_block red"><i class="fa fa-times-circle"></i>There was a problem. Please try again.</div>';
			}
	}
	else{
		echo '<div class="common_block red"><i class="fa fa-times-circle"></i>Your registration could not be updated. Please check the <b>team size</b> of the competition.</div>';
	}
}

function team_members($reg_array){
	$reg_html='<div class="comp_reg_user"><input type="checkbox" name="member_list[]" value="'.$_SESSION['user_id'].'"';
	if(in_array($_SESSION['user_id'], $reg_array)){ //tick registered participant
		$reg_html.= ' checked';
	}
	$reg_html.= '/>'.$_SESSION['user_name'].'</div>';
	$query= "SELECT * FROM `members` WHERE `team_id`= '".$_SESSION['team_id']."'";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)>0){ 
				while($query_row= mysql_fetch_assoc($query_run)){
					$reg_html.='<div class="comp_reg_user"><input type="checkbox" name="member_list[]" value="'.$query_row['user_id'].'"';
					if(in_array($query_row['user_id'], $reg_array)){ //tick registered participant
						$reg_html.= ' checked';
					}					
					$reg_html.= '/>'.member_user_name($query_row['user_id']).'</div>';
				}
			}
		}
	return $reg_html;	
}

	$query= "SELECT *, `registrations`.`index` AS 'index' FROM `registrations` LEFT JOIN `competitions` ON `registrations`.`comp_id`=`competitions`.`index` WHERE `team_id`= '".$_SESSION['team_id']."' ORDER BY `timestamp` DESC";
		if($query_run= mysql_query($query)){
			if(mysql_num_rows($query_run)>0){ //members have been added
				while($query_row= mysql_fetch_assoc($query_run)){
					echo '<div class="common_white_block registrations_block">
						<div class="reg_block_header">
							<span class="comp_display_name">'.$query_row['name'].'</span>
							<span class="comp_mod_name">'.module_name($query_row['module_id']).'</span>
							<div class="comp_details">';
								$comp_text='Solo';
								if($query_row['type']==1){
									$comp_text='Group | '.$query_row['min'].'-'.$query_row['max'].' Members';							
								}
								echo $comp_text.'
							<button class="solid_button rules_drop">Rules</button>
							<button class="outline_button members_drop">Edit Participants</button>
						
								<div class="comp_register">
									<div class="common_block"><b>Check the team member(s) you want to register for this competition</b></div>
									<form method="POST">
										<input type="hidden" name="comp_id" value="'.$query_row['comp_id'].'"/>
										<input type="hidden" name="reg_id" value="'.$query_row['index'].'"/>
										<div>'.team_members(explode(",",$query_row['members'])).'</div>
										<div class="clear_both"></div>
										<input type="submit" class="solid_button" value="Update Participants"/>
									</form>
								</div>
								<div class="comp_rules">'.nl2br($query_row['details']).'</div>
							</div>
						</div>	
						<div class="reg_mem_list">';
						$members= explode(",",$query_row['members']);
						foreach($members as $member_id){
							echo '<span><i class="fa fa-check"></i>'.member_user_name($member_id).'</span>';
						}
					echo '
						<div class="clear_both"></div>
						</div>
					</div>';
				}
			}
			else{
				echo '<div class="common_block"><i class="fa fa-exclamation-circle"></i> You have not registered in any competition so far.</div>';
			}
		}
echo '</div>
';
include 'right_panel.php';
?>
