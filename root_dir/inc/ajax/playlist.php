<?php
if(!empty($_POST['id'])){
    $id = $_POST['id'];
    if(!is_numeric($id))
	throwError("Invalid id provided");
    $result = mysql_query("SELECT * FROM music_playlist WHERE id=$id AND user_id=".login_id);
    if(mysql_num_rows($result)==0)
	throwError("Invalid playlist");
    if(!empty($_POST['action'])&&$_POST['action']=="delete"){
	while($row = mysql_fetch_array($result))
	    $name = $row['name'];
	mysql_query("DELETE FROM music_playlist WHERE id=$id");
	die($name);
    }
    
    if(empty($_POST['name']))
	throwError("Invalid name provided");
    $data = array();
    $data['id']=$id;
    $data['name']=$_POST['name'];
    updateSQL('music_playlist', $data);
    mysql_query("DELETE FROM music_playlist_songs WHERE music_playlist_id=$id");
}
else{
    if(empty($_POST['name']))
	throwError("Invalid name provided");
    
    $sql_name = mysql_escape_string($_POST['name']);
    $result = mysql_query("SELECT * FROM music_playlist WHERE name='$sql_name' AND user_id=".login_id);
    if(mysql_num_rows($result)>0)
	throwError("Playlist name already exists");

    $data = array();
    $data['user_id']=login_id;
    $data['name']=$_POST['name'];
    $id = insertSQL('music_playlist', $data);
}
if(empty($_POST['songs'])||count($_POST['songs'])==0)
     throwError('Invalid songs provided');
foreach($_POST['songs'] as $song){
    if(!is_numeric($song))
	continue;
    $music_id=null;
    $result = mysql_query("SELECT * FROM music_map WHERE file_id=$song");
    while($row = mysql_fetch_array($result))
	$music_id=$row['id'];
    
    $data = array();
    $data['music_playlist_id'] = $id;
    $data['music_id'] = $music_id;
    insertSQL('music_playlist_songs', $data);
}
echo $id;