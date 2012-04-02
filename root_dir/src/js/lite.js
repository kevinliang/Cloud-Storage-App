$(document).ready(function() {
    $(".button").button();
    $(".button-download").button({icons: {primary: 'ui-icon-disk'}});
    $(".button-open").button({icons: {primary: 'ui-icon-newwin'}});
    $(".button-upload").button({icons: {primary: 'ui-icon-disk'}});
    $(".input_url").click(function(){
	(this).focus();
	(this).select();
    });
    $("input").focusin(
	function(){
		$(this).addClass('active');
	}
    );
    $("input").focusout(
	function(){
		$(this).removeClass('active');
	}
    );
    $("#upload_form").submit(function(){
	$(this).slideUp("slow");
	$("body").append("<div><p>Uploading your files, to cancel, click on the red X at the top right of this pod.</p></div>")
    })
    $("#input_file").change(function(){
	var val = $(this).val();
	if(val){
	    $("#upload_form").submit();
	}
    })
});