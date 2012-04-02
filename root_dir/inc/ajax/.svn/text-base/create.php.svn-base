<?php
if(empty($_POST['id']))
    throwError("error-Invalid ID");
if(empty($_POST['name']))
    throwError("error-Invalid Name");
$folder = getFolder($_POST['id']);
if(!$folder)
    throwError("error-Folder does not exist");
if(!permission($folder['id'], "upload"))
    throwError("error-Permission Denied");

$name = getNewFolder($_POST['name'], $folder);
$data = array('name'=>$name);
$data['parent_id'] = $folder['id'];
$data['user_id'] = $folder['user_id'];
$data['root_id'] = $folder['root_id'];
insertSQL('folders',$data);
redirect("success");