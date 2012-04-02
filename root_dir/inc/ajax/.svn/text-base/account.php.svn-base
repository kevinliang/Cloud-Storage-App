<?php
if(!isset($_POST['send']))
    throwError("Invalid send");

$user = getUser(login_id);
if($user['email'] == "demo@clouthe.com")
	redirect("error-demo-account");

if($_POST['send']=="Change Password"){
	if($user['reset_password'] == 0){
	    $password = sha1($_POST['current_password'].$user['salt']);
	    if($password!=$user['password'])
		throwError("Invalid current password");
	}
	if($_POST['password1']!=$_POST['password2'])
	    throwError("New passwords do not match");
	if(strlen($_POST['password1'])<8)
	    throwError("Password less than 8 characters");

	$newSalt = randomString();
	$data = array('id' => login_id, 'reset_password' => 0, 'disabled' => 0, 'salt' => $newSalt, 'password' => sha1($_POST['password1'].$newSalt));
	updateSQL('users', $data);
	deleteSession();
	authUser(login_id);
	redirect("Password changed, all other sessions have been logged off");
}
else{
	if(empty($_POST['name']))
	    throwError("Invalid name");
	if(!validEmail($_POST['email']))
	    throwError("Invalid email");

	$sql_email = cleanSQL($_POST['email']);
	$result = mysql_query("SELECT email FROM users WHERE email='$sql_email' AND id !=".login_id);
	if(mysql_num_rows($result)>0)
	    throwError("Email already in system");
	$data = array('id' => login_id, 'name' => alphaNum($_POST['name']), 'email' => $_POST['email']);
	updateSQl('users', $data);
	authUser(login_id);
	redirect("Account information updated");
}