let params = {
	phpFile:'php/dbinput.php',
	type:'POST',

}
function checkker(element){
	let len = ($('#'+element).val()).length;
	if( len == 0){
		$('#'+element).closest('li').find('.checker').css({'background-image':'url(img/empty.png)'});
	} else {
		$('#'+element).closest('li').find('.checker').css({'background-image':'url(img/filled.png)'});
	}
}
let ansverKeys = ['geschleht1','geschleht2', 'alter', 'producte' ,'monatliche_kosten','bewertung','wonhort'];

$(document).ready(function(){
	$('#submit').phpRequest(params);
	$('.table').fieldChecker();	
	
});
(function($){
$.fn.phpRequest = function(params) {
		
	$(this).on('click', function(){
		let request = {};
		//let ansverKeys = ['geschleht1','geschleht2', 'alter', 'producte' ,'monatliche_kosten','bewertung','wonhort'];
		console.log('click');
		ansverKeys.forEach(function(element) {
			if(element  == 'geschleht1' || element  == 'geschleht2'){
				if(element  == 'geschleht1' && $('#'+element).checked){
					request['geschleht']= true;
				} else  if( element  == 'geschlecht2' && $('#'+element).checked){
					request['geschleht'] = false;
				} else { request['geschleht'] = null;}
			} else {
				request[element] = $('#'+element).val();
			}
		});
		console.log(request);
		//if(request['geschleht'] == null ){
			//alert('please select Geschlecht');
		//} else {
			$.ajax({
				url: params.phpFile, //This is the current doc
				type: params.type,
				data: (request),
				success: function(data){
					console.log(data);
				}
			});
		//}
		
		
	})

};

$.fn.fieldChecker = function(){
	ansverKeys.forEach(function(element) {
		checkker(element);
		$('#'+element).on('change', function(){
			checkker(element);
		})
		
	})
}
})(jQuery);