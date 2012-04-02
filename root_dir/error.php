<html>
<head>
<title>ClouThe Error</title>
<style type="text/css">
	body{
		font-family:"Lucida Sans Unicode", "Tahoma", "Verdana", "Arial";
		font-size:9.5pt;
		color:#666;
	}
</style>
</head>
<body>
<?php
if(empty($_GET['id'])||!is_numeric($_GET['id'])||$_GET['id']>7)
	$error_id=0;
else
	$error_id = $_GET['id'];

$error=null;
$error[0]=array("Invalid Error","The page you are trying to reach is unavailable");
$error[1]=array("tmp/ directory unaccessible","Please delete the <b>tmp/</b> directory in the root of the website to fix this issue");
$error[2]=array("tmp/ directory has insufficient permissions","Please allow the <b>tmp/</b> directory in the root of the website to have permissions to create directories chmod 777.");
$error[3]=array("Database Down","The site's database is currently unavailable");
$error[4]=array("Exceed Limit","You have exceeded your quota");
$error[5]=array("Acess Denied","You do not have access to this page");
$error[6]=array("Error 404","Page not found");
$error[7]=array("Down for Maintenance", "Our team is currently moving some files around, we will be back online soon!");
?>
<h2><?php echo $error[$error_id][0];?></h2>
<p><?php echo $error[$error_id][1];?></p>
</body>
</html>
