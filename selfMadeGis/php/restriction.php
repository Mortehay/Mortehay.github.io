<?php
//ini_set('display_errors', 1);
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
  if ($restriction != NULL) {
  $restriction=json_encode($restriction);
  print "<script type='text/javascript'>console.log(".$restriction.");</script>";
  }
  $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=postgres";
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
              if ($restriction=="full") {
                $sql = "SELECT id, city, links, links_description, city_eng   FROM public.links WHERE links IS NOT NULL ORDER BY city";
              }

              if ($restriction == "central" || $restriction =="eastern" || $restriction == "western") {
                $sql = "SELECT id, city, links, links_description, city_eng, region   FROM public.links WHERE links IS NOT NULL AND region = '$restriction' ORDER BY city";
              }

              if ($restriction!=="full" && $restriction !== "central" && $restriction !=="eastern" && $restriction !== "western") {
                $sql = "SELECT id, city, links, links_description, city_eng, prime_city   FROM public.links WHERE links IS NOT NULL AND prime_city = '$restriction' ORDER BY city";
              }
              $ret = pg_query($db, $sql);
              
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
			foreach ($city_array as $key => $value) {
			    $option .='<option value="'.$city_array[$key].'">'.$city_array[$key].'</option>';
			}
			 $tools .= '<div class="tools" id="tools">'.'<div class="tools__visible">'.'</div>'.'<div class ="tools__hidden clear">'.'<ul>';
             if ($restriction=="full"){
             	$tools .= '<li class="fullAccess"><h1>full access</h1></li>'.'<li class="fullAccess">'.'<button class="myToolButton" id="textexchange">host chasnge</button>'.'</li>'.'<li class="fullAccess">'.'<button class="myToolButton" id="userLoginView">user Login View</button>'.'</li>'.'<li class="fullAccess">'.'<select id="cable_channel_cable_data_city_eng">'.$option.'</select>'.'<button class="myToolButton" id="cableChannelCabelDataUpdate">cable Channel Cabel Data Update</button>'.'</li>'.'<li>'.'<select id="cable_channel_channel_data_city_eng">'.$option.'</select>'.'<button class="myToolButton" id="cableChannelChannelDataUpdate">cable Channel Channel Data Update</button>'.'</li>';

            }
            $tools .='<li>'.'<select id="city_building_data_eng">'.$option.'</select>'.'<button class="myToolButton" id="cityBuildingDataUpdate">city Building Data Update</button>'.'</li>'.'<li>'.'<select id="ctv_city_eng">'.$option.'</select>'.'<button class="myToolButton" id="ctvTopologyUpdate">ctv Topology Update</button>'.'.</li>'.'<li>'.'<select id="ether_city_eng">'.$option.'.</select>'.'<button class="myToolButton" id="etherTopologyUpdate">ether Topology Update</button>'.'. </li>'.'<li>'.'<select id="cable_channel_city_eng">'.$option.'.</select>'.'<button class="myToolButton" id="cableChannelTopologyUpdate">Cable channels topology update</button>'.'</li>'.'<li>'.'<select id="cable_channel_cable_dataView_city_eng">'.$option.'</select>'.'<button class="myToolButton" id="cableChannelCableDataView">cable Channel Cable Data View</button>'.'</li>'.'</ul>'.'</div>'.'</div>';

          } 
          
	$toolsList = array(
		array('fullAccess', 'Повний доступ', 
			array( 
				array('userLoginView', 'textexchange'), 
				array('Графік відвідувань', 'Заміна host в qgis файлі'), 
				array('NULL','NULL') 
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
					array('cityBuildingDataUpdate'),
					array('Оновлення даних про будинки'),
					array('city_building_data_eng')
			)
		),
		array('ctv','КТВ',
			array(
					array('ctvTopologyUpdate'),
					array('Оновлення топології КТВ'),
					array('ctv_city_eng')
					)
				),
		array('internet','Інтернет',
			array(
					array('etherTopologyUpdate'),
					array('Оновлення топології Ethernet'),
					array('ether_city_eng')
			)
		)
	);
	$toolListToString ='';
	$buttons='';
	$newTools = '<div class="tools" id="tools"><div class="tools__visible"></div><div class ="tools__hidden clear">';
	$toolListToString .='<ul class="labelsList">';
	foreach( $toolsList as $key =>$tool){
		if (is_array($tool)) {
			foreach ($tool as $key => $toolDescription) {
				
					if (is_array($toolDescription) ) {
						$buttons .='<div id="'.$tool[0].'_holder" class="clear invisible _holder"><ul>';
						foreach ($toolDescription as $key => $button) {
							if ($toolDescription[1][$key] !== NULL and $toolDescription[1][$key] !=='NULL') {
								$buttons .='<li>';	
							}
							if ($toolDescription[2][$key] !== NULL and $toolDescription[2][$key] !== 'NULL') {
								$buttons .= '<select id="'.$toolDescription[2][$key].'">'.$option.'</select>'; 
							}
							if ($toolDescription[0][$key] !== NULL and $toolDescription[0][$key] !=='NULL') {
								$buttons.= '<button id="'.$toolDescription[0][$key].'" class="myToolButton">'.$toolDescription[1][$key].'</button>';	
							}
							if ($toolDescription[1][$key] !== NULL and $toolDescription[1][$key] !=='NULL') {
								$buttons .='</li>';	
							}
						}
						$buttons .='</ul></div>';
				}
			}
			if ($restriction =='full') {
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