<?php
    $v = "?v=".VERSION_NUMBER;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
	<title><?php if(APPLICATION_ENV == "development") echo "DEV :: "; ?>ClouThe v<?php echo VERSION_NUMBER;?> - A free online file manager</title>
	<meta name="description" content="ClouThe is a free service that lets you share your photos, docs, and videos anywhere and share them ALL. "/>
	<meta name="keywords" content="online storage, free storage, file sharing, share files,  cloud storage, online backup, cross platform, sync, online collaboration, remote access"/>

	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="src/css/reset.css<?php echo $v;?>">
	<link rel="stylesheet" type="text/css" href="src/video/mwEmbed-player-static.css<?php echo $v;?>">
	<link rel="stylesheet" type="text/css" href="src/css/theme/ui-lightness/jquery-ui-1.8.14.custom.css<?php echo $v;?>">
	<link rel="stylesheet" type="text/css" href="src/fancybox/jquery.fancybox-1.3.4.css<?php echo $v;?>" />
	<link rel="stylesheet" type="text/css" href="src/css/default.css<?php echo $v;?>">

	<script type="text/javascript" src="src/js/swfobject.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/jw/jwplayer.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/jq/jquery.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/video/mwEmbed-player-static.js<?php echo $v;?>" ></script>
	<script type="text/javascript" src="src/js/jq/jquery-ui.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/jq/jquery-ui-timepicker.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js<?php echo $v;?>#pubid=xa-4e3ede670f8f9b54"></script>
	<script type="text/javascript" src="src/fancybox/jquery.fancybox-1.3.4.pack.js<?php echo $v;?>"></script>
	<script type='text/javascript' src="src/js/jq/form.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/global.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/default.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/media.js<?php echo $v;?>"></script>
	<script type="text/javascript" src="src/js/init.js<?php echo $v;?>"></script>
    </head>
    <body>
	<div id="page">
	<div id="complete">
	    <div id="header">
		<div id="top_nav">
		    <ul class="fright">
			<li id='link_account'><a href="javascript:load_page('account');"><span><img src='src/img/icons/user.png'> <?php echo login; ?></a></span></li>
			<li><a href="?page=logout"><img src="src/img/icons/lock_go.png"> Logout</a></li>
		</ul>
		    <ul>
			<li id='link_index'><a href="javascript:load_page('index');"><span><img src='src/img/icons/clouthe.png'> Home</a></span></li>
			<li id='link_home'><a href="javascript:load_page('home');"><span><img src='src/img/icons/drive_network.png'> Drives</a></span></li>
			<li id='link_search'><a href="javascript:load_page('search');"><img src='src/img/icons/zoom.png'> Search</a></li>
			<li id='link_music'><a href="javascript:load_page('music');"><img src='src/img/icons/music.png'> Music</a></li>
			<li id='link_photos'><a href="javascript:load_page('photos');"><img src='src/img/icons/pictures.png'> Photos</a></li>
			<li id='link_videos'><a href="javascript:load_page('videos');"><img src='src/img/icons/film.png'> Videos</a></li>
			<li id='link_docs'><a href="javascript:load_page('docs');"><img src='src/img/icons/page_white_text.png'> Docs</a></li>
			<li id='link_link'><a href='javascript:load_page("link");'><img src='src/img/icons/link.png'> Links</a></li>
			<li id='link_sessions'><a href='javascript:load_page("sessions");'><img src='src/img/icons/clock.png'> Sessions</a></li>
			<li id='link_settings'><a href='javascript:load_page("settings");'><img src='src/img/icons/wand.png'> Settings</a></li>
			<li id='link_contact'><a href='javascript:load_page("contact");'><img src='src/img/icons/report.png'> Feedback</a></li>
		</ul>
		</div>
		<div class='clearboth'></div>
	    </div>
	    <?php display_msg(); ?>
	    <div id="main">
		<div id="main_inside">
		    <table id="pane_table">
		    <tr><td id='left_pane'>
			    <div class="pane-inner">
				    <h2>Pods</h2>
				    <div id="pod_area" class="ui-corner-all">
					    &nbsp;
				    </div>
			    </div>
		    </td>
		    <td id="right_pane">
			    <div id="content">
				    <div id="page_index" class='page'>
					    <?php display_content();?>
				    </div>
			    </div>
		    </td></tr>
		    </table>
		<div id="msg_area">
		    &nbsp;
		</div>
		<div id="dialog_area">
		    &nbsp;
		</div>
	    </div>
	</div>
	</div>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-20564673-2']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
    </body>
</html>