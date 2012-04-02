<?php
$db_fail=false;
$con = mysql_connect($config_db_host,$config_db_username,$config_db_password);
if (!$con){
	$db_fail=true;
	header("Location: error.php?id=3");
	exit;
}
if($db_fail==false){
	mysql_select_db($config_db_name, $con);
}

function requireLocal(){
	if($_SERVER['REMOTE_ADDR']!="67.166.144.119"){
		header("Location: error.php?id=5");
		exit;
	}
}