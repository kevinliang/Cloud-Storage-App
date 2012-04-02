<?php
function displayFolder($row, $recycleBinId = null, $level = 1){
    echo "<li class='ui-corner-all' id='dir_$row[id]'>";
    echo "<span class='fright'>";
	echo formatSize($row['size'])." ";
	if($level >= 3){
	    echo "<a title='Create new folder' href='javascript:createDir($row[id]);'><img src='src/img/icons/folder_add.png'></a> ";
	    echo "<a title='Rename Folder' href='javascript:rename($row[id]);'><img src='src/img/icons/folder_edit.png'></a> ";
	}
	if($row['root_id']==$recycleBinId)
	    echo "<a title='Delete Folder' href='javascript:remove($row[id], true);'><img src='src/img/icons/delete.png'></a>";
	elseif($level >= 3)
	    echo "<a title='Recycle Folder' href='javascript:move($row[id],$recycleBinId, true);'><img src='src/img/icons/delete.png'></a>";
	if($level >= 2)
	    echo "<a title='Upload Files' href='javascript:showUpload($row[id],\"$row[name]\");'><img src='src/img/icons/page_add.png'></a> ";
	echo " <a title='Download Folder' target='_blank' href=\"zip/$row[id]/".alphaNum($row['name']).".zip\"><img src='src/img/icons/folder_go.png' /></a>";
	echo " <a title='Refresh Folder' href='javascript:toggleFolder($row[id], true);'><img src='src/img/icons/arrow_refresh.png' /></a>";
	echo "</span>";
    echo "<a href='javascript:toggleFolder($row[id]);'><img class='icon folder_icon' src='src/img/icons/folder.png'> <span class='file_name'>$row[name]</span></a></li> \n";
}
function displayFile($row, $recycleBinId = null, $level = 1){
    $formatSize = formatSize($row['size']);
    $docFile = false;
    $imageFile = false;

    if(in_array($row['exten'], $GLOBALS['doc_extens'])){
	$result = mysql_query("SELECT * FROM doc_map WHERE file_id=$row[id]");
	while($doc = mysql_fetch_array($result)){
	    $docFile = true;
	    break;
	}
    }

    if(in_array($row['exten'],$GLOBALS['image_extens'])){
	$imageFile =true;
    }
    if($imageFile || $docFile){
	$image_class = 'thumb_file_list';
	$name = substr(trim($row['name']), 0 ,12);
    }
    else{
	$name = $row['name'];
	$image_class = 'normal_file_list';
    }
    echo "<li class='ui-corner-all $image_class' id='file_$row[id]'><div class='file_container'>";
    echo "<span class='file_info ui-corner-br'><span class='fright'><span class='file_size'>$formatSize</span> ";
    if($level >= 3){
	echo "<a title='Rename File' href='javascript:rename($row[id],$row[folder_id]);'><img src='src/img/icons/page_edit.png'></a>";
	if($row['root_id']==$recycleBinId)
	    echo "<a title='Delete File' href='javascript:remove($row[id], false);'><img src='src/img/icons/delete.png'></a>";
	else
	    echo "<a title='Recycle File' href='javascript:move($row[id],$recycleBinId, false);'><img src='src/img/icons/delete.png'></a>";
    }
    echo "</span>";

    $url = "/download/$row[id]/".cleanURL($row['name']);
    if(login_id != 0)
	echo "<a title='Public Link' href='javascript:link($row[id]);'><img src='src/img/icons/drive_link.png'></a> ";
    echo "<a title='Download File' target='_blank' href=\"$url\"><img src='src/img/icons/drive_disk.png'></a> ";

    if(in_array($row['exten'],$GLOBALS['video_extens'])){
	$video_type = "html5_video";
	if(in_array($row['exten'],array("flv","f4v","mp4")))
	    $video_type = "flash_video";
	echo " <a title='Play Video' href='javascript:display_media($row[id],\"".cleanName($row['name'])."\", \"$video_type\");'>";
	echo "<img src='src/img/icons/film.png'>";
    }
    elseif(in_array($row['exten'],$GLOBALS['audio_extens'])){
	echo " <a title='Play Audio' href='javascript:display_media($row[id],\"".cleanName($row['name'])."\",\"audio\");'>";
	echo "<img src='src/img/icons/sound.png'>";
    }
    else
	echo "<a title='Download File' target='_blank' href=\"$url\">";

    echo " <span class='file_name'>$name</span></a>";
    echo "</span>";

    if($imageFile){
	echo " <a rel='lightbox' title=\"$row[name]\" href=\"?page=image&file_id=$row[id]\">";
	echo "<img class='img_thumbnail' src=\"?page=image&file_id=$row[id]&size=s\"></a> ";
    }
    if($docFile){
	echo " <a rel='framebox' title=\"$row[name]\" href=\"?page=doc&id=$doc[id]\">";
	echo "<img style='height:141px;width:111px' class='img_thumbnail' src=\"?page=image&file_id=$row[id]&type=doc\"></a> ";
    }

    echo "</div></li> \n";
}