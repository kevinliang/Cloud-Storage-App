<?php
session_start();
require_once('inc/config.php');
require_once('inc/connect.php');
require_once('inc/init.php');
require_once('inc/lib/core.php');
require_once('inc/lib/model.php');

// reset password
if($login == null && !empty($_GET['reset_password'])&&!empty($_GET['key'])&&is_numeric($_GET['reset_password'])){
	$sql_key = mysql_escape_string($_GET['key']);
	$result = mysql_query("SELECT * FROM users WHERE id=$_GET[reset_password] AND reset_key = '$sql_key' LIMIT 1");
	
	while($row = mysql_fetch_array($result)){
		$data = array('id' => $row['id']);
		$data['reset_key'] = "";
		$data['reset_password'] = 1;
		$data['disabled'] = 1;
		updateSQL('users', $data);
		authUser($row['id']);
		addMsg("success","Please set a new password now");
		redirect("?page=account");
	}
	addMsg("error","Invalid login reset key");
	redirect("?page=login");
}

// confirm email & new user
if($login == null && !empty($_GET['key']) && !empty($_GET['confirm_user']) && is_numeric($_GET['confirm_user'])){

	$sql_key = mysql_escape_string($_GET['key']);
	$result=mysql_query("SELECT * FROM users WHERE id='$_GET[confirm_user]' AND disabled=1 AND reset_key='$sql_key' LIMIT 1");
	
	while($row=mysql_fetch_array($result)){
		mysql_query("UPDATE users SET disabled=0, reset_key='' WHERE id='$_GET[confirm_user]'");
		
		if($row['password']==''){
			mysql_query("UPDATE users SET reset_password=1 WHERE id='$row[id]'");
			addMsg("success","Your account has been created, please set a new password and name below:");
			authUser($row['id']);
			redirect("Location: ?page=account");
		}
		
		addMsg("success","Your email has been confirmed, welcome to ClouThe!");
		authUser($row['id']);
		redirect("index.php");
	}
	addMsg("error","Invalid key provided");
	redirect("?page=login");
}



$load_page="index";
$load_ajax=null;

if(isset($_GET['page'])){
	$load_page=$_GET['page'];
	if($load_page=="login"&&$login)
		redirect("index.php");
}

if(isset($_GET['ajax']))
	$load_ajax=$_GET['ajax'];

if($load_ajax && !AJAX_CALL)
	throwError("Ajax only");
	
if($login == null && isset($_COOKIE['clouthe_login_key'])){
    $ex = explode("-",$_COOKIE['clouthe_login_key']);
    $id = reset($ex);
    $token = mysql_escape_string(end($ex));
    if(is_numeric($id)){
	$result = mysql_query("SELECT user_id FROM sessions WHERE id=$id AND token='$token' AND deleted=0 AND expires>NOW() LIMIT 1");
	while($row = mysql_fetch_array($result)){
	    authUser($row['user_id'], $id);
	    redirect($_SERVER['REQUEST_URI']);
	}
    }
}

$allowed_ajax = array("login","register","forgot","drives");
$allowed = array("login","register","forgot","public","image","doc","cron");

if(!in_array($load_page,$allowed)&&!in_array($load_ajax,$allowed_ajax)){
    $validSession = false;
    if($login != null){
	if(session_id){
	    $result = mysql_query("SELECT * FROM sessions WHERE id=$session_id AND deleted=0 AND expires>NOW() LIMIT 1");
	    if(mysql_num_rows($result) > 0)
		$validSession = true;
	}
    }
    if(!$validSession){
	session_destroy();
	session_start();
	if($login != null){
	    addMsg("error", "Your session has expired");
	}
	if($load_page!="index"){
	    $_SESSION['ref'] = $_SERVER['REQUEST_URI'];
	    addMsg("error","You need to login to continue");
	}
	elseif(isset($_GET['type']) && $_GET['type'] == "demo"){
	    addMsg("success","Demo Account: <br /> username: demo@clouthe.com <br /> pass: demo1234");
	}

	if(!AJAX_CALL)
	    redirect("?page=login");
	else{
	    header("HTTP/1.0 401 Unauthorized");
	    redirect("Please Login");
	}
    }
}

// Load other dependeices
if($load_page || $load_ajax == "page")
    require_once('inc/lib/view.php');
if($load_page == "register" || $load_page == "forgot" || $load_ajax == "register" || $load_ajax == "forgot"){
    require_once('inc/lib/recaptchalib.php');
}
if($load_page == "upload"){
    require_once('inc/lib/import.php');
    require_once('inc/lib/image.php');
    require_once('inc/lib/getid3/getid3.php');
}

if($load_ajax!=null){
    if(login_id && !in_array($load_ajax, $allowed_ajax)){
	if(!isset($_POST['session_token'])||$_POST['session_token']!=$_SESSION['session_token'])
	    throwError("Invalid session token");
    }
    $load_ajax = alphaNum($load_ajax);
    if(file_exists('inc/ajax/'.$load_ajax.'.php'))
	require_once('inc/ajax/'.$load_ajax.'.php');
    else
	throwError("Invalid ajax file");
}
else{
    if($login_id){
	require_once('inc/lib/tasks.php');
	loadMessages($login_id);
    }
    load_page($load_page);
}