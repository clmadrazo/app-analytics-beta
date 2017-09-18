$(function(){
	$('.box-dashboard-content ul').append('<div class="clear"></div>');
	$('.last').after('<div class="clear"></div>');
	$('.calendar-content-item ul li').prepend("<span class='glyphicon glyphicon-chevron-right'></span> ");

//	$( ".post-form #texto" ).keypress(function() {
//		$('.post-preview p').html($(this).val());
//	});

	var postFormH = $('.post-form').css('height');
	var postPreviewH = $('.post-preview').css('height');

	if(postFormH >= postPreviewH){
		$('.post-form').css('height', postFormH);
		$('.post-preview').css('height', postFormH);
	}else{
		$('.post-form').css('height', postPreviewH);
		$('.post-preview').css('height', postPreviewH);
	}

//$('.collapse').collapse();
});