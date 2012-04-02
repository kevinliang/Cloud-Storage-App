<?php
$key="upload_$_POST[id]";
$tmp_file= TMP_PATH."/$_POST[id].done";
if(function_exists("apc_fetch")){
    $status=apc_fetch($key);
    if(file_exists($tmp_file)){
	unlink($tmp_file);
	echo "101";
    }
    elseif($status&&$status['total']>0){
	$percent=$status['current']/$status['total'];
	$percent=ceil($percent*100);
	echo $percent;
    }
    else
	echo "0";
}
else{
    if(file_exists($tmp_file)){
	unlink($tmp_file);
	echo "101";
    }
    else
	echo "0";
}