<?php
if(empty($_GET['id'])||!is_numeric($_GET['id']))
    throwError("Invalid ID");
$result = mysql_query("SELECT * FROM files, doc_map WHERE doc_map.id=$_GET[id] AND doc_map.file_id=files.id");
while($row = mysql_fetch_array($result)){
    if(!permission($row['root_id'], "view", false))
	throwError("Permission Denied");
    
    $data = array();
    $data['user_id'] = login_id;
    $data['log_id'] = LOG_ID;
    $data['doc_id'] = $row['id'];
    insertSQL('doc_stats', $data);
    
    mysql_query("UPDATE doc_map SET count=count+1 WHERE id=$row[id]");
    
    $user = substr(sha1(session_id()."k59sd8fg67fsdt43t"), 0, 16);
    $sessionToken = substr(sha1(session_id()."k59sd8fg67fsdt43t"), 16, 80);
    
    $sign = md5(SCRIBD_SECRECT."document_id".$row['scribd_id']."session_id".$sessionToken."user_identifier".$user);
    ?>
	<script type='text/javascript' src='https://www.scribd.com/javascripts/view.js'></script>
	<div id='embedded_flash'><a target='_blank' href="http://www.scribd.com">Scribd</a></div>
	<script type="text/javascript">
	    var scribd_doc = scribd.Document.getDoc(<?php echo $row['scribd_id'];?>, '<?php echo $row['scribd_key'];?>' );
	    scribd_doc.addParam( 'jsapi_version', 1 );
	    scribd_doc.addParam("use_ssl", true);
	    scribd_doc.grantAccess('<?php echo $user;?>', '<?php echo $sessionToken;?>', '<?php echo $sign;?>'); 
	    scribd_doc.write( 'embedded_flash' );
	</script>
    <?php
    break;
}