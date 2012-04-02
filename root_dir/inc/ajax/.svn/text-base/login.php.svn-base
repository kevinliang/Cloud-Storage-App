<?php
if(empty($_POST['username'])||empty($_POST['password'])){
    sleep(2);
    redirect("error-login");
}
$username=mysql_escape_string(strtolower(trim($_POST['username'])));
$password=$_POST['password'];

$correct=false;
$result=mysql_query("SELECT * FROM users WHERE email='$username' AND disabled=0 AND deleted=0 LIMIT 1");
while($row=mysql_fetch_array($result)){
    if($row['password']===sha1($password.$row['salt'])){
	$correct=true;
	$login_id=$row['id'];
	authUser($row['id']);
	$userInfo = $row;
    }
    break;
}

if(!$correct){
    sleep(2);
    redirect("error-login");
}

$location="index.php";
if(!empty($_SESSION['ref'])){
	$location = $_SESSION['ref'];
	unset($_SESSION['ref']);
}
if(isset($_POST['remember']) && $_POST['remember'] == 1){
    $data = array('id' => $_SESSION['session_id']);
    $data['token'] = randomString();
    $expire = time() + 604800;
    $data['expires'] = date(MYSQL_TIMESTAMP, $expire);
    updateSQL('sessions', $data);
    $key = $_SESSION['session_id']."-".$data['token'];
    setcookie('clouthe_login_key', $key, $expire);
}


if($userInfo['last_login']!="0000-00-00 00:00:00"){
	$timestamp = convert_timestamp($userInfo['last_login']);
	addMsg("success","Welcome back, your last login was on $timestamp. <a href=\"javascript:load_page('sessions')\">view history</a>");
}
else{
    addMsg("success","Thank you for joining ClouThe!");
}
mysql_query("UPDATE users SET last_login=NOW() WHERE id=$login_id");
usleep(500000);
redirect("success-$location");