
$(document).ready(function(){

	$('#visible_maker').on('click', function(){
	 	$('#request').toggle();
	 	console.log('click');

	});
	$('#return').on('click', function(){
 		$('#request').hide();
 	});
 	$('.tools__visible').on('click', function(){

 		$('#tools').find('.tools__hidden').toggle();
 	});

});