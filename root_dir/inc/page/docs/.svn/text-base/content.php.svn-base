<?php
$permissions = getPermissions("view");
$sql_permissions = null;
if($permissions)
    $sql_permissions = implode(",", $permissions);
?>
<h2>Docs</h2>
<table>
    <tr><td style="width:300px">
	    <div class="box">
		<h3><img src="src/img/icons/page_white_text.png" /> All Docs</h3>
		<a href="javascript:show_media('docs');" class="button button-view">View All</a>
		<a href="javascript:show_media('docs','popular');" class="button button-view">Popular</a>
		<a href="javascript:show_media('docs','recent');" class="button button-view">Recent</a>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/user.png" /> Users</h3>
		<?php
		    $query = "SELECT DISTINCT folders.user_id FROM doc_map, files, folders ";
		    $query .= "WHERE doc_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
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
			echo "<li><a href='javascript:show_media(\"docs\",\"user\",$user[id],\"$user[name]\");'>$user[name] ($user[email])</a></li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	     <div class="box">
		<h3><img src="src/img/icons/page_white_office.png" /> Type</h3>
		<?php
		    $query = "SELECT DISTINCT files.exten FROM doc_map, files ";
		    $query .= "WHERE doc_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
		    $result = mysql_query($query);
		     $count = mysql_num_rows($result);
		    $height = min($count*14+15,200);
		?>
		<div style="height:<?php echo $height;?>px" class="media_side">
		    <ul>
		    <?php
		    while($row = mysql_fetch_array($result)){
			echo "<li><a href='javascript:show_media(\"docs\",\"type\",\"$row[exten]\",\"$row[exten]\");'>$row[exten]</li>";
		    }
		    ?>
		    </ul>
		</div>
	    </div>
	    <div class="box">
		<h3><img src="src/img/icons/drive.png" /> Drives</h3>
		<?php
		    $query = "SELECT DISTINCT files.root_id FROM doc_map, files ";
		    $query .= "WHERE doc_map.file_id = files.id AND files.root_id IN ($sql_permissions) ";
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
			    echo "<li><a href='javascript:show_media(\"docs\",\"drive\",$row[root_id],\"$row2[name]\");'>$row2[name] ($row2[email])</a></li>";
			}
		    }
		    ?>
		    </ul>
		</div>
	    </div>
    </td><td>
	<div class="box">
	    <h3><img src="src/img/icons/page_white_text.png" /> Docs <span class="title_media" id="title_docs"></span></h3>
	    <div class='content_media' id="content_docs">
		<p>Welcome to the docs app, please select from a category on the left to view docs.</p>
	    </div>
	</div>
    </td></tr>
</table>