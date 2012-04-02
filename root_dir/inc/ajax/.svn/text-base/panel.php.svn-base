<?php
if(empty($_POST['id']))
    throwError("Invalid drive ID");
$drive = getFolder($_POST['id']);
if(!$drive)
    throwError("Invalid drive");

if(isset($_POST['action'])||isset($_POST['send'])){
    $user = getUser(login_id);
    if($user['email'] == "demo@clouthe.com")
	throwError("Demo account");
}


if(isset($_POST['action'])){
    if($_POST['action'] == "public"){
	$key = mysql_escape_string(substr(randomString(),0,16));
	mysql_query("UPDATE folders SET token = '$key' WHERE id='$drive[id]' AND user_id = ".login_id);
	redirect("Drive <b>$drive[name]</b> is now public");
    }
    elseif($_POST['action'] == "remove"){
	if($drive['user_id']!=login_id)
	   throwError("Permission Denied");
	if(empty($_POST['permission_id'])||!is_numeric($_POST['permission_id']))
	    throwError("Invalid permission id");
	mysql_query("DELETE FROM permissions WHERE id='$_POST[permission_id]' AND folder_id='$drive[id]'");
	redirect("Removed permission for drive <b>$drive[name]</b>");
    }
    elseif($_POST['action'] == "private"){
	mysql_query("UPDATE folders SET token = '' WHERE id='$drive[id]' AND user_id = ".login_id);
	redirect("Drive <b>$drive[name]</b> is now private");
    }
    elseif($_POST['action'] == "delete"){
	if($drive['user_id']!=login_id)
	    throwError("Permission Denied");

	mysql_query("DELETE FROM permissions WHERE folder_id='$drive[id]'");
	mysql_query("DELETE FROM folders WHERE root_id='$drive[id]'");
	redirect("Drive <b>$drive[name]</b> deleted");
    }
    elseif($_POST['action'] == "deny"){
	mysql_query("DELETE FROM permissions WHERE folder_id=$drive[id] AND user_id=".login_id);
	redirect("Rejected drive <b>$drive[name]</b>");
    }
    elseif($_POST['action'] == "approve"){
	mysql_query("UPDATE permissions SET status=1 WHERE folder_id=$drive[id] AND user_id=".login_id);
	redirect("Approved drive <b>$drive[name]</b>");
    }
    else
	throwError("Invalid action");
}

if($drive['user_id']!=login_id)
    throwError("Permission Denied");

if(isset($_POST['send'])){
    foreach($_POST as $permission_id => $level)
	if(is_numeric($permission_id) && is_numeric($level))
	    mysql_query("UPDATE permissions SET level=$level WHERE id=$permission_id AND folder_id = $drive[id]");

    if(!empty($_POST['email'])){
	if(!validEmail($_POST['email']))
	    throwError("Invalid email address");

	$newUserId = userExists($_POST['email']);

	if($drive['user_id'] == $newUserId)
	    throwError("You are the owner of the drive");

	$newUser = false;
	if(!$newUserId){
	    $newUserId = createUser($_POST['email'],$user['name']);
	    $newUser = true;
	}

	$result = mysql_query("SELECT * FROM permissions WHERE folder_id='$drive[id]' AND user_id='$newUserId'");
	if(mysql_num_rows($result)>0)
	    throwError("User already has drive shared with you.");

	$data = array('folder_id'=>$drive['id'], 'user_id' => $newUserId, 'level' => $_POST['level']);
	$id = insertSQL('permissions',$data);

	if(!$newUser){
	    $subject = "$user[name] has shared a drive with you!";
	    $content = "$user[name] has shared a drive with you on ClouThe, ";
	    $content .= "to accept this request please go to:\n";
	    $content .= FULL_URL."?page=settings";
	    sendEmail($newUserId, $subject, $content);
	}
    }
    redirect("Permissions updated for drive <b>$drive[name]</b>");
}
else{
    // get a list of this drive's permission
    $result=mysql_query("SELECT * FROM permissions WHERE folder_id='$drive[id]'");
    $count = mysql_num_rows($result);

    $height = 90+$count*30;
    echo "<form class='ajaxform' action='updatePanel($drive[id])' style='height:$height"."px' id='form_panel_$drive[id]'>";
	echo "<table><tr><th>User</th><th>Permission Level<th></th></tr>";
	while($row = mysql_fetch_array($result)){
	    $user = getUser($row['user_id']);
	    echo "<tr id='permission_$row[id]'><td>$user[name] ($user[email])</td>";
	    echo "<td><div style='width:200px' class='input_permission'>";
	    echo "<input type='hidden' value='$row[level]' name='$row[id]'> <div class='permission_text'>View</div>";
	    echo "<div class='slider'></div></div></td>";
	    echo "<td><a href='javascript:removePermission($row[id], $drive[id])'>";
	    echo "<img src='src/img/icons/cross.png' style='position:relative;bottom:3px'></a></td>";
	    echo "</tr>";
	}

	echo "<tr><td>Email: <input type='text' name='email' class='input_connections' size='40'></td>";
	echo "<td><div style='width:200px' class='input_permission'><input value='1' type='hidden' name='level'> <div class='permission_text'>View</div><div class='slider'></div></div></td>";
	echo "</tr></table>";
	echo "<input type='hidden' name='id' value='$drive[id]' />";
	echo "<input type='hidden' name='send' value='form' />";
	echo "<div class='dialog-buttons'>";
	    echo "<a href='javascript:updatePanel($drive[id]);' class='ajaxsubmit button button-update'>Update</a>";
	echo "</div>";
    echo "</form>";
}