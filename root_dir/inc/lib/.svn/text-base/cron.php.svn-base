<?php

//error_reporting(E_ERROR);

// recursive function that scans all the files.
function list_dir($dir_handle,$path,$drive=null,$org_path=null){
    while (false !== ($file = readdir($dir_handle))) {
        $dir =$path.'/'.$file;
        if(is_dir($dir) && $file != '.' && $file !='..' ){
            $handle = @opendir($dir) or die("error-Unable to open file $file");
            list_dir($handle, $dir, $drive, $org_path);
        }
		elseif($file != '.' && $file !='..'){
			$full_path = mysql_escape_string(substr($path."/".$file, strlen($org_path)+1));
			mysql_query("UPDATE file_map SET remove=0 WHERE path='$full_path' AND drive='$drive'");
			if(mysql_affected_rows()==0){
				$data = array();
				$data['path'] = $full_path;
				$data['drive'] = $drive;
				$data['title'] = $file;
				$data['exten'] = file_extension($full_path);
				$data['size'] = filesize($full_path);
				addFile($data);
			}
        }
    }
    closedir($dir_handle);
}

function cronIndex(){
	mysql_query("UPDATE file_map SET remove=1");
	$result=mysql_query("SELECT * FROM file_drives");
	
	while($row=mysql_fetch_array($result)){
		$dir_handle = @opendir($row['submap_dir']) or die("error-Unable to open $row[submap_dir]");
		echo "DOING DRIVE: $row[id] <br />";
		list_dir($dir_handle,$row['submap_dir'],$row['id'],$row['submap_dir']);
		updateDriveSize($row['id']);
	}
	
	mysql_query("DELETE FROM file_map WHERE remove=1");
	$count = mysql_num_rows(mysql_query("SELECT id FROM file_map"));
	return "success-$count files have been indexed";
}

// gets the artist ID based on name, will create a new one if non-existant.
function artistId($name){
	$sql_name = mysql_escape_string($name);
	$result = mysql_query("SELECT id FROM music_artists WHERE name='$sql_name'");
	while($row=mysql_fetch_array($result))
		return $row['id'];
	mysql_query("INSERT INTO music_artists (name) VALUES ('$sql_name')");
	return mysql_insert_id();
}

// scans for music.
function cronMusic($id = null){
	$count = 0;
	
	if($id == null){
		mysql_query("UPDATE music_map SET remove=1");
		$result=mysql_query("SELECT * FROM file_map WHERE exten='mp3'");
	}
	
	elseif(is_numeric($id))
		$result=mysql_query("SELECT * FROM file_map WHERE exten='mp3' AND id='$id'");

	$getID3 = new getID3;
	$artists = array();
	//For each mp3 file...
	while($row=mysql_fetch_array($result)){
	
		// If the file is already in the music index, save the file and artist then skip.
		if(mysql_affected_rows(mysql_query("UPDATE music_map SET remove=0 WHERE file_id=$row[id]"))>0){
			continue;
		}
		
		$file = submap_dir($row['drive'])."/".$row['path'];
		if(file_exists($file)){
			$info = $getID3->analyze($file);
			$artist = $info['tags']['id3v2']['artist'][0];
			$song = $info['tags']['id3v2']['title'][0];
			$year = $info['tags']['id3v2']['year'][0];
			$genre = $info['tags']['id3v2']['genre'][0];
			$playtime = $info['playtime_seconds'];
			$bitrate = $info['bitrate'];
			
			$artist=ucwords(strtolower(trim(ereg_replace("[^A-Za-z0-9 ]", "",$artist))));
			$song=ucwords(strtolower(trim(ereg_replace("[^A-Za-z0-9 ]", "",$song))));
			
			if($song!=""&&$artist!=""&&!is_numeric($song)&&!is_numeric($artist)){
				$artist_id=artistId($artist);
				$sql_song=mysql_escape_string($song);
				$sql_artist=mysql_escape_string($artist);
				$sql_genre=mysql_escape_string($genre);
				
				$sql="INSERT INTO music_map (song,artist_id,file_id,drive_id,playtime,bitrate,genre,year) VALUES('$sql_song','$artist_id','$row[id]','$row[drive]','$playtime','$bitrate','$sql_genre','$year')";
				mysql_query($sql);
				$count++;
			}
		}
	}
	
	$removed = 0;
	if($id == null){
		mysql_query("DELETE FROM music_map WHERE remove=1");
		$removed = mysql_affected_rows();
	}
	mysql_query("INSERT INTO file_updates (count,update_type) VALUES ('$count','music')");
	return "success-Music Updated, Added $count, Removed $removed";
}

function addFile($data){
	$id = insertSQL('file_map',$data);
	cronMusic($id);
	return $id;
}

function deleteFile($id){
	if(!is_numeric($id))
		return false;
	mysql_query("DELETE FROM file_map WHERE id='$id'");
	mysql_query("DELETE FROM music_map WHERE file_id='$id'");
	return true;
}