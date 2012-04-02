<?php
session_start();
require_once('inc/config.php');
require_once('inc/connect.php');
require_once('inc/init.php');
require_once('inc/lib/core.php');
require_once('inc/lib/model.php');

$uri = substr($_SERVER['REQUEST_URI'],3);

$ex = explode("/", $uri);
$count = count($ex);
if($count != 3)
    throwError("Invalid URL");

$linkId = $ex[0];
$key = $ex[1];

if(!is_numeric($linkId))
    throwError("Invalid item id");

$sql_key = cleanSQL($key);
$result = mysql_query("SELECT * FROM links WHERE id=$linkId AND hash='$sql_key' LIMIT 1");
while($row = mysql_fetch_array($result)){
    mysql_query("UPDATE links SET count=count+1 WHERE id=$linkId");

    if(!validLink($row))
	throwError("Link expired");

    $file = getFile($row['item_id']);
    if(!$file)
	throwError("File missing");

    openFile($file);
}
throwError("Link unavailable");