<h2>Links</h2>
<p>Below are your links that you can share over the web.</p>
<p><b>Note:</b> For links to last forever, set the expires on to blank and the max to 0</p>
<div id="fb-root"></div>
<?php
$result = mysql_query("SELECT * FROM files, links WHERE links.type=0 AND links.item_id = files.id AND links.user_id=".login_id." ORDER BY links.id DESC");
$count = mysql_num_rows($result);
if($count == 0)
    echo "<p>You do not have any links. Click on the <img src='src/img/icons/drive_link.png' /> icon next to a file to create a link.</p>";
else{
    echo "<div class='box'><form id='form_link_default'><table class='spec_table'>";
    echo "<tr><th>Name</th><th>Share URL</th><th>Clicks</th><th>Max</th><th>Expires on</th><th>Created on</th><th>Share</th><th></th></tr>";
    while($row = mysql_fetch_array($result)){
	$url = SITE_URL."l/$row[id]/$row[hash]/".alphaNum($row['name']);
	echo "<tr id='row_link_$row[id]'>";
	echo "<td><a target='_blank' href=\"download/$row[item_id]/$row[name]\">".substr($row['name'], 0, 20)."</a></td>";
	if(validLink($row))
	    echo "<td><input class='input_copy_paste' type='text' style='width:100%;' value='$url' /></td>";
	else
	    echo "<td>Expired</td>";
	echo "<td>$row[count]</td>";
	echo "<td><input type='text' name='max[$row[id]]' value='$row[max]' size='3'/></td>";
	echo "<td><input type='text' style='width:120px' name='expires[$row[id]]' value='$row[expires]' /></td>";
	echo "<td>".convert_timestamp($row['added'])."</td>";
	echo "<td>"?>
	    <div class="addthis_toolbox addthis_default_style"
	       addthis:url="<?php echo $url;?>"
	       addthis:title="<?php echo $row['name'];?>">
	    <a class="addthis_button_facebook"></a>
	    <a class="addthis_button_twitter"></a>
	    <a class="addthis_button_googlebuzz"></a>
	    <a class="addthis_button_email"></a>
	    </div>
    <?php
	echo "</td><td><a class='button button-delete' href='javascript:removeLink($row[id])'>&nbsp;</a></td>";
	echo "</tr>";
    }
    echo "</table>";
    echo "<input type='hidden' name='send' value='Update' />";
    echo "<a href='javascript:updateLinks();' class='button button-update'>Update</a>";
    echo " <a href='javascript:removeLink();' class='button button-delete'>Remove Expired Links</a>";
    echo "</form></div>";
}
?>