<?php
$id=$_GET['id'];
if(!is_numeric($id)){echo "NO ID";exit;}
$result=mysql_query("SELECT * FROM temp_files WHERE id='$id' LIMIT 1");
if(mysql_num_rows($result)==0){echo "NO FILE";exit;}
while($row=mysql_fetch_array($result)){
	$drive_map=submap_dir($row['drive']);
	$file_path="$drive_map/$row[file]";
	$file_name=end(explode("/",$row['file']));
	$temp_file="tmp/file/$row[id]-$row[rand]/$file_name";
	$temp_path=$temp_file;
	if(isset($_SERVER['WINDIR'])){
		copy($file_path,$temp_path);
	}
	else{
		$cmd="cp \"$file_path\" \"$temp_path\"";
		$handle = popen("$cmd", 'r');
	}
	//
}