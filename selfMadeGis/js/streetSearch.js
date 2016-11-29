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
	//console.log('searchParams', searchParams);
	//let test = $('#SearchTabPanel').contents();
	//console.log('test',test);
	
	//$( "input[name*='cubic_street']" ).attr('list','street_list');
	//---------------------------street search ajax request---------------------------------------------------------------------------
	//$(document).find('#SearchTabPanel input').data('list', 'street_list');
	$(document).phpRequest(searchParams.streetSearch);
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
				//$("input[name$='cubic_street']" ).attr('list','street_list');
				//$("input[name$='cubic_street']" ).prop('list','street_list');
				//$('#SearchTabPanel').children().children().attr('list','street_list');
				$('#ext-gen324').attr('list','street_list');
				//$('div.x-form-element').children().attr('list','street_list');
				//$('form.x-panel-body.x-panel-body-noheader.x-panel-body-noborder.x-form').css('background-color','red');
				//$('.x-form-element').children().attr('list','street_list');
				// with the result from the ajax call
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
};

})(jQuery);