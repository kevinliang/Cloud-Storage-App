<?php
$user = getUser(login_id);
if($user['email'] == "demo@clouthe.com")
    throwError("Demo account");

if(empty($_POST['name']))
    throwError("Please provide a name");
$name = alphaNum($_POST['name']);
$sql_name = cleanSQL($name);
$result = mysql_query("SELECT * FROM folders WHERE parent_id=0 AND name='$sql_name' AND user_id=".login_id);
if(mysql_num_rows($result)>0)
    throwError("Drive already exist");

$data = array('name'=> $name, 'user_id' => login_id);
$id = insertSQL('folders', $data);
$data = array('id' => $id);
$data['root_id'] = $id;
updateSQL('folders', $data);
redirect("Drive <b>$name</b> successfully created");
