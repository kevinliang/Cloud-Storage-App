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
	$query = "SELECT * FROM folders, files, video_map WHERE ";
	$query .= "files.root_id IN ($sql_permissions) AND video_map.file_id=files.id ";
	$query .= "AND files.root_id=folders.id AND folders.user_id=".$value." ORDER BY video_map.id DESC";
	break;
    case 'drive':
	if(!in_array($value, $permissions))
	    throwError("Permission denied");
	$query = "SELECT * FROM files, video_map WHERE video_map.file_id=files.id ";
	$query .= "AND files.root_id=$value ORDER BY video_map.id DESC";
	break;
    case 'length':
	$min = null;
	$max = null;
	if($value == 2)
	    $min = 1200;
	elseif($value == 1){
	    $min = 300;
	    $max = 1200;
	}
	else
	    $max = 300;
	$query = "SELECT * FROM files, video_map WHERE video_map.file_id=files.id ";
	if($min)
	    $query .= "AND video_map.playtime>=$min ";
	if($max)
	    $query .= "AND video_map.playtime<$max ";
	$query .= "AND files.root_id IN ($sql_permissions) ORDER BY video_map.id DESC";
	break;
    case 'quality':
	$min = null;
	$max = null;
	if($value == 3)
	    $min = 720;
	elseif($value == 2){
	    $max = 720;
	    $min = 480;
	}
	elseif($value == 1){
	    $min = 240;
	    $max = 480;
	}
	else
	    $max = 240;
	$query = "SELECT * FROM files, video_map WHERE video_map.file_id=files.id ";
	if($min)
	    $query .= "AND video_map.res_y>=$min ";
	if($max)
	    $query .= "AND video_map.res_y<$max ";
	$query .= "AND files.root_id IN ($sql_permissions) ORDER BY video_map.id DESC";
    break;
    case 'type':
	$query = "SELECT * FROM files, video_map WHERE video_map.file_id=files.id ";
	$query .= "AND files.root_id IN ($sql_permissions) AND files.exten='$value' ORDER BY video_map.id DESC";
	break;
    case 'recent':
	$query = "SELECT DISTINCT video_map.id, files.name as name, video_map.file_id as file_id, files.size as size, files.exten as exten, ";
	$query .= "video_map.playtime as playtime, video_map.bitrate as bitrate, video_map.res_x as res_x, video_map.res_y as res_y ";
	$query .= "FROM video_stats, files, video_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND video_map.file_id=files.id AND video_stats.video_id=video_map.id AND video_stats.user_id=".login_id;
	$query .= " ORDER BY video_stats.id DESC";
	break;
    case 'popular':
	$query = "SELECT * FROM files, video_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND video_map.file_id=files.id AND video_map.count>0 ORDER BY video_map.count DESC";
	break;
    default:
	$type = "all";
	$query = "SELECT * FROM files, video_map WHERE files.root_id IN ($sql_permissions) ";
	$query .= "AND video_map.file_id=files.id ORDER BY video_map.id DESC";
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
    echo "<a target='_blank' href='zip/$implode_files/videos-$type-$value.zip' class='button button-download'>Download All ($count)</a>";
}

echo "<ul class='file_list'>";

foreach($rows as $row){
    $formatSize = formatSize($row['size']);
    $url  = "/download/$row[id]/".cleanURL($row['name']);
    $name = cleanName(substr($row['name'], 0, 30));
    $length = format_time($row['playtime']);
    $rate = round($row['bitrate']/1000);
    
    $link = $url;
    if(in_array($row['exten'],$GLOBALS['video_extens'])){
	$video_type = "html5_video";
	if(in_array($row['exten'],array("flv","f4v","mp4")))
	    $video_type = "flash_video";
	$link = "javascript:display_media($row[file_id],\"".cleanName($row['name'])."\", \"$video_type\");";
    }
    
    echo "<li class='ui-corner-all media_video_item' id='file_$row[id]'><div class='file_container'>";
    echo "<div class='fright'>$formatSize <br /> $rate KB/s <br /> $length <br /> $row[res_x]x$row[res_y]</div>";
    echo "<a href='$link'>";
    echo "<img class='img_thumbnail' src=\"?page=image&file_id=$row[file_id]&type=video\">";
    echo "</a>";
    
    echo "<h4><a href='$link'><span class='file_name'>$name</span></a></h4>";
    echo "<a title='Public Link' href='javascript:link($row[file_id]);'><img src='src/img/icons/drive_link.png'></a> ";
    echo "<a title='Download File' target='_blank' href=\"$url\"><img src='src/img/icons/drive_disk.png'></a> ";
    if(in_array($row['exten'],$GLOBALS['video_extens'])){
	echo "<a href='$link' title='Play Video'><img src='src/img/icons/film.png' /></a> ";
    }
    echo "<div class='clearboth'></div>";
    echo "</div></li>";
}
echo "</ul><div class='clearboth'>";