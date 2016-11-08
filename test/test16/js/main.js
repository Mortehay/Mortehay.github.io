var string = 'Вт: 15:00, Пн: 8:00, Чт: 12:00, Вт: 15:30, Вт: 16:00, Вт: 17:00, Чт: 11:00, Ср: 14:30, Вт: 14:00, Ср: 17:30, Вт: 10:00, Чт: 11:30';





//----------------------------------------------

//----------------------------------------------
$(document).ready(function(){

	$('#button').clickFunction();

});



 
//jquery plugins--------------------------------------
(function($){

$.fn.clickFunction = function(){
	
	this.on('click', function(){
		var input;
		var output;
		var value;
		var arraySorted =[];
		var arrayInput = [];
		var arrayTemp = [];
		var arrayIndex = [];
		var arrayIndexNew = [];
		var arrayIndexNew2D = [];
		var arrayOutput = [];
		var temp= -10000;
		var day = '';
		var time = '';
		var tempTime = 0;
		var theOrder = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс' ];
		
		input = $('#input').val();
		//input = string;
		if (input =='' ){
			alert('please insert data');
		} else {
			console.log('input', input);
			
			arrayInput = input.split(',');
			
			//console.log('arrayInput with spaces', arrayInput);
			for (var i = 0; i < arrayInput.length; i++) {
				if(arrayInput[i].substr(0, 1) ==' '){
					
					arrayInput[i] = arrayInput[i].slice(1);//remove empty space
					arrayIndex.push(theOrder.indexOf(arrayInput[i].substring(0,2))); //get index of week day
					arrayInput[i] = arrayInput[i].split(': '); //split 
				} else { 
					arrayIndex.push(theOrder.indexOf(arrayInput[i].substring(0,2))); //get index of week day
					arrayInput[i] = arrayInput[i].split(': ');}; //split
				if (arrayInput[i][1].indexOf(':30') >0) {

					arrayInput[i][1] = parseFloat(arrayInput[i][1].replace(':3', '.5'));
				} else {arrayInput[i][1] = parseFloat(arrayInput[i][1].replace(':', '.'));}
				arrayInput[i][0]=arrayIndex[i];

			}

			console.log('arrayInput', arrayInput);
			console.log('arrayIndex', arrayIndex);
			arraySorted = arrayInput.sort();			
			console.log('arraySorted', arraySorted); //sorted array
			//---------------------------
			for (var j = 0; j < theOrder.length; j++) {
				temp = -100000;
				for( var z = 0; z < arraySorted.length; z++) {
				
					if (arraySorted[z][0] == j){//console.log(arraySorted[z][1])
						arrayIndexNew2D.push(['','','']);	
						if( Math.abs(temp  - arraySorted[z][1]) <= 0.5) {
							console.log(temp - arraySorted[z][1]);
							console.log(z);
							arrayIndexNew.push(z);
							arrayIndexNew2D.push([arraySorted[z][0],arraySorted[z][1],z]);						
						};

					}
					temp = arraySorted[z][1];

				}
				
				console.log('---------------------------');
				
			}
			console.log('arrayIndexNew', arrayIndexNew);
			console.log('arrayIndexNew2D', arrayIndexNew2D);
			//-----generate result array
			for (var s = 0; s < theOrder.length; s++) {
				tempTime='';
				day = theOrder[s];
				for (var w = 0; w < arraySorted.length; w++) {
					if (arraySorted[w][0] == s) {
						if (arrayIndexNew.indexOf(w) >= 0) {
							console.log('---------');
							if ( (arraySorted[w][1] - Math.floor(arraySorted[w][1]) >0) ) {
							time = Math.floor(arraySorted[w][1]) + ':' + ((arraySorted[w][1] - Math.floor(arraySorted[w][1]) ) +'').replace('0.5','30');
							} else {
								time = arraySorted[w][1] + ':00';
							}

							if (tempTime =='') {
								tempTime = tempTime+time;
							} else {
								tempTime = tempTime+' - '+time;
							}
						} else {
							if ( (arraySorted[w][1] - Math.floor(arraySorted[w][1]) >0) ) {
							time = Math.floor(arraySorted[w][1]) + ':' + ((arraySorted[w][1] - Math.floor(arraySorted[w][1]) ) +'').replace('0.5','30');
							} else {
								time = arraySorted[w][1] + ':00';
							}
							if (tempTime =='') {
								tempTime = tempTime+time;
							} else {
								tempTime = tempTime+', '+time;
							}
						}
						
						
					}
					
				}
				
				if (arrayIndex.indexOf(s) >= 0) {
					arrayTemp.push(day +' ' + tempTime);
				}
				

			}				
			
			//console.log('arraySorted', arraySorted); //sorted array
			//console.log('arrayIndexNew.length', arrayIndexNew.length);
			//console.log('arraySorted.length', arraySorted.length);
			console.log('arrayTemp', arrayTemp);
			value = arrayTemp.toString();
			//value = arrayOutput.toString();
			console.log('arrayOutput', arrayOutput);
			$('.result').find('ul').remove('ul');
			$('#output').val(value);
			var list = $('.result').append('<ul></ul>').find('ul');
			for (var count = 0; count < arrayTemp.length; count++){
			    list.append('<li>'+arrayTemp[count]+'</li>');
			}
			

		};
		
	})
}
   
	
})(jQuery);