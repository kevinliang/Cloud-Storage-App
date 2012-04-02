<?php
$type = null;
$value = null;
if(!empty($_POST['type']))
    $type = cleanSQL($_POST['type']);
if(!empty($_POST['value']))
    $value = cleanSQL($_POST['value']);

$permissions = getPermissions("view");
if(count($permissions) == 0)
    redirect("<p>You do not have any drives</p>");
$sql_permissions = implode(",",$permissions);

switch($type){
    case 'user':
	$query = "SELECT * FROM folders, files, doc_map WHERE ";
	$query .= "files.root_id IN ($sql_permissions) AND doc_map.file_id=files.id ";
	$query .= "AND files.root_id=folders.id AND folders.user_id=".$value." ORDER BY doc_map.id DESC";
	break;
    case 'drive':
	if(!in_array($value, $permissions))
	    throwError("Permission denied");
	$query = "SELECT * FROM files, doc_map WHERE doc_map.file_id=files.id ";
	$query .= "AND files.root_id=$value ORDER BY doc_map.id DESC";
	break;
    case 'type':
	$query = "SELECT * FROM files, doc_map WHERE doc_map.file_id=files.id ";
	$query .= "AND files.root_id IN ($sql_permissions) AND files.exten='$value' ORDER BY doc_map.id DESC";
	break;
    case 'recent':
	$query = "SELECT DISTINCT doc_map.id, files.name as name, doc_map.file_id as file_id ";
	$query .= "FROM doc_stats, files, doc_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND doc_map.file_id=files.id AND doc_stats.doc_id=doc_map.id AND doc_stats.user_id=".login_id;
	$query .= " ORDER BY doc_stats.id DESC";
	break;
    case 'popular':
	$query = "SELECT * FROM files, doc_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND doc_map.file_id=files.id AND doc_map.count>0 ORDER BY doc_map.count DESC";
	break;
    default:
	$type = "all";
	$query = "SELECT * FROM files, doc_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND doc_map.file_id=files.id ORDER BY doc_map.id DESC";
}
if(!$value)
    $value = "default";

$count = 0;
$rows = array();
$files = array();
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){
    $rows[]=$row;
    $files[]=$row['file_id'];
    $count++;
}
 
if($count > 1){
    $implode_files=implode("-", $files);
    echo "<a target='_blank' href='zip/$implode_files/docs-$type-$value.zip' class='button button-download'>Download All ($count)</a>";
}

echo "<ul class='file_list'>";

foreach($rows as $row){
    $url  = "/download/$row[file_id]/".cleanURL($row['name']);
    $name = substr($row['name'], 0, 16);
    echo "<li class='ui-corner-all thumb_file_list' id='file_$row[file_id]'><div class='file_container'>";
    echo "<span class='file_info ui-corner-br'>";
    echo "<a title='Public Link' href='javascript:link($row[file_id]);'><img src='src/img/icons/drive_link.png'></a> ";
    echo "<a title='Download File' target='_blank' href=\"$url\"><img src='src/img/icons/drive_disk.png'></a> ";
    echo "<a title='Download File' target='_blank' href=\"$url\"><span class='file_name'>$name</span></a></span>";

    echo " <a rel='framebox' title=\"$row[name]\" href=\"?page=doc&id=$row[id]\">";
    echo "<img style='height:141px;width:111px' class='img_thumbnail' src=\"?page=image&file_id=$row[file_id]&type=doc\"></a> ";
    echo "</div></li>";
}
echo "</ul><div class='clearboth'>";