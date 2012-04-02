<?php
session_start();
require_once('inc/config.php');
require_once('inc/connect.php');
require_once('inc/init.php');
require_once('inc/lib/core.php');
require_once('inc/lib/model.php');

$ex = explode("/", $_SERVER['REQUEST_URI']);
if(count($ex) != 4)
    throwError("Invalid URL");

$page = $ex[1];
$pages = array("download", "zip","slideshow","image");
if(!in_array($page, $pages))
    throwError("Invalid URL");

$id = $ex[2];
$files = array();
if(!is_numeric($id)){
    $id = cleanURL($id);
    $files = explode("-", $id);
    if(count($files)==0)
	throwError("Invalid ID");
    foreach($files as $file){
	if(!is_numeric($file))
	    throwError("Invalid ID");
    }
}

$name = cleanURL($ex[3]);
if(empty($name)||file_extension($name) == "")
    throwError("Invalid name");

if($page == "download"||$page=="image"){
    $file = getFile($id);
    if(!$file)
	throwError("File does not exist");

    if(!permission($file['root_id'],"view", false))
	throwError("Permission Denied");


    $data = array();
    $data['user_id']=login_id;
    $data['log_id']=LOG_ID;
    
    if($page == "download"){
	$result = mysql_query("SELECT * FROM video_map WHERE file_id=$id");
	while($row = mysql_fetch_array($result)){
	    mysql_query("UPDATE video_map SET count=count+1 WHERE id=$row[id]");
	    $data['video_id'] = $row['id'];
	    insertSQL('video_stats', $data);
	}
    }
    if($page == "image"){
	$type = 's';
	if($name=="l.jpg"){
	    $type = 'l';
	    $result = mysql_query("SELECT * FROM photo_map WHERE file_id=$id");
	    while($row = mysql_fetch_array($result)){
		$data['photo_id']=$row['id'];
		insertSQL('photo_stats', $data);
		mysql_query("UPDATE photo_map SET count=count+1 WHERE file_id=$row[id]");
	    }
	}
	$file['path'] = $file['path']."_$type.jpg";
    }
    openFile($file);
}
elseif($page == "slideshow"){    
    $perms=getPermissions("view");
    if(count($perms)==0)
	throwError("Invalid Permission");
    $sql_perms = implode(",",$perms);
    
    echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
    echo '<playlist version="1" xmlns="http://xspf.org/ns/0/">'."\n";
    echo "\t".'<trackList>'."\n";
    foreach($files as $id){
	if(!is_numeric($id))
	    continue;
	$result = mysql_query("SELECT * FROM files WHERE id=$id AND root_id IN($sql_perms)");
	while($row = mysql_fetch_array($result)){
	    echo "\t\t<track>\n";
	    echo "\t\t\t<title>$row[name]</title>\n";
	    echo "\t\t\t<location>/image/$row[id]/l.jpg</location>\n";
	    echo "\t\t</track>\n";
	}
    }
    echo "\t".'</trackList>'."\n";
    echo '</playlist>'."\n";
    
}
elseif($page == "zip"){
    require_once('inc/lib/zip.php');
    $zipFile = TMP_PATH."/$id.zip";
    exec("rm $zipFile");
    $file = array();
    $file['exten'] = "zip";
    $file['path'] = $zipFile;
    
    if(count($files) > 0){
	$perms = getPermissions("view");
	if(count($perms)==0)
	    throwError("No drive permissions");
	$sql_perms = implode(",",$perms);
	$sql_files = implode(",",$files);
	if(!file_exists($zipFile)){
	    $sql_name = reset(explode(".zip", $name));
	    $zipper = new Zipper();
	    $zipper->open($zipFile, ZIPARCHIVE::CREATE);
	    $zipper->addFiles($sql_files, $sql_perms, $sql_name);
	    $zipper->close();
	}
	$file['name'] = $name;
    }
    else{
	$folder = getFolder($id);
	if(!$folder)
	    throwError("Folder does not exist");

	if(!permission($folder['root_id'], "view", false))
	    throwError("Permission Denied");
	
	$file['name'] = $folder['name'].".zip";
	
	if(!file_exists($zipFile)){
	    $zipper = new Zipper();
	    $zipper->open($zipFile, ZIPARCHIVE::CREATE);
	    $zipper->addDir($folder);
	    $zipper->close();
	}
    }
    openFile($file);
}