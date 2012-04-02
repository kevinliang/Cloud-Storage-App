function uniqid(){
    var newDate = new Date;
    return newDate.getTime();
}
function reloadLightBox(){
    $("a[rel='lightbox']").fancybox({
	titlePosition	: 'over',
	transitionIn: 'elastic',
	transitionOut: 'elastic',
	type: 'image'
    });
    $("a[rel='framebox']").fancybox({
	titlePosition	: 'over',
	transitionIn: 'elastic',
	transitionOut: 'elastic',
	type: 'iframe',
	width: '960',
	height: '100%'
    });
}
function displayDialog(content, title){
    var id = uniqid();
    if(title == undefined){
	title = "Confirm Action";
    }
    $("#dialog_area").append("<div class='dialog hidden' id='dialog_"+id+"'>"+content+"</div>");
    $("#dialog_"+id).dialog({
	    width: 600,
	    title: title,
	    modal: true
    });
    init_load();
    return id;
}
function closeDialog(id){
    if(id != undefined){
	$("#dialog_"+id).remove();
    }
    else{
	$(".dialog").remove();
    }
    $(".ui-state-hover").removeClass("ui-state-hover");
}
var loading_page = false;
function load_page(page){
    if(loading_page == true){
	return;
    }
    loading_page = true;

    $(".page").hide();
    $("ul li").removeClass("selected");
    $("#link_" + page).addClass("selected");
    $("#link_" + page).addClass("opened");

    var display_prop = $("#page_" + page).css("display");
    if(display_prop=="none"){
	$("#page_"+page).show();
	loading_page = false;
    }
    else{
	var request = {
	    page: page
	}
	var callback = function(data){
	    $("#content").prepend("<div class='page' id='page_"+page+"'>"+data+"</div>");

	    var content = " <div class='page_buttons'><a class='close_link' title='Close Page' href='javascript:close_page(\""+page+"\");'>";
	    content += "<img src='src/img/icons/cancel.png'></a> ";
	    content += "<a title='Reload Page' class='reload_link' href='javascript:reload_page(\""+page+"\");'>";
	    content += "<img src='src/img/icons/arrow_refresh.png'></a> </div>";
	    $("#page_" + page+" h2:first").prepend(content);
	    init_load();
	    loading_page = false;
	}
	doAjax('page', request, callback);
    }
}
function close_page(page){
    $("ul li").removeClass("selected");
    $("#link_"+page).removeClass("opened");
    $("#page_"+page).remove();
}
function reload_page(page,data){
    close_page(page);
    load_page(page);
    if(data!=undefined){
	var split = data.split("-");
	display_msg(split[0],split[1]);
    }
}
function set_droppable(id){
    if(id==null){
	selector = ".drive_list li";
    }
    else{
	selector = "#dir_"+id+" .dir_list  li";
    }
    $(selector).droppable({
	activeClass: 'ui-state-hover',
	hoverClass: 'ui-state-active',
	greedy: true,
	drop: function(event, ui) {
	    var to_id = $(this).attr("id");
	    var from_id = $(ui.draggable).attr("id");

	    var to = to_id.split("_");
	    to = to[1];

	    var from = from_id.split("_");
	    from = from[1];

	    var list_class =$(ui.draggable).parent("ul").attr("class");
	    if(list_class=="dir_list"){
		move(from,to,true);
	    }
	    else{
		move(from,to,false);
	    }
	}
    });
}
function display_msg(type,message){
    var msg_id=uniqid();
    if(type=="success"){
	var icon_name="accept";
    }
    else{
	var icon_name="cancel";
    }
    var content = "<div class='message "+type+"' id='msg_"+msg_id+"'><div><img src='src/img/icons/"+icon_name+".png'> "+message+"</div></div>";
    $("#msg_area").prepend(content);
    $(".message").addClass("ui-corner-all");
    $("#msg_"+msg_id).fadeIn("slow");
    t=setTimeout('close_msg('+msg_id+')',8000);
}
function close_msg(msg_id){
    $("#msg_"+msg_id).fadeOut("slow");
    $("#msg_"+msg_id).remove();
}
function doAjax(ajax, request, callback, errorCall){
    if(typeof(request) == "string"){
	request += "&session_token=" + $("#session_token").text();
    }
    else{
	request.session_token = $("#session_token").text();
    }
    $.ajax({
       type: "POST",
       url: "index.php?ajax="+ajax,
       data: request,
       success: callback,
       error: function(data){
	   if(data.status == 401){
	       alert("Please Login");
	       window.location = "?page=login";
	   }
	   else if(errorCall != undefined){
	       errorCall(data.responseText);
	   }
	   else{
	       if(data.status == 500){
		   display_msg("error", data.responseText);
	       }
	       else{
		   display_msg("error","Error: Unable to load ajax request");
	       }
	   }
           console.log(data);
       }
     });
}
function create_pod(title,content,pod_id){
    if(pod_id==null){
	var pod_id=uniqid();
    }
    var pod_content = "<div id='pod_"+pod_id+"' class='pod_item ui-corner-all'><h3 class='ui-corner-top'><span class='fright'><a title='Close Pod' href='javascript:close_pod("+pod_id+");'><img class='icon' src='src/img/icons/cross.png'></a></span>"+title+"</h3><div class='pod_content resizable'>"+content+"</div></div>";

    $("#pod_area").prepend(pod_content);
    $(".resizable").resizable();
    return pod_id;
}
function close_pod(pod_id){
    $("#pod_"+pod_id).remove();
}