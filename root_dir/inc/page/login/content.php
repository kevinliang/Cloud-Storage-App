<div id="dialog_box">
	<span class='hidden' id='dialog_title'>ClouThe v<?php echo VERSION_NUMBER;?></span>
	<div id="msg_area">
		<?php
		display_msg();
		?>
	</div>
	<div id="dialog_content">
		<div style='background:url(src/img/site/logo-med.png) top left no-repeat;height:150px'>
		<div style='padding:110px 0 0 180px'>
		<a class='button button-play' href='http://clouthe.com' target='_blank'>Learn More</a>
		</div>
		</div>
		
		<table style='width:100%;margin-top:10px;'><tr><td>
			<h3>Login</h3>
			<table class='td_center'>
				<tr><td><b>Email:</b></td><td> <input id="input_username" size="30" type="text" name="username"></td></tr>
				<tr><td><b>Password:</b></td><td> <input id="input_password" size="30" type="password" name="password"></td></tr>
			</table>
			<div style='margin:5px 0 10px 0'>
			<input id='input_remember' type='checkbox' name='remember' value='1' /> Remember Me
			</div>
			<a class="button button-login" href="javascript:login();">Login</a> or 
			<a class="button button-register" href="?page=forgot">Forgot Password</a>
			</td><td style='width:1px;background:#ddd'></td><td style='vertical-align:top'>
			<div style='margin-left:10px'>
				<h3>Register</h3>
				<a class="button button-register" href="?page=register">Sign Up!</a>
			</div>
			</td></tr>
		</table>
	</div>
</div>