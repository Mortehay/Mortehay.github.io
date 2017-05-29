<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
  
if ($_POST['ether_city_add_eng']) {
  $selectedCity= $_POST['ether_city_add_eng'];
} else {
  $selectedCity = $_REQUEST['ether_city_add_eng'];
}  
  $promeLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/cubic/";
  $tableType = "_switches";
  $fileExtention =".csv";
  $files = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['files'];

  $linkStorage = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['linkStorage'];
  $updateState = fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention)['updateState'];
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
  
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$tableType.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE TEMP TABLE temp( ".$queryModificator['var'].");".$deleteNotselectedShe." select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding']."); INSERT INTO " . $selectedCity.".".$selectedCity."_switches(cubic_switch_id, cubic_mac_address, cubic_ip_address, cubic_switch_serial_number, cubic_hostname, cubic_switch_model, cubic_switch_role, cubic_house_id, cubic_house_entrance_num, cubic_switch_location, cubic_house_floor, cubic_monitoring_method, cubic_inventary_state, cubic_vlan, cubic_switch_date_create, cubic_switch_date_change, cubic_switch_is_control, cubic_switch_is_opt82, cubic_parent_switch_id, cubic_parent_mac_address, cubic_parent_down_port, cubic_up_port, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt)  SELECT ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER, HOSTNAME, SW_MODEL, SW_ROLE, HOUSE_ID, DOORWAY, LOCATION, FLOOR, SW_MON_TYPE, SW_INV_STATE, VLAN, DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82, PARENT_ID, PARENT_MAC, PARENT_PORT, PORT_NUMBER, CONTRACT_CNT, CONTRACT_ACTIVE_CNT FROM temp WHERE ID NOT IN(SELECT distinct cubic_switch_id FROM ". $selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL);";
        $queryArrayKeys = false;
        echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
      }
    } 
  }
}
?>

