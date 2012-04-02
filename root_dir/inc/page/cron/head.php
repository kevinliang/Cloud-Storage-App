<?php
// 0 = no thumb
// 1 = coming soon thumb
// 2 real thumb

$result = mysql_query("SELECT * FROM files, doc_map WHERE status < 2 AND doc_map.file_id=files.id");
while($row = mysql_fetch_array($result)){
    $scribd_thumbnail = getDocThumbnail($row['scribd_id']);
    $path = BUCKET_PATH."/$row[bucket_id]/$row[file_id]_doc";
    
    exec("rm ".escapeshellarg($path));
    exec("wget --no-cache -O ".escapeshellarg($path)." ".escapeshellarg($scribd_thumbnail));
    
    $updateStatus = null;
    
    if($row['status'] == 1){
	$md5 = md5_file($path);
	echo $md5;
	echo " $scribd_thumbnail";
	if($md5 != "0010f0bd9a95a5d91c778ed240791b06")
	    $updateStatus=2;
	if(strtotime($row['added']) < strtotime('-30 min'))
	    $updateStatus=2;
    }
    elseif($row['status'] == 0 && $scribd_thumbnail){
	$updateStatus=1;
    }
    if($updateStatus)
	mysql_query("UPDATE doc_map SET status=$updateStatus WHERE id=$row[id]");
    $sql_thumb = mysql_escape_string($scribd_thumbnail);
    
    
   
    echo "DOC: $row[id] has status $updateStatus <br />";
}