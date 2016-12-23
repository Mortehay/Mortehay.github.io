

//----------compare function---compare array of objects-----------------
function compare(a,b) {
  if (a.table_id < b.table_id)
    return -1;
  if (a.table_id > b.table_id)
    return 1;
  return 0;
}
//---------------------------------------------------------------------------------------
//-------------------two new prototype functions, contains and unique
Array.prototype.contains = function(v) {
    for(let i = 0; i < this.length; i++) {
        if(this[i] === v) return true;
    }
    return false;
};

Array.prototype.unique = function() {
    var arr = [];
    for(let i = 0; i < this.length; i++) {
        if(!arr.contains(this[i])) {
            arr.push(this[i]);
        }
    }
    return arr; 
}
//----------------------------------------------------------------------------------------------------------------
// user counting tool
  function count(user, users,uniqueVisitTime,countArr){
    	for (let i = 0; i < uniqueVisitTime.length; i++) {
	let num = 0;

        	for (let j = 0; j < users.length; j++) {
        		if (uniqueVisitTime[i] == users[j].login_time) {
        			if (users[j].e_mail == user ) {
        				++num;	
        			}
        		} 
        		
        		        		
        	}
        	countArr.push(num);
        }
    	return countArr
}
//----------------------------------------------------------------------------------------------------------------
//statistics drwing tool
function statistcsDraw(data){
	logins = JSON.parse(data);
                //console.log('data', logins);
                // console.log('data', logins.response.length);
                 let users =  logins.response;   
                 let usersName =[];
                 let uniqueVisitTime =[];
                 let visitorsArr = [];
                 let countArr = [];
                 let names =[];
                 for (let i = 0; i < users.length; i++) {
               	usersName.push(users[i].e_mail);
               	uniqueVisitTime.push(users[i].login_time)
                 }
                 //console.log('usersName', usersName.unique());
                 uniqueVisitTime =uniqueVisitTime.unique();
                 names = usersName .unique();
                 //console.log('names', names);
                 //console.log('uniqueVisitTime', uniqueVisitTime.unique());
                for (let j = 0; j < names.length; j++) {
                	 count(names[j], users,uniqueVisitTime, countArr)
	                 for (let i = 0; i < uniqueVisitTime.length; i++) {
	                 	if (countArr[i]>0) {
	                 		visitorsArr.push({
		                 		x: uniqueVisitTime[i],
		                 		y: countArr[i],
		                 		group:j,
		                 		label: names[j]
		                 	});
	                 	}
	                 	
	                 }
	                 countArr = [];
	                 //console.log('visitorsArr', visitorsArr);
                }
                $('.visualization').remove();   
                $('.container').next().after('<div class="visualization" id="visualization"></div>');
                         
                let groups = new vis.DataSet();
                for (let i = 0; i < names.length; i++) {
                	groups.add({
                		id:i,
                		content: names[i],
                		options:{
                			drawPoints:'square'
                		}

                	});
                }
                let container = document.getElementById('visualization');
                //------------------------------------------------------
	let dataset = new vis.DataSet(visitorsArr);
	let options = {
		start: uniqueVisitTime[0],
		end: uniqueVisitTime[uniqueVisitTime.length -1],
		legend: true,
		defaultGroup: 'ungrouped'

	};
	vargraph2d = new vis.Graph2d(container, visitorsArr, groups,options);
	//------------------------------------------------------
}
//-------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------
//closer span :)
function closeSpan(index){
	$('.'+index).prepend('<span class="closeSpan"></span>');
	$('.closeSpan').on('click', function(){
		console.log('click');
		$(this).parent().remove();
	})
}

//------ function displays data as table - it can be linkt to diifferent tag class----------------------
function displayTableData(mainTagClass, joinedToTgClass, data, vocabulary = 'noVocabulary'){
	//console.log('data', data);
               // console.log('data', resp.response.length);
               //console.log('click');
	resp = JSON.parse(data);
	console.log('resp',resp);
	$('.'+mainTagClass).remove();
	
		$('.'+joinedToTgClass).next().after('<div class="'+mainTagClass+' clear"><table style="width:inherit;"></table></div>');
		 let header = '';
	               if (vocabulary !==  'noVocabulary') {
	               	header +='<tr>'
	               	for (let i = 0; i < vocabulary.length; i++) {
	               		header +='<th>'+vocabulary[i]+'</th>';
	               	}
	               	header +='</tr>';
	               	$('.'+mainTagClass).find('table').append(header);
	               } else {
	               	console.log('noVocabulary for table header')
	               }

	               //let unsortedList = resp.response;
	               let list = resp.response;
	               //let list =unsortedCableList.sort(compare);

	               for (let i = 0; i < list.length; i++) {
	               	let row ='<tr>';
	               	let rowData = list[i];
	               	//console.log('rowData',rowData);
	               	
	               	if(vocabulary.findIndex(x => x == '№')>-1){
	               		rowData['1'] = i+1;
	               	}
	               	//console.log('rowData', rowData);
	               	for (let key in rowData) {
	               		row +='<td>'+rowData[key]+'</td>';
	               	}
	               	row += '</tr>';
	               	$('.'+mainTagClass).find('table').append(row);	
	               }

}
//-------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------
let params = {
	cableChannelTopologyUpdate:{
		phpFile: 'cableChannelTopologyUpdate',
		id:'cable_channel_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	ctvTopologyUpdate:{
		phpFile:'ctvTopologyUpdate',
		id:'ctv_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	etherTopologyUpdate:{
		phpFile:'etherTopologyUpdate',
		id:'ether_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	cableChannelCabelDataUpdate:{
		phpFile:'cableChannelCabelDataUpdate',
		id:'cable_channel_cable_data_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	cableChannelChannelDataUpdate:{
		phpFile:'cableChannelChannelDataUpdate',
		id:'cable_channel_channel_data_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	textexchange:{
		phpFile:'textexchange',
		id:'textexchange',
		type:'GET',
		displayResult: false,
		displayStyle:'none'
	},
	userLoginView:{
		phpFile:'userLoginView',
		id:'userLoginView',
		type:'GET',
		displayResult: true,
		displayStyle:'graph'
	},
	cityBuildingDataUpdate:{
		phpFile:'cityBuildingDataUpdate',
		id:'city_building_data_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	cableChannelCableDataView:{
		phpFile:'cableChannelCableDataView',
		id:'cable_channel_cable_dataView_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	cableAirCableDataUpdate:{
		phpFile:'cableAirCableDataUpdate',
		id:'cable_air_cable_data_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cableAirCableDataView:{
		phpFile:'cableAirCableDataView',
		id:'cable_air_cable_dataView_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	toCoverageUpdate:{
		phpFile:'toCoverageUpdate',
		id:'city_supply_to_eng',
		type:'POST'
	},
	usoCoverageUpdate:{
		phpFile:'usoCoverageUpdate',
		id:'city_supply_uso_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	ctvNodCoverageUpdate:{
		phpFile:'ctvNodCoverageUpdate',
		id:'ctv_city_nod_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityBuildingDublicatesFinder:{
		phpFile:'cityBuildingDublicatesFinder',
		id:'city_building_dublicates_finder_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	cityStateSwitches:{
		phpFile:'cityStateSwitches',
		id:'switches_state_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'table'
	},
	simpleuserRestrictionUpdate:{
		phpFile:'simpleuserRestrictionUpdate',
		id:'simpleuserRestrictionUpdate',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityEntranceDataUpdateOSM:{
		phpFile:'cityEntranceDataUpdateOSM',
		id:'building_entrance_OSM_data_update_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityTablesCreate:{
		phpFile:'cityTablesCreate',
		id:'tables_create_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityEntranceDataUpdateCUBIC:{
		phpFile:'cityEntranceDataUpdateCUBIC',
		id:'building_entrance_CUBIC_data_update_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityBuildingDataUpdateOSM:{
		phpFile:'cityBuildingDataUpdateOSM',
		id:'city_building_OSM_data_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityRoadsDataUpdateOSM:{
		phpFile:'cityRoadsDataUpdateOSM',
		id:'city_roads_OSM_data_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	}

}
//-------------------file upload params-------------------------------------------------------------------------------------------------
let fileUploadParams = {
	csvUpload:{
		phpFile:'csvUpload',
		fileId:'csv_file_upload',
		formId:'csvUploadForm',
		fileName:'csv_file_upload',
		formValueUpload:'Завантажити CSV',
		method:'POST',
		enctype:'multipart/form-data',
		submitName:'csvSubmit'
	}
}
//----------vocabulars----------------------------------------------------------------------------------------------------------------------
let vocabulary ={
	cableChannelCableDataView:['№','Технічні умови','Договір', 'Даткова угода','Акт прийомки','Затверджена картограма','Опис маршруту','Тип кабелю','Посилання на архів','id кабеля','Статус використання','запасна','Статус договору','№ПГС'],
	cityBuildingDataUpdate:['№','Місто', 'Вулиця','№будинку', '"Кубік" HOUSE_ID','Кільк.Квартир'],
	cableAirCableDataView:['№','id кабеля', 'Посилання на архів','Дата монтажа кабелю','Тип кабелю','Волоконність/Тип','Марка кабелю','№проекту', 'Призначення','Опис маршруту', 'Довжина, км'],
	cityBuildingDublicatesFinder:['№','Вулиця OSM', '№будинку OSM', 'Вулиця CUBIC', '№будинку CUBIC', '"Кубік" HOUSE_ID', 'Тип мережі', 'Координата будинку'],
	ctvTopologyUpdate:['№', 'Місто', 'Вулиця', '№будинку', 'Квартира', 'id вузла', 'Найменування вузла', 'Адреса ПГС', 'Адреса мат.вузла', 'id мат.вузла', 'Дата установки', 'notes','Відповідальний', 'Тип мережі', '"Кубік" HOUSE_ID'],
	etherTopologyUpdate:['№','Вулиця', '№будинку',  '№під"їзду', '№Поверху', 'Розташування', 'house_id', 'mac_address', 'ip_address', 'serial_numbe', 'hostname', 'sw_model',  'sw_inv_state', 'дата установки', 'дата зміни']
};

//------document ready-------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){
	//----------------tools--panel---------------------------------------------------------------------------------------------------
	//$('.container').toolsDisplay(vocabulary,'newTools', 'newTools');

	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------ajax requests------------------------------------------------------------------------------
	$('#cableChannelTopologyUpdate').phpRequest(params.cableChannelTopologyUpdate);
	$('#ctvTopologyUpdate').phpRequest(params.ctvTopologyUpdate);
	$('#etherTopologyUpdate').phpRequest(params.etherTopologyUpdate);
	$('#cableChannelCabelDataUpdate').phpRequest(params.cableChannelCabelDataUpdate);
	$('#textexchange').phpRequest(params.textexchange);
	$('#userLoginView').phpRequest(params.userLoginView);
	$('#simpleuserRestrictionUpdate').phpRequest(params.simpleuserRestrictionUpdate);
	//--------------------------------------------------------------------------------------------------------------------------------------
	$('#cityBuildingDataUpdateOSM').phpRequest(params.cityBuildingDataUpdateOSM);
	$('#cityRoadsDataUpdateOSM').phpRequest(params.cityRoadsDataUpdateOSM);

	$('#cityBuildingDataUpdate').phpRequest(params.cityBuildingDataUpdate);
	$('#cityBuildingDublicatesFinder').phpRequest(params.cityBuildingDublicatesFinder);
	$('#cityEntranceDataUpdateOSM').phpRequest(params.cityEntranceDataUpdateOSM);
	$('#cityEntranceDataUpdateCUBIC').phpRequest(params.cityEntranceDataUpdateCUBIC);
	//--------------------------------------------------------------------------------------------------------------------------------------
	$('#cableChannelChannelDataUpdate').phpRequest(params.cableChannelChannelDataUpdate);
	$('#cableChannelCableDataView').phpRequest(params.cableChannelCableDataView);
	//-------------------------------------------------------------------------------------------------------------------------------------
	$('#cableAirCableDataUpdate').phpRequest(params.cableAirCableDataUpdate);
	$('#cableAirCableDataView').phpRequest(params.cableAirCableDataView);
	//-----------------------------------------------------------------------------------------------------------------------------------
	$('.toolsListLabel').visibility('newTools');
	//-----------------------------------------------------------------TO///SO----------------------------------------------------------
	$('#toCoverageUpdate').phpRequest(params.toCoverageUpdate);
	$('#usoCoverageUpdate').phpRequest(params.usoCoverageUpdate);
	//-----------------------------ctv nod coverage update -----------------------------------------------------------------------
	$('#ctvNodCoverageUpdate').phpRequest(params.ctvNodCoverageUpdate);
	//-----------------------------switches state update------------------------------------------------------------------------
	$('#cityStateSwitches').phpRequest(params.cityStateSwitches);
	//-----------------------------add tables update------------------------------------------------------------------------
	$('#cityTablesCreate').phpRequest(params.cityTablesCreate);

	//----------------------------------file upload------------------------------------------------------------------------------
	$('#fullAccess_holder').fileUploadToTmp(fileUploadParams.csvUpload,'#fullAccess_holder');
});
//--------ajax error-------------------------------------------------------------------------------------------------------------------
$( document ).ajaxError(function( event, request, settings ) {
 console.log( "Error requesting page " , settings.url );
});
//---------ajax complete-------------------------------------------------------------------------------------------------------------------------------
$( document ).ajaxComplete(function( event,request, settings ) {
 console.log( "Request Complete");
});
//----------------------------------------------------------------------------------------------------------------------------------------
(function($){


//--------------ajax/php request----------------------------------------------------------------------------

$.fn.phpRequest = function(params) {
	$(this).on('click', function(){
		console.log(params.phpFile,$('#'+params.id).val() );
		let request = {};
		let attributId = $(this).attr('id');
		
		//-------------------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------
		console.log($(this).attr('id'));
		request[params.id] = $('#'+params.id).val();
		if ($('#'+params.id).val() !=='вибери місто') {
			$('.phpScripStatus').show();
			console.log('request',request);
			$.ajax({
				url: params.phpFile+'.php', //This is the current doc
				type: params.type,
				data: (request),
				success: function(data){
					if(  (data) && (params.displayResult == true) ) {
						let test =  JSON.parse(data);
						if( (test == null) || ( test.response == null)){
							alert('Відсутні нові елементи');
							$('.phpScripStatus').hide();
						} else {
							if( params.displayStyle == 'table' ) {
								displayTableData('displayResult'+attributId, 'container', data, vocabulary[attributId]);
								closeSpan('displayResult'+attributId);
							}
							if( params.displayStyle == 'graph'){
								statistcsDraw(data);
								closeSpan('visualization' );
							}
							// with the result from the ajax call
							console.log('data', data);
							$('.phpScripStatus').hide();
							
						}
					} else {
						$('.phpScripStatus').hide();
					}
				//	
				}
				
			}); 

		} else {
			alert('Будь ласка виберіть місто')
		}
		
	})
  	
};

//--------------troll tools display----------------------------------------------------------------------------

$.fn.visibility = function(selfTagId) {
	$(this).on('click', function(){
		let tempId = $(this).attr('id') ;
		$('._holder').removeClass('visible');
		$('#'+tempId+'_holder').addClass('visible');
		//console.log(tempId);
	})
}

//-------------add file upload box---------------------------------------------------------------------------
$.fn.fileUploadToTmp = function(params, target){
	console.log('target', target);
	$(target).append('<form id="'+params.formId+'" action="'+params.phpFile+'.php'+'" method="'+params.method+'" enctype="'+params.enctype+'"></form>');
	$('#'+params.formId).append(/*'<label>виберіть файл CSV</label>'*/
		'<input type="file" name="'+params.fileName+'" id="'+params.fileId+'" class="myToolButton">'+
		'<input type="submit" value="'+params.formValueUpload+'"name="'+params.submitName+'" class="myToolButton">');

}
	
})(jQuery);