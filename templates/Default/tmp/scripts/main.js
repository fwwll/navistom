$(document).ready(function(e) {
	
	$('.datepicker').datepicker();
	
    $('.a-toggle-down').aDropDownMenu();
	
	$(".select-2").select2({
		closeOnSelect: false,
        blurOnChange: true
	});
	
	$(".validation").validationEngine({
		promptPosition: "topLeft"
	});
	
	$("input[type=radio], input[type=checkbox], input[type=file]").not(".uploadify").uniform();
	
	/*$(".autosize").autosize({
				minHeight: 50
			});*/
	
	$('[maxlength]').inputlimiter({
		limit: $(this).attr('maxlength'),
		remText: 'Осталось %n символов'
	});
	
	$('.editor').redactor({
		lang: 'ru',
		buttons: ['bold', 'italic', 'deleted', '|', 
			'alignleft', 'aligncenter', 'alignright', '|', 
			'unorderedlist', 'orderedlist', '|',
			'link', 'image', 'video']
	});
	
	/*$(".ad-gallery").adGallery({
		width: 700,
		height: 560,
		effect: 'fade'
	});*/
});