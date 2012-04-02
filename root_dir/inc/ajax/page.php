<?php
if(empty($_POST['page']))
    throwError("Invalid page");
$page = alphaNum($_POST['page']);
if(!file_exists("inc/page/$page/content.php"))
    throwError("Page not found");
define("load_page", $page);
require_once("inc/page/$page/content.php");