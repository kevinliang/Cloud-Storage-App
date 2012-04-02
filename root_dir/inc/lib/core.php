<?php
function generateNow(){
    return date("l F j, Y - h:i:s A");
}
function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function file_extension($filename){
    return strtolower(end(explode(".",end(explode("/",$filename)))));
}
function alphaNum($string){
    return trim(preg_replace("/[^a-zA-Z0-9\s]/", "", $string));
}
function superClean($string){
    return ucwords(strtolower(trim(ereg_replace("[^A-Za-z0-9 ]", "", $string))));
}
function cleanFileName($string){
    return str_replace("/", "", $string);
}
function cleanName($string){
    $remove = array("'",'"');
    return str_replace($remove, "", $string);
}
function cleanSQL($string){
    return mysql_escape_string(strip_tags(trim($string)));
}
function randomString($string = null){
    return hash('sha512', BLOWFISH_STRING.rand(100000,999999)*BLOWFISH_NUMBER.$string);
}
function secureHash($string){
    return hash('sha512', BLOWFISH_STRING.$string.BLOWFISH_NUMBER);
}
function isIE(){
    return (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false));
}
function cleanURL($string){
    $result = strtolower($string);
    $result = preg_replace("/[^a-z0-9\s-.]/", "", $result);
    $result = trim(preg_replace("/[\s-]+/", " ", $result));
    $result = preg_replace("/\s/", "-", $result);
    return $result;
}
function getMime($path){
    $exten = file_extension($path);
    if($exten == "webm")
	$mime = "video/webm";
    elseif($exten == "ogv" || $exten == "ogv" || $exten == "ogg")
	$mime = "video/ogg";
    elseif($exten == "mp4")
	$mime = "video/mp4";
    else
	$mime = mime_content_type($path);
    return $mime;
}
function displayPermission($level){
    switch($level){
	case 1:
	    return "View";
	case 2:
	    return "View & Upload";
	case 3:
	    return "View, Upload & Edit";
    }
}
function validLink($row){
    if($row['max'] > 0 && $row['count'] >= $row['max'])
	return false;
    if($row['expires'] != null && strtotime($row['expires']) < time())
	return false;
    return true;
}
function openFile($file){
    header('Content-type: '.getMime($file['name'].".".$file['exten']));
    header('Content-Disposition: attachment; filename="'.$file['name'].'"');
    header('X-Sendfile: '.$file['path']);
    exit;
}
function format_time($seconds){
    if(!is_numeric($seconds))
	return false;
    $min=floor($seconds/60);
    $sec=round($seconds%60,2);
    if($sec<=9)
	$sec="0$sec";
    return "$min:$sec";
}
function convert_timestamp($stamp, $change_hour=0){
    $time = strtotime($stamp);
    $display_ts = date('g:iA n/j/Y', $time);
    if($change_hour!=0)
	$display_ts = date('Y-m-d H:i:s', $time+$change_hour*3600);
    return $display_ts;
}
function formatSize($size){
    if(!is_numeric($size))
	    return false;
    if($size>1000000000){$num=$size/1000000000;$num=round($num,2);$value="$num GB";}
    elseif($size>1000000){$num=$size/1000000;$num=round($num);$value="$num MB";}
    elseif($size>1000){$num=$size/1000;$num=round($num);$value="$num KB";}
    else{$value="$size B";}
    return $value;
}
function display_msg(){
    if(isset($_SESSION['msgs'])&&count($_SESSION['msgs'])>0){
	foreach($_SESSION['msgs'] as $msg){
	    if($msg['type']=="error"){$icon="cancel";}
	    elseif($msg['type']=="note"){$icon="information";}
	    elseif($msg['type']=="warning"){$icon="error";}
	    else{$icon="accept";}
	    echo "<div class='message $msg[type]'><div class='message_i'>";
	    echo "<a class='close_msg fright'><img src='src/img/icons/cross.png' /></a> ";
	    echo "<img src='src/img/icons/$icon.png' > $msg[content]</div></div>";
	}
	unset($_SESSION['msgs']);
    }
}
function redirect($uri){
    if(isset($_GET['ajax'])||AJAX_CALL)
	die($uri);
    header("Location: $uri");
    exit;
}
function actionToLevel($action){
    switch($action){
	case 'view':
	    return 1;
	case 'upload':
	    return 2;
	case 'edit':
	    return 3;
    }
}
function load_page($name){
    define("load_page",$name);
    function display_content(){
	echo "<div id='session_token' class='hidden'>".SESSION_TOKEN."</div>";
	if(file_exists("inc/page/".load_page."/content.php"))
	    include("inc/page/".load_page."/content.php");
	else
	    include("inc/page/index/content.php");
    }
    $site_url=SITE_URL;
    if(file_exists("inc/page/".load_page."/head.php"))
	include("inc/page/".load_page."/head.php");
    else{
	if(!file_exists("inc/page/".load_page."/content.php"))
	    addMsg("error","Page not found");
	if(file_exists("inc/templates/default/$name.php"))
	    include("inc/templates/default/$name.php");
	else
	    include("inc/templates/default/index.php");
    }
}