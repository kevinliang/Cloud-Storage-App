<?php

if(APPLICATION_ENV == "stage")
    redirect("error-beta not open to public");

sleep(1);
if(empty($_POST['recaptcha_response_field']))
    redirect("error-recaptcha");
$data = array();
	
$resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
if (!$resp->is_valid)
    redirect("error-recaptcha");

if(empty($_POST['password'])||strlen($_POST['password'])<8)
    redirect("error-password");

if(empty($_POST['email'])||strlen($_POST['email'])>128)
    redirect("error-email");
	
$sql_email = mysql_escape_string(strtolower(trim($_POST['email'])));
$result = mysql_query("SELECT * FROM users WHERE email='$sql_email'");
if(mysql_num_rows($result)>0)
    redirect("error-taken");

$data['email'] = strtolower(trim($_POST['email']));
$data['salt'] = randomString();
$data['reset_key'] = randomString();
$data['password'] = sha1($_POST['password'].$data['salt']);
$data['name'] = $_POST['name'];
$data['disabled'] = 1;

$id = insertSQL('users', $data);

sendEmail($id, "Welcome to ClouThe","Thank you for signing up for ClouThe,\n\nTo confirm your email address, visit the following URL:\n".FULL_URL."?confirm_user=$id&key=$data[reset_key]");
redirect("success");