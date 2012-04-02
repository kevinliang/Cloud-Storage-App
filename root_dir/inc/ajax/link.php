<?php
if(isset($_POST['send'])){
    foreach($_POST['max'] as $key => $value){
	if(!is_numeric($value)||!is_numeric($key))
	    continue;
	if(!isset($_POST['expires'][$key]))
	    continue;

	$expires = cleanSQL($_POST['expires'][$key]);
	mysql_query("UPDATE links SET max=$value, expires='$expires' WHERE id=$key AND user_id=".login_id);
    }
    redirect("Links successfully updated");
}
else{
    if(empty($_POST['id'])||!is_numeric($_POST['id']))
	$id = "(expires<NOW() OR count>=max) AND";
    else
	$id = "id=$_POST[id] AND";
    if(isset($_POST['action'])&&$_POST['action']=="remove"){
	mysql_query("DELETE FROM links WHERE $id user_id=".login_id);
	redirect("success");
    }

    $file = getFile($_POST['id']);
    if(!$file)
	throwError("No file");
    if(!permission($file['root_id'],"view", false)){
	throwError("File permission denied");
    }

    $data = array();
    $data['item_id'] = $file['id'];
    $data['user_id'] = login_id;
    $data['hash'] = substr(randomString(), 0, 16);
    $data['max'] = 1;
    $data['expires'] = date(MYSQL_TIMESTAMP, strtotime("+1 day"));

    $id = insertSQL('links',$data);
    $url = SITE_URL."l/$id/$data[hash]/".cleanURL($file['name']);
    redirect($url);
}