<?php

include 'header.php';
include 'left_panel.php';
$team_id=$_SESSION['team_id'];
echo '<div id="main_block">';

	


		/*echo '<div class="common_white_block">
		<i class="fa fa-info-circle"></i>Add all team members coming to Alcheringa from your college. You can then register your team members in one or more solo/group competitions. 
		<b>Add 15 or more members to be eligible for General Championship.</b>
		</div>
		<div class="message">Showing all team members</div>
		
		<div class="common_white_block team_mem_block">
			<span>'.$_SESSION['user_name'].'</span>
			<span class="alcher_id">ALCHER-'.($_SESSION['user_id']+1000).'</span>
		</div>
		';*/
		echo '
		<div class="message">Showing all team members</div>
		
		<div class="common_white_block team_mem_block">
			<span>'.$_SESSION['user_name'].'</span>
			<span class="alcher_id">ALCHER-'.($_SESSION['user_id']+1000).'</span>
		</div>
		';
		?>
		<style type="text/css">
table tr th, table tr td{font-size: 1.2rem;}
.glyphicon{font-size: 20px;}
.glyphicon-plus{float: right;}
a.glyphicon{text-decoration: none;}
a.glyphicon-trash{margin-left: 10px;}
.none{display: none;}
</style>
		<script>
function getUsers(){
    $.ajax({
        type: 'POST',
        url: 'userAction.php',
        data: 'action_type=view&'+$("#userForm").serialize(),
        success:function(html){
            $('#userData').html(html);
        }
    });
}
function userAction(type,id){
    id = (typeof id == "undefined")?'':id;
    var statusArr = {add:"added",edit:"updated",delete:"deleted"};
    var userData = '';
	var phone='';
    if (type == 'add') {
        userData = $("#addForm").find('.form').serialize()+'&action_type='+type+'&id='+id;
		phone=$("#phone").val();
    }else if (type == 'edit'){
        userData = $("#editForm").find('.form').serialize()+'&action_type='+type;
		phone=$("#phoneEdit").val();
    }else{
        userData = 'action_type='+type+'&id='+id;
    }
	if(phone=='' && (type=='add' || type=='edit')){
		alert("Enter phone number.");
	}else{
		$.ajax({
			type: 'POST',
			url: 'userAction.php',
			data: userData,
			success:function(msg){
				if(msg == 'ok'){
					alert('User data has been '+statusArr[type]+' successfully.');
					getUsers();
					$('.form')[0].reset();
					$('.formData').slideUp();
				}else{
					alert('Some problem occurred, please try again.');
				}
			}
		});
	}   
}
function editUser(id){
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'userAction.php',
        data: 'action_type=data&id='+id,
        success:function(data){
            $('#idEdit').val(data.index);
            $('#first_nameEdit').val(data.first_name);
			$('#last_nameEdit').val(data.last_name);
            $('#emailEdit').val(data.email);
            $('#phoneEdit').val(data.phone);
			$('#genderEdit').val(data.gender);
            $('#editForm').slideDown();
        }
    });
}
</script>
		
		<div class="panel panel-default users-content">
            <div class="panel-heading">Team Members <a href="javascript:void(0);" class="glyphicon glyphicon-plus" id="addLink" onclick="javascript:$('#addForm').slideToggle();"></a></div>
            <div class="panel-body none formData" id="addForm">
                <h2 id="actionLabel">Add Member</h2>
                <form class="form" id="userForm">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name"/>
                    </div>
					<div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name"/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" id="email"/>
                    </div>
                    <div class="form-group">
                        <label>Phone<span style="color:red;padding:5px">mandatory</span></label>
                        <input type="text" class="form-control" name="phone" id="phone" />
                    </div>
					<div class="form-group">
						<label>Gender</label>
						<select name="gender">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</div>
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#addForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('add')">Add User</a>
                </form>
            </div>
			<div class="panel-body none formData" id="editForm">
                <h2 id="actionLabel">Edit User</h2>
                <form class="form" id="userForm">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_nameEdit"/>
                    </div>
					<div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_nameEdit"/>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" id="emailEdit"/>
                    </div>
                    <div class="form-group">
                        <label>Phone<span style="color:red;padding:5px;">(mandatory)</span></label>
                        <input type="text" class="form-control" name="phone" id="phoneEdit" />
                    </div>
					<div class="form-group">
						<label>Gender</label>
						<select name="gender" id="genderEdit">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</div>
                    <input type="hidden" class="form-control" name="id" id="idEdit"/>
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#editForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="userAction('edit')">Update User</a>
                </form>
            </div>
			<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Alcher-id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
						<th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userData">
                    <?php
                        include 'DB.php';
                        $db = new DB();
                        $members = $db->getRows('members',array('where'=>array('team_id'=>$team_id),'order_by'=>'user_id DESC'));
                        if(!empty($members)): foreach($members as $member):
												$users=$db->getRows('users',array('where'=>array('`index`'=>$member['user_id'])));
												$user=$users[0];
                    ?>
                    <tr>
                        <td><?php echo 'ALCHER-'.($member['user_id']+1000); ?></td>
                        <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['phone']; ?></td>
						<td><?php echo $user['gender']; ?></td>
                        <td>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editUser('<?php echo $member['user_id']; ?>')"></a>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure to delete data?')?userAction('delete','<?php echo $member['user_id']; ?>'):false;"></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5">No user(s) found......</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
		</div>
<?php	
echo '</div>
';
include 'right_panel.php';

?>
