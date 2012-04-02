<?php
if(!empty($_POST['id'])&&is_numeric($_POST['id'])){
	mysql_query("UPDATE music_map SET play_count=play_count+1 WHERE id='$_POST[id]'");
	redirect("success");
}
elseif(!isset($_POST['type']))
	redirect("error-No type");
$type=cleanURL($_POST['type']);
$value = cleanURL($_POST['value']);
$perms = getPermissions("view");
$perms = implode("','",$perms);
switch($_POST['type']){
    case 'playlist':
	$query = "SELECT * FROM music_playlist, music_playlist_songs, music_map ";
	$query .= "WHERE music_playlist_songs.music_playlist_id=music_playlist.id ";
	$query .=" AND music_playlist.id=$value ";
	$query .= "AND music_playlist_songs.music_id=music_map.id ";
	$query .= "AND music_playlist.user_id=".login_id;
	$query .= " ORDER BY music_playlist_songs.id";
	$result = mysql_query($query);
	break;
    case 'popular':
	$result=mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms') AND play_count > 0 ORDER BY play_count DESC LIMIT $value");
	break;
    case 'all':
	$result=mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms')");
	break;
    case 'random':
	$result=mysql_query("SELECT * FROM music_map WHERE root_id IN ('$perms') ORDER BY RAND() LIMIT $value");
	break;
    case 'artist':
	$result=mysql_query("SELECT * FROM music_map WHERE artist_id='$value' AND root_id IN ('$perms')");
	break;
    case 'year':
	$result=mysql_query("SELECT * FROM music_map WHERE year='$value' AND root_id IN ('$perms')");
	break;
    case 'genre':
	$result=mysql_query("SELECT * FROM music_map WHERE genre='$value' AND root_id IN ('$perms')");
	break;
    default:
	redirect("error");
}
if(!$value)
    $value = "default";
$count = 0;
$rows = array();
$files = array();
while($row = mysql_fetch_array($result)){
	$rows[]=$row;
	$files[]=$row['file_id'];
	$count++;
}
if($count==0){
	echo "<p>No songs found</p>";	
}
else{
	
	$ten = min(10,$count);
	$fourty = min(40,$count);
	if($count>1)
	    echo "<a href='javascript:music_mix($count);' class='button button-play'>Play All ($count)</a> ";
	if($count>=40)
		echo "<a href='javascript:music_mix($fourty);' class='button button-play'>Play First 40</a> ";
	if($count>=10)
		echo "<a href='javascript:music_mix($ten);' class='button button-play'>Play First 10</a> ";
	if($count>1){
	    $implode_files=implode("-", $files);
	    echo "<a target='_blank' href='zip/$implode_files/music-$type-$value.zip' class='button button-download'>Download All ($count)</a>";
	}
	
	echo "<ul id='music_list'>";
	$i = 0;
	foreach($rows as $row){
	    $file = getFile($row['file_id']);
	    $url = "/download/$row[file_id]/$file[name]";
	    $artist = null;
	    if($_POST['type']!='artist'){
		    $result3=mysql_query("SELECT * FROM music_artists WHERE id='$row[artist_id]' LIMIT 1");
		    while($row3=mysql_fetch_array($result3))
			    $artist="$row3[name] - ";
	    }
	    $song_name = "$artist $row[song]";

	    $length = format_time($row['playtime']);
	    $rate = round($row['bitrate']/1000);
	    echo "<li id='song_$i'><span class='fright'>$length @ $rate"."kbps</span>";
	    echo "<span class='song_id hidden'>$row[id]</span>";
	    echo "<span class='song_url hidden'>$row[file_id]</span>";
	echo "<span class='song_name hidden'>$file[name]</span>";

	    echo "<a title='Public Link' href='javascript:link($row[file_id]);'><img src='src/img/icons/drive_link.png'></a> ";
	    echo "<a title='Download File' target='_blank' href=\"$url\"><img src='src/img/icons/drive_disk.png'></a> ";
	    echo "<a title='play now' href='javascript:add_music($i,".($i+1).",1);'><img src='src/img/icons/sound.png'></a> ";
	    echo "<a title='add to playlist' href='javascript:add_music($i,".($i+1).");'><img src='src/img/icons/sound_add.png'> <span class='song_title hidden'>$song_name</span> $song_name</a></li>";
	    $i++;
	}
	echo "</ul>";
}
