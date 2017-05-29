<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');

if ($_POST['ether_city_eng']) {  $selectedCity= $_POST['ether_city_eng']; } else { $selectedCity = $_REQUEST['ether_city_eng'];} 

$promeLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
$secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/cubic/";
$tableType = "_switches";
$fileExtention =".csv";
$files = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['files'];

$linkStorage = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['linkStorage'];
$updateState = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['updateState'];
//echo $linkStorage.'<hr>';
//echo $updateState.'<hr>';
if ($updateState == 'auto') { $queryModificator = array(
    'var' => 'idt serial, ID character varying(100),MAC_ADDRESS character varying(100),IP_ADDRESS character varying(100),SERIAL_NUMBER character varying(100),HOSTNAME character varying(100),DEV_FULL_NAME text,VENDOR_MODEL character varying(100),SW_MODEL character varying(100),SW_ROLE character varying(100),HOUSE_ID character varying(100),DOORWAY character varying(100),LOCATION character varying(100),FLOOR character varying(100),SW_MON_TYPE character varying(100),SW_INV_STATE character varying(100),VLAN character varying(100),DATE_CREATE character varying(100),DATE_CHANGE character varying(100),IS_CONTROL character varying(100),IS_OPT82 character varying(100),PARENT_ID character varying(100), PARENT_MAC  character varying(100),PARENT_PORT character varying(100),CHILD_ID character varying(100),CHILD_MAC character varying(100),CHILD_PORT character varying(100),PORT_NUMBER character varying(100),PORT_STATE character varying(100),CONTRACT_CNT character varying(100),CONTRACT_ACTIVE_CNT character varying(100),GUEST_VLAN character varying(100),CITY_ID character varying(100),CITY character varying(100),CITY_CODE character varying(100),REPORT_DATE character varying(100)', 
    'val'=> 'ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER, HOSTNAME, DEV_FULL_NAME, VENDOR_MODEL, SW_MODEL, SW_ROLE, HOUSE_ID, DOORWAY, LOCATION, FLOOR, SW_MON_TYPE, SW_INV_STATE,VLAN, DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82, PARENT_ID, PARENT_MAC, PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT, PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN,CITY_ID, CITY, CITY_CODE, REPORT_DATE',
    'delimiter' =>"','" , 
    'encoding'=>"'utf-8'");
} elseif ($updateState == 'manual') { $queryModificator = array(
  'var' => 'idt serial, ID character varying(100), MAC_ADDRESS character varying(100),IP_ADDRESS character varying(100),SERIAL_NUMBER character varying(100),HOSTNAME character varying(100),DEV_FULL_NAME text,VENDOR_MODEL character varying(100),SW_MODEL character varying(100),SW_ROLE character varying(100),HOUSE_ID character varying(100),DOORWAY character varying(100),LOCATION character varying(100),FLOOR character varying(100),SW_MON_TYPE character varying(100),SW_INV_STATE character varying(100),VLAN character varying(100),DATE_CREATE character varying(100),DATE_CHANGE character varying(100),IS_CONTROL character varying(100),IS_OPT82 character varying(100),PARENT_ID character varying(100),PARENT_MAC character varying(100),PARENT_PORT character varying(100),CHILD_ID character varying(100),CHILD_MAC character varying(100),CHILD_PORT character varying(100),PORT_NUMBER character varying(100),PORT_STATE character varying(100),CONTRACT_CNT character varying(100),CONTRACT_ACTIVE_CNT character varying(100),GUEST_VLAN character varying(100)', 
  'val'=> 'ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER, HOSTNAME, DEV_FULL_NAME, VENDOR_MODEL,SW_MODEL, SW_ROLE, HOUSE_ID,DOORWAY, LOCATION, FLOOR, SW_MON_TYPE, SW_INV_STATE,VLAN,DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82, PARENT_ID, PARENT_MAC, PARENT_PORT, CHILD_ID, CHILD_MAC,CHILD_PORT,PORT_NUMBER,PORT_STATE,CONTRACT_CNT,CONTRACT_ACTIVE_CNT,GUEST_VLAN',
  'delimiter' =>"';'", 
  'encoding'=>"'windows-1251'"
  ); 
}
$arr_response = array('response' => array());
$newDBrequest = new dbConnSetClass;
 if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
         //print_r($str_file);
      if ($str_file == $selectedCity.$tableType.$fileExtention) {
        $query = "CREATE TEMP TABLE temp( ".$queryModificator['var'].");".$deleteNotselectedShe." select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; DELETE FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id NOT IN(SELECT DISTINCT ID FROM temp) ;UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_mac_address = temp.MAC_ADDRESS,cubic_ip_address = temp.IP_ADDRESS,cubic_hostname = temp.HOSTNAME,cubic_switch_model = temp.SW_MODEL,cubic_switch_role = temp.SW_ROLE,cubic_house_id = temp.HOUSE_ID,cubic_house_entrance_num = temp.DOORWAY,cubic_monitoring_method = temp.SW_MON_TYPE,cubic_inventary_state = temp.SW_INV_STATE,cubic_vlan = temp.VLAN, cubic_parent_down_port = temp.PARENT_PORT,cubic_parent_mac_address = temp.PARENT_MAC,cubic_up_port = temp.PORT_NUMBER,cubic_rgu = temp.CONTRACT_CNT FROM  temp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_switch_id = temp.ID; SELECT cubic_street, cubic_house, temp.ID, PARENT_ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER,HOSTNAME, SW_MODEL,HOUSE_ID, DOORWAY, LOCATION, FLOOR,SW_INV_STATE, DATE_CREATE, DATE_CHANGE FROM temp LEFT JOIN ". $selectedCity.".".$selectedCity."_buildings ON temp.HOUSE_ID = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id WHERE temp.ID NOT IN(SELECT distinct cubic_switch_id FROM ". $selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL);";
        //echo $query.'<hr>';
        $queryArrayKeys = array('cubic_street', 'cubic_house', 'ID', 'PARENT_ID','MAC_ADDRESS','IP_ADDRESS','SERIAL_NUMBER','HOSTNAME','SW_MODEL','HOUSE_ID','DOORWAY','LOCATION','FLOOR','SW_INV_STATE','DATE_CREATE','DATE_CHANGE');
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $arr_response = array('response' => array());
        $sumObjectsArray = $retuenedArray;
         foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
          $arr = array(
            'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_street'],
            'cubic_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_house'],
            'DOORWAY' => $sumObjectsArray[$sumObjectsArrayKey]['DOORWAY'],
            'FLOOR' => $sumObjectsArray[$sumObjectsArrayKey]['FLOOR'],
            'LOCATION' => $sumObjectsArray[$sumObjectsArrayKey]['LOCATION'],
            'HOUSE_ID' => $sumObjectsArray[$sumObjectsArrayKey]['HOUSE_ID'],
            'ID' => $sumObjectsArray[$sumObjectsArrayKey]['ID'],
            'PARENT_ID' => $sumObjectsArray[$sumObjectsArrayKey]['PARENT_ID'],
            'MAC_ADDRESS' => $sumObjectsArray[$sumObjectsArrayKey]['MAC_ADDRESS'],
            'IP_ADDRESS' => $sumObjectsArray[$sumObjectsArrayKey]['IP_ADDRESS'],
            'SERIAL_NUMBER' => $sumObjectsArray[$sumObjectsArrayKey]['SERIAL_NUMBER'],
            'HOSTNAME' => $sumObjectsArray[$sumObjectsArrayKey]['HOSTNAME'],
            'SW_MODEL' => $sumObjectsArray[$sumObjectsArrayKey]['SW_MODEL'],
            'SW_INV_STATE' => $sumObjectsArray[$sumObjectsArrayKey]['SW_INV_STATE'],
            'DATE_CREATE' => $sumObjectsArray[$sumObjectsArrayKey]['DATE_CREATE'],
            'DATE_CHANGE' => $sumObjectsArray[$sumObjectsArrayKey]['DATE_CHANGE']
         ); 
          array_push($arr_response['response'], $arr );
          
        }
      }
    } 
  }
}
//print $updateState;
print json_encode($arr_response);
$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_switches.switches_geom IS NULL AND ".$selectedCity.".".$selectedCity."_switches.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
//echo $query.'<hr>';
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
$query ="CREATE TEMP TABLE tmp AS SELECT cubic_switch_id, cubic_switch_role, cubic_switch_model,  switches_geom FROM ".$selectedCity.".".$selectedCity."_switches where cubic_switch_id IN (SELECT distinct cubic_switch_id FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_switches SET parent_switches_geom = tmp.switches_geom, cubic_parent_switch_role = tmp.cubic_switch_role, cubic_parent_switch_model = tmp.cubic_switch_model FROM tmp WHERE ".$selectedCity."_switches.cubic_parent_switch_id = tmp.cubic_switch_id; DROP TABLE tmp;   UPDATE ".$selectedCity.".".$selectedCity."_switches SET topology_line_geom = ST_MakeLine(parent_switches_geom, switches_geom) WHERE ".$selectedCity."_switches.parent_switches_geom IS NOT null AND ".$selectedCity."_switches.switches_geom IS NOT NULL;";
//echo $query.'<hr>';
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_city = ".$selectedCity."_buildings.cubic_city, cubic_district = ".$selectedCity."_buildings.cubic_distr_new,
 cubic_street = ".$selectedCity."_buildings.cubic_street, cubic_house_num = ".$selectedCity."_buildings.cubic_house FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity."_switches.cubic_house_id = ".$selectedCity."_buildings.cubic_house_id AND ".$selectedCity."_switches.cubic_house_id IS NOT NULL AND ".$selectedCity."_buildings.cubic_house_id IS NOT NULL;";
//echo $query.'<hr>';
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
//------------------
$query ="CREATE TEMP TABLE tmp_agr (cubic_switch_id varchar(100), cubic_parent_switch_id varchar(100), cubic_switch_role varchar(100), cubic_switch_agr_id varchar(100), level integer); INSERT INTO tmp_agr WITH RECURSIVE tmp_agr ( cubic_switch_id, cubic_parent_switch_id, cubic_switch_role, cubic_parent_switch_agr_id , LEVEL ) AS (SELECT T1.cubic_switch_id , T1.cubic_parent_switch_id , T1.cubic_switch_role , T1.cubic_parent_switch_id as cubic_parent_switch_agr_id , 1 FROM ".$selectedCity.".".$selectedCity."_switches T1 WHERE T1.cubic_parent_switch_role = 'agr' union select T2.cubic_switch_id, T2.cubic_parent_switch_id, T2.cubic_switch_role,tmp_agr.cubic_parent_switch_agr_id ,LEVEL + 1 FROM ".$selectedCity.".".$selectedCity."_switches T2 INNER JOIN tmp_agr ON( tmp_agr.cubic_switch_id = T2.cubic_parent_switch_id) ) select * from tmp_agr  ORDER BY cubic_parent_switch_agr_id; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_switch_agr_id = tmp_agr.cubic_switch_agr_id FROM tmp_agr WHERE ".$selectedCity."_switches.cubic_switch_id = tmp_agr.cubic_switch_id; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_switch_agr_id = null WHERE ".$selectedCity."_switches.cubic_switch_id not in (select distinct cubic_switch_id from tmp_agr where cubic_switch_id is not null);";

//echo $query.'<hr>';
$retuenedArray = $newDBrequest -> dbConnect($query, false, true);
?>

