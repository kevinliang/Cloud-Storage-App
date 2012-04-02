<h2>Sessions</h2>
<p class="help">This page provides extra security over who has accessed your account.</p>
<div class="box">
    <h3><img src="src/img/icons/clock_play.png" /> Active Sessions</h3>
    <?php
    $result = mysql_query("SELECT * FROM sessions WHERE expires > NOW() AND deleted=0 AND user_id=".login_id." ORDER BY id DESC");
    $count = mysql_num_rows($result);
    ?>
    <p class="help">You are currently logged in at <b><?php echo $count;?></b> locations. The highlighted row shows your current location. To logoff from any other locations, click the force logoff button.</p>
    <table class="spec_table">
	<tr><th></th><th>Login time</th><th>IP Address</th><th>Location</th><th>Browser</th><th>Expires</th></tr>
	<?php
	while($row = mysql_fetch_array($result)){
	    $added = convert_timestamp($row['added']);
	    $expires = convert_timestamp($row['expires']);
	    $browser = get_browser($row['user_agent'], true);
	    //var_dump($browser);
	    
	    if($row['id'] == session_id)
		echo "<tr class='row_highlight'><td><a class='button button-delete' href='?page=logout'>Logout</a></td>";
	    else
		echo "<tr><td><a class='button button-delete' href='javascript:deleteSession($row[id]);'>Force Logout</a></td>";

	    echo "<td>$added</td>";
	    echo "<td>$row[ip]</td>";
	    //echo "<td>$row[hostname]</td>";
	    echo "<td>$row[location]</td>";
	    echo "<td>$browser[platform] $browser[parent]</td>";
	    echo "<td>$expires</td>";
	    echo "</tr>";
	}
	?>
    </table>
    <div class="submit_area">
	<a href="javascript:deleteSession();" class="button button-delete">Logout all</a>
    </div>
</div>
<div class="box">
<h3><img src="src/img/icons/clock_stop.png" /> Expired Sessions</h3>
<?php
$result = mysql_query("SELECT * FROM sessions WHERE ( expires < NOW() OR deleted!=0) AND user_id=".login_id." ORDER BY id DESC LIMIT 10");
$count = mysql_num_rows($result);
?>
<p class="help">This shows your login history</p>
<table class="spec_table">
<tr><th>Reason</th><th>Login time</th><th>IP Address</th><th>Location</th><th>Browser</th><th>Expired</th></tr>
<?php
while($row = mysql_fetch_array($result)){
    $added = convert_timestamp($row['added']);
    $expires = convert_timestamp($row['expires']);
    $browser = get_browser($row['user_agent'], true);
    
    if($row['deleted'] == 2)
	$reason = "Forced";
    elseif($row['deleted'] == 1)
	$reason = "Normal";
    else
	$reason = "Expired";
    
    echo "<tr>";
    echo "<td>$reason</td>";
    echo "<td>$added</td>";
    echo "<td>$row[ip]</td>";
    //echo "<td>$row[hostname]</td>";
    echo "<td>$row[location]</td>";
    echo "<td>$browser[platform] $browser[parent]</td>";
    echo "<td>$expires</td>";
    echo "</tr>";
}
?>
</table>
</div>