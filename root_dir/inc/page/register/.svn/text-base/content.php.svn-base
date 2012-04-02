<div id="dialog_box" class="hidden">
	<span class='hidden' id='dialog_title'>Register</span>
	<div id="msg_area">
		<?php
		display_msg();
		?>
	</div>
	<div id="dialog_content">	
		<p>Welcome, use this form to sign up for Clouthe!</p>
		<table class='td_center'>
		<tr><td><b>Name:</b></td><td> <input id="input_name" size="40" type="text" name="name"></td></tr>
		<tr><td><b>Email:</b></td><td> <input id="input_email" size="40" type="text" name="username"></td></tr>
		<tr><td><b>Password:</b></td><td> <input id="input_password1" size="20" type="password" name="password1"> <span class='notes'>8 char min, 1 number</span></td></tr>
		<tr><td><b>Repeat:</b></td><td> <input id="input_password2" size="20" type="password" name="password2"></td></tr>
		</table>
		<br />
		<?php
		echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY, null);
		?>
		<br />
		<a class="button button-register" href="javascript:register();">Register</a> or <a class="button button-login" href="?page=login">Return to Login</a>
	</div>
</div>