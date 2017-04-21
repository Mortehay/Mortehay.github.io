
<?php
ini_set('display_errors', 1);
//include('restriction.php');
$server_address = $_SERVER['SERVER_ADDR']; 
echo  $server_address;
include('classFunctionStorage.php');
$postgres = new dbConnSetClass;
$query = "SELECT city_eng FROM public.links where links IS NOT NULL;";
$queryArrayKeys = array('city_eng');;
echo $query.'<hr>';
$postgresCitiesArray = $postgres -> dbConnect($query, $queryArrayKeys, true);
//header('Content-type: text/plain; charset=utf-8');
$city_array = $postgresCitiesArray;
//print_r($city_array);
foreach ($city_array as $city) {
//echo $city['city_eng'].'<hr>';
	$selectedCity = $city['city_eng'];
	$tableType = "_ctv_topology";
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
	/////ctv topology tables  update
	$queryModificator = array(
      'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)', 
      'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE', 
      'delimiter' =>"','" , 
      'encoding'=>"'utf-8'");
 	$postgresCtvTopology = new dbConnSetClass;
 	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE " . $selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE; SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL);  DROP TABLE tmp;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresCtvTopology -> dbConnect($query, $queryArrayKeys, true);
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = CASE "." WHEN cubic_name LIKE '%Магистральный распределительный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Магістральний оптичний вузол%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Оптический узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Оптичний приймач%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Передатчик оптический%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Порт ОК%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Домовой узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Ответвитель магистральный%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Распределительный стояк%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Магистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Субмагистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Кросс-муфта%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." END FROM  ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL; DROP TABLE tmp;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house, cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL) ;UPDATE  ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;";
	$postgresCtvTopology -> dbConnect($query, false, true); 	
	echo $query.'<hr>';
	$dir_arr_response = array();
	$query = "SELECT  cubic_name, cubic_code  FROM ".$selectedCity.".".$selectedCity."_ctv_topology;";
	$queryArrayKeys = array('cubic_name', 'cubic_code');
	//echo $query;
	$retuenedArray = $postgresCtvTopology -> dbConnect($query, $queryArrayKeys, true);
	$dir_arr_response = array();
	$sumObjectsArray = $retuenedArray;
	foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
	  //print_r($objectArray);
	  $description = array(
	      'cubic_name' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['label'],
	      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
	    );
	  //print_r($description);
	  if($description['cubic_name'] !==null){
	    array_push($dir_arr_response, $description );
	    //print_r($description);
	   // echo'<br>';
	    topologyDirCreate($description, $selectedCity);
	    //echo'<hr>';
	  }
	}

	$link_left_part = '"<a href="http://'.$server_address.'/qgis-ck/tmp/archive/';
	$link_right_part = '/" target="_blank">посилання на архів</a>"';
	$query = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "." WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Кросс-муфта%' THEN '$link_left_part"."$selectedCity"."/topology/cc/"."'||cubic_code||'"."$link_right_part' "."END ;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
    ////////////////////
    //////buildings tables update
    $queryModificator = array(
    'var' => 'id serial, CITY character varying(100),REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100),HOUSE character varying(100), COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100), LNAME character varying(100),LADRESS character varying(100), HPNAME character varying(100),HPADRESS character varying(100), HPCODE character varying(100), FREQ character varying(100),DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100), BOX_TYPE character varying(100),HOUSE_ID character varying(100), SECTOR_CNT character varying(100),CNT character varying(100), PARNET character varying(200), SERV_PARNET character varying(200), NETTYPE character varying(100), CNT_ATV character varying(100),CNT_VBB character varying(100), CNT_ETH character varying(100),CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100), REPORT_DATE character varying(100)', 
    'val'=> 'CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE, CNT_ATV, CNT_VBB, CNT_ETH, CNT_DOCSIS, CNT_KTV,CNT_ACTIVE_CONTR, MAX_SPEED_ETHERNET, MAX_SPEED_DOCSIS, REPORT_DATE', 
    'delimiter' =>"','" , 
    'encoding'=>"'utf-8'");
    $tableType = "_buildings";
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
    $postgresBuildings = new dbConnSetClass;
    $query = "CREATE TEMP TABLE temp(".$queryModificator['var']."); select copy_for_testuser('temp(".$queryModificator['val'].")', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = temp.CITY, cubic_region = temp.REGION, cubic_distr_new = temp.DISTR_NEW, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_subdep = temp.SUBDEP, cubic_uso = temp.USO, cubic_lname = temp.LNAME, cubic_ladress = temp.LADRESS, cubic_hpname = temp.HPNAME, cubic_hpadress = temp.HPADRESS, cubic_network_type = temp.NETTYPE, cubic_freq = temp.FREQ, cubic_house_type = temp.HOUSE_TYPE, cubic_csd = temp.CSD, cubic_cnt = temp.CNT, cubic_comm = temp.COMM, cubic_cnt_vbb = temp.CNT_VBB, cubic_cnt_eth = temp.CNT_ETH, cubic_cnt_docsis = temp.CNT_DOCSIS, cubic_cnt_ktv = temp.CNT_KTV, cubic_cnt_atv = temp.CNT_ATV, cubic_cnt_active_contr = temp.CNT_ACTIVE_CONTR, cubic_date_building = temp.DATE_BUILDING, cubic_date_building_eth = temp.DATE_BUILDING_ETH, cubic_date_ct = temp.DATE_CT, cubic_segment = temp.SEGMENT, cubic_digital_segment = temp.DIGITAL_SEGMENT, cubic_digital_stage = temp.DIGITAL_STAGE, cubic_digital_date = temp.DIGITAL_DATE, cubic_box_type = temp.BOX_TYPE, cubic_house_id = temp.HOUSE_ID, cubic_parnet = temp.PARNET, cubic_serv_parnet = temp.SERV_PARNET, cubic_sector_cnt = temp.SECTOR_CNT, cubic_hpcode = temp.HPCODE, cubic_max_speed_ethernet = temp.MAX_SPEED_ETHERNET, cubic_max_speed_docsis = temp.MAX_SPEED_DOCSIS FROM  temp WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id = temp.HOUSE_ID; DROP TABLE temp;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresBuildings -> dbConnect($query, $queryArrayKeys, true);
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_city = NULL, cubic_region = NULL, cubic_distr_new = NULL, cubic_street = NULL, cubic_house = NULL, cubic_subdep = NULL, cubic_uso = NULL, cubic_lname = NULL, cubic_ladress = NULL, cubic_hpname = NULL, cubic_hpadress = NULL, cubic_network_type =NULL, cubic_freq = NULL, cubic_house_type = NULL, cubic_csd = NULL, cubic_cnt = NULL, cubic_comm = NULL, cubic_cnt_vbb = NULL, cubic_cnt_eth = NULL, cubic_cnt_docsis = NULL, cubic_cnt_ktv = NULL, cubic_cnt_atv = NULL, cubic_cnt_active_contr = NULL, cubic_date_building = NULL, cubic_date_building_eth = NULL, cubic_date_ct = NULL, cubic_segment = NULL, cubic_digital_segment = NULL, cubic_digital_stage = NULL, cubic_digital_date = NULL, cubic_box_type = NULL, cubic_parnet = NULL, cubic_serv_parnet = NULL, cubic_sector_cnt = NULL, cubic_hpcode = NULL, cubic_max_speed_ethernet = NULL, cubic_max_speed_docsis = NULL  WHERE " . $selectedCity.".".$selectedCity."_buildings.cubic_house_id IS NULL;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresBuildings -> dbConnect($query, $queryArrayKeys, true);
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_firstpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),1), 32636) WHERE building_geom_firstpoint IS NULL ;  UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_secondpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),2), 32636) WHERE building_geom_secondpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_thirdpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),3), 32636) WHERE building_geom_thirdpoint IS NULL ; UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom_fourthpoint  = ST_SetSRID(ST_PointN(ST_LineMerge(ST_GeometryN (ST_Boundary(building_geom),1)),4), 32636) WHERE building_geom_fourthpoint IS NULL ;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresBuildings -> dbConnect($query, $queryArrayKeys, true);
    ///////////////////////
}
?>