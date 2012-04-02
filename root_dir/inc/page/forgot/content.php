<div id="dialog_box" class="hidden">
	<span class='hidden' id='dialog_title'>Forgot Password</span>
	<div id="msg_area">
		<?php
		display_msg();
		?>
	</div>
	<div id="dialog_content">	
		<p>Please provide us your email:</p>
		Email: <input type="text" id="input_email" name="email" size="50">
		<br /><br />
		<?php
			echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
		?>
		<br />
		<a class="button button-register" href="javascript:forgot();">Retrive Password</a> or <a class="button button-login" href="?page=login">Return to Login</a>
	</div>
</div>
