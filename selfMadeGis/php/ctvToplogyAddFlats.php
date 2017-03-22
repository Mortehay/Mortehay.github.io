<?php

//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ctv_city_flats_eng']) {$selectedCity= $_POST['ctv_city_flats_eng'];} else {$selectedCity = $_REQUEST["ctv_city_flats_eng"];} 
if ($_POST['she']) {$selectedShe= $_POST['she'];} else {$selectedShe = $_REQUEST["she"];} 
if($selectedShe == 'виберіть ПГС') 
	{$deleteNotselectedShe =''; $selectedShe = '';} 
else {
	$deleteNotselectedShe= "DELETE FROM _city_hlam".$selectedCity."_ctv_topology_full  WHERE PGS_ADDR <> '".$selectedShe."'; ";
	$selectedShe = " AND cubic_pgs_addr  ='".$selectedShe."'  ";
}
  $promeLink = "/tmp/";
  $secondaryLink = "/var/www/QGIS-Web-Client-master/site/csv/archive/";
  $fileExtention ="_ctv_topology_full.csv";
  $files = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['files'];
  //print_r($files);
  $linkStorage = fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention)['linkStorage'];
  //print_r($linkStorage);
if ($files) {
  foreach($files as $file) {
    $str_file = (string)$file;
    if ($str_file !== '.' && $str_file !== '..') {
      //print_r($str_file);
      if ($str_file == $selectedCity.$fileExtention) {
        $newDBrequest = new dbConnSetClass;
        $query = "CREATE  TEMP TABLE _city_hlam".$selectedCity."_ctv_topology_full (id serial, CITY varchar(100),STREET varchar(100), HOUSE varchar(100), FLAT varchar(100),CODE varchar(100),NAME varchar(100),PGS_ADDR varchar(100),OU_OP_ADDR varchar(100),OU_CODE varchar(100),DATE_REG varchar(100),COMENT varchar(100),UNAME varchar(100),NET_TYPE varchar(100),HOUSE_ID  varchar(100)); select copy_for_testuser('_city_hlam".$selectedCity."_ctv_topology_full(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)', ".$linkStorage.", ';', 'windows-1251');".$deleteNotselectedShe;
        $queryArrayKeys = false;
        //echo $query;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, false);
        $query = "CREATE TEMP TABLE tmp AS select _full.code, (WITH RECURSIVE subordinates AS ( SELECT code, ou_code, name, street, house, flat FROM _city_hlam".$selectedCity."_ctv_topology_full WHERE code = _full.code UNION ALL SELECT e.code,e.ou_code,e.name, e.street, e.house, e.flat FROM _city_hlam".$selectedCity."_ctv_topology_full e INNER JOIN subordinates s ON s.code = e.ou_code ) select array_agg(min_max_flat)  from (SELECT subordinates.name,  concat(subordinates.street,' №', subordinates.house,'--',min(subordinates.flat::int) ,'/', max(subordinates.flat::int)) as min_max_flat FROM subordinates where textregexeq(replace(subordinates.flat, ' ', ''),'^[[:digit:]]+(\.[[:digit:]]+)?$') group by subordinates.name, subordinates.street, subordinates.house order by subordinates.name, subordinates.street, subordinates.house) as amplifier_flats) as flats  from _city_hlam".$selectedCity."_ctv_topology_full _full where _full.code in (select _full.code from _city_hlam".$selectedCity."_ctv_topology_full _full where _full.name = 'Домовой узел');  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology set flats = tmp.flats from tmp WHERE cubic_code = tmp.code; drop table tmp; drop table _city_hlam".$selectedCity."_ctv_topology_full;";
        $queryArrayKeys = false;
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $query = "SELECT cubic_street,cubic_house,cubic_code,cubic_name,cubic_pgs_addr,cubic_ou_op_addr,cubic_ou_code,cubic_date_reg,cubic_coment,cubic_uname,cubic_net_type, cubic_house_id,flats from ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name = 'Домовой узел' ".$selectedShe.";";
        $queryArrayKeys = array('cubic_street','cubic_house','cubic_code','cubic_name','cubic_pgs_addr','cubic_ou_op_addr','cubic_ou_code','cubic_date_reg','cubic_coment','cubic_uname','cubic_net_type', 'cubic_house_id','flats');
        $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
        $sumObjectsArray = $retuenedArray;
        //print_r($sumObjectsArray);
        $arr_response = array('response' => array());
        foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
          $arr = array(
            'cubic_street' => $retuenedArray[$sumObjectsArrayKey]['cubic_street'],
            'cubic_house' => $retuenedArray[$sumObjectsArrayKey]['cubic_house'],
            'cubic_code' => $retuenedArray[$sumObjectsArrayKey]['cubic_code'],
            'cubic_name' => $retuenedArray[$sumObjectsArrayKey]['cubic_name'],
            'cubic_pgs_addr' => $retuenedArray[$sumObjectsArrayKey]['cubic_pgs_addr'],
            'cubic_ou_op_addr' => $retuenedArray[$sumObjectsArrayKey]['cubic_ou_op_addr'],
            'cubic_ou_code' => $retuenedArray[$sumObjectsArrayKey]['cubic_ou_code'],
            'cubic_date_reg' => $retuenedArray[$sumObjectsArrayKey]['cubic_date_reg'],
            'cubic_coment' => $retuenedArray[$sumObjectsArrayKey]['cubic_coment'],
            'cubic_uname' => $retuenedArray[$sumObjectsArrayKey]['cubic_uname'],
            'cubic_net_type' => $retuenedArray[$sumObjectsArrayKey]['cubic_net_type'],
            'cubic_house_id' => $retuenedArray[$sumObjectsArrayKey]['cubic_house_id'],
            'flats' => $retuenedArray[$sumObjectsArrayKey]['flats'],
          );
          array_push($arr_response['response'], $arr ); 
        }
        print json_encode($arr_response);
      }
    } 
  }
}  
?>