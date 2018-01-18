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
		displayResult: false,
		displayStyle:'none'
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
	},
	cableChannelPitsDataUpdate:{
		phpFile:'cableChannelPitsDataUpdate',
		id:'cable_channel_pits_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	opticalCouplersUpdate:{
		phpFile:'opticalCouplersUpdate',
		id:'city_optical_couplers_data_update_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	ctvTopologyDataView:{
		phpFile:'ctvTopologyDataViewVis',
		id:'ctv_topology_dataView_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'window',
		displayCss: '../css/ctvTopologyDataView.css',
		displayCode:'../js/ctvTopologyDataView.js'
	},
	ctvTopologyLoad:{
		phpFile:'ctvTopologyLoad',
		id:'ctv_city_topology_load_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cityBuildingDataUpdateAuto:{
		phpFile:'cityBuildingDataUpdateAuto',
		id:'city_building_data_eng_auto',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},
	ctvToplogyAddFlats:{
		phpFile:'ctvToplogyAddFlats',
		id:'ctv_city_flats_eng',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},
	ctvTopologyCouplerView:{
		phpFile:'ctvTopologyCouplerView',
		id:'ctv_city_couplers_eng',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},
	ethernetTopologyLoad:{
		phpFile:'ethernetTopologyLoad',
		id:'ether_city_add_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	ethernetTopologyDataViewVis:{
		phpFile:'ethernetTopologyDataViewVis',
		id:'ethernet_topology_dataView_city_eng',
		type:'POST',
		displayResult: true,
		displayStyle:'window',
		displayCss: '../css/ctvTopologyDataView.css',
		displayCode:'../js/ctvTopologyDataView.js'
	},
	userTable:{
		phpFile:'userTable',
		id:'user_table',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},
	sendFeedback:{
		phpFile:'sendFeedback',
		id:'sendFeedback',
		wrapper:'#request',
		type:'POST',
		displayResult: true,
		displayStyle:'table'

	},
	qgisProjectFiles:{
		phpFile:'qgisProjectFiles',
		id:'qgisProjectFiles',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},
	cityBiomsDataUpdateOSM:{
		phpFile:'cityBiomsDataUpdateOSM',
		id:'city_bioms_OSM_data_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	cableAirPolesUpdate:{
		phpFile: 'cableAirPolesUpdate',
		id:'cable_air_poles_data_city_eng',
		type:'POST',
		displayResult: false,
		displayStyle:'none'
	},
	fileLinksTable:{
		phpFile:'fileLinksTable',
		id:'fileLinksTable',
		type:'POST',
		displayResult:true,
		displayStyle:'table'
	},

}
//-------------------file upload params-------------------------------------------------------------------------------------------------
let fileUploadParams = {
	fileUpload:{
		phpFile:'fileUpload',
		fileId:'file_upload',
		formId:'fileUploadForm',
		fileName:'file_upload',
		formValueUpload:'Завантажити CSV/QGS',
		method:'POST',
		enctype:'multipart/form-data',
		submitName:'fileSubmit'
	},
	csvDownload:{
		phpFile:'csvDownload',
		fileId:'csv_file_download',
		formId:'csvDownloadForm',
		fileName:'csv_file_download',
		formValueUpload:'Зкачати CSV',
		method:'POST',
		enctype:'multipart/form-data',
		submitName:'csvSubmitDownload'
	},
	qgsUpload:{
		phpFile:'qgsUpload',
		fileId:'qgs_file_upload',
		formId:'qgsUploadForm',
		fileName:'qgs_file_upload',
		formValueUpload:'Завантажити QGS',
		method:'POST',
		enctype:'multipart/form-data',
		submitName:'qgsSubmit'
	},
	//	filesUpload:{
	//	phpFile:'filesUpload',
	//	fileId:'files_upload',
	//	formId:'filesUploadForm',
	//	fileName:'files_upload[]',
	//	formValueUpload:'Завантажити файли',
	//	method:'POST',
	//	enctype:'multipart/form-data',
	//	submitName:'filesSubmit'
	//}

}
//----------vocabulars----------------------------------------------------------------------------------------------------------------------
let vocabulary ={
	cableChannelCableDataView:['№','Технічні умови','дата_файлу','Договір', 'дата_файлу', 'Додаткова угода','дата_файлу','Акт прийомки', 'дата_файлу','Затверджена картограма','дата_файлу','Опис маршруту','Тип кабелю','Посилання на архів','id кабеля','Статус використання','запасна','Статус договору','№ПГС', 'статус'],
	cityBuildingDataUpdate:['№','Місто', 'Вулиця','№будинку', '"Кубік" HOUSE_ID','Кільк.Квартир'],
	cityBuildingDataUpdateAuto:['№','Місто', 'Вулиця','№будинку', '"Кубік" HOUSE_ID','Кільк.Квартир'],
	cableAirCableDataView:['№','id кабеля', 'Посилання на архів','Дата монтажу кабелю','Тип кабелю','Волоконність/Тип','Марка кабелю','№проекту', 'Призначення', 'Довжина, км','Опис маршруту', 'статус'],
	cityBuildingDublicatesFinder:['№','Вулиця OSM', '№будинку OSM', 'Вулиця CUBIC', '№будинку CUBIC', 'кільк. кв.', '"Кубік" HOUSE_ID', 'Тип мережі', 'Координата будинку'],
	ctvTopologyUpdate:['№', 'Місто', 'Вулиця', '№будинку', 'Квартира', 'id вузла', 'Найменування вузла', 'Адреса ПГС', 'Адреса мат.вузла', 'id мат.вузла', 'Дата установки', 'notes','Відповідальний', 'Тип мережі', '"Кубік" HOUSE_ID','статус вузла'],
	//etherTopologyUpdate:['№','Вулиця', '№будинку',  '№під&acute;їзду', '№Поверху', 'Розташування', 'house_id', 'id комутатора', 'id мат. комутатора','mac_address', 'ip_address', 'serial_number', 'hostname', 'sw_model',  'sw_inv_state', 'дата установки', 'дата зміни'],
	etherTopologyUpdate:['№','Місто', 'Адреса','house id','switch id', '№під&acute;їзду', '№Поверху', 'mac_address', 'ip_address', 'dev_name', 'dev_type', 'sw_model',  'status', 'дата установки', 'serial_number', 'дата зміни','mon_type','report_date'],
	ctvToplogyAddFlats:['№',  'Вулиця', '№будинку', 'id вузла', 'Найменування вузла', 'Адреса ПГС', 'Адреса мат.вузла', 'id мат.вузла', 'Дата установки', 'notes','Відповідальний', 'Тип мережі', '"Кубік" HOUSE_ID','Квартири'],
	ctvTopologyCouplerView:['№', 'Місто', 'Коментар', 'Адреса ПГС', 'Найменування вузла', 'id вузла', 'Вулиця', '№будинку', 'Найменування мат.вузла', 'id мат.вузла', 'Вулиця мат.вузла', '№будинку мат.вузла','Архів','Схема зварювань','xlsx','xls','dwg','pdf','png','дата_файлу pdf'],
	userTable:['№','E-mail','Доступ','pass','доступні карти','доступні файли','Тип користувача','Редагування'],
	sendFeedback:['№','e-mail','Тема','Запит','Статус','Час відкриття запиту','Час закриття запиту'],
	qgisProjectFiles:['№','Назва файлу','дата_файлу','Скачати','Редагувати'],
	fileLinksTable:['№','Місто','Лінки','Регіон','Головне місто','Місто - англ.','координати вікна карти']
};
//---------------remove element from array----------------------------------
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
//-----------------unique values from array ------------------------------------
Array.prototype.uniqueValues = function()
{
	var n = {},r=[];
	for(var i = 0; i < this.length; i++)
	{
		if (!n[this[i]])
		{
			n[this[i]] = true;
			r.push(this[i]);
		}
	}
	return r;
}
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
	$('.vis-legend').find('svg').css({"height": names.length*21+"px"});
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
		$('#showFileDate').remove();
	})
}

//------ function displays data as table - it can be linkt to diifferent tag class----------------------
function displayTableData(mainTagClass, joinedToTgClass, data, vocabulary = 'noVocabulary'){
	//console.log('data', data);
               // console.log('data', resp.response.length);
               //console.log('click');
	resp = JSON.parse(data);
	//console.log('resp',resp);
	$('.'+mainTagClass).remove();
	$('#showFileDate').remove();
	if (resp !=null) {
		console.log('resp',resp);
		let list = resp.response;
		if ((list !=null) && (list.length>0) ) {  
		$('.'+joinedToTgClass).next().after('<div class="'+mainTagClass+' clear tableDisplayResult"><table class="displayDataView"></table></div>');
		$('.'+mainTagClass).prepend('<button id="restoreDefaultTable">очистити фільтр</button>');
		
		 let header = '';
		 let headerSelectors = '';
		 let sortedUniqueMatrix = [];
	      let listNames = Object.keys(list[0]);
	      //console.log('listNames', listNames);
	      let selectionParams = [];
	      let hidenColumns = [];
	       if (vocabulary !==  'noVocabulary') {
	       	header +='<tr>'
	       	for (let i = 0; i < vocabulary.length; i++) {
	       		if ( String(vocabulary[i]).includes('дата_файлу')){
	       			header +='<th class="filePresentDate">'+vocabulary[i]+'</th>';
	       			hidenColumns.push(i-1);
	       		} else { header +='<th>'+vocabulary[i]+'</th>';}
	       		
	       	}
	       	//console.log('hidenColumns',hidenColumns);
	       	header +='</tr>';
	       	headerSelectors = '<td></td>';
	       	$('.'+mainTagClass).find('table').append(header);
	       	listNames.forEach(function(item,index){
	       		//console.log('hidenColumns',hidenColumns);
	       		//console.log('index',index);
	       		if(hidenColumns.indexOf(index) > -1){
	       			headerSelectors +='<td class="filePresentDate"><input type="text" class="tableRowSelector" name="name_'+item+'" data-name="'+index+'" id="id_'+item+'" list="list_'+item+'"  style="background-color:#FAEBD7;"><datalist id="list_'+item+'" class="trimSelection"></datalist></td>';
	       		} else {headerSelectors +='<td><input type="text" class="tableRowSelector" name="name_'+item+'" data-name="'+index+'" id="id_'+item+'" list="list_'+item+'"  style="background-color:#FAEBD7;"><datalist id="list_'+item+'" class="trimSelection"></datalist></td>';}
	       		//headerSelectors +='<td><input type="text" class="tableRowSelector" name="name_'+item+'" data-name="'+index+'" id="id_'+item+'" list="list_'+item+'"  style="background-color:#FAEBD7;"><datalist id="list_'+item+'" class="trimSelection"></datalist></td>';
	       	});
	       	$('.'+mainTagClass).find('table').append('<tr>'+headerSelectors +'</tr>');
	               	//---------------------crear table filters--------------------------------------------------
			$('#restoreDefaultTable').on('click', function(){
				$('.dataCell').remove();
				
				sortedSelectLists(listNames, list);
				rowDraw(list,vocabulary,mainTagClass,hidenColumns);
				selectionParams =[];
				$('.tableRowSelector').val('');
				$('button.mapWindow').openNewMapWindow(params);
				$('.filePresentDate').hide();
			});
			//---------------------------------------------------------------------------------------------
	               } else { console.log('noVocabulary for table header') }
	               	//console.log('listNames',listNames);
	               	
	               	function sortedSelectLists(listNames, list){
	               		for (let z = 0; z < listNames.length; z++) {
		               		let sortedUniqueColumn = [];
					list.forEach(function(item,index){
						sortedUniqueColumn.push(item[listNames[z]]);
					});
					///------------------------------------
					sortedUniqueColumn = sortedUniqueColumn.uniqueValues();
					
					for (let j = 0; j < sortedUniqueColumn.length; j++) {
						$('#list_'+listNames[z]).append('<option value="'+sortedUniqueColumn[j]+'">'+sortedUniqueColumn[j]+'</option>');
					}
					//console.log('sortedUniqueColumn', sortedUniqueColumn);
					//sortedUniqueMatrix.push(sortedUniqueColumn);
	               		}
	               		//return sortedUniqueMatrix;
	               	}
	               	////////////////////////////////////////
	               	sortedSelectLists(listNames, list);
	               	////////////////////////////////////////
	               	
	               	$('.tableRowSelector').change(function(){
	               		selectionParams.push({
	               			selectedValue:$(this).val(),
	               			selectedColumnName:($(this).attr('id')).substr(3)
	               		});

				let selectedValue = $(this).val();
				let selectedColumnName = ($(this).attr('id')).substr(3);
				//console.log('selectedValue', selectedValue);
				//console.log('selectedColumnName', selectedColumnName);
				console.log('selectionParams',selectionParams);
				trimList = list;
				for (let k = 0; k< selectionParams.length; k++) {
				        trimList = trimList.filter(function( obj ) { if(obj[selectionParams[k].selectedColumnName] == selectionParams[k].selectedValue){  return obj;}});
				}
				console.log('list',list);
				console.log('trimList',trimList);
				$('.trimSelection').empty();
				$('.dataCell').remove();
				sortedSelectLists(listNames, trimList);
				rowDraw(trimList,vocabulary,mainTagClass);
				$('.filePresentDate').hide();
				$('button.mapWindow').openNewMapWindow(params);
			        	//---------------------crear table filters--------------------------------------------------
				$('#restoreDefaultTable').on('click', function(){
					$('.dataCell').remove();
					sortedSelectLists(listNames, list);
					rowDraw(list,vocabulary,mainTagClass,hidenColumns);
					selectionParams =[];
					$('.tableRowSelector').val('');
					$('button.mapWindow').openNewMapWindow(params);
					$('.wiringShow').imgLinkShow();
					$('.filePresentDate').hide();
				});
				//---------------------------------------------------------------------------------------------  
			        
			});

	               	//console.log('sortedUniqueMatrix', sortedUniqueMatrix);
	               	//-------------row of selectors cration-----------------------------------
			//------------------------------------------------------------------------------
			function rowDraw(list,vocabulary,mainTagClass,hidenColumns){

			
		               for (let i = 0; i < list.length; i++) {
		               	let row ='<tr>';
		               	let rowData = list[i];
		               	//console.log('rowData',rowData);
		               	
		               	if(vocabulary.findIndex(x => x == '№')>-1){
		               		rowData['1'] = i+1;
		               	}
		               	let pathIndex;
		               	let pathIndexPKP;
		               	let pathIndexCC;
		               	let fileState=[];
		               	let fileDate =[];
		               	let imgLink;
		               	if(vocabulary.findIndex(y => y =='Опис маршруту') >-1){
		               		pathIndexCC = 'summ_route_description';
		               	}
		               	if(vocabulary.findIndex(y => y =='Опис маршруту') >-1){
		               		pathIndexPKP = 'rote_description';
		               	}
		               	if(vocabulary.findIndex(y => y =='Схема зварювань') >-1){
		               		imgLink = 'link';
		               	}
		               	if((vocabulary.findIndex(y => y =='xlsx') >-1) || (vocabulary.findIndex(y => y =='xls') >-1) || (vocabulary.findIndex(y => y =='dwg') >-1) || (vocabulary.findIndex(y => y =='png') >-1) ){
		               		fileState = ['xlsxFile','xlsFile','dwgFile','imgFile','pdfFile'];
		               	}
		               	if((vocabulary.findIndex(y => y =='summ_tu_date') >-1) || (vocabulary.findIndex(y => y =='summ_contract_sum_date') >-1) || (vocabulary.findIndex(y => y =='summ_sub_contract_date') >-1) || (vocabulary.findIndex(y => y =='summ_acceptance_act_date') >-1)  || (vocabulary.findIndex(y => y =='summ_approval_cartogram_date') >-1)){
		               		fileDate = ['summ_tu_date','summ_contract_sum_date','summ_sub_contract_date','summ_acceptance_act_date','summ_approval_cartogram_date'];
		               	}
		               	//console.log('rowData', rowData);
		               	for (let key in rowData) {
		               		//console.log('vocabulary.indexOf(key)',vocabulary.indexOf(key) );
		               		if (key == pathIndexCC ) {
		               			if(rowData['geom_state'] =='+'){
		               				row +='<td class="dataCell">'+'<button id="'+rowData['table_id']+'" class="mapWindow" data-cable="cc" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</button>'+'</td>';
		               			} else {
		               				row +='<td class="dataCell">'+'<p data-cable="cc" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</p>'+'</td>';
		               			}
		               		} else if (key == pathIndexPKP) {
		               			if(rowData['geom_state'] =='+'){
		               				row +='<td class="dataCell">'+'<button id="'+rowData['table_id']+'" class="mapWindow" data-cable="pkp" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</button>'+'</td>';
		               			} else {
		               				row +='<td class="dataCell">'+'<p data-cable="pkp" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</p>'+'</td>';
		               			}
		               			//row +='<td class="dataCell">'+'<button id="'+rowData['table_id']+'" class="mapWindow" data-cable="pkp" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</button>'+'</td>';
		               		} else if ( fileState.indexOf(key)>-1) {
		               			if(rowData[key] == '+'){
		               				row +='<td class="dataCell '+'filePresent'+'" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</td>';
		               			} else if (rowData[key] == '-'){
		               				row +='<td class="dataCell '+'fileAbsend'+'" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</td>';
		               			} else {
		               				row +='<td class="dataCell '+'fileNotDefinedState'+'" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</td>';
		               			}
		               		} else if ( String(key).includes('_date')) {
		               			row +='<td class="dataCell '+'filePresentDate'+'" data-city="'+$('#'+params.id).val()+'"><span style ="color:blue">'+rowData[key]+'</span></td>';
		               		} else if(key == imgLink) {
		               			$('.wiringShow').imgLinkShow();
		               			if (rowData[key] == '-') {
		               				row +='<td class="dataCell" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</td>';	
		               			} else {
		               				row +='<td class="dataCell">'+'<button id="'+rowData['cubic_code']+'" class="wiringShow" data-link="'+rowData[key]+'">схема зварювань</button>'+'</td>';
		               			}	
		               		} else if(String(rowData[key]).includes('https://') && (String(rowData[key]).includes('.qgs') == false ) && (String(rowData[key]).includes('.csv') == false) ){
		               			row +='<td class="dataCell">'+'<a target="_blank" href="'+rowData[key]+'">посилання на архів</a>'+'</td>';
		               		} else if(String(rowData[key]).includes('https://') && (String(rowData[key]).includes('.qgs') || String(rowData[key]).includes('.csv')) ){
		               			row +='<td class="dataCell">'+'<a href="'+rowData[key]+'" download>посилання на архів</a>'+'</td>';
		               		} else {
		               			row +='<td class="dataCell" data-city="'+$('#'+params.id).val()+'">'+rowData[key]+'</td>';	
		               		}
		               		//row +='<td>'+rowData[key]+'</td>';
		               	}
		               	row += '</tr>';
		               	$('.'+mainTagClass).find('table').append(row);	
		               }
		               //$('button.mapWindow').openNewMapWindow(params);  
		            } 
		            rowDraw(list,vocabulary,mainTagClass,hidenColumns);
		            $('.filePresentDate').hide();
	               }
	}
	
		
	               

}
//-------------------------------------------------------------------------------------------------------------------
		
//-------------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------------


//------document ready-------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){
	//----------------tools--panel---------------------------------------------------------------------------------------------------
	$('.myToolButton').on('click', function(){
		//console.log('clicked',$(this).attr('id'));
		$('.tableDisplayResult').remove();
		let callId = $(this).attr('id');
		if(params.hasOwnProperty(callId)){
			$('#'+callId).phpRequest(params[callId]);
		} else{ console.log('you forgot input data into params object')}
		
	})
	$('#feedback').on('click', function(){
		$('#request').toggle();
	})

	//-----------------------------------------------------------------------------------------------------------------------------------
	$('.toolsListLabel').visibility('newTools');

	//----------------------------------file upload------------------------------------------------------------------------------
	//$('#fullAccess_holder').fileUploadToTmp(fileUploadParams.fileUpload,'#fullAccess_holder');
	$('#filesUpload_holder').fileUploadToTmpAll(fileUploadParams.fileUpload,'#filesUpload_holder');
	//$('#filesUpload_holder').fileUploadToTmpAll(fileUploadParams.csvDownload,'#filesUpload_holder');
	//$('#cableChannelCables_holder').fileUploadToTmpAll(fileUploadParams.filesUpload,'#cableChannelCables_holder');
	//---------------------------------------------------------------------------------------------------------------------------
	$('#ctv_holder').addSheList('ctv_holder'); 
	$('#opticalCouplers_holder').addSheList('opticalCouplers_holder');
	//---------------------------------------

	$('body').pageUpScroll($(document).scrollTop());
	
	
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
	//$(this).on('click', function(){
		//console.log($('#'+params.id).val() );
		let request = {};
		let attributId = $(this).attr('id');
		let map_links = [];
		let file_links = [];
		let user_type = '';
		localStorage.setItem("map_links", JSON.stringify(map_links));
		localStorage.setItem("file_links", JSON.stringify(file_links));
		//-------------------------------------------------------------------------------------
		//-------------------------------------------------------------------------------------
		console.log('phpFile',$(this).attr('id'));
		console.log(params);
		request[params.id] = $('#'+params.id).val();
		request['she'] = $('#sheSelection').val();
		if(params['wrapper'] != undefined){
			request['sub'] = $('#request').find('select').val();
			request['request'] = $('#request').find('textarea').val();
			request['restriction'] = $(this).data('restriction');
			request['e_mail'] = $(this).data('e_mail');
		}
		if(request[params.id] == undefined ){ request['buttonId'] =params.id}
		if ($('#'+params.id).val() !=='вибери місто') {

			$('.curtenScripStatus').show();
			console.log('request',request);
			$.ajax({
				url: params.phpFile+'.php', //This is the current doc
				type: params.type,
				data: (request),
				success: function(data){
					//console.log(data);
					if(  (data) && (params.displayResult == true) ) {
						let test =  JSON.parse(data);
						//console.log('test', test);
						if( test = null) {
							alert('Відсутні нові елементи');
							$('.curtenScripStatus').hide();
						} else {
							if( (params.displayStyle == 'table')  ) {
								displayTableData('displayResult'+attributId, 'container', data, vocabulary[attributId]);
								closeSpan('displayResult'+attributId);
								$('button.mapWindow').openNewMapWindow(params);
								if ($('.filePresentDate')[0]){
									$('<div id="showFileDate">&#9716;</div>').insertBefore('#back-to-top');
									if($('body').height() < screen.height ){$('.filePresentDate').show();}
									$('#showFileDate').on('click', function(e){
										e.preventDefault();
										console.log('click');
										$('.filePresentDate').toggle();
									})
								}
								//----------------------user addition or removement---------------------------
								$('input[type="checkbox"].deleteUser').click(function() {
								    //console.log($(this).data('mail'));
								    $('button[data-mail="' + $(this).data('mail')+'"]').toggle(this.checked);
								});
								//bad code :))
								$('.map_links').on('change', function(){
									//console.log('click');
									if($(this).prop('checked')){
										//console.log('click');
										$(this).next().css({"background-color": "yellow"});
										map_links.push($(this).data('map'));
										localStorage.setItem("map_links", JSON.stringify(map_links));
									} else { 
										$(this).next().css({"background-color": "#cceeff"});
										map_links.remove($(this).data('map'));
										localStorage.setItem("map_links", JSON.stringify(map_links));

									};
									console.log('map_links', map_links);
									return map_links;
								});
								$('.file_links').on('change', function(){
									//console.log('click');
									if($(this).prop('checked')){
										//console.log('click');
										$(this).next().css({"background-color": "yellow"});
										file_links.push($(this).data('file'));
										localStorage.setItem("file_links", JSON.stringify(file_links));
									} else { 
										$(this).next().css({"background-color": "#cceeff"});
										file_links.remove($(this).data('file'));
										localStorage.setItem("file_links", JSON.stringify(file_links));

									};
									console.log('file_links', file_links);
									return file_links;
								});
								$('.user_type').on('change', function(){
									//console.log('click');
									if($(this).prop('checked')){
										//console.log('click');
										$('.user_type').css({"background-color": "#cceeff"});
										$(this).next().css({"background-color": "yellow"});
										user_type = $(this).data('user');
										localStorage.setItem("user_type", JSON.stringify(user_type));
									} 
									console.log('user_type', user_type);
									return user_type;
								});
								///----------------------
								//console.log('map_links', map_links);
								$('button#addNewUser').newUser('addNewUser','addNewUser',params,attributId);
								$('button.deleteUser').newUser('addNewUser','deleteUser',params,attributId);
								//------------------------------------------------------------------------------
								if ($('.'+'displayResult'+attributId)[0]){
									$('html, body').animate({ scrollTop: $('.'+'displayResult'+attributId).offset().top }, 'slow');
								}
								
							}
							if( params.displayStyle == 'graph'){
								statistcsDraw(data);
								closeSpan('visualization' );
							}
							if( params.displayStyle == 'window'){
								$(this).openNewWindow(data, params, request);
							}								
							// with the result from the ajax call
							//console.log('data', data);
							$('.curtenScripStatus').hide();
							
						}
					} else {
						$('.curtenScripStatus').hide();
					}
				//	
				}
				
			}); 

		} else {
			alert('Будь ласка виберіть місто')
		}
		
	//})
  	
};

//---------scrollUp--------------------------------------------------------------------------------------------
$.fn.pageUpScroll = function(documentHeight){
	$( window ).scroll(function() {
		if( $('body').scrollTop() > documentHeight){
			$('#back-to-top').addClass('show');
			if($('.filePresentDate')[0]){$('#showFileDate').addClass('show');}
		} else { try {$('#back-to-top').removeClass('show'); $('#showFileDate').removeClass('show')} catch(err) { console.log(err); }}
	});
	$('#back-to-top').on('click', function(e){
		$('#back-to-top').removeClass('show');
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
	})
}
//------------new user-----------------------------------------------------------------------------------------
$.fn.newUser = function(url,buttonId,params,attributId){
	$(this).on('click', function(){
		let request = {};
		let map_links = [];
		let file_links =[];
		let user_type = '';
		console.log('params',params);
		console.log('attributId',attributId);
		if(buttonId =='addNewUser'){
			map_links = JSON.parse(localStorage.getItem("map_links"));
			file_links = JSON.parse(localStorage.getItem("file_links"));
			user_type = JSON.parse(localStorage.getItem("user_type"));
			console.log('map_links', map_links);
			console.log('map_links.length', map_links.length);
			if((file_links.length > 0) || (map_links.length > 0) || (user_type != '') || ($('#addNewUserRestriction').val() != '')  || ($('#addNewUserEmail').val() != '')  || ($('#addNewUserPassword').val() != '')){
				request ={
					'buttonId':buttonId,
					'Email':$('#'+buttonId+'Email').val(),
					'Password':$('#'+buttonId+'Password').val(),
					'Restriction':$('#'+buttonId+'Restriction').val(),
					'map_links': map_links,
					'file_links': file_links,
					'user_type': user_type
				};
				console.log('request',request);
				$('.tableDisplayResult').remove();
				localStorage.setItem("map_links", JSON.stringify([]));
				localStorage.setItem("file_links", JSON.stringify([]));
				localStorage.setItem("user_type", JSON.stringify(''));
				//$('#'+attributId).phpRequest(params[attributId]);
			} else {alert('будь ласка, заповніть поля та виберіть карти для доступу');}
			

		} else if(buttonId =='deleteUser'){
			request ={
				'buttonId':buttonId,
				'Email':$(this).data('mail'),
			}
			//$( '.'+ buttonId +'[data-mail="test@mail.com"]').parents('tr:first').remove();
			$('.tableDisplayResult').remove();
		}
		
		//console.log(request);
		$.ajax({
			url: url+'.php', //This is the current doc
			type: 'POST',
			data: (request),
			success: function(data){
				//console.log(data);
			}
		})
	})
}
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
//-----------add file upload not for ful access----------------------------------------------------------
$.fn.fileUploadToTmpAll = function(params, target){
	console.log('target', target);
	let selector = params.phpFile.toLowerCase();

	if( selector.includes('fileupload')){
		$(target).find('ul').append('<li><form id="'+params.formId+'" action="'+params.phpFile+'.php'+'" method="'+params.method+'" enctype="'+params.enctype+'"></form></li>');
		$('#'+params.formId).append(/*'<label>виберіть файл CSV</label>'*/
			'<input type="file" name="'+params.fileName+'" id="'+params.fileId+'" class="myToolButton">'+
			'<input type="submit" value="'+params.formValueUpload+'"name="'+params.submitName+'" class="myToolButton">');
	}
	else if(selector.includes('download')){
		$(target).find('ul').append('<li><select id="'+params.csv_file_download+'"></select><select id="csvDownloadType"></select><button class="myToolButton" id="'+params.submitName+'">Зкачати</button></li>');
		cityArray.forEach(function(item,index){
			$(target).find('#'+params.csv_file_download).append('<option value="'+item+'">'+item+'</option>')
		});
	}
	//else if (selector.includes('filesupload')){
	//	$(target).find('ul').append('<li><div id="'+params.formId+'" ></div></li>');
	//	$('#'+params.formId).append(/*'<label>виберіть файл CSV</label>'*/
	//		'<input type="file" name="'+params.fileName+'" id="'+params.fileId+'" class="myToolButton" multiple="multiple" />'+
	//		'<input type="submit" value="'+params.formValueUpload+'"name="'+params.submitName+'" class="myToolButton">');
	//}
	

}
//-------open link in new window-----------------------------------------------------------------------
$.fn.openNewMapWindow = function(params) {
	$(this).on('click', function(){
		let tempId = $(this).attr('id') ;
		let cityId;
		//let cityId = $('#'+params.id).val();
		//localStorage.setItem("cityId", cityId);
		if ($('#'+params.id).val() !=undefined) {
			cityId = $('#'+params.id).val();
			localStorage.setItem("cityId", cityId);	
		} else {
			cityId = localStorage.getItem("cityId");
		}
		//let cityId = $(this).data('city');
		let cableType = $(this).data('cable');
		let insideText = $(this).text();
		//console.log('tempId', tempId);
		//console.log('cityId', cityId);
		console.log( $( this ).text() );
		let geomRequest ={
			tempId:tempId,
			cityId:cityId,
			cableType: cableType
		};
		let url = 'cableGeomGenerate.php';
		console.log('geomRequest ',geomRequest );
		let newWindow = window.open("", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=300,left=200,width=800,height=600");
		$.ajax({
			url: url, //This is the current doc
			type: 'POST',
			data: (geomRequest),
			success: function(data){
				//console.log(data);
				let test =  JSON.parse(data);
				//console.log(test.features[0].geometry.coordinates[0]);
				
				let centerPoint = {
					point:[test.features[0].geometry.coordinates[0], test.features[0].geometry.coordinates[1]],
					zoom: 18
				}
				newWindow.document.write('<meta http-equiv="content-type" content="text/html; charset=utf-8"/>');
				newWindow.document.write(' <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>');
				newWindow.document.write('<style>.map { height: 600px;width: 800px;}</style>');
				newWindow.document.write('<script src="https://openlayers.org/en/v3.20.1/build/ol.js"></script>');
				newWindow.document.write('<h4>'+insideText+'</h4>');
				newWindow.document.write('<div id="map" class="map"></div><script type="text/javascript" >'+
					"let centerPoint = {point:["+centerPoint.point[0]+","+centerPoint.point[1]+"],zoom:"+centerPoint.zoom+"};"+
					"let raster = new ol.layer.Tile({source: new ol.source.OSM({})});"+
					"let cableStyle = new ol.style.Style({image: new ol.style.Circle({radius: 4, stroke: new ol.style.Stroke({ color: 'blue', width: 2 }), fill: new ol.style.Fill({ color: 'rgba(255,0,0,0.2)' })}), stroke: new ol.style.Stroke({color: 'red', width: 2})});"+
					"let vector = new ol.layer.Vector({source: new ol.source.Vector({features: (new ol.format.GeoJSON()).readFeatures("+data+", {featureProjection: ol.proj.get('EPSG:4326')})}),style: cableStyle});"+
					"let map = new ol.Map({target: 'map', renderer: 'canvas', layers: [raster, vector], view: new ol.View({center: ["+centerPoint.point+"],zoom:"+ centerPoint.zoom+"})});"+
					'</script>');
					
			}
				
		}); 
	})
}
//-------------------------------open ctv new topology window-----------------------------	
$.fn.openNewWindow = function(data,params,request){
		let newWindow = window.open("", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=300,left=200,width=800,height=600");
		//localStorage.clear();	
		localStorage.setItem("tempTopologyArray", data);
		localStorage.setItem("tempParams", params);
		let obj = JSON.parse(data);
		let objLength =0; 
		if (obj.nodes.length <100) {objLength = 20*(obj.nodes.length) } 
		else if ((obj.nodes.length >=100) && (obj.nodes.length < 800)) { objLength = 10*(obj.nodes.length)  }
		else if((obj.nodes.length >=800) && (obj.nodes.length < 2000)) { objLength = 5*(obj.nodes.length)  }
		else  { objLength = 3*(obj.nodes.length)  };
		newWindow.document.write('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		newWindow.document.write('<script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>');
		newWindow.document.write('<link rel="stylesheet" href="'+params.displayCss+'" type="text/css">');
		newWindow.document.write('<link rel="stylesheet" href="../css/vis.css" type="text/css">');
		newWindow.document.write('<script  src="../libs/vis/vis.js"></script>');
		let nettype;
		if((params.id).toLowerCase().includes('ethernet')){ nettype ='ethernet';}
		else if((params.id).toLowerCase().includes('ctv')){ nettype ='ctv';}
		else { nettype ='none';}
		newWindow.document.write('<h4 id="selectedCity">'+request[params.id]+'</h4>');
		newWindow.document.write('<div id="mynetwork" data-nettype="'+nettype+'" width="'+objLength+'" height="'+objLength+'"></div>');
		newWindow.document.write('<script  type="text/javascript" src="../js/rotatingArrows.js"></script>');
		newWindow.document.write('<script type="text/javascript" src="'+params.displayCode+'"></script>');
					
}
$.fn.addSheList = function(holder){
	let notCities = ['вибери місто'];
	let nextButtonId = ['ctvTopologyDataView', 'ctvTopologyLoad'];
	$('#'+holder+' select').on('change', function(){
		if(notCities.indexOf($(this).val())<0 ) {
			if(nextButtonId.indexOf($(this).next().attr('id'))<0){
				$('#sheSelection').remove();
				let request = {};
				request['selectedCity'] = $(this).val();
				$(this).next().before('<select id="sheSelection" style="max-width:100px;"><option value="виберіть ПГС">виберіть ПГС</option></select>');
				$.ajax({
					url: 'sheSelection.php', //This is the current doc
					type: 'GET',
					data: (request),
					success: function(data){
						let obj =  JSON.parse(data);
						console.log('obj', obj);
						for (let i = 0; i < obj.response.length; i++) {
							$('#sheSelection').append('<option value="'+obj.response[i].she+'">'+obj.response[i].she+'</option>');
						}

					}
				})
				

			} else {
				$('#sheSelection').remove();
			}
		} else {
			$('#sheSelection').remove();
		}
	});
}
$.fn.imgLinkShow = function(){
	$(this).on('click', function(){
		$('.wiringImgOuter').remove();
		let imgLink = $(this).data('link');
		let attributId = $(this).data('code');
		console.log(imgLink);
		$('body').append('<div class="wiringImgOuter"><div class="wiringImg"><img src="..'+imgLink+'"></div></div>');
		$('.wiringImg').append('<span class="closeSpan"></span>');
		$('.closeSpan').on('click', function(){
			console.log('click');
			$(this).parent().parent().remove();
		})

	})
}
})(jQuery);