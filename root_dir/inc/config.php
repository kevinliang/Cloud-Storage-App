<?php
/*
	I had to remove the passwords in this file!
*/

if(!defined('APPLICATION_ENV'))
	define('APPLICATION_ENV', getenv('APPLICATION_ENV'));

$config_db_host="localhost";
$config_email_username="noreply@clouthe.com";
$config_email_password="XXXX";

if(APPLICATION_ENV == "development"){
	$config_db_username="clouthe_dev";
	$config_db_password="XXXX";
	$config_db_name="clouthe_dev";
}
elseif(APPLICATION_ENV == "stage"){
	$config_db_username="clouthe_stage";
	$config_db_password="XXXX";
	$config_db_name="clouthe_stage";
}
else{
	$config_db_username="clouthe_app";
	$config_db_password="XXX";
	$config_db_name="clouthe_app";
}