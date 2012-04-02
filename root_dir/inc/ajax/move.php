<?php
if(empty($_POST['from']))
    throwError('Invalid from');
if(empty($_POST['to']))
    throwError('Invalid to');
if(empty($_POST['isDir']))
    throwError('Invalid isDir');

$to = getFolder($_POST['to']);
if(!$to)
    throwError('To does not exist');

if($_POST['isDir'] == "true"){
    $from = getFolder($_POST['from']);
    if(!$from)
	throwError('From does not exist');
    if($from['parent_id'] == $to['id'])
	redirect("#NOCHANGE#");
    if(!permission($from['id'],"view"))
	throwError('From permission denied');
    if(!permission($to['id'],"upload"))
	throwError("To permission denied");
    if($from['user_id'] != $to['user_id']){
	$remaining = getRemainingSpace($to['user_id']);
	if($from['size'] > $remaining)
	    throwError("Insufficient disk space in <b>$to[name]</b>");
    }

    $name = getNewFolder($from['name'], $to);
    $data = array('id'=>$from['id']);
    $data['name'] = $name;
    $data['parent_id'] = $to['id'];
    $data['user_id'] = $to['user_id'];
    $data['root_id'] = $to['root_id'];
    updateSQL('folders',$data);
}
else{
    $from = getFile($_POST['from']);
    $fromFolder = getFolder($from['folder_id']);
    if(!$from)
	throwError('From does not exist');
    if($from['folder_id'] == $to['id'])
	redirect("#NOCHANGE#");
    if(!permission($from['folder_id'],"view"))
	throwError('From permission denied');
    if(!permission($to['id'],"upload"))
	throwError("To permission denied");
    if($fromFolder['user_id'] != $to['user_id']){
	$remaining = getRemainingSpace($to['user_id']);
	if($from['size'] > $remaining)
	    throwError("Insufficient disk space in <b>$to[name]</b>");
    }
    $name = getNewFile($from['name'], $to);
    $data = array('id'=>$from['id']);
    $data['name'] = $name;
    $data['folder_id'] = $to['id'];
    $data['root_id'] = $to['root_id'];
    updateSQL('files',$data);
}

require_once('inc/lib/import.php');
$size = getFolderSize($from['root_id']);
if($from['root_id'] != $to['root_id'])
    $size = getFolderSize($to['id']);

redirect("success");