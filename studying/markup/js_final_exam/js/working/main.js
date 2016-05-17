//grlobal variebles-----------------------------------
var info=[];
var currentLeftValue =0;
/*var pixelOffset=260;*/
var options1={elementsList:'.carousel-1__list', tempValue:'.carousel-1__element', arrowLeft:'carousel-1__arrow-left', arrowRight:'carousel-1__arrow-right', visibleElement: 1, currentLeftValue:0, /*pixelsOffset:280*/};
var options2={elementsList:'.carousel-2__list', tempValue:'.carousel-2__element', arrowLeft:'carousel-2__arrow-left', arrowRight:'carousel-2__arrow-right', visibleElement: 1, currentLeftValue:0, /*pixelsOffset:280*/};
var options3={elementsList:'.carousel-3__list', tempValue:'.carousel-3__element', arrowLeft:'carousel-3__arrow-left', arrowRight:'carousel-3__arrow-right', visibleElement: 1, currentLeftValue:0, /*pixelsOffset:280*/};
//----------------------------------------------------
function carouselChange(){
	var winWidth = $(window).width();
	console.log('winWidth',winWidth);
	if ( winWidth <=320) {
		options1.pixelsOffset = 280;
		options2.pixelsOffset = 280;
		options3.pixelsOffset = 280;
		options1.currentLeftValue=0;
		options2.currentLeftValue=0;
		options3.currentLeftValue=0;
	}
	if ( (winWidth >320) && (winWidth<768) ) {
		options1.pixelsOffset = 280;
		options2.pixelsOffset = 280;
		options3.pixelsOffset = 280;
		options1.currentLeftValue=0;
		options2.currentLeftValue=0;
		options3.currentLeftValue=0;
	}
	if ( winWidth >=768  ) {
		options1.pixelsOffset = 260;
		options2.pixelsOffset = 260;
		options3.pixelsOffset = 260;
		options1.currentLeftValue=0;
		options2.currentLeftValue=0;
		options3.currentLeftValue=0;
	}
	
	/*console.log('options1.pixelsOffset ',options1.pixelsOffset );
	console.log('options2.pixelsOffset ',options2.pixelsOffset );
	console.log('options3.pixelsOffset ',options3.pixelsOffset );*/
};
//generating object with link an classes--------------
for (var i = 0; i < 6; i++) {
		info.push({
			link: 'img/masonry/img'+(i+1)+'.png',
			tagClass: 'background'+(i+1)
		});
};
//----------------------------------------------------

//--jquery main---------------------------------------

$(document).ready(function(){
//-it does not work correctly :(
	carouselChange();
	
	$(window).on('resize', carouselChange);
	
  	$('.carousel-1__arrow-left').carouselMove(options1);
	$('.carousel-1__arrow-right').carouselMove(options1);
	$('.carousel-2__arrow-left').carouselMove(options2);
	$('.carousel-2__arrow-right').carouselMove(options2);
	$('.carousel-3__arrow-left').carouselMove(options3);
	$('.carousel-3__arrow-right').carouselMove(options3);

	$('.grid').masonry({
	  itemSelector: '.grid-item',
	  columnWidth: '.grid-sizer',
	  isFitWidth: true
	});
	$('.grid-sizer').template();
	for (i=0; i < info.length; i++) {
	    $('.background'+(+1+i) ).css("background-image", "url('" + info[i].link + "')");
	};

	
    
});

//---------------------------------------------------



(function($){				
//carousel plugin------------------------------------	
	$.fn.carouselMove = function(options){
		
		/*currentLeftValue =0;*/
		var elementsList = $(options.elementsList);
		var tempValue = $(options.tempValue).length-options.visibleElement;

		this.on({
			click: function(){
	        	
	        	if ($(this).hasClass(options.arrowRight)) {

	        		if (options.currentLeftValue > (-tempValue*options.pixelsOffset) ) {
	        			options.currentLeftValue -= options.pixelsOffset;
		        		elementsList.animate({ left : options.currentLeftValue + "px"}, 1000);
		        		console.log("currentLeftValue---=",options.currentLeftValue);
	        		};
	        	}
	        	if ($(this).hasClass(options.arrowLeft)) {

	        		if (options.currentLeftValue < 0) {
	        			options.currentLeftValue += options.pixelsOffset;
	       				elementsList.animate({ left: options.currentLeftValue + "px"}, 1000);
		        		console.log("currentLeftValue+++=",options.currentLeftValue);
	        		};
	        	}
	        	
	        	console.log("currentLeftValue=***",options.currentLeftValue);
	        	console.log('options1.pixelsOffset ',options1.pixelsOffset );
	        }
		});
	};
//----------------------------------------------------

//template plugin-------------------------------------
	$.fn.template = function(options){
		var html=$('#template').html();
		
	
		console.log('info', info);

		var content = tmpl(html, {
			data: info
		});
		$(this).append(content);

	};
//-----------------------------------------------------	
	
})(jQuery);



