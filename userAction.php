<?php
include 'DB.php';
session_start();
$team_id=$_SESSION['team_id'];
$db = new DB();
$tblName_users = 'users';
$tblName_members = 'members';
if(isset($_POST['action_type']) && !empty($_POST['action_type'])){
    if($_POST['action_type'] == 'data'){
        $conditions['where'] = array('`index`'=>$_POST['id']);
        $conditions['return_type'] = 'single';
        $user = $db->getRows($tblName_users,$conditions);
        echo json_encode($user);
    }elseif($_POST['action_type'] == 'view'){
        $members = $db->getRows($tblName_members,array('where'=>array('team_id'=>$team_id),'order_by'=>'user_id DESC'));
        if(!empty($members)){
            foreach($members as $member):
				$users=$db->getRows($tblName_users,array('where'=>array('`index`'=>$member['user_id'])));
				$user=$users[0];
                echo '<tr>';
                echo '<td>ALCHER-'.($member['user_id']+1000).'</td>';
                echo '<td>'.$user['first_name'].' '.$user['last_name'].'</td>';
                echo '<td>'.$user['email'].'</td>';
                echo '<td>'.$user['phone'].'</td>';
				echo '<td>'.$user['gender'].'</td>';
                echo '<td><a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editUser(\''.$member['user_id'].'\')"></a><a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm(\'Are you sure to delete data?\')?userAction(\'delete\',\''.$member['user_id'].'\'):false;"></a></td>';
                echo '</tr>';
            endforeach;
        }else{
            echo '<tr><td colspan="5">No user(s) found......</td></tr>';
        }
    }elseif($_POST['action_type'] == 'add'){
        $userData_users = array(
			'`index`' => '',
            'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'gender' => $_POST['gender'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        );
		$insert_users = $db->insert($tblName_users,$userData_users);
		$userData_members = array(
			'`index`' => '',
			'user_id' => $insert_users,
			'team_id' => $team_id
		);
		$insert_members = $db->insert($tblName_members,$userData_members);
        echo $insert_members?'ok':'err';
    }elseif($_POST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $userData = array(
                'first_name' => $_POST['first_name'],
				'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
				'gender' => $_POST['gender']
            );
            $condition = array('`index`' => $_POST['id']);
            $update = $db->update($tblName_users,$userData,$condition);
            echo $update?'ok':'err';
        }
    }elseif($_POST['action_type'] == 'delete'){
        if(!empty($_POST['id'])){
            $condition_users = array('`index`' => $_POST['id']);
			$condition_members = array('user_id' => $_POST['id']);
            $delete_users = $db->delete($tblName_users,$condition_users);
			$delete_members = $db->delete($tblName_members,$condition_members);
            echo ($delete_users?($delete_members?'ok':'err'):'err');
        }
    }
    exit;
}
?>