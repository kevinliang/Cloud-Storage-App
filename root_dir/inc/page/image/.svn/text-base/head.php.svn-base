<?php
if(empty($_GET['file_id']))
    throwError("No file provided");
$file = getFile($_GET['file_id']);
if(!$file)
    throwError("File does not exist");

if(!permission($file['root_id'],"view", false))
    throwError("Permission Denied");

if(isset($_GET['type'])&&$_GET['type']=="doc"){
    $path = $file['path']."_doc";
    if(!file_exists($path))
	exit;
}
elseif(isset($_GET['type'])&&$_GET['type']=="video"){
     $path = $file['path']."_videothumb.jpg";
    if(!file_exists($path))
	exit;
}
else{
    $type = 's';
    if(empty($_GET['size']) || $_GET['size'] != 's'){
	$type = 'l';
	$result = mysql_query("SELECT * FROM photo_map WHERE file_id=$_GET[file_id]");
	while($row = mysql_fetch_array($result)){
	    $data = array();
	    $data['user_id']=login_id;
	    $data['photo_id']=$row['id'];
	    $data['log_id']=LOG_ID;
	    insertSQL('photo_stats', $data);
	    mysql_query("UPDATE photo_map SET count=count+1 WHERE file_id=$row[id]");
	}
    }

    $path = $file['path']."_$type.jpg";
}

header('Content-type: image/jpeg');
header('Content-Disposition: attachment; filename="'.$file['name'].'"');
header('X-Sendfile: '.$path);