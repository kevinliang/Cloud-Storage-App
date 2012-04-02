<?php
if(empty($_POST['id']))
    throwError("error-Invalid ID");
if(empty($_POST['name']))
    throwError("error-Invalid Name");
if(!isset($_POST['isDir']))
    throwError("error-Invalid isDir");

if($_POST['isDir'] == "true"){
    $folder = getFolder($_POST['id']);
    if(!$folder)
	throwError("error-Folder does not exist");

    if(!permission($folder['id'],"view"))
	throwError("error-Permission Denied");

    if($_POST['name'] == $folder['name'])
	redirect("#NOCHANGE#");

    $parentFolder = getFolder($folder['parent_id']);
    $name = getNewFolder($_POST['name'], $parentFolder);
    $data = array('id'=>$folder['id']);
    $data['name'] = $name;
    updateSQL('folders',$data);
}
else{
    $file = getFile($_POST['id']);
    if(!$file)
	throwError("error-File does not exist");

    if(!permission($file['folder_id'],"view"))
	throwError("error-Permission Denied");

    if($_POST['name'] == $file['name'])
	redirect("#NOCHANGE#");
    
    $name = getNewFile($_POST['name'], getFolder($file['folder_id']));
    $data = array('id'=>$file['id']);
    $data['name'] = $name;
    $data['exten'] = file_extension($name);
    updateSQL('files',$data);
}
redirect($name);