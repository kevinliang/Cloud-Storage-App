<?php

$name = null;
$result = mysql_query("SELECT * FROM photo_albums, photo_map WHERE photo_map.album_id = photo_albums.id AND photo_albums.user_id=".login_id." ORDER BY photo_albums.name DESC LIMIT 90");
while($row = mysql_fetch_array($result)){
    if($name != $row['name']){
		echo "</ul><div class='clearboth'> </div></li></ul><ul class='drive_list'><li><h3><div class='renameAlbum'><a href='javascript:renameAlbum(\"$row[album_id]\");' title='Rename Album'><img src='src/img/icons/picture_edit.png'></a></div><div id='album_$row[album_id]'>$row[name]</div></h3><ul class='file_list'>";
    }
    $name = $row['name'];
    echo "<li class='thumb_file_list'><a rel='lightbox' href='?page=image&file_id=$row[file_id]'><img src='?page=image&size=s&file_id=$row[file_id]' /></a></li>";
}
?>
<h2>Photos</h2>
<table>
    <tr><td style="width:300px">
	    <div class="box">
		<h3><img src="src/img/icons/pictures.png" /> All Photos</h3>
		<a href="javascript:show_media('photos');" class="button button-view">View All</a>
		<a href="javascript:show_media('photos','popular');" class="button button-view">Popular</a>
		<a href="javascript:show_media('photos','recent');" class="button button-view">Recent</a>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/user.png" /> Users</h3>
		<?php
		$query = "SELECT DISTINCT folders.user_id FROM photo_map, files, folders ";
		    $query .= "WHERE photo_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $query .= "AND folders.id = files.root_id";
		    $result = mysql_query($query);
		    $count = mysql_num_rows($result);
		    $height = min($count*14+15,200);
		?>
		<div style="height:<?php echo $height;?>px" class="media_side">
		    <ul>
		    <?php
		    
		    while($row = mysql_fetch_array($result)){
			$user = getUser($row['user_id']);
			echo "<li><a href='javascript:show_media(\"photos\",\"user\",$user[id],\"$user[name]\");'>$user[name] ($user[email])</a></li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	     <div class="box">
		<h3><img src="src/img/icons/calendar.png" /> Date</h3>
		<div class="media_side">
		    <ul>
		    <?php
		    $query = "SELECT DISTINCT photo_map.month FROM photo_map, files ";
		    $query .= "WHERE photo_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $query .= "AND month!='0000-00' ";
		    $query .= "ORDER BY photo_map.month DESC";
		    $result = mysql_query($query);
		    
		    $current_year = null;
		    while($row = mysql_fetch_array($result)){
			$year = substr($row['month'], 0, 4);
			if($current_year!=$year){
			    echo "</ul><h4>$year</h4><ul>";
			}
			$month = date('F', strtotime($row['month']."-00"));
			echo "<li><a href='javascript:show_media(\"photos\",\"date\",\"$row[month]\",\"$year - $month\");'>$month</a></li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/drive.png" /> Drives</h3>
		<?php
		    $query = "SELECT DISTINCT files.root_id FROM photo_map, files ";
		    $query .= "WHERE photo_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $result = mysql_query($query);
		    $count = mysql_num_rows($result);
		    $height = min($count*14+15,200);
		?>
		<div style="height:<?php echo $height;?>px" class="media_side">
		    <ul>
		    <?php
		  
		    while($row = mysql_fetch_array($result)){
			$result2 = mysql_query("SELECT * FROM users, folders WHERE folders.id=$row[root_id] AND folders.user_id=users.id");
			while($row2 = mysql_fetch_array($result2)){
			    echo "<li><a href='javascript:show_media(\"photos\",\"drive\",$row[root_id],\"$row2[name]\");'>$row2[name] ($row2[email])</a></li>";
			}
		    }
		    ?>
		    </ul>
		</div>
	    </div>
    </td><td>
	<div class="box">
	    <h3><img src="src/img/icons/pictures.png" /> Photos <span class="title_media" id="title_photos"></span></h3>
	    <div class='content_media' id="content_photos">
		<p>Welcome to the photos app, please select from a category on the left to view photos.</p>
	    </div>
	</div>
    </td></tr>
</table>