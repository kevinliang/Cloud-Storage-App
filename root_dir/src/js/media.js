function display_media(fileId, title, type, next, total, sid){
   
    
    var style = "style='height:224px';";
    var podTitle = title;
    if(type == "audio"){
	style = "style='height:24px';";
	podTitle = "Music Player";
    }

    var url = "/download/"+fileId+"/"+title;
    
    var media_id=uniqid();
    var content = "<div "+style+" class='media_box' name='"+type+"' id='jw_"+media_id+"'></div>";
   
    create_pod(podTitle,content,media_id);
    if(type=="photos"){
	var width = 340;
	var height = 224;
	var content = "<div id='photo_slideshow_"+media_id+"'></div>";
	$("#jw_"+media_id).html(content);
	
	var s1 = new SWFObject("src/swf/imagerotator.swf","rotator",width, height,"7");
	s1.addParam("allowfullscreen","true");
	s1.addVariable("file","/slideshow/"+fileId+"/file.xml");
	s1.addVariable("width",width);
	s1.addVariable("height",height);
	s1.write("photo_slideshow_"+media_id);
    }
    else if(type=="audio"){
	var temp = $(".pod_audio:first object").attr("id");
	if(temp!=undefined){
	    close_pod(media_id);
	    var item = addSong(fileId,title);
	    playSong(item);
	}
	else{
	    $("#pod_"+media_id).addClass("pod_audio");
	    var content = "<span class='hidden playing'>true</span><ul id='music_playlist'>";
	    content += "<li id='playlist_0' class='now_playing ui-corner-all'><span class='hidden url'>"+fileId+"</span>";
	    content += "<a class='fright' href='javascript:removeSong(0);'><img src='src/img/icons/cross.png' class='icon'></a>";
	    content += "<a href='javascript:playSong(0);'><img class='icon' src='src/img/icons/control_play.png'> ";
	    content += "<span class='name'>"+title+"</span></a></li></ul>";
	    content += "<div class='music_create_playlist ui-corner-all'><input type='text' id='input_music_playlist' /> ";
	    content += "<a href='javascript:createPlaylist();' class='button button-list'>Create Playlist</a></div>"
	    
	    $("#pod_"+media_id+" .pod_content").append(content);
	    init_load();
	    $(".pod_content:first").css("height","auto");
	    jwplayer('jw_'+media_id).setup({
		file: url,
		flashplayer: "src/js/jw/player.swf",
		height: 24,
		width: '100%',
		autostart: true,
		controlbar: 'bottom',
		events:{
		    onPause: function(event){
			$("#pod_"+media_id+" .playing").text("false");
		    },
		    onPlay: function(event){
			$("#pod_"+media_id+" .playing").text("true");
		    },
		    onComplete: function(event){
			playNext();
		    },
		    onReady: function(event){
			display_msg("success","Now Playing: "+title);
			if(sid>0){
			    request = new Object();
			    request.id = sid;
			    callback = function(data){
				if(data!="success"){
					display_msg("error",data);
				}
			    }
			    doAjax("music", request, callback);
			}
			if(next>0){
			    add_music(next,total);
			}
		    }
		}
	    });
	    $("#jw_"+media_id).addClass("audio-player");
	}
    }
    else if(type=="html5_video"){
	video_data='<video style="border:none;" poster="/src/img/site/clear.gif" autoplay="autoplay" width="100%" height="100%"><source src="'+url+'" type="video/webm" /></video>';
	mw.ready( function(){
	    mw.load( 'EmbedPlayer', function(){
		    $j("#jw_"+media_id).html(video_data);
	    });
	    $j.embedPlayers();
	});
    }
    else{
	jwplayer('jw_'+media_id).setup({
	    file: url,
	    flashplayer: "src/js/jw/player.swf",
	    height: '220',
	    width: '100%',
	    autostart: true,
	    controlbar: 'bottom'
	});
    }
}
function updatePlaylist(){
    var playlistId = $(".music_playlist_id").text();
    if(!playlistId)
	return;
    var request = new Object();
    request.id = playlistId;
    request.name = $("#input_music_playlist").val();
    request.songs = new Array();
    var i = 0;
    $("#music_playlist li").each(function(){
	request.songs[i] = $(".url", this).text();
	i++;
    });
    var callback = function(data){
	var name = $("#input_music_playlist").val();
	display_msg("success", "Playlist <b>"+name+"</b> successfully updated");
	
    }
    doAjax('playlist', request, callback);
}
function deletePlaylist(id){
    var request = new Object();
    request.id = id;
    request.action = "delete";
    var callback = function(data){
	$("#music_playlist_num_"+id).remove();
	display_msg("success", "Playlist <b>"+data+"</b> successfully deleted");
    }
    doAjax('playlist', request, callback);
}
function createPlaylist(){
    var name = $("#input_music_playlist").val();
    if(!name){
	display_msg("error", "You must give this playlist a name");
    }
    else{
	var callback = function(data){
	    var name = $("#input_music_playlist").val();
	    display_msg("success", "Playlist <b>"+name+"</b> successfuly created");
	    $(".music_create_playlist").prepend("<span class='music_playlist_id hidden'>"+data+"</span>");
	    $(".music_create_playlist a").replaceWith("<a href='javascript:updatePlaylist()' class='button button-list'>Update Playlist</a>");
	    init_load();
	}
	var request = new Object();
	request.name = name;
	request.songs = new Array();
	var i = 0;
	$("#music_playlist li").each(function(){
	    request.songs[i] = $(".url", this).text();
	    i++;
	});
	doAjax('playlist', request, callback);
    }
    
}
function addSong(url, title, sid){
	var last  = $("#music_playlist li:last").attr('id');
	var sp = last.split("_");
	last = (sp[1]*1)+1;
	$("#music_playlist").append("<li class='ui-corner-all' id='playlist_"+last+"'><span class='hidden sid'>"+sid+"</span><span class='hidden url'>"+url+"</span><a href='javascript:removeSong("+last+");' class='fright'><img src='src/img/icons/cross.png' class='icon'></a><a href='javascript:playSong("+last+");'><img src='src/img/icons/control_play.png' class='icon'> <span class='name'>"+title+"</span></a></li>");
	$(".pod_content:first").css("height","auto");
	return last;
}
function removeSong(item){
	if($("#playlist_"+item).hasClass("now_playing")){
		var media_id = $(".audio-player").attr('id');
		jwplayer(media_id).stop();
		playNext();
	}
	$("#playlist_"+item).remove();
	var cid = $("#music_playlist li:last").attr('id');
	if(!cid){
		var media_id = $(".pod_audio").attr('id');
		var sp = media_id.split("_");
		close_pod(sp[1]);
	}
}
function playSong(item){
    var media_id = $(".audio-player").attr('id');
    var url = $("#playlist_"+item+" .url").text();
    var sid = $("#playlist_"+item+" .sid").text();
    var song_name = $("#playlist_"+item+" .name").text();

    $(".pod_audio .playing").text("true");

    display_msg("success","Now Playing: "+song_name);
    $("#music_playlist li").removeClass("now_playing");
    $("#playlist_"+item).addClass("now_playing");
    var newItem = {
	    file: "/download/"+url+"/"+song_name
    };
    
    jwplayer(media_id).load(newItem);
    var request = new Object();
    request.id = sid;
    doAjax("music", request);
}
function playNext(){
    var cid = $("#music_playlist .now_playing").attr('id');
    var id = $("#music_playlist li:last").attr('id');
    if(cid==id){
	    var playing = $(".pod_audio .playing").text("false");
	    return 0;
    }
    var next = $("#music_playlist .now_playing").next().attr('id');
    if(next){
	var split = next.split("_");
	playSong(split[1]);
    }
}
function music_mix(total){
	display_msg("success","Adding " + total + " songs to the playlist");
	add_music(0,total);
}
function add_music(current,total,now){
	current = current * 1;
	if(current>=total){
	    $("#music_playlist li").hover(function(){
		$(this).addClass("hover");
	    },function(){
		$(this).removeClass("hover");
	    });
	    $("#music_playlist").sortable();
	    return 0;
	}
	var fileId = $("#song_"+current+" .song_url").text();
	var sid = $("#song_"+current+" .song_id").text();
	var title = $("#song_"+current+" .song_name").text();
	
	var media_id=$(".pod_audio:first object").attr("id");
	if(media_id==undefined){
	    var next = current+1;
	    display_media(fileId,title,"audio", next, total, sid);
	}
	else{
	    var last = addSong(fileId, title, sid);
	    var next = current + 1;
	    if(now==1){
		playSong(last);
	    }
	    else{
		var playing = $(".pod_audio .playing").text();
		if(playing != "true"){
			playSong(last);
		}
	    }
	    add_music(next,total);
	}
}
function show_media(medium,type,value,title){
    $("#content_"+medium).html("<div class='loading_dir'></div>");
    var request = new Object();
    request.value = value;
    request.type = type;
    var callback = function(data){
	if(title){
	    $("#title_"+medium).text(" - "+title);
	}
	else{
	     $("#title_"+medium).text(" ");
	}
	$("#content_"+medium).html(data);
	init_load();
    }
    doAjax(medium, request, callback);
}