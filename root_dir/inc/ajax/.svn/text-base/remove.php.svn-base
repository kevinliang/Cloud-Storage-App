<?php
if(empty($_POST['id']))
    throwError("error-Invalid ID");
if(!isset($_POST['isDir']))
    throwError("error-Invalid isDir");


function deleteFiles($folders){
    $count = 0;
    $sql_folders = implode(",",$folders);
    $result = mysql_query("SELECT * FROM files WHERE folder_id IN ($sql_folders)");
    while($row = mysql_fetch_array($result)){
	$row['path'] = BUCKET_PATH."/".$row['bucket_id'].'/'.$row['id'];
	exec("rm ".escapeshellarg($row['path']));
	exec("rm ".escapeshellarg($row['path'])."_*");
	mysql_query("DELETE FROM files WHERE id=$row[id]");
	$count++;
    }
    mysql_query("DELETE FROM folders WHERE id IN ($sql_folders)");
    return $count;
}

$fileCount = 1;
$folderCount = 0;
$recycleBinId = getDriveIdByName('recyclebin');

if($_POST['isDir'] == "true"){
    $folder = getFolder($_POST['id']);
    if(!$folder)
	throwError("No folder");
    if(!permission($folder['id'],"edit"))
	throwError("Folder permission denied");
    if($folder['root_id'] != $recycleBinId)
	throwError("Non recycle bin folder");
    $folders = getChildFolders($folder['id'], array());
    $folderCount = count($folders);
    $fileCount = deleteFiles($folders);
    $size = getFolderSize($folder['root_id']);
}
else{
    $file = getFile($_POST['id']);
    if(!$file)
	throwError("No file");
    if(!permission($file['folder_id'],"edit"))
	throwError("File permission denied");
    if($file['root_id'] != $recycleBinId)
	throwError("Non recycle bin file");
    exec("rm ".escapeshellarg($file['path']));
    exec("rm ".escapeshellarg($file['path'])."_*");
    mysql_query("DELETE FROM files WHERE id=$file[id]");
    $size = getFolderSize($file['root_id']);
}
redirect("Deleted $fileCount files and $folderCount folders");
