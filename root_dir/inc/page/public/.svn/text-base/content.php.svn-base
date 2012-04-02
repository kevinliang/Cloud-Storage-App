<h2>Public Drive</h2>
<?php
$folder = getFolder($_GET['drive']);
if(!$folder)
    echo "<p>Invalid drive</p>";
else if(empty($_GET['key'])||$_GET['key']!=$folder['token'])
    echo "<p>Invalid Key</p>";
else{
    echo "<ul class='drive_list'>";
    echo "<li id='dir_$folder[id]'>";
    echo "<a href='javascript:toggleFolder($folder[id]);'>";
    echo " <img class='icon drive_icon' src='src/img/icons/drive_network.png'>";
    echo " $folder[name] </a></li> \n";
    echo "</ul>";
}
?>
<script type='text/javascript'>
$(document).ready(function(){
	<?php echo "toggleFolder($folder[id]);";?>
});
</script>