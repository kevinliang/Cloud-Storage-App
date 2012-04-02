<?php
function getSize($file_path) {
    return  exec ('stat -c %s '. escapeshellarg ($file_path));
}
function getAvailableBucket(){
    $result = mysql_query("SELECT * FROM buckets WHERE count<16000 LIMIT 1");
    while($row = mysql_fetch_array($result))
	return $row['id'];
    $id = insertSQL('buckets', array('count' => 0));
    exec("mkdir ".BUCKET_PATH."/$id");
    return $id;
}
function incrementBucket($id){
    if(!is_numeric($id))
	return false;
    mysql_query("UPDATE buckets SET count=count+1 WHERE id=$id");
    return true;
}
function addFile($path, $folderId, $rootId, $name = null, $userid = null){
    if(!$name)
	$name = basename($path);
    if(!$userid)
	$userid = login_id;
    
    $data = array();
    $data['bucket_id'] = getAvailableBucket();
    incrementBucket($data['bucket_id']);
    $data['folder_id'] = $folderId;
    $data['root_id'] = $rootId;
    $data['name'] = $name;
    $data['exten'] = file_extension($name);
    $data['size'] = getSize($path);
    $id = insertSQL('files',$data);
    $newPath = BUCKET_PATH."/$data[bucket_id]/".$id;
    $tmpPath = TMP_PATH."/".$id.".".$data['exten'];

    exec("cp ".escapeshellarg ($path)." ".escapeshellarg($newPath));
    exec("cp ".escapeshellarg ($path)." ".escapeshellarg($tmpPath));
    //echo "$path -> $newPath <br />";

    $data['id'] = $id;
    $data['path'] = $tmpPath;
    $data['bucket_path'] = $newPath;

    if(in_array($data['exten'], $GLOBALS['audio_extens']))
	addSong($data, $userid);

    if(in_array($data['exten'], $GLOBALS['image_extens']))
	addImage($data, $userid);

    if(in_array($data['exten'], $GLOBALS['doc_extens']))
	addDoc($data, $userid);
    
    if(in_array($data['exten'], $GLOBALS['youtube_extens']))
	addVideo($data, $userid);

    exec("rm ".escapeshellarg($tmpPath));
    return $data['size'];
}
function createTmpLink($file){
    $data = array();
    $data['user_id'] = login_id;
    $data['type'] = 1;
    $data['item_id'] = $file['id'];
    $data['hash'] = substr(randomString(), 0, 32);
    $data['max'] = 1;
    $data['expires'] = date(MYSQL_TIMESTAMP, strtotime("+1 hour"));
    $id = insertSQL('links',$data);
    return SITE_URL."l/$id/$data[hash]/".cleanURL($file['name']);
}
function getVideoThumb($in, $out, $seek){
    if(!$seek)
	$seek = 3;
    exec("ffmpeg -i ".escapeshellarg($in)." -an -ss $seek -an -r 1 -vframes 1 -y ".escapeshellarg($out));
    smart_resize_image($out, $out);
}
function addVideo($file, $userId = null){
    if(!$userId)
	$userId = login_id;
    
    $getID3 = new getID3;
    $info = $getID3->analyze($file['path']);
    
    $data = array();
    $data['file_id'] = $file['id'];
    $data['playtime'] = round($info['playtime_seconds']);
    $data['res_x'] = $info['video']['resolution_x'];
    $data['res_y'] = $info['video']['resolution_y'];
    $data['bitrate'] = round($info['bitrate']);
    
    getVideoThumb($file['path'], $file['bucket_path']."_videothumb.jpg", round($data['playtime']/2));
    
    return insertSQL('video_map', $data);
}
function addDoc($file, $userId = null){
    $url = createTmpLink($file);
    $remoteURL = SCRIBD_URL."docs.uploadFromUrl&secure=1&api_key=".SCRIBD_API."&url=".$url;
    $contents = file_get_contents($remoteURL);
    $info = json_decode(json_encode((array) simplexml_load_string($contents)),1);
    if($info['@attributes']['stat'] == "ok"){
	$data = array();
	$data['file_id'] = $file['id'];
	$data['scribd_id'] = $info['doc_id'];
	$data['scribd_key'] = $info['access_key'];
	return insertSQL('doc_map', $data);
    }
    else
	throwError(var_export($info, true), 'internal');
    return false;
}
/*
function getAlbum($name, $userId = null){
    if(!$userId)
	$userId = login_id;
    $sql_name = cleanSQL($name);
    if(!is_numeric($userId))
	return 0;
    $result = mysql_query("SELECT * FROM photo_albums WHERE name LIKE '$sql_name' AND user_id=$userId ORDER BY id DESC");
    while($row = mysql_fetch_array($result)){
	return $row['id'];
    }
    $data = array('name' => $name, 'user_id' => $userId);
    return insertSQL('photo_albums', $data);
}
 */
function addImage($file, $userid = null){
    if(!$userid)
	$userid = null;
    $getID3 = new getID3;
    $info = $getID3->analyze($file['path']);

    if(isset($info['jpg']['exif']['IFD0']['DateTime'])){
	$data = array();
	$data['time_taken'] = $info['jpg']['exif']['IFD0']['DateTime'];
	$data['month'] = date('Y-m', strtotime($data['time_taken']));
	$data['file_id'] = $file['id'];
	//$data['album_id'] = getAlbum(date('Y-m-d', strtotime($data['time_taken'])), $userid);
	
	insertSQL('photo_map', $data);
    }

    smart_resize_image($file['path'], $file['bucket_path']."_s.jpg");
    smart_resize_image($file['path'], $file['bucket_path']."_l.jpg", 800, 600);
    return true;
}
function artistId($name){
    $sql_name = cleanSQL($name);
    $result = mysql_query("SELECT id FROM music_artists WHERE name='$sql_name'");
    while($row=mysql_fetch_array($result))
	return $row['id'];
    mysql_query("INSERT INTO music_artists (name) VALUES ('$sql_name')");
    return mysql_insert_id();
}
function addSong($file){
    $getID3 = new getID3;

    $info = $getID3->analyze($file['path']);

    $data = array();
    $data['file_id'] = $file['id'];
    $data['root_id'] = $file['root_id'];
    $data['playtime'] = $info['playtime_seconds'];
    $data['genre'] = $info['tags']['id3v2']['genre'][0];
    $data['year'] = $info['tags']['id3v2']['year'][0];
    $data['bitrate'] = $info['bitrate'];

    $artist = $info['tags']['id3v2']['artist'][0];
    $song = $info['tags']['id3v2']['title'][0];

    $artist=superClean($artist);
    $song=superClean($song);

    if($song!=""&&$artist!=""&&!is_numeric($song)&&!is_numeric($artist)){
	$artist_id=artistId($artist);

	$data['song'] = $song;
	$data['artist_id'] = $artist_id;

	return insertSQL('music_map', $data);
    }
}
function recursiveScan($dir_handle, $path, $folderId, $rootId, $userId, $size){
    while (false !== ($file = readdir($dir_handle))) {
    	if($file == '.' || $file == '..')
    		continue;
        $dir =$path.'/'.$file;
        if(is_dir($dir)){
            $handle = @opendir($dir) or die("error-Unable to open folder $file");
            $data = array();
            $data['parent_id'] = $folderId;
            $data['user_id'] = $userId;
            $data['name'] = $file;

	    $newFolderId = insertSQL('folders', $data);

	    $data2 = array('id'=>$newFolderId);
	    if($folderId == 0)
		$data2['root_id'] = $newFolderId;
	    else
		$data2['root_id'] = $rootId;
	    updateSQL('folders', $data2);

            $size += recursiveScan($handle, $dir, $newFolderId , $data2['root_id'], $userId, $size);
        }
	else
	    $size += addFile($path."/".$file, $folderId, $rootId, $file, $userId);
    }
    closedir($dir_handle);
    return $size;
}
function importFilesFromPath($path, $userId){
    $dir_handle = @opendir($path) or die("error-Unable to open $path");
    $size = recursiveScan($dir_handle, $path, 0, 0, $userId, 0);
    $result = mysql_query("SELECT id FROM folders WHERE user_id=$userId AND parent_id=0");
    while($row = mysql_fetch_array($result)){
	$size = getFolderSize($row['id']);
    }
}