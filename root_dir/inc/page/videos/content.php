<?php
$permissions = getPermissions("view");
$sql_permissions = null;
if($permissions)
    $sql_permissions = implode(",", $permissions);
?>
<h2>videos</h2>
<table>
    <tr><td style="width:300px">
	    <div class="box">
		<h3><img src="src/img/icons/film.png" /> All Videos</h3>
		<a href="javascript:show_media('videos');" class="button button-view">View All</a>
		<a href="javascript:show_media('videos','popular');" class="button button-view">Popular</a>
		<a href="javascript:show_media('videos','recent');" class="button button-view">Recent</a>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/user.png" /> Users</h3>
		<?php
		    $query = "SELECT DISTINCT folders.user_id FROM video_map, files, folders ";
		    $query .= "WHERE video_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $query .= "AND folders.id = files.root_id";
		    $result = mysql_query($query);
		    $num_users = mysql_num_rows($result);
		    $height = min($num_users*14+15,200);
		?>
		<div style="height:<?php echo $height;?>px" class="media_side">
		    <ul>
		    <?php
		    while($row = mysql_fetch_array($result)){
			$user = getUser($row['user_id']);
			echo "<li><a href='javascript:show_media(\"videos\",\"user\",$user[id],\"$user[name]\");'>$user[name] ($user[email])</a></li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/clock_play.png" /> Length</h3>
		<div style="height:57px" class="media_side">
		    <ul>
		    <?php
		    $lengths = array("Short (0-5 min)", "Medium (5-20 min)", "Long (20+ min)");
		    foreach($lengths as $key => $length){
			  echo "<li><a href='javascript:show_media(\"videos\",\"length\",$key,\"$length\");'>$length</a></li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	    <div  class="box">
		<h3><img src="src/img/icons/monitor.png" /> Quality</h3>
		<div style="height:71px" class="media_side">
		    <ul>
			 <?php
			    $resolutions = array("Low (< 240p)", "Medium (240-480p)", "DVD (480-720p)", "HD (> 720p)");
			    foreach($resolutions as $key=> $resolution){
				 echo "<li><a href='javascript:show_media(\"videos\",\"quality\",$key,\"$resolution\");'>$resolution</a></li>";
			    }
			 ?>
		    </ul>
		</div>
	    </div>
	     <div class="box">
		<h3><img src="src/img/icons/cd.png" /> Format</h3>
		<?php
		    $query = "SELECT DISTINCT files.exten FROM video_map, files ";
		    $query .= "WHERE video_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $result = mysql_query($query);
		    $count = mysql_num_rows($result);
		    $height = min($count*14+15,200);
		?>
		<div style="height:<?php echo $height;?>px" class="media_side">
		    <ul>
		    <?php
		    while($row = mysql_fetch_array($result)){
			echo "<li><a href='javascript:show_media(\"videos\",\"type\",\"$row[exten]\",\"$row[exten]\");'>$row[exten]</li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/drive.png" /> Drives</h3>
		<?php
		    $query = "SELECT DISTINCT files.root_id FROM video_map, files ";
		    $query .= "WHERE video_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
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
			    echo "<li><a href='javascript:show_media(\"videos\",\"drive\",$row[root_id],\"$row2[name]\");'>$row2[name] ($row2[email])</a></li>";
			}
		    }
		    ?>
		    </ul>
		</div>
	    </div>
    </td><td>
	<div class="box">
	    <h3><img src="src/img/icons/film.png" /> Videos <span class="title_media" id="title_videos"></span></h3>
	    <div class='content_media' id="content_videos">
		<p>Welcome to the videos app, please select from a category on the left to view videos.</p>
	    </div>
	</div>
    </td></tr>
</table>