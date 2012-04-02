<?php
require_once('sql.php');
function getFolderSize($folderId, $root = null){
    if(!$root)
	$root = getFolder($folderId);
    $size = 0;
    $result = mysql_query("SELECT * FROM folders WHERE parent_id=$folderId");
    while($row = mysql_fetch_array($result)){
	$size += getFolderSize($row['id'], $root);
    }
    $result = mysql_query("SELECT * FROM files WHERE folder_id=$folderId");
    while($row = mysql_fetch_array($result)){
	$size += $row['size'];
	mysql_query("UPDATE files SET root_id=$root[root_id] WHERE id=$row[id]");
    }
    mysql_query("UPDATE folders SET size=$size, user_id=$root[user_id], root_id=$root[root_id] WHERE id=$folderId");
    return $size;
}
function getDocThumbnail($docId){
    $remoteURL = SCRIBD_URL."thumbnail.get&api_key=".SCRIBD_API."&doc_id=$docId";
    $contents = file_get_contents($remoteURL);
    $info = json_decode(json_encode((array) simplexml_load_string($contents)),1);
    //throwError(var_export($info, true), 'internal');
    if($info['@attributes']['stat'] == "ok"){
	return $info['thumbnail_url'];
    }
    return null;
}
function getConnections($userId = null){
    if(!$userId || !is_numeric($userId))
	$userId = login_id;
    $drives = array();
    $result = mysql_query("SELECT * FROM folders WHERE parent_id=0 AND user_id=$userId");
    while($row = mysql_fetch_array($result))
	$drives[] = $row['id'];
    if(count($drives) == 0)
	return null;
    $sql_drives = implode("','", $drives);
    $sql = "SELECT * FROM permissions, users WHERE permissions.folder_id IN ('$sql_drives')";
    $sql .= " AND permissions.status=1 AND users.id=permissions.user_id";
    $result = mysql_query($sql);
    $rows = array();
    while($row = mysql_fetch_array($result))
	$rows[] = array("label" => "$row[name] ($row[email])", "value" => $row['email']);
    return $rows;
}
function deleteSession($id = null){
    if(empty($id)||!is_numeric($id))
	$sql_id = null;
    else
	$sql_id = "id=$id AND";
    mysql_query("UPDATE sessions SET deleted=2, expires=NOW() WHERE $sql_id (deleted=0 AND expires>NOW()) AND id!= ".session_id." AND user_id=".login_id);
}
function getNewFolder($oldName, $folder){
    $count = 0;
    $name = cleanFileName($oldName);
    while(true){
	$sql_name = cleanSQL($name);
	$result = mysql_query("SELECT id FROM folders WHERE name='$sql_name' AND parent_id = $folder[id] AND user_id=$folder[user_id]");
	if(mysql_num_rows($result)>0){
	    $count++;
	    $name = $oldName." ($count)";
	}
	else
	    return $name;
    }
}
function getNewFile($oldName, $folder){
    $name = cleanFileName($oldName);
    $count = 0;
    while(true){
	$sql_name = cleanSQL($name);
	$result = mysql_query("SELECT id FROM files WHERE name='$sql_name' AND folder_id = $folder[id]");
	if(mysql_num_rows($result)>0){
	    $count++;
	    $exten = file_extension($oldName);
	    $title = substr($oldName, 0, -1 * (strlen($exten) +1));
	    $name = $title." ($count).".$exten;
	}
	else
	    return $name;
    }
}
function authUser($login_id, $session_id = null){
    if(!is_numeric($login_id))
	return false;
    $result = mysql_query("SELECT * FROM users WHERE id=$login_id LIMIT 1");
    while($row = mysql_fetch_array($result)){
	$_SESSION['login_id'] = $row['id'];
	$_SESSION['login'] = $row['email'];
	$_SESSION['userInfo'] = $row;

	if(is_numeric($session_id)){
	    $data = array('id' => $session_id);
	    $data['expires'] = date(MYSQL_TIMESTAMP, time() + (3600 * 24 * 7));
	    updateSQL('sessions', $data);
	    $_SESSION['session_id'] = $session_id;
	}
	else{
	    $data = array();
	    $data['user_id'] = $login_id;
	    $data['ip'] = $_SERVER['REMOTE_ADDR'];
	    $data['hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	    $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	    $data['session_key'] = session_id();
	    $data['expires'] = date(MYSQL_TIMESTAMP, time() + (3600 * 6));
	    $data['location'] = "";
	    $location = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
	    $location_string = array($location['city'], $location['region'], $location['country_name']);
	    $data['location'] = implode(", ", $location_string);
	    $_SESSION['session_id'] = insertSQL('sessions', $data);
	}
	$_SESSION['session_token'] = randomString();
	return true;
    }
    return false;
}
function getUserQuota($id){
    if(!is_numeric($id))
	return false;
    $result = mysql_query("SELECT SUM(size) as total FROM folders WHERE user_id = $id AND parent_id=0");
    $row = mysql_fetch_array($result);
	return $row['total'];
}
function getRemainingSpace($id){
    $user = getUser($id);
    if(!$user)
	return false;
    $used = getUserQuota($id);
    $quota = $user['quota'];
    return $quota - $used;
}
function getDriveIdByName($name){
    $sql_name = mysql_escape_string($name);
    $result = mysql_query("SELECT id FROM folders WHERE parent_id=0 AND user_id=".login_id." AND name='$sql_name' LIMIT 1");
    while($row=mysql_fetch_array($result))
	return $row['id'];
    return null;
}
function throwError($message, $type = 'system_error'){
    $data = array();

    if($type == 'internal')
	$message .= " BACKTRACE: <br /><br /> ". var_export(debug_backtrace(), true);
    
    $data['log_id'] = LOG_ID;
    $data['type'] = $type;
    $data['content'] = $message;
    $id = insertSQL('messages', $data);

    if($type == 'system_error'){
	header("HTTP/1.0 500 Internal Server Error");
	die($message);
    }
    elseif($type == 'internal'){
	header("HTTP/1.0 500 Internal Server Error");
	trigger_error ($message, E_USER_ERROR);
    }
    else
	return $id;
}
function addMsg($type ,$msg){
    if(!$type)
	$type = "success";
    $_SESSION['msgs'][] = array('type'=>$type,'content'=>$msg);
    throwError($msg, $type);
}
function getFolder($id){
    if(empty($id)||!is_numeric($id))
	return false;
    $result = mysql_query("SELECT * FROM folders WHERE id='$id'");
    if(mysql_num_rows($result)==0)
	return false;
    while($row = mysql_fetch_array($result))
	return $row;
}
function getUser($id){
    if(empty($id)||!is_numeric($id))
	return array();
    if(defined("login_id") && $id == login_id)
	return $_SESSION['userInfo'];
    $result = mysql_query("SELECT * FROM users WHERE id='$id'");
    if(mysql_num_rows($result)==0)
	return array();
    while($row = mysql_fetch_array($result))
	return $row;
}
function createUser($email, $friend){
    $key = randomString();
    $data=array('email'=>$email,'reset_key'=>$key,'disabled'=>1);
    $userId =  insertSQL('users',$data);
    $subject = "You have been invited to join ClouThe by $friend";
    $content = "$friend has shared files with you and has invited you to join Clouthe, a new way to store your files on the cloud. ".CLOUTHE_ABOUT;
    $content .= "\n\nTo activate your account, go to ".FULL_URL."?page=account&confirm_user=$userId&key=$key";
    sendEmail($userId, $subject, $content);
    return $userId;
}
function userExists($email){
    $sql_email = cleanSQL(strtolower($email));
    $result = mysql_query("SELECT id FROM users WHERE email='$sql_email'");
    while($row=mysql_fetch_array($result))
	return $row['id'];
    return false;
}
function sendEmail($user_id, $subject, $message){
    $data = array();
    $data['subject'] = "ClouThe Notification: $subject";
    if($user_id == null){
	    $data['subject'] = "ClouThe Contact: $subject";
	    $user_id = 1;
    }

    $userInfo = getUser($user_id);
    if(!$userInfo)
	    return false;

    $data['email'] = $userInfo['email'];
    $data['name'] = $userInfo['name'];

    $data['message'] = "Hi $data[name],\n\n$message\n\nThanks for using ClouThe\n".FULL_URL;
    insertSQL('emails',$data);
    $handle = popen('w3m '.SITE_URL.'email.php -dump', 'r');
    pclose($handle);
}
function getPermissions($action){
    $perms=array();
    $result=mysql_query("SELECT * FROM folders WHERE parent_id=0 AND user_id = ".login_id);
    while($row=mysql_fetch_array($result))
	$perms[]=$row['id'];
    $level = actionToLevel($action);
    $result=mysql_query("SELECT * FROM permissions WHERE status=1 AND level >= $level AND user_id = ".login_id);
    while($row = mysql_fetch_array($result))
	$perms[]=$row['folder_id'];
    return array_unique($perms);
}
function getFile($fileId){
    if(!is_numeric($fileId))
	return false;
    $result = mysql_query("SELECT * FROM files WHERE files.id=".$fileId);
    while($row = mysql_fetch_array($result)){
	$row['path'] = BUCKET_PATH."/".$row['bucket_id'].'/'.$row['id'];
	return $row;
    }
    return false;
}
function getChildFolders($id, $folders){
    $folders[] = $id;
    $result = mysql_query("SELECT * FROM folders WHERE parent_id = $id");
    while($row = mysql_fetch_array($result))
	$folders = getChildFolders($row['id'],$folders);
    return $folders;
}
function permission($drive, $action, $strict = true){
    $drive = getFolder($drive);
    if($drive['user_id']==login_id)
	return true;
    if(login_id == 0 && $action == "view" && !empty($drive['token']))
	return true;
    $level = actionToLevel($action);
    $result=mysql_query("SELECT level FROM permissions WHERE level >= $level AND folder_id=$drive[root_id] AND user_id=".login_id);
    while($row=mysql_fetch_array($result))
	return true;
    if(!$strict){
	if($action == "view" && !empty($drive['token']))
	    return true;
    }

    return false;
}
function getPermissionLevel($drive){
    $drive = getFolder($drive);
    if($drive['user_id']==login_id)
		return 3;
    if(login_id == 0 && !empty($drive['token']))
		return 1;
    $result=mysql_query("SELECT level FROM permissions WHERE folder_id=$drive[id] AND user_id=".login_id);
    while($row=mysql_fetch_array($result))
	return $row['level'];
    return false;
}