

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
               $('.'+joinedToTgClass).next().after('<div class="'+mainTagClass+' clear"><table></table></div>');
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
               	console.log('rowData',rowData);
               	
               	if(vocabulary.findIndex(x => x == '№')>-1){
               		rowData['1'] = i+1;
               		
               	}
               	console.log('rowData', rowData);
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
		type:'POST'
	},
	ctvTopologyUpdate:{
		phpFile:'ctvTopologyUpdate',
		id:'ctv_city_eng',
		type:'POST'
	},
	etherTopologyUpdate:{
		phpFile:'etherTopologyUpdate',
		id:'ether_city_eng',
		type:'POST'
	},
	cableChannelCabelDataUpdate:{
		phpFile:'cableChannelCabelDataUpdate',
		id:'cable_channel_cable_data_city_eng',
		type:'POST'
	},
	cableChannelChannelDataUpdate:{
		phpFile:'cableChannelChannelDataUpdate',
		id:'cable_channel_channel_data_city_eng',
		type:'POST'
	},
	textexchange:{
		phpFile:'textexchange',
		id:'textexchange',
		type:'GET'
	},
	userLoginView:{
		phpFile:'userLoginView',
		id:'userLoginView',
		type:'GET'
	},
	cityBuildingDataUpdate:{
		phpFile:'cityBuildingDataUpdate',
		id:'city_building_data_eng',
		type:'POST'
	},
	cableChannelCableDataView:{
		phpFile:'cableChannelCableDataView',
		id:'cable_channel_cable_dataView_city_eng',
		type:'POST'
	},
	cableAirCableDataUpdate:{
		phpFile:'cableAirCableDataUpdate',
		id:'cable_air_cable_data_city_eng',
		type:'POST'
	},
	cableAirCableDataView:{
		phpFile:'cableAirCableDataView',
		id:'cable_air_cable_dataView_city_eng',
		type:'POST'
	},

}
//----------vocabulars----------------------------------------------------------------------------------------------------------------------
let vocabulary ={
	CCcablesList:['№','Технічні умови','Договір', 'Даткова угода','Акт прийомки','Затверджена картограма','Опис маршруту','Тип кабелю','Посилання на архів','id кабеля','Статус використання','запасна','Статус договору','№ПГС'],
	missedBuildings:['№','Місто', 'Вулиця','№будинку', '"Кубік" HOUSE_ID','Кільк.Квартир'],
	AIRcablesList:['№','id кабеля', 'Посилання на архів','Дата монтажа кабелю','Тип кабелю','Волоконність/Тип','Марка кабелю','№проекту', 'Призначення','Опис маршруту', 'Довжина, км']
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
	$('#cityBuildingDataUpdate').phpRequest(params.cityBuildingDataUpdate);
	//--------------------------------------------------------------------------------------------------------------------------------------
	$('#cableChannelChannelDataUpdate').phpRequest(params.cableChannelChannelDataUpdate);
	$('#cableChannelCableDataView').phpRequest(params.cableChannelCableDataView);
	//-------------------------------------------------------------------------------------------------------------------------------------
	$('#cableAirCableDataUpdate').phpRequest(params.cableAirCableDataUpdate);
	$('#cableAirCableDataView').phpRequest(params.cableAirCableDataView);
	//-----------------------------------------------------------------------------------------------------------------------------------
	$('.toolsListLabel').visibility('newTools');
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
		let loginSwitcher = false;
		if(attributId== 'userLoginView'){  loginSwitcher = true }
		let buildingDataSwitcher = false;
		if(attributId == 'cityBuildingDataUpdate'){  buildingDataSwitcher = true }
		let cableChannelCableDataViewSwitcher = false;
		if(attributId == 'cableChannelCableDataView'){  cableChannelCableDataViewSwitcher = true }
		let cableAirCableDataViewSwitcher  = false;
		if(attributId == 'cableAirCableDataView'){  cableAirCableDataViewSwitcher = true }
		console.log($(this).attr('id'));
		request[params.id] = $('#'+params.id).val();
		$.ajax({
			url: params.phpFile+'.php', //This is the current doc
			type: params.type,
			data: (request),
			success: function(data){
				// with the result from the ajax call
				console.log('data', data);
				if (loginSwitcher) {
					statistcsDraw(data);
					closeSpan('visualization' );
				}
				if (buildingDataSwitcher) {
					//missedBuildings(data);
					displayTableData('missed_buildings', 'container', data, vocabulary.missedBuildings);
					closeSpan('missed_buildings' );
				}
				if (cableChannelCableDataViewSwitcher) {
					//displayDrawnCCcables(data);
					displayTableData('drawnCCcables', 'container', data, vocabulary.CCcablesList);
					closeSpan('drawnCCcables' );
				}
				if (cableAirCableDataViewSwitcher) {
					//displayDrawnCCcables(data);
					displayTableData('drawnAIRcables', 'container', data, vocabulary.AIRcablesList);
					closeSpan('drawnAIRcables' );
				}
				

			}
		});      
	})
  	
};

//--------------troll tools display----------------------------------------------------------------------------
/*$.fn.toolsDisplay = function(params, selfTagClass, selfTagId){
	let listAll = params.toolsList;
	let listId=[]
	$(this).next().after('<div class="'+selfTagClass+' clear" id="'+selfTagId+'"></div>');
	$('#'+selfTagId).append('<ul class= labelsList></ul>');
	
	for (let i = 0; i < listAll.length; i++) {
		$('.labelsList').append('<li class="toolsListLabel" id="'+listAll[i].id+'">'+'<h2>'+listAll[i].name+'</h2>'+'</li>');
		$('#'+selfTagId).append('<div id="'+listAll[i].id+'_holder" class="invisible"><ul></ul></div>');
		let buttonListing = listAll[i].inner[0];
		//console.log(listAll[i].id);
		listId.push(listAll[i].id);

		//console.log('buttonListing',buttonListing);
		for (let j = 0; j < buttonListing.id.length; j++) {
			let innerSelect ='';
			if (buttonListing.select[j] !=='NULL') {
				let phpInjection = "<?php echo '<h2>'.$option.'</h2>'; ?>";
				innerSelect = '<select id="'+buttonListing.select[j]+'">'+phpInjection+'<select>';
			}
			$('#'+listAll[i].id+'_holder').find('ul').append('<li class="clear">'+innerSelect+'<button id="'+buttonListing.id[j]+'" class="myToolButton">'+buttonListing.name[j]+'<button>'+'</li>');
		}
		
	}
	

}*/
$.fn.visibility = function(selfTagId) {
	$(this).on('click', function(){
		let tempId = $(this).attr('id') ;
		$('._holder').removeClass('visible');
		$('#'+tempId+'_holder').addClass('visible');
		//console.log(tempId);
	})
}
	
})(jQuery);