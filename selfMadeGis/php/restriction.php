<?php
//('display_errors', 1);
function postgres_to_php_array($postgresArray) {

   $postgresStr = trim($postgresArray,"{}");
    $elmts = explode(",",$postgresStr);
    return $elmts;

  }

$restriction=NULL;
//$option = '';
include('login.php'); 
$option = '';
$accordion='';
$tools ='';
 $restriction = $_GET["restriction"];
 //echo $restriction;
  if ($restriction != NULL) {
  $restriction=json_encode($restriction);
  print "<script type='text/javascript'>console.log(".$restriction.");</script>";
  }
  $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";
           $city_array = array(); 
          
           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
             }
            $restriction = $_GET["restriction"];
              if ($restriction != NULL) {
              
              //echo $restriction;
              
              //echo $sql;
              if ($restriction =="full" || $restriction =="admin") {
                $sql = "SELECT id, city, links, links_description, city_eng   FROM public.links WHERE links IS NOT NULL ORDER BY city";

              }
                           

              if ($restriction == "central" || $restriction =="eastern" || $restriction == "western") {
                $sql = "SELECT id, city, links, links_description, city_eng, region   FROM public.links WHERE links IS NOT NULL AND region = '$restriction' ORDER BY city";
              }

              if ($restriction!=="full" && $restriction !== "central" && $restriction !=="eastern" && $restriction !== "western"&& $restriction !== "admin") {
                $sql = "SELECT id, city, links, links_description, city_eng, prime_city   FROM public.links WHERE links IS NOT NULL AND prime_city = '$restriction' ORDER BY city";
              }
              $ret = pg_query($db, $sql);
              $rows = pg_num_rows($ret);

				//echo $rows . " row(s) returned.\n";
              
              if(!$ret){
                  echo pg_last_error($db);
              } else {
                while ($row = pg_fetch_row($ret))  {
          				
                      $accordion.='<div class="accordion-section'.(int)$row[0].'">'.'<a class="accordion-section-title" href="#accordion-'.(int)$row[0].'">'.$row[1].'</a>'.'<div id="accordion-'.(int)$row[0].'" class="accordion-section-content">';
                      $array_names = postgres_to_php_array($row[3]);
                      $array_links = postgres_to_php_array($row[2]);
                      //print_r($array_names);
                      foreach ($array_names as $index => $value) {
                        $accordion.='<a href="'.$array_links[$index].'">'.$array_names[$index].'</a>';
                      }

                      $accordion.='</div>'.'</div>';
                      $city_array[] = $row[4];
                }
             }
             //print_r($city_array);
             	 if ( count($city_array) > 1) {
	           	array_unshift($city_array, 'вибери місто');
	           }
		foreach ($city_array as $key => $value) {
		    $option .='<option value="'.$city_array[$key].'">'.$city_array[$key].'</option>';
		}


          } 
          // tools lbutton list description-----------------------------------------------------------------------------------------------------------------
	$toolsList = array(
		array('fullAccess', 'Повний доступ', 
			array( 
				array(/*id  for buttons*/
					'userLoginView', 
					'textexchange',
					'simpleuserRestrictionUpdate',
					'cityTablesCreate'
				), 
				array(/*buttons names*/
					'Графік відвідувань', 
					'Заміна host в qgis файлі',
					'Апдейт дозволу доступу simpleuser до схем Postgresql',
					'Додавання комплекту таблиць до міста'
				), 
				array(/*seletors ids*/
					'NULL',
					'NULL',
					'NULL',
					'tables_create_city_eng'
				),
				array(/*titles for buttons*/
					'Відображає статистику відвідуваньт вебклієнту',
					'Нажати після заміни QGIS файлу на сервері'/*Замінює адресу в QGIS файлі на localhost, в іншому разі файл не буде читатись сервером*/,
					'Нажати після додавання нових таблиць'/*Потрібно запускати після зтворення кожної нової таблички root-користувачем, щоб нею міг користуватись - simpleuser*/,
					'Додає шаблонний комплет таблиць, фунцій та тригерів до нового міста')
			) 
		),
		array('filesUpload', 'Завантаження файлів',
			array(
				array(
					'NULL',
				),
				array(
					'NULL',
				),
				array(
					'NULL',
				),
				array(
					'NULL' ,
				)
				
			)
		),
		array('cableChannelChannels', 'КК - канали',
			array( 
				array(/*id  for buttons*/
					'cableChannelChannelDataUpdate',
					//'cableChannelTopologyUpdate',
					'cableChannelPitsDataUpdate'
				), 
				array(/*buttons names*/
					'Оновлення даних/топології звязків КК - канали',
					//'Оновлення топології КК- канали',
					'Оновлення привязки колодязів КК'
				), 
				array(/*seletors ids*/
					'cable_channel_channel_data_city_eng',
					//'cable_channel_city_eng',
					'cable_channel_pits_city_eng'
				),
				array(/*titles for buttons*/
					'Оновлює данні про кабельні канали, з завантаженої CSV(city_cable_channels_channels.csv та Оновлює лінії каналів кабельних каналів на картіб нажати після /Оновлення даних КК - канали)',
					//'Оновлює лінії каналів кабельних каналів на картіб нажати після /Оновлення даних КК - канали/',
					'Привязує колодязі КК до мікрорайону/району/зони покриття ПГС'
				)
			) 
		),
		array('cableChannelCables','КК - кабелі',
			array( 
				array(/*id  for buttons*/
					'cableChannelCabelDataUpdate',
					'cableChannelCableDataView'
				), 
				array(/*buttons names*/
					'Оновлення даних КК - кабелі',
					'Вивести таблицю КК - кабелі'
				),
				array(/*seletors ids*/
					'cable_channel_cable_data_city_eng',
					'cable_channel_cable_dataView_city_eng'
				),
				array(/*titles for buttons*/
					'Оновлює данні в таблиці про кабельні прокладки в кабельній каналізації з завантаженого файлу CSV(city_cable_channels.csv, шаблон_реєстру_ВОЛЗ КК)',
					'Виводить на екрані кабелі КК, які вже мають геометрію та данні в таблиці'
				)
			)
		),
		array('cableAirCables','ПКП - кабелі',
			array(
				array(/*id  for buttons*/
					'cableAirCableDataUpdate', 
					'cableAirCableDataView'
				),
				array(/*buttons names*/
					'Оновлення даних ПКП - кабелі', 
					'Вивести таблицю ПКП - кабелі'
				),
				array(/*seletors ids*/
					'cable_air_cable_data_city_eng', 
					'cable_air_cable_dataView_city_eng'
				),
				array(/*titles for buttons*/
					'Оновлює данні в таблиці про кабельні прокладки ПКП(повітряно кабельнв переходи) з завантаженого файлу CSV(city_cable_air.csv, шаблон_реєстру_ВОЛЗ ПКП)',
					'Виводить на екран кабел ПКП, які вже мають геометрію та данні в таблиці'
				)
			)
		),
		array('opticalCouplers','Оптичні муфти',
			array(
				array(/*id  for buttons*/
					'opticalCouplersUpdate',
					'ctvTopologyCouplerView'
				),
				array(/*buttons names*/
					'Оновлення привязки оптичних муфт',
					'Вивеси перелік оптичних муфт'
				),
				array(/*seletors ids*/
					'city_optical_couplers_data_update_eng',
					'ctv_city_couplers_eng'
				),
				array(/*titles for buttons*/
					'Привязує оптичні муфти до мікрорайону/району/зони покриття ПГС',
					'Привязує занесені оптичні муфти, які в зоні покриття ПГС чи на все місто(з вказанням які наявності архівних файлів)'
					
				)
			)
		),		
		array('buildings','Будинки',
			array(
				array(/*id  for buttons*/
					'cityBuildingDataUpdateOSM',
					'cityRoadsDataUpdateOSM',
					'cityBuildingDataUpdate',//update from csv manually loaded from cubic
					//'cityBuildingDataUpdateAuto', //update from csv automaticali loaded from cubic
					'cityBuildingDublicatesFinder',//findes building with same geometry
					'cityEntranceDataUpdateOSM', //adds entrances from csv created using osm data
					'cityEntranceDataUpdateCUBIC' //adds entrances from csv created using cubic data
				),
				array(/*buttons names*/
					'Оновлення даних про будинки OSM',
					'Оновлення даних про дороги OSM',
					'Оновлення даних про будинки CUBIC',
					//'Оновлення даних про будинки CUBIC(from auto)', 
					'Відобразити дублікати будинків',
					'Прив"язка OSM під"їздів до будинку', 
					'Прив"язка CUBIC під"їздів до будинку'
				),
				array(/*seletors ids*/
					'city_building_OSM_data_eng',
					'city_roads_OSM_data_eng',
					'city_building_data_eng', 
					//'city_building_data_eng_auto', 
					'city_building_dublicates_finder_eng',
					'building_entrance_OSM_data_update_city_eng', 
					'building_entrance_CUBIC_data_update_city_eng'
				),
				array(/*titles for buttons*/
					'Оновлення даних про будинки з завантаненої CSV(city_buildings_osm.csv), яка зформована з даних вивантажених з overpass-turbo',
					'Оновлення даних про дороги з завантаненої CSV(city_roads_osm.csv), яка зформована з даних вивантажених з overpass-turbo',
					'Оновлення даних про будинки з завантаненої CSV(city_buildings.csv), яка зформована з даних вивантажених з КУБІКУ(Дислокация по районам и типам сетей (дополненный) чи автоматично)',
					//'Оновлення даних про будинки з автоматично завантаненої CSV(city_buildings.csv), яка зформована з даних вивантажених з КУБІКУ(автоматично)',
					'Відображає перелік будинків з однаковою адресою та різною геометрією',
					'Додає entrance_id до підїздів(підїзди мають знаходитись в межах будинків, інакше дані не підтягнуться) і додає підїзди з файлу, які відсутні в таблиці CSV(city_entrances_osm.csv) УВАГА, МОЖЕ ЗАЙНЯТИ БАГАТО ЧАСУ',
					'Додає підїзди в будинки(де немає підїздів з id) з геометрією підїзду першої точки будинку, та назнача entrance_id CSV(city_entrances_cubic.csv) КУБІК(Количество абонентов поподъездно)')
			)
		),
		array('ctv','КТВ',
			array(
				array(/*id  for buttons*/
					'ctvTopologyUpdate',
					'ctvTopologyLoad', 
					'ctvNodCoverageUpdate',
					'ctvTopologyDataView',
					'ctvToplogyAddFlats'
				),
				array(/*buttons names*/
					'Оновлення топології КТВ',
					'Додавання до топології КТВ елементів', 
					'Оновлення покриття оптичних вузлів',
					'Відображення топології КТВ',
					'Додає квартири до підсилювачів'
				),
				array(/*seletors ids*/
					'ctv_city_eng',
					'ctv_city_topology_load_eng', 
					'ctv_city_nod_eng',
					'ctv_topology_dataView_city_eng',
					'ctv_city_flats_eng'
				),
				array(/*titles for buttons*/
					'Оновлює топлогію з файлу CSV(city_ctv_topology.csv), КУБІК(Реестр введенных узлов  без КУ)',
					'Додає відсутні елементи топлогії з файлу CSV(city_ctv_topology.csv), КУБІК(Реестр введенных узлов  без КУ)',
					'Нажати після оновлення даних в КУБІКУ(для великих міст може заняти багато часу)',
					'Нажати для відображення топології міста',
					'Додає квартири до підсилювачів CSV(city_ctv_topology_full.csv), КУБІК(Реестр введенных узлов)'
				)
			)
		),
		array('internet','Інтернет',
			array(
				array(/*id  for buttons*/
					'etherTopologyUpdate',
					'cityStateSwitches'
				),
				array(/*buttons names*/
					'Оновлення топології Ethernet',
					'Оновити стан комутаторів'
				),
				array(/*seletors ids*/
					'ether_city_eng',
					'switches_state_city_eng'
				),
				array(/*titles for buttons*/
					'Оновлює топлогію з файлу CSV(city_ethernet_topology.csv поки не реалізовано), КУБІК(Топологія мережі Ethernet)',
					'Нажати для термінового оновлення стану комутаторів'
				)
			)
			
		),
		array('networkSupply', 'Підтримка мережі',
			array(
				array(/*id  for buttons*/
					'toCoverageUpdate',
					'usoCoverageUpdate'
				),
				array(/*buttons names*/
					'Оновлення зони покриття дільниць ТО',
					'Оновлення зони покриття дільниць СО'
				),
				array(/*seletors ids*/
					'city_supply_to_eng',
					'city_supply_uso_eng'
					),
				array(/*titles for buttons*/
					'Нажати для термінового оновлення покриття дільниць ТО',
					'Нажати для термінового оновлення покриття дільниць CО'
				)
			)
		)
	);
	//------------------------------------------------------------------------------------------------------------
	//tools button list generator--------------------------------------------------------------------------
	$toolListToString ='';
	$buttons='';
	$newTools = '<div class="tools" id="tools"><div class="tools__visible"></div><div class ="tools__hidden clear">';
	$toolListToString .='<ul class="labelsList">';
	foreach( $toolsList as $key1 =>$tool){
		if (is_array($tool)) {
			foreach ($tool as $key2 => $toolDescription) {
				
					if (is_array($toolDescription) ) {
						$buttons .='<div id="'.$tool[0].'_holder" class="clear invisible _holder"><ul>';
						foreach ($toolDescription as $key3 => $button) {
							
							if (is_array($button) ) {

								foreach ($button as $key4 => $value) {
									if ($key3 == 0) {
										$buttons.= '<li>';
										if ($toolDescription[2][$key4] !=='NULL' and $toolDescription [2][$key4] !==NULL) {
											$buttons .= '<select id="'.$toolDescription[2][$key4].'">'.$option.'</select>'; 
										}
										if ($toolDescription[$key3][$key4] !=='NULL' and $toolDescription [$key3][$key4] !==NULL) {
											$buttons.= '<button id="'.$toolDescription[0][$key4].'" class="myToolButton" title="'.$toolDescription[3][$key4].'">'.$toolDescription[1][$key4].'</button></li>'; 
										}	
									}
									

	
								}

							}
							
						}
						$buttons .='</ul></div>';
				}
			}
			if ($restriction =='admin') {
				$toolListToString .= '<li id="'.$tool[0].'" class="toolsListLabel"><h2>'.$tool[1].'</h2></li>';
			} else {
				if ($tool[0] !== 'fullAccess') {
					$toolListToString .= '<li id="'.$tool[0].'" class="toolsListLabel"><h2>'.$tool[1].'</h2></li>';
				}
			}
			
			
		} else {
			echo 'erro with array';
		}
			
	}
	$toolListToString .='</ul>';
	$newTools .= '<div class="newTools clear">'.$toolListToString.$buttons.'</div>'.'</div></div>';
	//------------------------------------------------------------------------------------------------------------
	//----------------------------city array--------------------------------------------------------------------
	$cities = array(
	array('volia.vn','vinnitsa','Вінниця'),
	array('volia.kh','kharkiv','Харків'),
	array('volia.sevastopol','sevastopol','Севастополь'),
	array('volia.kr','kropyvnytskyi','Кропивницький'),
	array('volia.ck','cherkassy','Черкаси'),
	array('volia.pl','poltava','Полтава'),
	array('volia.km','khmelnitsky','Хмельницький'),
	array('volia.simferopol','simferopol','Сімферополь'),
	array('volia.sumy','sumy','Суми'),
	array('volia.dn','donetsk','Донецьк'),
	array('volia.dp','dnipro','Дніпро'),
	array('volia.zp','zaporizhia','Запоріжжя'),
	array('volia.kiev','kiev','Київ'),
	array('volia.al','alchevsk','Алчевськ'),
	array('volia.cv','chernivtsi','Чернівці'),
	array('volia.kherson','kherson','Херсон'),
	array('volia.krm','kramatorsk','Краматорськ'),
	array('volia.rv','rivne','Рівне'),
	array('volia.lviv','lviv','Львів'),
	array('volia.kryvyirih','kryvyirih','Кривий Ріг'),
	array('volia.putyvl','putyvl','Путивль'),
	array('volia.kremenec','kremenets','Кременець'),
	array('volia.lutsk','lutsk','Луцьк'),
	array('volia.terebovlia','terebovlya','Теребовля'),
	array('volia.ternopil','ternopil','Тернопіль'),
	array('volia.chortkiv.chortkiv','chortkiv','Чортків'),
	array('volia.illichivsk','Chornomorsk','Іллічівськ'),
	array('volia.ovidiopol','ovidiopol','Овідіополь'),
	array('volia.karlivka','karlivka','Карлівка'),
	array('volia.fas','fastiv','Фастів'),
	array('volia.makiyivka','makiyivka','Макіївка'),
	array('volia.volochysk','volochisk','Волочиськ'),
	array('volia.solonitsevka','solonitsevka','Солоницівка'),
	array('volia.brovary','brovary', 'Бровари'),
	array('volia.zhytomyr','zhitomir','Житомир'),
	array('volia.melitopol','melitopol','Мелітополь'),
	array('volia.stebnyk','stebnyk','Стебник'),
	array('volia.odessa','odesa','Одеса'),
	array('volia.berdychiv','berdychiv', 'Бердичів'),
	array('volia.ukrainka','ukrainka','Українка'),
	array('volia.dobrotvir','dobrotvir','Добротвір'),
	array('volia.truskavets','truskavets','Трускавець'),
	array('volia.kamianets-podilskyi','kamianets-podilskyi','Кам_янець-Подільський'),
	array('volia.obukhiv','obukhiv','Обухів'),
	array('volia.bila-tserkva','bilatserkva', 'Біла Церква'),
	array('volia.nvm','novomoskovsk','Новомосковськ') 
	);
	//------------------------------------------------------------------------------------------------------------

	//echo $toolListToString;
	//echo $buttons;
	//echo $newTools;

	//$toolsNew .= '<div class="toolsNew" id="toolsNew">'.'<div class="tools__visible">'.'</div>'.'<div class ="tools__hidden clear">'.'<ul>'; 
          //print_r($city_array);
         
?>