//var string = 'Пн: 8:00, Вт: 15:00, Вт: 15:30, Вт: 16:00, Вт: 17:00, Ср: 14:30, Ср: 17:30, Чт: 11:30';
var input;
var output;
var arrayInput;
var arrayTemp =  [];
var arrayIndex = [];
var arrayOutput = [];
var temp='';
var tempL='';
var tempS='';
var value;
$(document).ready(function(){

	$('#button').clickFunction();

});



 
//jquery plugins--------------------------------------
(function($){

$.fn.clickFunction = function(){
	this.on('click', function(){
		input = $('#input').val()
		if (input =='' ){
			alert('please insert data');
		} else {
			console.log('input', input);
			arrayInput = input.split(',');
			console.log('arrayInput with spaces', arrayInput);
			for (var i = 0; i < arrayInput.length; i++) {
				if(arrayInput[i].substr(0, 1) ==' '){
					arrayInput[i] = arrayInput[i].slice(1);	
				};
			}
			arrayInput = arrayInput.sort();
			console.log('arrayInput', arrayInput);
			//------------task1
			for (var i = 0; i < arrayInput.length; i++) {
				if(arrayInput[i].slice(0, 1) != temp) {
					arrayIndex.push(i);
				};

				temp = arrayInput[i].slice(0, 1);			
			}
			console.log('arrayIndex', arrayIndex);
			for (var i = 0; i < arrayIndex.length; i++) {

				if ( (arrayIndex[i] !=  arrayIndex[i+1] - 1) && (i+1 < arrayIndex.length) ) {
				
					arrayOutput.push(' ' +arrayInput[arrayIndex[i]]+' - '+arrayInput[arrayIndex[i+1]-1].slice(4));
				} else {
					arrayOutput.push(' ' + arrayInput[arrayIndex[i]]);
				};
	
			}
			value = arrayOutput.toString();
			console.log('arrayOutput', arrayOutput);
			$('#output').val(value);
			var list = $('.result').append('<ul></ul>').find('ul');
			for (var i = 0; i < arrayOutput.length; i++){
			    list.append('<li>'+arrayOutput[i]+'</li>');
			//-----------------------

			}
			//-----task2-----   
			arrayIndex = [];
			temp = '';
			console.log('arrayIndex', arrayIndex);

			/*for (var i = 0; i < arrayInput.length; i++) {
				
				if(arrayInput[i].slice(0, 1) == temp) {
					arrayIndex.push(i);
				};
			temp = arrayInput[i].slice(0, 1);
							
			}
			console.log('arrayIndex', arrayIndex);*/
			for (var i = 0; i < arrayInput.length; i++) {
				
				if(arrayInput[i].endsWith('30') ) {
					arrayIndex.push(i);
				};
			
							
			}
			console.log('arrayIndex', arrayIndex);
		};
		
	})
}
   
	
})(jQuery);