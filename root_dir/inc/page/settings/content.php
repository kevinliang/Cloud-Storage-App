<h2>Control Panel</h2>
<div class='box'>
    <h3><img class='icon' src="src/img/icons/drive_network.png"> Your Drives</h3>
    <table class='spec_table'><tr><th style='width:270px'></th><th>Name</th><th>Size</th><th>Public Link</th></tr>
    <?php
    $drives=array();
    $user = getUser(login_id);
    $result=mysql_query("SELECT * FROM folders WHERE user_id=".login_id." AND parent_id=0 AND name!='recyclebin' ORDER BY name");
    while($row = mysql_fetch_array($result)){
	    $percent = min(100, round(100*$row['size']/$user['quota']));
	    echo "<tr><td style='width:280px'>";
	    echo "<a class='button button-delete' href='javascript:deleteDrive($row[id]);'>Delete</a> ";
	    echo "<a class='button button-view' href='javascript:panelViewUsers($row[id],\"Drive: $row[name]\");'> Users</a> ";
	    if(empty($row['token']))
		    echo "<a class='button button-unlock' href='javascript:makePublic($row[id],\"$row[name]\");'>Make Public</a>";
	    else
		    echo "<a class='button button-lock' href='javascript:makePrivate($row[id],\"$row[name]\");'>Make Private</a>";
	    echo "</td>";
	    echo "<td>$row[name]</td>";
	    echo "<td>".formatSize($row['size'])." ($percent% of Total)</td>";
	    echo "<td>";
	    if(!empty($row['token']))
		    echo "<a class='button button-view' target='_blank' href='".FULL_URL."?page=public&drive=$row[id]&key=$row[token]'>Open</a>";
	    echo "</td>";
	    echo "</tr>";
    }
    ?>
    <tr><td><b>New Drive:</b></td><td><input type='text' id="input_add_drive" size="24"> <a class='button button-add' href="javascript:addDrive();">Add drive</a></td></tr>
    </table>
    
</div>
<div class='box'><h3><img class='icon' src="src/img/icons/drive_network.png"> Shared Drives</h3>
<?php
function displayBool($value){
	if($value == 1)
		return "yes";
	return "no";
}

$sql = "SELECT permissions.level as level, users.name as username, users.email as email, folders.id as id, folders.name as name";
$sql .= " FROM users, folders, permissions";
$sql .= " WHERE users.id = folders.user_id AND permissions.status=1 AND permissions.folder_id = folders.id AND permissions.user_id=".login_id;

$result = mysql_query($sql);

$count = mysql_num_rows($result);
if($count == 0){
	echo "<p>No drives have been shared with you</p>";
}
else{
	echo "<p>These are drives that are shared to you by others</p>";
	echo "<table class='spec_table'><tr><th style='width:150px'></th><th>Name</th><th>Owner</th><th>Permission</th></tr>";
	while($row = mysql_fetch_array($result)){
		echo "<tr>";
		echo "<td><a class='button button-delete' href='javascript:denyDrive($row[id]);'>Remove</a></td>";
		echo "<td>$row[name]</td>";
		echo "<td>$row[name] ($row[email])</td>";
		echo "<td>".displayPermission($row['level'])."</td>";
	}
	echo "</table>";
}
?>
</div>

<?php
$sql = "SELECT permissions.level as level, users.name as username, users.email as email, folders.id as id, folders.name as name";
$sql .= " FROM users, folders, permissions";
$sql .= " WHERE users.id = folders.user_id AND permissions.status=0 AND permissions.folder_id = folders.id AND permissions.user_id=".login_id;

$result=mysql_query($sql);
$count = mysql_num_rows($result);

echo "<div class='box";
if($count > 0)
	echo " box_highlight "; 
echo "'><h3><img class='icon' src='src/img/icons/drive_network.png'> Approve Drives</h3>";
if($count == 0){
	echo "<p>You do not have any drives that require approval</p>";
}
else{
	echo "<div class='info-text ui-corner-all'><b>Attention:</b> The following $count drives need your permission</div>";
	echo "<table class='spec_table'><tr><th></th><th>Name</th><th>Owner</th><th>Permission</th></tr>";
	while($row = mysql_fetch_array($result)){
		echo "<tr>";
		echo "<td><a class='button button-accept' href='javascript:approveDrive($row[id]);'>Approve</a> ";
		echo "<a class='button button-delete' href='javascript:denyDrive($row[id]);'>Deny</a></td>";
		echo "<td>$row[name]</td>";
		echo "<td>$row[username] ($row[email])</td>";
		echo "<td>".displayPermission($row['level'])."</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
</div>