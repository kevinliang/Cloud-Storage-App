function uniqid(){
    var newDate = new Date;
    return newDate.getTime();
}
function display_message(msg,type){
    if(type==null){
	    type="error";
    }
    if(type=="error"){
	    var icon = "cancel";
    }
    else{
	    var icon = "accept";
    }
    var id = uniqid();
    $("#msg_area").append("<div id='msg_"+id+"' class='message "+type+"'><div class='message_i'><img src='src/img/icons/" + icon + ".png'><a title='close' href='javascript:closeMsg("+id+");' class='fright'><img src='src/img/icons/cross.png'></a> " + msg + "</div></div>");

    $("#msg_"+id).fadeIn("slow");
}
function closeMsg(id){
    $("#msg_"+id).remove();
}

function validateEmail(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      return false;
   }
   return true;
}
function replaceDialog(content){
    $(".loading").remove();
    if(content){
	    $("#dialog_box").append(content);
    }
    else{
	    $("#dialog_content").slideDown();
    }
}
function hideDialog(){
    $("#dialog_content").slideUp('slow');
    $("#dialog_box").prepend("<div class='loading' style='width:100%;text-align:center;padding:20px 0;'><img src='src/img/site/loading_bar.gif' /></div>");
}
function forgot(){
    $(".message").remove();
    $(".loading").remove();
    var error = 0;
    var email = $("#input_email").val();
    if(!email){
	    display_message("You must supply an email");
	    error = 1;
    }
    else if(!validateEmail(email)){
	    display_message("Invalid email address");
	    error = 1;
    }
    if(error == 0){
	    hideDialog();
	    var field = $("#recaptcha_response_field").val();
	    var challenge = $("#recaptcha_challenge_field").val();
	    $.post("index.php?ajax=forgot", { email: email, recaptcha_response_field: field, recaptcha_challenge_field: challenge },
	    function(data,status){
		    if(status!="success"){
			    replaceDialog();
			    display_message("Connection Failed");
		    }
		    else{

			    var split=data.split("-");
			    if(data==""){replaceDialog();display_message("Page failed to load");}
			    else if(data=="error-email"){replaceDialog();display_message("Email address invalid");}
			    else if(data=="error-recaptcha"){replaceDialog();display_message("The reCaptcha was incorrect");}
			    else if(data=="error-none"){replaceDialog();display_message("Email address does not exist in our system");}
			    else if(split[0]=="success"){
				    display_message("Reset key sent","success");
				    replaceDialog("<p>An email has been sent to your inbox with instructions to reset your account</p>");
			    }
			    else{

				    replaceDialog();
				    display_message("Page unable to load");
			    }
		    }
		    Recaptcha.reload();
	    });
    }
}
function register(){
    $(".message").remove();
    $(".loading").remove();
    var error = 0;
    var name = $("#input_name").val();
    var email = $("#input_email").val();
    var password1 = $("#input_password1").val();
    var password2 = $("#input_password2").val();
    if(!name){
	    display_message("You must supply a name");
	    error = 1;
    }
    if(!email){
	    display_message("You must supply an email");
	    error = 1;
    }
    else if(!validateEmail(email)){
	    display_message("Invalid email address");
	    error = 1;
    }
    if(!password1||!password2){
	    display_message("You must enter your password twice");
	    error = 1;
    }
    if(password1!=password2){
	    display_message("Your two passwords do not match");
	    error = 1;
    }
    if(!validatePassword(password1, {
    length:   [8, Infinity],
    numeric:  1
    })){
	    display_message("Your password does not follow the security rules");
	    error = 1;
    }
    if(error == 0){
	    hideDialog();
	    var field = $("#recaptcha_response_field").val();
	    var challenge = $("#recaptcha_challenge_field").val();
	    $.post("index.php?ajax=register", { name: name, email: email, password: password1, recaptcha_response_field: field, recaptcha_challenge_field: challenge },
	    function(data,status){
		    if(status!="success"){
			    replaceDialog();
			    display_message("Connection Failed");
		    }
		    else{
			    var split=data.split("-");
			    if(data==""){replaceDialog();display_message("Page failed to load");}
			    else if(data=="error-email"){replaceDialog();display_message("Email address invalid");}
			    else if(data=="error-recaptcha"){replaceDialog();display_message("The reCaptcha was incorrect");}
			    else if(data=="error-taken"){replaceDialog();display_message("Email address already in our system");}
			    else if(data=="error-password"){replaceDialog();display_message("Invalid password");}
			    else if(split[0]=="success"){
				    display_message("Successfully registered","success");
				    replaceDialog("<p>An email has been sent to your inbox with instructions to open your account</p>");
			    }
			    else{
				    replaceDialog();
				    display_message("Page unable to load");
			    }
		    }
		    Recaptcha.reload();
	    });
    }
}

function login(){
    $(".message").remove();
    $(".loading").remove();
    $("#msg_area").empty();
    hideDialog();

    var username = $("#input_username").val();
    var password = $("#input_password").val();
    var remember = $("#input_remember:checked").val();

    $.post("index.php?ajax=login", { username: username, password: password, remember: remember},
    function(data,status){
	    if(status!="success"){
		    replaceDialog();display_message("Connection Failed");
	    }
	    else{
		    var split=data.split("-");
		    if(data==""){replaceDialog();display_message("Login failed to load");}
		    else if(data=="error-login"){replaceDialog();display_message("Invalid login");}
		    else if(data=="error-database"){replaceDialog();display_message("Database offline");}
		    else if(split[0]=="success"){
			    var t = setTimeout("window.location = '"+split[1]+"'",500);
		    }
		    else{
			    display_message(data);
			    replaceDialog();
			    display_message("Login failed, report bug.");
		    }
	    }
	    $("#input_username").focus();
    });
}
function loadLogin(){
    $('#input_password').keyup(function(event) {
	    if (event.keyCode == '13') {
		    login();
	    }
    });
}
$(document).ready(function() {
    var title = $("#dialog_title").text();
    $("#dialog_box").dialog({
	    title: title,
	    width: 500,
	    draggable: false,
	    resizable: false,
	    modal: true,
	    closeOnEscape: false,
	    create: function(){
		$("#dialog_box").css("height","500px");
	    },
	    close: function(){
		    window.location = "http://clouthe.com";
	    },
    });
    
    $("input").focus(function(){
	    $(this).addClass("active");
    });
    $("input").blur(function(){
	    $(this).removeClass("active");
    });
    $("input:first").focus();
    $(".button-play").button({icons: { primary: "ui-icon-play" }});
    $(".button-login").button({icons: { primary: "ui-icon-key" }});
    $(".button-register").button({icons: { primary: "ui-icon-script" }});
    loadLogin();
    $(".message").fadeIn("slow");
     $(".message .close_msg").click(function(){
	$(this).parents(".message").remove();
    });
});