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
			foreach ($city_array as $key => $value) {
			    $option .='<option value="'.$city_array[$key].'">'.$city_array[$key].'</option>';
			}
	

          } 
          
	$toolsList = array(
		array('fullAccess', 'Повний доступ', 
			array( 
				array('userLoginView', 'textexchange','simpleuserRestrictionUpdate','cityTablesCreate'), 
				array('Графік відвідувань', 'Заміна host в qgis файлі','Апдейт дозволу доступу simpleuser до схем Postgresql','Додавання комплекту таблиць до міста'), 
				array('NULL','NULL','NULL','tables_create_city_eng') 
			) 
		),
		array('cableChannelChannels', 'КК - канали',
			array( 
				array('cableChannelChannelDataUpdate','cableChannelTopologyUpdate'), 
				array('Оновлення даних КК - канали','Оновлення топології КК- канали'), 
				array('cable_channel_channel_data_city_eng','cable_channel_city_eng') 
			) 
		),
		array('cableChannelCables','КК - кабелі',
			array( 
				array('cableChannelCabelDataUpdate','cableChannelCableDataView'), 
				array('Оновлення даних КК - кабелі','Вивести таблицю КК - кабелі'),
				array('cable_channel_cable_data_city_eng','cable_channel_cable_dataView_city_eng')
			)
		),
		array('cableAirCables','ПКП - кабелі',
			array(
				array('cableAirCableDataUpdate', 'cableAirCableDataView'),
				array('Оновлення даних ПКП - кабелі', 'Вивести таблицю ПКП - кабелі'),
				array('cable_air_cable_data_city_eng', 'cable_air_cable_dataView_city_eng')
			)
		),
		array('buildings','Будинки',
			array(
				array('cityBuildingDataUpdate', 'cityBuildingDublicatesFinder','cityEntranceDataUpdate'),
				array('Оновлення даних про будинки', 'Відобразити дублікати будинків','Прив"язка під"їздів до будинку'),
				array('city_building_data_eng', 'city_building_dublicates_finder_eng','building_entrance_data_update_city_eng')
			)
		),
		array('ctv','КТВ',
			array(
				array('ctvTopologyUpdate', 'ctvNodCoverageUpdate'),
				array('Оновлення топології КТВ', 'Оновлення покриття оптичних вузлів'),
				array('ctv_city_eng', 'ctv_city_nod_eng')
			)
		),
		array('internet','Інтернет',
			array(
				array('etherTopologyUpdate','cityStateSwitches'),
				array('Оновлення топології Ethernet','Оновити стан комутаторів'),
				array('ether_city_eng','switches_state_city_eng')
			)
			
		),
		array('networkSupply', 'Підтримка мережі',
			array(
				array('toCoverageUpdate','usoCoverageUpdate'),
				array('Оновлення зони покриття дільниць ТО','Оновлення зони покриття дільниць СО'),
				array('city_supply_to_eng','city_supply_uso_eng')
			)
		)
	);
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
											$buttons.= '<button id="'.$toolDescription[0][$key4].'" class="myToolButton">'.$toolDescription[1][$key4].'</button></li>'; 
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
	//echo $toolListToString;
	//echo $buttons;
	echo $newTools;

	//$toolsNew .= '<div class="toolsNew" id="toolsNew">'.'<div class="tools__visible">'.'</div>'.'<div class ="tools__hidden clear">'.'<ul>'; 
          //print_r($city_array);
         
?>