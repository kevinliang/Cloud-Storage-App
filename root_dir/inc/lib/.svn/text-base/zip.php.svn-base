<?php
class Zipper extends ZipArchive {
    public function addDir($folder, $trail = null) {
	$path = $trail."/".$folder['name'];
	$this->addEmptyDir($path);
	$result = mysql_query("SELECT * FROM folders WHERE parent_id=$folder[id]");
	while($row = mysql_fetch_array($result)){
	    $this->addDir($row, $path);
	}
	$result = mysql_query("SELECT * FROM files WHERE folder_id=$folder[id]");
	while($row = mysql_fetch_array($result))
	    $this->addFile(BUCKET_PATH."/$row[bucket_id]/".$row['id'], $path."/".$row['name']);
    }
    public function addFiles($files, $perms, $name){
	$this->addEmptyDir("/".$name);
	$result = mysql_query("SELECT * FROM files WHERE id IN ($files) AND root_id IN ($perms)");
	while($row = mysql_fetch_array($result))
	    $this->addFile(BUCKET_PATH."/$row[bucket_id]/".$row['id'], "/".$name."/".$row['name']);
    }
}