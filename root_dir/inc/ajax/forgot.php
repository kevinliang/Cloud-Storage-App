<?php
sleep(2);
if(empty($_POST['email']))
	redirect("error-email");
if(empty($_POST['recaptcha_response_field']))
	redirect("error-recaptcha");
$resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
if (!$resp->is_valid)
	redirect("error-recaptcha");

$email = strtolower(trim($_POST['email']));
$sql_email = mysql_escape_string($email);
$result = mysql_query("SELECT * FROM users WHERE email='$sql_email' LIMIT 1");
if(mysql_num_rows($result)==0)
	redirect("error-none");
while($row = mysql_fetch_array($result)){
	$key = randomString();
	mysql_query("UPDATE users SET reset_key = '$key' WHERE id='$row[id]'");
	$subject = "Request to reset password";
	$content = "To reset your password, go to: ".FULL_URL."?reset_password=$row[id]&key=$key";
	sendEmail($row['id'], $subject, $content);
	redirect("success");
}