function renameAlbum(albumId){
    var element = "#album_"+albumId;
   // console.log(element);
    var currentName=$(element).text();
   // console.log(currentName);
    
    
    var content = "<span id='renameAlbum_"+albumId+"'><input type='text' class='input_renameAlbum"+albumId+"' value='"+currentName+"'> <a href='#'><img src='src/img/icons/add.png' class='icon'></a></span>";

    $(element).replaceWith(content);
    
    
    $("#renameAlbum_"+albumId+" > .input_renameAlbum").focus();
    $("#renameAlbum_"+albumId+" > a").click(function(){
		doRenameAlbum(albumId);
    });
    
    $(".input_renameAlbum"+albumId).keyup(function(event) {
	    if (event.keyCode == '13') {
			event.preventDefault();
			doRenameAlbum(albumId);
	    }
	});
 }

function doRenameAlbum(albumId){
    var request = new Object();
    request.id = albumId;
    request.name = $("#renameAlbum_"+albumId+" > input:first").val();
  //  console.log(request.name);
    
    var callback = function(data){
		display_msg("success", data);
		$("#renameAlbum_"+albumId).replaceWith("<div id=album_"+albumId+">"+request.name+"</div>");
	}
    doAjax("photos",request, callback);
}
