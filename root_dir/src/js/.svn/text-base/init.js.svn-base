function loadButtons(){
    $(".button,input:submit").button();
    $(".buttonset").buttonset();
    $("input:file").button({icons: {primary: 'ui-icon-disk'}});
    $(".button-accept").button({icons: {primary: "ui-icon-check"}});
    $(".button-contact").button({icons: {primary: "ui-icon-contact"}});
    $(".button-add").button({icons: {primary: "ui-icon-plusthick"}});
    $(".button-delete").button({icons: {primary: "ui-icon-circle-close"}});
    $(".button-view").button({icons: {primary: "ui-icon-copy"}});
    $(".button-play").button({icons: {primary: "ui-icon-play"}});
    $(".button-update").button({icons: {primary: "ui-icon-refresh"}});
    $(".button-top").button({icons: {primary: "ui-icon-signal"}});
    $(".button-unlock").button({icons: {primary: "ui-icon-unlocked"}});
    $(".button-lock").button({icons: {primary: "ui-icon-locked"}});
    $(".button-list").button({icons: {primary: "ui-icon-note"}});
    $(".button-download").button({icons: {primary: 'ui-icon-disk'}});
    $(".button-image").button({icons: {primary: 'ui-icon-image'}});
}
var global_connections = false;
function init_load(){
    $("#input_music_playlist").unbind('keypress');
    $("#input_music_playlist").keypress(function(event){
     if (event.which == 13) {
	    event.preventDefault();
	    if($(this).siblings(".music_playlist_id").text()){
		updatePlaylist();
	    }
	    else{
		createPlaylist();
	    }
	}
    });
    if($(".input_connections").length > 0){
	if(global_connections == false){
	    var request = new Object();
	    var callback = function(data){
		global_connections = $.parseJSON(data);
		$(".input_connections").autocomplete({
		    source: global_connections
		});
	    }
	    doAjax('connections', request, callback);
	}
	else{
	    $(".input_connections").autocomplete({
		source: global_connections
	    });
	}
    }
    $(".input_copy_paste").focus(function(){
	$(this).select();
    });
    addthis.toolbox(".addthis_toolbox");
    $(".ajaxform input").keypress(function(event){
	if (event.which == 13) {
	    event.preventDefault();
	    var formId = $(this).parents(".ajaxform").attr('action');
	    setTimeout(formId+";", 1);
	}
    });
    $(".progressbar").each(function(){
	    var percent = $(this).text();
	    $(this).empty();
	    if(percent){
		    percent = 1 * percent;
		    $(this).progressbar({
			    value: percent
		    });
	    }
    });
    $("#top_nav ul li, #music_playlist li").hover(function(){
	    $(this).addClass("hover");
    },function(){
	    $(this).removeClass("hover");
    });
    $("#input_search").keyup(function(){
	    loadSearch();
    });
    $("input:radio[name=input_type]").change(function(){
	    loadSearch();
    });
    $("#input_search").focus();
    $("h2, ul li, .message, .box, .warning, input").addClass("ui-corner-all");
    reloadLightBox();
    set_droppable();
    $(".resizable").resizable();
    $(".progress").progressbar();
    loadButtons();
}
$(document).ready(function() {
    init_load();
    $(".message").slideDown("slow").delay(10000).slideUp("slow");
    $(".message .close_msg").click(function(){
	$(this).parents(".message").remove();
    });
    mw.ready( function(){
	mw.setConfig('EmbedPlayer.EnableOptionsMenu', false );
	mw.setConfig('EmbedPlayer.AttributionButton', false );
	mw.setConfig('jQueryUISkin', 'sunny');
    });
});