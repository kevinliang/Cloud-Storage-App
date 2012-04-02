<h2>Drives</h2>
<ul class='drive_list'>
<?php
$drives = getPermissions("view");
$count = count($drives);
if($count == 0)
    echo "<p>You do not have any drives, <a href='javascript:load_page(\"settings\");'>go create one</a>.</p>";
else{
    $sql_drives = implode(",", $drives);
    $count = 0;
    $result=mysql_query("SELECT * FROM folders WHERE id IN ($sql_drives) AND parent_id=0 ORDER BY name");
    while($row=mysql_fetch_array($result)){
	if(permission($row['id'],"view",true)){
	    echo "<li id='dir_$row[id]'>";

	    echo "<span class='fright'>";
	    echo formatSize($row['size'])." ";
	    
	    if($row['token'])
		echo "<a title='View as public drive' href='?page=public&drive=$row[id]&key=$row[token]' target='_blank'><img src='src/img/icons/drive_web.png'></a> ";
	    if(permission($row['id'],"edit"))
		echo "<a title='Create new folder' href='javascript:createDir($row[id]);'><img src='src/img/icons/folder_add.png'></a> ";
	 
	    echo "</span>";

	    echo "<a href='javascript:toggleFolder($row[id]);'> ";
	    if($row['name'] == "recyclebin")
		echo "<img class='icon drive_icon_close' src='src/img/icons/drive_burn.png'>";
	    elseif($row['token'])
		echo "<img class='icon drive_icon_close' src='src/img/icons/drive_web.png'>";
	    elseif($row['user_id']!=login_id)
		echo "<img class='icon drive_icon_close' src='src/img/icons/drive_user.png'>";
	    else
		echo "<img class='icon drive_icon_close' src='src/img/icons/drive_network.png'>";
	    echo "<img class='icon drive_icon_open hidden' src='src/img/icons/drive.png'> ";
	    if($row['user_id']!=login_id){
		$owner = getUser($row['user_id']);
		echo " <b>$owner[name]</b> ";
	    }
	    echo " $row[name] </a></li> \n";
	}
    }
}
echo "<ul>";