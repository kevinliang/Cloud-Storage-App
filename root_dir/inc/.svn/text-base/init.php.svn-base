<?php
$version_number = "3.0";
$clouthe_about = "About ClouThe:\n\nClouThe is an easy, fun and powerful web app that allows users ";
$clouthe_about .= "to manage and share their files seamlessly on the web. By utilizing HTML5 and modern web technologies, ";
$clouthe_about .= "ClouThe offers a next-generation User Interface that feels just like any other desktop application. ";
$clouthe_about .= "With the use of Pods, Drag and Drop functionality, multi-file uploads, and HTML5 video, file management ";
$clouthe_about .= "is completely redesigned from the ground up. For more info about ClouThe, visit http://clouthe.com\n\n";


$GLOBALS['doc_extens'] = $doc_extens = array("pdf", "ps", "doc", "docx", "ppt", "pps", "pptx", "ppsx", "xls", "xlsx", "odt", "sxw", "odp", "sxi", "ods", "sxc", "txt", "rtf");
$GLOBALS['image_extens'] = $image_extens = array("jpg","jpeg","png","gif");
$GLOBALS['video_extens'] = $video_extens = array("flv","f4v","mp4","webm","m4v","ogg","ogv","ogm");
$GLOBALS['youtube_extens'] = $youtube_extens = array("mpg","mpeg","mpe","m1s","mp2v","m2v","m2s","avi","mov","qt","asf","asx","wmv","rm","rmvb","mp4","3gp","ogv","ogg","ogm","mkv","flv","f4v","webm");
$GLOBALS['audio_extens'] = $audio_extens = array("mp3");

$login_id=0;	
$login=null;
$session_id=null;
$session_token=null;

if(isset($_SESSION['login'])){
    $login=$_SESSION['login'];
    $login_id=$_SESSION['login_id'];
    if(isset($_SESSION['session_id'])){
	$session_id=$_SESSION['session_id'];
    }
    if(isset($_SESSION['session_token']))
	$session_token=$_SESSION['session_token'];
}

$ip=$_SERVER['REMOTE_ADDR'];
$user_agent=$_SERVER['HTTP_USER_AGENT'];
$url = $_SERVER['REQUEST_URI'];
$script=end(explode("/",$_SERVER["SCRIPT_NAME"]));

$ajax = false;
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
    $ajax = true;

$full_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].'/' : "http://".$_SERVER['SERVER_NAME'].'/';

mysql_query("INSERT INTO logs (url,ip,session_id) VALUES ('$url', '$ip','$session_id')");
$log_id = mysql_insert_id();

if(APPLICATION_ENV == "development")
    define("BUCKET_PATH", "/var/drives/wd/dev_buckets");
else if(APPLICATION_ENV == "stage")
    define("BUCKET_PATH", "/var/drives/wd/stage_buckets");
else
    define("BUCKET_PATH", "/var/drives/wd/buckets");

define("SCRIBD_URL", "https://api.scribd.com/api?method=");
define("SCRIBD_API", "1ik0u8x1fnbw4db98egs6");
define("SCRIBD_SECRECT", "sec-2pm1ay5qro653hycv3rhsdt7th");
define("BLOWFISH_STRING", "32g5f90rihdslfpmali23ur9p8fm223");
define("BLOWFISH_NUMBER", 3242435);
define("CLOUTHE_ABOUT", $clouthe_about);
define("VERSION_NUMBER",$version_number);
define("RECAPTCHA_PUBLIC_KEY","6Lf4YMQSAAAAAGGEcfrnnQeVw5tOHZRaAin4ifkF");
define("RECAPTCHA_PRIVATE_KEY","6Lf4YMQSAAAAAP_4YYt7Edf8FxKKGqI4gbCHv80w");
define("login",$login);
define("login_id",$login_id);
define("session_id", $session_id);
define("SESSION_TOKEN", $session_token);
define("AJAX_CALL",$ajax);
define("LOG_ID", $log_id);
define("FULL_URL",$full_url);
define("SITE_URL",$full_url);
define("TMP_PATH", __DIR__ . "/tmp");
define("MYSQL_TIMESTAMP", "Y-m-d H:i:s");