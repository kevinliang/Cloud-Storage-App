<?php
if(empty($_POST['term'])||!isset($_POST['type']))
    exit;
$terms = explode(" ",$_POST['term']);
if(count($terms) == 0)
    exit;

$perms=getPermissions("view");
if(count($perms)==0)
    exit;

$perms=implode("','",$perms);
$input_type = true;
if($_POST['type']=="image")
    $types = $image_extens;
elseif($_POST['type']=="music")
    $types = $audio_extens;
elseif($_POST['type']=="video")
    $types = $video_extens;
elseif($_POST['type']=="doc")
    $types = $doc_extens;
else
    $input_type = null;
if($input_type)
    $input_type = " AND exten IN ('".implode("','",$types)."') ";

$page = 1;
if(!empty($_POST['page'])&&is_numeric($_POST['page']))
    $page = $_POST['page'];
$per_page = 40;

foreach($terms as $key=> $term)
    $terms[$key] = trim(mysql_escape_string($term));

$sql_terms = implode("%' AND name LIKE '%", $terms);

$count_folder = 0;
$count_file = 0;
$num_folder = 0;
$num_file = 0;

if(!$input_type){
    $sql_folder = "SELECT * FROM folders WHERE parent_id>0 AND name LIKE '%".$sql_terms."%' AND root_id IN ('$perms')";
    $result_folder = mysql_query($sql_folder);
    $count_folder = mysql_num_rows($result_folder);
    $pages_folder = floor($count_folder/$per_page);
    $num_folder = min($per_page, $count_folder - (($page-1) * $per_page));
}
if($_POST['type']!="folder"){
    $sql_file = "SELECT * FROM files WHERE name LIKE '%".$sql_terms."%' $input_type AND root_id IN ('$perms')";
    $result_file = mysql_query($sql_file);
    $count_file = mysql_num_rows($result_file);
    $num_file = min($per_page, $per_page - $num_folder);
}

$count = $count_folder + $count_file;
if(($count) ==0)
    exit;
$total = ceil($count/$per_page);
$next = null;
$back = null;

if($page<$total)
    $next = "<a class='sbutton button-next' href='javascript:loadSearch(".($page+1).");'>Next</a>";
if($page>1)
    $back = "<a class='button button-back' href='javascript:loadSearch(".($page-1).");'>Back</a>";

echo "<h3><div class='controls fright'>$back $next</div> $count Results - Showing Page $page/$total</h3>";
echo "<ul class='drive_list'><li class='ui-corner-all'>";

if($num_folder && $count_folder && ($page-1)*$per_page < $count_folder){
    echo "<ul class='dir_list'>";
    $start = ($page-1)*$per_page;
    $result_folder=mysql_query("SELECT * FROM folders WHERE parent_id>0 AND name LIKE '%".$sql_terms."%' AND root_id IN ('$perms') ORDER BY name LIMIT $start, $num_folder");
    while($row=mysql_fetch_array($result_folder))
	displayFolder($row);
    echo "</ul>";
}

if($num_file && $count_file && ($page)*$per_page > $count_folder){
    echo "<ul class='file_list'>";
    if($num_folder > 0)
	$start = 0;
    else
	$start = ($page-1)*$per_page - ($count_folder%$per_page);

    $result_file=mysql_query("SELECT * FROM files WHERE name LIKE '%".$sql_terms."%' $input_type AND root_id IN ('$perms') ORDER BY name LIMIT $start, $num_file");
    while($row=mysql_fetch_array($result_file))
	displayFile($row);
    echo "</ul>";
}
echo "<div class='clearboth'></div></li></ul>";