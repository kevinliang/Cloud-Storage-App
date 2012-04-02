<?php
$folderId = $_POST['folder_id'];

if(empty($folderId))
    redirect("error-No folder ID");
if(!is_numeric($folderId))
    redirect("error-Invalid folder ID");

$folder = getFolder($folderId);
if(!$folder)
    redirect("error-Folder not found");

$level = getPermissionLevel($folderId);
$recycleBinId = getDriveidByName('recyclebin');

echo "<ul class='dir_list'>";
$result = mysql_query("SELECT * FROM folders WHERE parent_id='$folderId' ORDER BY name");
while($row = mysql_fetch_array($result)){
    displayFolder($row, $recycleBinId, $level);
}
echo "</ul>";
echo "<ul class='file_list'>";
$result = mysql_query("SELECT * FROM files WHERE folder_id=$folderId ORDER BY name");
while($row = mysql_fetch_array($result)){
    displayFile($row, $recycleBinId, $level);
}
echo "<div class='clearboth'></div></ul>";