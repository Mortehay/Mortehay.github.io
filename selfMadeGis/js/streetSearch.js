let searchParams = {
	streetSearch:{
		phpFile:'cityStreetSearch',
		type:'POST'
	}
}

$(document).ready(function(){
	//----------------street-search---------------------------------------------------------------------------------------------------
	let link = $(document).contents().find('#panel_header_title').context.URL;
	//console.log('cityName', link);
	let qgisPosition = link.search('qgis_');
	//console.log('qgisPosition', qgisPosition);
	let cityName  = link.substr(qgisPosition+5);
	cityName =cityName.substr(0, cityName.length-4);
	
	if(cityName.indexOf('_') == -1) {
		//console.log('cityName', cityName);
		searchParams.streetSearch['id'] = cityName;
	} else{
		cityName = cityName.substr(0,cityName.search('_'));
		//console.log('cityName', cityName);
		searchParams.streetSearch['id'] = cityName;
	}

	//---------------------------street search ajax request---------------------------------------------------------------------------
	//$(".x-form-element").children().attr("list","street_list");
	$(document).phpRequest(searchParams.streetSearch);

	//$(document.body).append('<script type="text/javascript">$(document).ready(function(){ $(".x-form-element").children().attr("list","street_list"); });</script>');
	//$('.x-form-element').children().attr('list','street_list');
});

//--------------ajax/php request----------------------------------------------------------------------------
(function($){

$.fn.phpRequest = function(params) {
		let request = {};
	
		//-------------------------------------------------------------------------------------

		request['city'] = params.id;
		//console.log(request);
		$.ajax({
			url: params.phpFile+'.php', //This is the current doc
			type: params.type,
			data: (request),
			success: function(data){
				//console.log('data', data);
				resp = JSON.parse(data);
				//console.log('resp',resp);
				let streets = resp.response;
				//console.log('streets', streets);	
				
				$('#streetName').empty();
				$('#streetName').append('<datalist id="street_list"></datalist>');
				for (let i = 0; i < streets.length; i++) {

			               	//$('input[type="text"]').css('color', 'blue');
			               	$('#street_list').append('<option>' + streets[i].cubic_street + '</option>')
		               	}
			}

		});
			$(document).on('click', function(){
				console.log('click');
				$(".x-form-element").children().attr("list","street_list"); 
			})  	
};

})(jQuery);