var text = '';
var img = '';
var state =[];
var newState =[];
var newStateVal;
var num = 0;
//---------------------------------------
function experience(data){
	text = '';
	//console.log('data',data)
	for (var i = 0; i < data.length; i++) {
		text = text+'<p>'+data[i].position+ ' -&nbsp;</p>'+ '<p>'+(data[i].company)+'</p>';   			
	}
	//console.log('text',text);
	return text;
}
//---------------------------------------------

function fullExperience(data){
	text = '';
	//console.log('data',data)
	for (var i = 0; i < data.length; i++) {
		text = text+
		'<p>'+data[i].position+'</p>'+ 
		'<p>'+(data[i].company)+'</p>'+ 
		'<p>'+(data[i].workingTime)+'</p>'+
		'<p>'+(data[i].responsibilities)+'</p>';   			
	}
	//console.log('text',text);
	return text;
}
//---------------------------------------------
function imgChecker(img){
	if(img!==''){
		img = '<img src="'+img+'">'
	} else {
		img = '';
	}
	return img;
}
//------------------------------------------------------
//https://api.myjson.com/bins/4gepe
$( document ).ready(function(){
	//start status check-------------------------------
	$('.selectionBar').find('input').each(function(){
		$(this).attr('data-num', num++);
		state.push($(this).prop('checked'));
		return state;
	});
	num = 0;
	console.log('state', state);
	newState = state;
	//----------------------------------------------------------
	$.get('https://api.myjson.com/bins/44nxy', function (data) {

	    	//console.log('data', data);
	    	$('.selectedList').template(data);

	    	$('.selectedList-inner__item').on('click', function(){
	    		var id = $(this).data('id');
	    		$('.selectedCv-view').empty();
	    		$('.selectedCv-view').fullTemplate(data, id);
	    	});
	    	$('.selectionBar_work-experience').find('label').on('click', function(){
	    		$('.selectedList').empty();
	    		$('.selectedCv-view').empty();
	    		var newData =[];
	    		//console.log('data',data);
	    		var tempVal = $(this).text();
	    		console.log(tempVal);
    			for (var b = 0; b < data.length; b++) {

				if (tempVal =='All') {
    					newData.push(data[b]);
    				}
    				if (tempVal =='Up to 1 year') {
					if (  data[b].workTime >= 0 && data[b].workTime <= 12  ) {
						newData.push(data[b]);
					}
    				}
    				if (tempVal =='1 to 3 years') {
					if (data[b].workTime  > 12 && data[b].workTime  <= 36) {
						newData.push(data[b]);
					}
    				}
    				if (tempVal =='3 to 6 years') {
    					if (data[b].workTime  > 36 && data[b].workTime  <= 72) {
	    					newData.push(data[b]);
	    				}
    				}
    				if (tempVal =='6+') {    				
    					if (data[b].workTime > 72) {
	    					newData.push(data[b]);
	    				}
    					
    				}
    			}	
    			
    			console.log('newData', newData);
	    		$('.selectedList').template(newData);
	    		$('.selectedList-inner__item').on('click', function(){
		    		var id = $(this).data('id');
		    		$('.selectedCv-view').empty();
		    		$('.selectedCv-view').fullTemplate(data, id);
		    	});	
	    	});

	});
    	$('.selectionBar').find('input').change(function(){
    		newStateVal = $(this).prop('checked');
    		newState[$(this).data('num')] = $(this).prop('checked');
    		//console.log('newStateVal', );
		if( state == newState) {
			$('.selectionBar__clear').show();
		}
		//console.log('newState', newState);
		//console.log('newState', $(this).data('num'));
	});
    	$('.selectionBar__clear').on('click', function(){
    		$(this).hide();
    		for (var k = 0; k < state.length; k++) {
    			$('.selectionBar').find('input').data('num', k).prop('checked', state[k]);
    		}
    		newState = state;
    		/*$('.selectionBar').find('input').each(function(){
			$(this).prop( "checked", state[$(this).data('num')] );
		});*/
    	});
    	

});
		
	
	


(function($){				

//template plugin-------------------------------------
	$.fn.template = function(data){
		var list = $('.selectedList').append('<ul class="selectedList-inner"></ul>').find('ul');
		for (var count = 0; count < data.length; count++){
		    list.append('<li>'+
		    	'<div class="selectionBar-item selectedList-inner__item clear" data-id="'+data[count].id+'">'+
			    	'<div>'+
			    		imgChecker(data[count].avatar)+
			    	'</div>'+
			    	'<div>'+
			    		'<p>'+data[count].job+'</p>'+
			    		'<p>'+data[count].name+'</p>'+
			    		'<p>'+data[count].salary+'</p>'+
			    		'<div class="clear itemAbility">'+
				    		'<img src="images/star.png">'+
				    		'<p>'+(data[count].workTime-(data[count].workTime)%12)/12+'y'+'</p>'+
				    		'<p>'+(data[count].workTime)%12+'m'+'</p>'+
				    		'<img src="images/cap.png">'+
				    		'<p>'+data[count].degree+'</p>'+
				    		'<img src="images/earth.png">'+
				    		'<p>'+data[count].language+'</p>'+
			    		'</div>'+
			    	'</div>'+
			    	'<div class="clear">'+
			    		experience(data[count].experience)+
			    	'</div>'+
		    	'</div>'+'</li>');
		}
	
	};
//-----------------------------------------------------
		$.fn.fullTemplate = function(data, id){
		var list = $('.selectedCv-view').append('<ul class="selectedCv-view-inner"></ul>').find('ul');
		for (var count = 0; count < data.length; count++){
			if (data[count].id == id ) {
				list.append('<li>'+
		    	'<div class="selectionBar-item selectedCv-view__item clear">'+
			    	'<div>'+
			    		'<img src="images/pin.png" alt"non">'+
			    		'<p>'+'&#8635;&nbsp;'+data[count].date+'</p>'+
			    	'</div>'+
			    	'<div>'+
			    		'<p>'+data[count].job+'</p>'+
			    		'<p>'+data[count].name+'</p>'+
			    		'<p>'+data[count].salary+'</p>'+
			    		'<div class="clear itemAbility">'+
			    			'<div>'+ '<img src="images/phone.png" alt="non">' + '<p>'+data[count].phone+'</p>'+ 
				    		'<img src="images/pointer_red.png" alt="non">'+ '<p>'+data[count].city+'</p>'+ '</div>' +
				    		'<div>'+ '<img src="images/mail.png" alt="non">'+ '<p>'+data[count].mail+'</p>'+
				    		'<img src="images/status.png" alt="non">'+ '<p>'+data[count].status+'</p>'+ '</div>' +
				    		'<div>'+ '<img src="images/knowledge.png" alt="non">'+ '<p>'+data[count].knowledge+'</p>'+ '</div>' +
			    		'</div>'+
			    	'</div>'+
			    	'<div class="clear">'+
			    		fullExperience(data[count].experience)+
			    	'</div>'+
			    	'<div>'+'<a target="_blank" href="'+data[count].cvLink+'">Viev CV</a>'+'</div>'+
		    	'</div>'+'</li>');
			}
		    
		}
	
	};
	
})(jQuery);