<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/var/framework');

require_once '/var/framework/Zend/Loader.php';
require_once 'inc/config.php';
require_once 'inc/connect.php';

//requireLocal();

Zend_Loader::loadClass('Zend_Mail');
Zend_Loader::loadClass('Zend_Mail_Transport_Smtp');

$config = array();
$config['auth'] = 'login';
$config['username'] = $config_email_username;
$config['password'] = $config_email_password;
$config['ssl'] = 'tls';
$config['port'] = 587;

$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

$count = 0;
$result = mysql_query("SELECT * FROM emails WHERE sent = 0 ORDER BY id");
while($row = mysql_fetch_array($result)){
	$mail = new Zend_Mail();
	$mail->setBodyText($row['message']);
	$mail->setFrom($config_email_username, 'ClouThe Service');
	$mail->addTo($row['email'], $row['name']);
	$mail->setSubject($row['subject']);
	$mail->send($transport);
	mysql_query("UPDATE emails SET sent = 1 WHERE id = $row[id]");
	$count ++;
}
echo $count." emails sent";