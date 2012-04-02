// PAGE: SESSIONS
function deleteSession(id){
    var request = {id: id}
    var callback = function(data){
	display_msg("success", data);
	reload_page('sessions');
    }
    doAjax('sessions', request, callback);
}

// PAGE: ACCOUNT
function updateBasic(){
    var request = $("#form_account_basic").formSerialize();
    var callback = function(data){
	display_msg("success", data);
	reload_page('account');
    }
    doAjax('account', request, callback);
}
function updatePassword(){
    var request = $("#form_account_password").formSerialize();
    var callback = function(data){
	display_msg("success", data);
	reload_page('account');
    }
    doAjax('account', request, callback);
}
// PAGE: CONTACT
function sendContact(){
    var request = $("#form_contact_default").formSerialize();
    var callback = function(data){
	display_msg("success", data);
	reload_page('contact');
    }
    doAjax('contact', request, callback);
}

// PAGE: SETTINGS
function makePublic(id, name){
    var content = "<p>Are you sure you would like to make the drive \""+name+"\" public for the world to view?</p>";
    content += "<div class='dialog-buttons'><a class='button button-update' href='javascript:doDrivePermission("+id+",\"public\");'>Confirm</a></div>";
    displayDialog(content, "Make drive "+name+" public");
}
function makePrivate(id, name){
    var content = "<p>Are you sure you would like to make the drive \""+name+"\" private?</p>";
    content += "<div class='dialog-buttons'><a class='button button-update' href='javascript:doDrivePermission("+id+",\"private\");'>Confirm</a></div>";
    displayDialog(content, "Make drive "+name+" private");
}
function doDrivePermission(id, action){
    var request = new Object();
    request.id = id;
    request.action = action;
    var callback = function(data){
	reload_page('settings');
	display_msg("success", data);
    }
    closeDialog();
    doAjax('panel', request, callback);
}
function deleteDrive(id){
    var content = "<p>Are you sure you would like to delete this drive?</p>";
    content += "<div class='dialog-buttons'><a class='button button-delete' href='javascript:doDeleteDrive("+id+");'>Delete</a></div>";
    displayDialog(content, "Delete Drive");
}
function doDeleteDrive(id){
    var request = new Object();
    request.id = id;
    request.action = "delete";
    var callback = function(data){
	display_msg("success", data);
	closeDialog();
	reload_page('settings');
    }
    doAjax('panel', request, callback);
}
function addDrive(){
    var request = new Object();
    request.name = $("#input_add_drive").val();
    var callback = function(data){
	display_msg("success", data);
	reload_page('settings');
    }
    doAjax('settings', request, callback);
}
function panelViewUsers(id, driveName){
    var request = new Object();
    request.id = id;
    var callback = function(data){
	displayDialog(data, driveName);
	$(".input_permission .slider").slider({
	    range: "min",
	    min: 1,
	    max: 3,
	    step: 1,
	    create: function(){
		var value = $(this).siblings("input").val();
		$(this).slider("value",value);
	    },
	    slide: function( event, ui ) {
		var permission_text = "View";
		if(ui.value == 2){
			permission_text = "Upload";
		}
		else if(ui.value == 3){
			permission_text = "Edit";
		}
		$(this).siblings(".permission_text").text(permission_text);
		$(this).siblings("input").val(ui.value);
	    }
	});
    }
    doAjax('panel', request, callback);
}
function updatePanel(id){
    var element = "#form_panel_"+id;
    var request = $(element).formSerialize();
    var callback = function(data){
	display_msg("success", data);
	closeDialog();
    }
    $(element).hide();
    $(element).after("<div class='loading'>&nbsp;</div>");
    doAjax('panel', request, callback);
}
function removePermission(id, drive){
    var request = {
	action: "remove",
	permission_id: id,
	id: drive
    }
    var callback = function(data){
	$("#permission_"+id).remove();
	display_msg("success", data);
    }
    doAjax('panel', request, callback);
}
function denyDrive(id){
    var request = {
	action: "deny",
	id: id
    }
    var callback = function(data){
	reload_page('settings');
	display_msg("success", data);
    }
    doAjax('panel', request, callback);
}
function approveDrive(id){
    var request = {
	action: "approve",
	id: id
    }
    var callback = function(data){
	reload_page('settings');
	display_msg("success", data);
    }
    doAjax('panel', request, callback);
}
// PAGE: LINK
function updateLinks(){
    var element = "#form_link_default";
    var request = $(element).formSerialize();
    var callback = function(data){
	display_msg("success", data);
	reload_page('link');
    }
    doAjax('link', request, callback);
}
function removeLink(id){
    var content = "<p>Are you sure you want to delete ";
    if(id == undefined){
	content += "all expired links?</p>";
    }
    else{
	content += "this link?</p>";
    }
    content += "<div class='dialog-buttons'><a class='button button-delete' href='javascript:doRemoveLink("+id+");'>Delete</a></div>";
    displayDialog(content, "Confirm Delete");
}
function doRemoveLink(id){
    closeDialog();
    var request = new Object();
    request.id = id;
    request.action = "remove";
    
    var callback = function(data){
	display_msg("success", data);
	reload_page('link');
    }
    doAjax('link', request, callback);
}
function link(id){
    var name = $("#file_"+id+" .file_name:first").text();
    var content = "<div class='loading'>&nbsp;</div>";
    var podId = create_pod("Public Share Link: "+name, content);
    var request = new Object();
    request.id = id;
    var callback = function(data){
	$(".loading","#pod_"+podId).remove();
	var newContent = "<p>Below is a unique public url for <b>"+name+"</b>,";
	newContent += " by default this url will last 24hours and will expire after";
	newContent += " one use. To change these settings go to your <a href=\"javascript:reload_page('link');\">links page</a>.</p>";
	newContent += "URL: <input class='input_copy_paste' type='text' style='width:280px' value=\""+data+"\" />";
	$("#pod_"+podId+" .pod_content").html(newContent);
	init_load();
    }

    doAjax('link', request, callback);
}
// PAGE: HOME
function move(from,to,isDir){
    var request = new Object();
    request.from = from;
    request.to = to;
    request.isDir = isDir;

    var callback = function(data){
	toggleFolder(to,true);
	if(isDir){
	    $("#dir_"+from).remove();
	    if(data != "#NOCHANGE#"){
		display_msg("success","Folder successfully moved");
	    }
	}
	else{
	    $("#file_"+from).remove();
	    if(data != "#NOCHANGE#"){
		display_msg("success","File successfully moved");
	    }
	}
    }
    var errorCall = function(data){
	display_msg("error",data);
	$("#"+from).animate({
	    top: 0,
	    left: 0
	});
    }
    doAjax('move', request, callback , errorCall);
}
function remove(id, isDir){
    var object= "";
    if(isDir)
	object = "folder";
    else
	object = "file";
    var content = "Are you sure you would like to delete this "+object+" permanently?";
    content += "<div class='dialog-buttons'><a href='javascript:doRemove("+id+", "+isDir+");' class='button button-delete'>Delete</a></div>";
    displayDialog(content,"Confirm Delete");
    
}
function doRemove(id, isDir){
    $(".dialog").remove();
    var request = new Object();
    request.id = id;
    request.isDir = isDir;
    var callback = function(data){
	if(isDir){
	    display_msg("success",data);
	    $("#dir_"+id).remove();
	}
	else{
	    display_msg("success",data);
	    $("#file_"+id).remove();
	}
    }
    doAjax('remove', request, callback);
}
function rename(fileId, dirId){
    var element = "#file_"+fileId+" .file_name:first";
    if(!dirId){
	element="#dir_"+fileId+" .file_name:first";
    }

    var currentName=$(element).text();
    var content = "<span id='rename_"+fileId+"'><input type='text' class='input_rename' value='"+currentName+"'> <a href='#'><img src='src/img/icons/add.png' class='icon'></a></span>";

    $(element).replaceWith(content);
    $("#rename_"+fileId+" > .input_rename").focus();
    $("#rename_"+fileId+" > a").click(function(){
	doRename(fileId, dirId);
    });
    $("#rename_"+fileId+" > .input_rename").keyup(function(event) {
	if (event.keyCode == '13') {
	    event.preventDefault();
	    doRename(fileId, dirId);
	}
    });
}
function doRename(fileId, dirId){
    var request = new Object();
    request.id = fileId;
    request.name = $("#rename_"+fileId+" > input:first").val();
    request.isDir = false;
    if(dirId == undefined){
	request.isDir = true;
    }
    
    var callback = function(data){
	if(dirId != undefined){
	    if(data != "#NOCHANGE#"){
		display_msg("success","File renamed");
		$("#rename_"+fileId).remove();
	    }
	    toggleFolder(dirId,true);
	}
	else{
	    if(data != "#NOCHANGE#"){
		display_msg("success","Folder renamed");
		$("#rename_"+fileId).replaceWith("<span class='file_name'>"+data+"</span>")
	    }
	    else{
		$("#rename_"+fileId).replaceWith("<span class='file_name'>"+request.name+"</span>")
	    }
	    toggleFolder(fileId,true);
	}
    }
    doAjax("rename",request, callback);
}
function createDir(id){
    var callback = function(){
	var newId=uniqid();
	var content = "<li class='ui-corner-all' id='create_dir_"+newId+"'><img src='src/img/icons/folder_add.png' /> <input type='text' class='input_create_dir'>";
	content +=" <a href='#'><img src='src/img/icons/add.png' class='icon'></a></li>";

	$("#dir_"+id+" .dir-content .dir_list:first").prepend(content);

	$("#create_dir_"+newId+" input").focus();
	$("#create_dir_"+newId+" > a").click(function(){
	    doCreateDir(id,newId);
	});
	$("#create_dir_"+newId+" > .input_create_dir").keyup(function(event) {
	    if (event.keyCode == '13') {
		event.preventDefault();
		doCreateDir(id,newId);
	    }
	});
    }
    toggleFolder(id, true, callback);
}
function doCreateDir(id , newId){
    var name = $("#create_dir_"+newId+" > input").val();
    if(name == ""){
	$("#create_dir_"+newId).remove();
    }
    else{
	var request = new Object();
	request.id = id;
	request.name = name;
	var callback = function(data){
	    $("#create_dir_"+newId).remove();
	    toggleFolder(id,true);
	}
	doAjax('create',request, callback);
    }
}

var opening_dir=false;

function toggleFolder(id, refresh, callbackReturn){
    if(opening_dir)
	return;
    var display_prop = $("#dir_" + id + " > .dir-content").css("display");
    if(refresh == true){
	$("#dir_" + id + " > .dir-content").remove();
	var value = "refresh";
    }
    else{
	var value = display_prop;
    }
    if(value=="block"){
            $("#dir_" + id + " > .dir-content").hide();
            $("#dir_"+id+" > a > .folder_icon").replaceWith(" <img class='icon folder_icon' src='src/img/icons/folder.png'> ");
            $("#dir_"+id+" > a > .drive_icon_open").addClass("hidden");
            $("#dir_"+id+" > a > .drive_icon_close").removeClass("hidden");
    }
    else if(value=="none"){
            $("#dir_" + id + " > .dir-content").show();
            $("#dir_"+id+" > a > .folder_icon").replaceWith(" <img class='icon folder_icon' src='src/img/icons/folder_page.png'> ");
            $("#dir_"+id+" > a > .drive_icon_open").removeClass("hidden");
            $("#dir_"+id+" > a > .drive_icon_close").addClass("hidden");
    }
    else if(opening_dir==false){
	opening_dir=true;
	$("#dir_"+id).append("<div class='loading_dir' id='loading_" + id + "'></div>");

	var request = new Object();
	request.folder_id = id;
	var callback = function(data){
	    $("#loading_"+id).remove();
	    $("#dir_"+id).append("<div style='padding-top:4px' class='dir-content'>" + data + "</div>");
	    $("#dir_"+id+" ul li").draggable({revert: 'invalid'});
	    set_droppable(id);
	    reloadLightBox();
	    $("ul li").hover(function(){
		    $(this).addClass("hover");
	    },function(){
		    $(this).removeClass("hover");
	    });
	    $("#dir_"+id+" > a > .folder_icon").replaceWith(" <img class='icon folder_icon' src='src/img/icons/folder_page.png'> ");

	    $("#dir_"+id+" > a > .drive_icon_close").addClass("hidden");
	    $("#dir_"+id+" > a > .drive_icon_open").removeClass("hidden");
	    opening_dir=false;
	    if(callbackReturn != undefined){
		callbackReturn();
	    }

	}
	doAjax("drives",request, callback);
    }
}
function showUpload(dirId, name){
    var uploadId=uniqid();
    var content = '<div id="upload_'+uploadId+'"><div class="upload_main"><span class="timer hidden"></span>';
    content += '<iframe src="?page=upload&id='+uploadId+'&folder_id='+dirId+'" class="frame_upload" frameborder="0" scrolling"no"></iframe>';
    content += '<div class="upload_bar"></div></div></div>';
    
    create_pod("Upload to: "+name,content,uploadId);
    $("#upload_"+uploadId+" .upload_bar").progressbar({
	    value: 0
    });
    uploadStatus(uploadId,dirId, 0);
}
function checkUpload(uploadId, prevPercent){
    var currentPercent = $("#upload_"+uploadId+" .upload_bar").progressbar("option", "value");
    if(currentPercent != 0 && currentPercent == prevPercent && prevPercent > 90){
	var processing = $("#upload_"+uploadId+" .info_processing").text();
	//console.log(processing);
	if(!processing){
	    $('#upload_'+uploadId+' .upload_bar').progressbar({value: 100});
	    $("#upload_"+uploadId+" .upload_main").slideUp();
	    $("#upload_"+uploadId).append("<p class='info_processing'>We have received your files and they are now being processed on the cloud.</p><div class='loading'></div>");
	}
    }
}

function uploadStatus(uploadId, dirId, prevPercent){
    if($("#upload_"+uploadId).length == 0)
	return null;
    
    var callback = function(data){
	var percent = data * 1;
	if(percent==101){
	    display_msg("success","File successfully uploaded");
	    var uploadTimer = $("#upload_"+uploadId+" .timer").text();
	    clearTimeout(uploadTimer);
	    close_pod(uploadId);
	    toggleFolder(dirId,true);
	}
	else{
	    $('#upload_'+uploadId+' .upload_bar').progressbar({value: percent});
	    var uploadTimer=setTimeout('uploadStatus('+uploadId+','+dirId+','+percent+')',1500);
	    $("#upload_"+uploadId+" .timer").text(uploadTimer);
	    
	}
    }
    var request = new Object();
    request.id = uploadId;
    doAjax("apc", request, callback);
    var t = setTimeout('checkUpload('+uploadId+','+prevPercent+')', 3200);
}

// PAGE: SEARCH
var searching = false;
var searchRequest;
var searchCallback;
function loadSearch(page){
    var string = $("#input_search").val();
    var len = string.length;
    if(len<1){
	$("#search_results").html("");
	searching = false;
	return;
    }
    /*
    if(searching == true){
	var searchingString = $("#search_results .searching_now").text();
	if(string == searchingString)
	    return;
    }
    */

    searching = true;
    
    $("#search_results").html("<div class='loading' style='height:90px'><span class='searching_now hidden'>"+string+"</span>&nbsp;</div>");
    var input_type = $('input:radio[name=input_type]:checked').val();
    searchCallback = function(data){
	var searchingString = $("#search_results .searching_now").text();
	var newString = $("#input_search").val();
	if(newString == searchingString){
	    if(data==""){
		    data = "<p>Sorry, no results found.</p>";
	    }
	    $("#search_results").html(data);

	    reloadLightBox();
	    $(".button-next").button({icons: {secondary: "ui-icon-circle-arrow-e"}});
	    $(".button-back").button({icons: {primary: "ui-icon-circle-arrow-w"}});
	    searching = false;
	}
    }
    searchRequest = new Object();
    searchRequest.term = string;
    searchRequest.type = input_type;
    searchRequest.page = page;
    var t = setTimeout('doSearch("'+string+'");', 400);
}
function doSearch(searchingString){
    var newString = $("#input_search").val();
    if(searchingString == newString){
	doAjax('search', searchRequest, searchCallback);
    }
}