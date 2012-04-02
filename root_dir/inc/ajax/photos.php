<?php

$id=$_POST['id'];
$name=$_POST['name'];
$cleanName=cleanSQL($name);

if(empty($id) || !is_numeric($id))
    throwError("Invalid ID");
if(empty($cleanName))
    throwError("Invalid Name");

    
/* NOT WORKING?
$data = array('id'=>$_POST['id'], 'name'=>$_POST['name']);
updateSQL('photo_albums', $data);
*/

mysql_query("UPDATE photo_albums SET name='$cleanName' WHERE id='$id'");
redirect("Album has been renamed to <b>".$cleanName."</b>");