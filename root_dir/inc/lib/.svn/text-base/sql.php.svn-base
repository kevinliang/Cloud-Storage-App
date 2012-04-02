<?php
function insertSQL($table, $data = null){
    $keys = implode(",",array_keys($data));
    $sql_data = array();
    foreach($data as $key=> $raw_data){
	    $sql_data[] = cleanSQL($raw_data);
    }
    $values = implode("','",$sql_data);
    mysql_query("INSERT INTO $table ($keys) VALUES ('$values')");
    $error = mysql_error();
    if($error)
	throwError($error,'internal');
    return mysql_insert_id();
}
function updateSQL($table, $data){
    $count = 0;
    foreach($data as $key => $value){
	if($count == 0){
	    $primary = mysql_escape_string($key);
	    $primary_value = mysql_escape_string($value);
	    $count++;
	    continue;
	}
	$value = mysql_escape_string(strip_tags($value));
	mysql_query("UPDATE $table SET $key='$value' WHERE $primary = '$primary_value'");
	$error = mysql_error();
	if($error)
	    throwError($error,'internal');
	$count++;
    }
    return $count;
}