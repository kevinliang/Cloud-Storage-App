<?php
if(isset($_COOKIE['clouthe_login_key']))
    setcookie("clouthe_login_key", "", time()-3600);

mysql_query("UPDATE sessions SET deleted=1, expires=NOW() WHERE user_id=".login_id." AND id=".session_id);

session_destroy();
session_start();
addMsg("success","You have been successfully logged out");
redirect("?page=login");