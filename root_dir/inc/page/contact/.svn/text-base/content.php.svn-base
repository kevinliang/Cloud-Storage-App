<?php
    $user = getUser(login_id);
?>
<h2>Contact</h2>
<p>Use this form below to send us any bug reports or feedback!</p>
<form class="ajaxform" action="sendContact();" id="form_contact_default">
<div class="box"  style='width:500px'>
    <h3>Contact Form</h3>
    <table style='margin:5px;' class='input_table'>
    <tr><td>Name: </td><td><?php echo $user['name'];?></td></tr>
    <tr><td>Email: </td><td><?php echo $user['email'];?></td></tr>
    <tr><td>Subject: </td><td><input type="text" name="subject" style='width:90%;'></td></tr>
    <tr><td>Message: </td><td><textarea name='message' style='width:90%;height:200px'></textarea></td></tr>
    </table>
    <br />
    <a class="ajaxsend button button-contact" href="javascript:sendContact();">Send</a>
</form>