$(document).ready(function(){
	$('select:not(.not-chosen, .ui-datepicker select)').chosen({ width: '100%' });
	$('.modal-image').click(function(){	
		var url =  $(this).attr("href");
		$.fancybox.open({
			href 		: url, type : 'image',
			padding   : 0,
		});
		return false;
	});
	window.scrollTo(0,1);
});