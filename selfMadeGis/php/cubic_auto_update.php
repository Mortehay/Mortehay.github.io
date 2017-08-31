
<?php
ini_set('display_errors', 1);
//include('restriction.php');
if($_SERVER['SERVER_ADDR']) {
	$server_address = $_SERVER['SERVER_ADDR']; 
} else {
	$server_address = '10.112.129.170';
}
//$server_address = $_SERVER['SERVER_ADDR']; 
echo  $server_address;
include('classFunctionStorage.php');
$postgres = new dbConnSetClass;
$query = "SELECT city_eng FROM public.links where links IS NOT NULL;";
$queryArrayKeys = array('city_eng');;
echo $query.'<hr>';
$postgresCitiesArray = $postgres -> dbConnect($query, $queryArrayKeys, true);
//header('Content-type: text/plain; charset=utf-8');
$city_array = $postgresCitiesArray;
//$city_array = array(array('city_eng'=>'ukrainka'));
print_r($city_array);
//print_r($city_array);
foreach ($city_array as $city) {
echo $city['city_eng'].'<hr>';
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
 	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_code AS SELECT DISTINCT CODE FROM temp WHERE CODE IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code NOT IN(SELECT CODE FROM alien_cubic_code); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = CASE WHEN temp.COMENT LIKE '% № %' THEN replace(temp.COMENT, ' № ', '№') ELSE temp.COMENT END, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = temp.HOUSE_ID and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = temp.NAME; (SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, 'missing' as state FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ) UNION ALL (with data(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)  as (select CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID  from temp) select d.CITY, d.STREET, d.HOUSE, d.FLAT, d.CODE, d.NAME, d.PGS_ADDR, d.OU_OP_ADDR, d.OU_CODE, d.DATE_REG, d.COMENT, d.UNAME, d.NET_TYPE, d.HOUSE_ID, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_ctv_topology u where u.cubic_code = d.CODE and u.cubic_house_id = d.HOUSE_ID) and CODE IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ); ";
    $queryArrayKeys = array('CITY','STREET','HOUSE','FLAT','CODE','NAME','PGS_ADDR','OU_OP_ADDR','OU_CODE','DATE_REG','COMENT','UNAME','NET_TYPE','HOUSE_ID', 'state');
    echo $query.'<hr>';
    $postgresCtvTopology -> dbConnect($query, $queryArrayKeys, true);
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = CASE "." WHEN cubic_name LIKE '%Магистральный распределительный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Магістральний оптичний вузол%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Оптический узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Оптичний приймач%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Передатчик оптический%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Порт ОК%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Домовой узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Ответвитель магистральный%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Распределительный стояк%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Магистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Субмагистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Кросс-муфта%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." END FROM  ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house, cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL) ;UPDATE  ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;";
	$postgresCtvTopology -> dbConnect($query, false, true); 	
	echo $query.'<hr>';

	$link_left_part = 'http://'.$server_address.'/qgis-ck/tmp/archive/';
	$link_right_part = '/';
	$query = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "." WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "." WHEN cubic_name like '%Кросс-муфта%' THEN '$link_left_part"."$selectedCity"."/topology/cc/"."'||cubic_code||'"."$link_right_part' "."END ;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';

	//json field generation
	$query ="CREATE temp table t1 AS select cubic_ou_name, cubic_ou_code, array_agg(cubic_code) AS children from ".$selectedCity.".".$selectedCity."_ctv_topology  where cubic_ou_name in ('Кросс-муфта', 'Магистральный распределительный узел', 'Передатчик оптический', 'Оптический узел', 'Оптичний приймач') group by cubic_ou_code,cubic_ou_name ; CREATE temp table t2 AS select cubic_name, cubic_code, cubic_ou_code, archive_link from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code in (select distinct cubic_code from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code is not null and cubic_name in ('Кросс-муфта', 'Магистральный распределительный узел', 'Передатчик оптический', 'Оптический узел', 'Оптичний приймач' , 'Оптичний приймач'));  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET json_data = tmp.json_data from (select t.id AS cubic_code ,row_to_json(t) AS json_data from (select t2.cubic_name AS name, t2.cubic_ou_code AS parents, t2.cubic_code AS id, t1.children,  t2.archive_link from t1 right join t2  on t1.cubic_ou_code = t2.cubic_code) t) tmp where ".$selectedCity."_ctv_topology.cubic_code = tmp.cubic_code AND ".$selectedCity."_ctv_topology.archive_link is not null;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query;
	$query = "SELECT  cubic_name, cubic_code, json_data  FROM ".$selectedCity.".".$selectedCity."_ctv_topology;";
	$queryArrayKeys = array('cubic_name', 'cubic_code', 'json_data');
	//echo $query;
	$retuenedArray = $postgresCtvTopology -> dbConnect($query, $queryArrayKeys, true);
	$sumObjectsArray = $retuenedArray;
	$separator = $file_names_values = '';
	foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
	  //print_r($objectArray);
	  $description = array(
	      'cubic_name' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['label'],
	      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
	      'rootDir' => '/var/www/QGIS-Web-Client-master/site/tmp/archive/',
	      'subDirType' => '/topology/',
	      'json_data' => json_decode($sumObjectsArray[$sumObjectsArrayKey]['json_data'])
	    );
	  //print_r($description);
	  if($description['cubic_name'] !==null){
	    
	    //print_r($description);
	   // echo'<br>';
	    topologyDirCreate($description, $selectedCity);
	    if($description['json_data'] !== null){
	        if(dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/') ){ $file_names = dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/');
	          $description['json_data']->file_names = $file_names;
	          $file_names_values .= $separator."('".$description['cubic_code']."','".json_encode($description['json_data'] )."')";  
	          $separator =",";
	      }
	    }
	  }
	}
	if ($file_names_values != ''){
	  $query = "CREATE TEMP TABLE tmp(cubic_code varchar(100), json_data text); INSERT INTO tmp VALUES ".$file_names_values.";UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET json_data = tmp.json_data FROM tmp WHERE tmp.cubic_code = ".$selectedCity."_ctv_topology.cubic_code ;";
	  echo $query;
	  $postgresCtvTopology -> dbConnect($query, false, true);
	}
    ////////////////////
    //////buildings tables update
    $queryModificator = array(
    'var' => 'id serial, CITY character varying(100),REGION character varying(100), DISTR_NEW character varying(100), STREET character varying(100),HOUSE character varying(100), COMM text,CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100), LNAME character varying(100),LADRESS character varying(100), HPNAME character varying(100),HPADRESS character varying(100), HPCODE character varying(100), FREQ character varying(100),DATE_BUILDING character varying(100), DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100), DIGITAL_SEGMENT character varying(100), DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100), BOX_TYPE character varying(100),HOUSE_ID character varying(100), SECTOR_CNT character varying(100),CNT character varying(100), PARNET text, SERV_PARNET text, NETTYPE character varying(100), CNT_ATV character varying(100),CNT_VBB character varying(100), CNT_ETH character varying(100),CNT_DOCSIS character varying(100), CNT_KTV character varying(100), CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100), MAX_SPEED_DOCSIS character varying(100), REPORT_DATE character varying(100)', 
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
    ///coverage update
    $postgresCoverage = new dbConnSetClass;
    //she coverage update/
    $query = "CREATE TEMP TABLE temp AS SELECT cubic_hpadress,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_hpadress is not null group by cubic_hpadress order by cubic_cnt desc; UPDATE ".$selectedCity.".".$selectedCity."_coverage SET cubic_cnt = temp.cubic_cnt, cubic_cnt_docsis = temp.cubic_cnt_docsis , cubic_cnt_ktv = temp.cubic_cnt_ktv , cubic_cnt_atv = temp.cubic_cnt_atv , cubic_cnt_vbb = temp.cubic_cnt_vbb, cubic_cnt_eth = temp.cubic_cnt_eth , cubic_cnt_active_contr = temp.cubic_cnt_active_contr FROM temp WHERE ".$selectedCity."_coverage.notes = temp.cubic_hpadress and geom_area is not null;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresCoverage -> dbConnect($query, $queryArrayKeys, true);
    //////////////////////
    
    //Nod Coverage 
    $query = "CREATE TEMP TABLE temp AS SELECT cubic_lname, cubic_ladress,  array_agg(cubic_house_id) as  agg_cubic_house_id, ST_ConvexHull(ST_union(ST_makevalid(building_geom))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_lname not in('не опр') group by cubic_lname, cubic_ladress order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_nod_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_nod_coverage(cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_lname, cubic_ladress, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp; ";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresCoverage -> dbConnect($query, $queryArrayKeys, true);
    //return path coverage
    $query = "create temp table tmp as( WITH RECURSIVE included_parts(cubic_code, cubic_ou_code, cubic_name, cubic_house_id) AS (SELECT cubic_code, cubic_ou_code, cubic_name, cubic_house_id FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name in(  'Ответвитель магистральный','Распределительный стояк', 'Магистральный узел','Домовой узел') UNION SELECT p.cubic_code, p.cubic_ou_code, p.cubic_name, pr.cubic_house_id FROM included_parts pr, ".$selectedCity.".".$selectedCity."_ctv_topology p WHERE p.cubic_code = pr.cubic_ou_code ) SELECT  distinct on(i_p.cubic_house_id )  i_p.cubic_name, i_p.cubic_code, i_p.cubic_ou_code, i_p.cubic_house_id as houses, case when city.cubic_cnt_docsis is null then 0 else city.cubic_cnt_docsis::int8 end as cubic_cnt_docsis, case when city.cubic_cnt is null then 0 else city.cubic_cnt::int8 end as cubic_cnt, case when city.cubic_cnt_atv is null then 0 else city.cubic_cnt_atv::int8 end as cubic_cnt_atv, case when city.cubic_cnt_ktv is null then 0 else city.cubic_cnt_ktv::int8 end as cubic_cnt_ktv, case when city.cubic_cnt_vbb is null then 0 else city.cubic_cnt_vbb::int8 end as cubic_cnt_vbb, case when city.cubic_cnt_active_contr is null then 0 else city.cubic_cnt_active_contr::int8 end as cubic_cnt_active_contr, case when city.cubic_cnt_eth is null then 0 else city.cubic_cnt_eth::int8 end as cubic_cnt_eth, i_p.cubic_house_id as house_count, city.building_geom FROM included_parts i_p right join ".$selectedCity.".".$selectedCity."_buildings city on(i_p.cubic_house_id = city.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_ctv_topology equipment on(equipment.cubic_code = i_p.cubic_code) where i_p.cubic_name in ('Порт ОК') );  create temp table nod as select cubic_code, cubic_name, cubic_coment from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_name = 'Оптический узел'; create temp table tmp_fixed as select tmp.cubic_name, tmp.cubic_code, tmp.cubic_ou_code, array_agg(tmp.houses) as houses, sum(tmp.cubic_cnt_docsis::int8) as cubic_cnt_docsis, sum(tmp.cubic_cnt::int8) as cubic_cnt, sum(tmp.cubic_cnt_ktv::int8) as cubic_cnt_ktv, sum(tmp.cubic_cnt_atv::int8) as cubic_cnt_atv, sum(tmp.cubic_cnt_active_contr::int8) as cubic_cnt_active_contr, sum(tmp.cubic_cnt_vbb::int8) as cubic_cnt_vbb, sum(tmp.cubic_cnt_eth::int8) as cubic_cnt_eth, ST_ConvexHull(ST_union(ST_makevalid(tmp.building_geom))) as beauty_geom, nod.cubic_coment from tmp  right join nod on(tmp.cubic_ou_code = nod.cubic_code) group by tmp.cubic_name, tmp.cubic_code, tmp.cubic_ou_code, nod.cubic_coment; DELETE FROM ".$selectedCity.".".$selectedCity."_rp_coverage; INSERT INTO ".$selectedCity.".".$selectedCity."_rp_coverage(cubic_code, cubic_cnt_docsis, cubic_cnt,	cubic_cnt_ktv,	cubic_cnt_atv,	cubic_cnt_active_contr,	cubic_cnt_vbb,	cubic_cnt_eth, beauty_geom, cubic_coment) SELECT cubic_code, cubic_cnt_docsis,	cubic_cnt,	cubic_cnt_ktv,	cubic_cnt_atv,	cubic_cnt_active_contr,	cubic_cnt_vbb,	cubic_cnt_eth, beauty_geom, cubic_coment from tmp_fixed;";	
$postgresCoverage -> dbConnect($query, false, true);
echo $query;
    //to Coverage
    $query = "CREATE TEMP TABLE temp AS SELECT cubic_subdep,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_subdep is not null group by cubic_subdep order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_to_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_to_coverage(cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_subdep, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresCoverage -> dbConnect($query, $queryArrayKeys, true);
    //uso Coverage  
    $query = "CREATE TEMP TABLE temp AS SELECT cubic_uso,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_uso is not null group by cubic_uso order by cubic_cnt desc; DELETE FROM ".$selectedCity.".".$selectedCity."_uso_coverage;INSERT INTO ".$selectedCity.".".$selectedCity."_uso_coverage(cubic_uso, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom) SELECT cubic_uso, cubic_cnt, cubic_cnt_docsis, cubic_cnt_ktv, cubic_cnt_atv, cubic_cnt_vbb, cubic_cnt_eth, cubic_cnt_active_contr, beauty_geom from temp;";
    $queryArrayKeys = false;
    echo $query.'<hr>';
    $postgresCoverage -> dbConnect($query, $queryArrayKeys, true);
    ///////////////////////
    ///switches tables update
    $queryModificator = array(
    'var' => 'idt serial, ID character varying(100),MAC_ADDRESS character varying(100),IP_ADDRESS character varying(100),SERIAL_NUMBER character varying(100),HOSTNAME character varying(100),DEV_FULL_NAME  text,VENDOR_MODEL character varying(100),SW_MODEL character varying(100),SW_ROLE character varying(100),HOUSE_ID character varying(100),DOORWAY character varying(100),LOCATION character varying(100),FLOOR character varying(100),SW_MON_TYPE character varying(100),SW_INV_STATE character varying(100),VLAN character varying(100),DATE_CREATE character varying(100),DATE_CHANGE character varying(100),IS_CONTROL character varying(100),IS_OPT82 character varying(100),PARENT_ID character varying(100), PARENT_MAC  character varying(100),PARENT_PORT character varying(100),CHILD_ID character varying(100),CHILD_MAC character varying(100),CHILD_PORT character varying(100),PORT_NUMBER character varying(100),PORT_STATE character varying(100),CONTRACT_CNT character varying(100),CONTRACT_ACTIVE_CNT character varying(100),GUEST_VLAN character varying(100),CITY_ID character varying(100),CITY character varying(100),CITY_CODE character varying(100),REPORT_DATE character varying(100)', 
    'val'=> 'ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER, HOSTNAME, DEV_FULL_NAME, VENDOR_MODEL, SW_MODEL, SW_ROLE, HOUSE_ID, DOORWAY, LOCATION, FLOOR, SW_MON_TYPE, SW_INV_STATE,VLAN, DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82, PARENT_ID, PARENT_MAC, PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT, PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN,CITY_ID, CITY, CITY_CODE, REPORT_DATE',
    'delimiter' =>"','" , 
    'encoding'=>"'utf-8'");
    $tableType = "_switches";
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
    $postgresSwitches = new dbConnSetClass;
    $query ="CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_switch_id AS SELECT DISTINCT ID FROM temp WHERE ID IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id NOT IN(SELECT ID FROM alien_cubic_switch_id) ; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_mac_address = temp.MAC_ADDRESS,cubic_ip_address = temp.IP_ADDRESS,cubic_hostname = temp.HOSTNAME,cubic_switch_model = temp.SW_MODEL,cubic_switch_role = temp.SW_ROLE,cubic_house_id = temp.HOUSE_ID,cubic_house_entrance_num = temp.DOORWAY,cubic_monitoring_method = temp.SW_MON_TYPE,cubic_inventary_state = temp.SW_INV_STATE,cubic_vlan = temp.VLAN, cubic_parent_down_port = temp.PARENT_PORT,cubic_parent_mac_address = temp.PARENT_MAC,cubic_up_port = temp.PORT_NUMBER,cubic_rgu = temp.CONTRACT_CNT FROM  temp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_switch_id = temp.ID;UPDATE ". $selectedCity.".".$selectedCity."_switches SET switches_geom = null  where cubic_switch_id in(select switches.cubic_switch_id from ". $selectedCity.".".$selectedCity."_switches switches  right join ". $selectedCity.".".$selectedCity."_buildings buildings on (switches.cubic_house_id= buildings.cubic_house_id) where ST_Contains(st_buffer(buildings.building_geom,1), switches.switches_geom) = false)  OR cubic_switch_id IN(select switches.cubic_switch_id from ".$selectedCity.".".$selectedCity."_switches switches right join ".$selectedCity.".".$selectedCity."_buildings buildings on(switches.cubic_house_id=buildings.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_entrances entrances on (switches.cubic_house_id||'p'||switches.cubic_house_entrance_num = entrances.cubic_entrance_id) where switches.cubic_switch_id is not null and st_equals(switches.switches_geom,entrances.geom) = false);";
    $queryArrayKeys = false;
    echo $query.'<hr>';
	//$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_switches.switches_geom IS NULL AND ".$selectedCity.".".$selectedCity."_switches.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
	$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = CASE WHEN summ.geom IS NOT NULL THEN summ.geom WHEN summ.geom IS NULL THEN summ.building_geom_thirdpoint  END FROM (select switches.cubic_switch_id, switches.switches_geom, switches.cubic_house_id, switches.cubic_house_entrance_num, buildings.building_geom_thirdpoint, entrances.cubic_entrance_id, entrances.geom, st_equals(switches.switches_geom,entrances.geom)  from ".$selectedCity.".".$selectedCity."_switches switches right join ".$selectedCity.".".$selectedCity."_buildings buildings on(switches.cubic_house_id=buildings.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_entrances entrances on (switches.cubic_house_id||'p'||switches.cubic_house_entrance_num = entrances.cubic_entrance_id) where switches.cubic_switch_id is not null) summ Where summ.cubic_switch_id = ".$selectedCity.".".$selectedCity."_switches.cubic_switch_id ;";
	echo $query.'<hr>';
	$retuenedArray = $postgresSwitches -> dbConnect($query, false, true);
	$query ="CREATE TEMP TABLE tmp AS SELECT cubic_switch_id, cubic_switch_role, cubic_switch_model,  switches_geom FROM ".$selectedCity.".".$selectedCity."_switches where cubic_switch_id IN (SELECT distinct cubic_switch_id FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_switches SET parent_switches_geom = tmp.switches_geom, cubic_parent_switch_role = tmp.cubic_switch_role, cubic_parent_switch_model = tmp.cubic_switch_model FROM tmp WHERE ".$selectedCity."_switches.cubic_parent_switch_id = tmp.cubic_switch_id; DROP TABLE tmp;   UPDATE ".$selectedCity.".".$selectedCity."_switches SET topology_line_geom = ST_MakeLine(parent_switches_geom, switches_geom) WHERE ".$selectedCity."_switches.parent_switches_geom IS NOT null AND ".$selectedCity."_switches.switches_geom IS NOT NULL;";
	echo $query.'<hr>';
	$postgresSwitches -> dbConnect($query, false, true);
	$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_city = ".$selectedCity."_buildings.cubic_city, cubic_district = ".$selectedCity."_buildings.cubic_distr_new,
	 cubic_street = ".$selectedCity."_buildings.cubic_street, cubic_house_num = ".$selectedCity."_buildings.cubic_house FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity."_switches.cubic_house_id = ".$selectedCity."_buildings.cubic_house_id AND ".$selectedCity."_switches.cubic_house_id IS NOT NULL AND ".$selectedCity."_buildings.cubic_house_id IS NOT NULL;";
	echo $query.'<hr>';
	$postgresSwitches -> dbConnect($query, false, true);
	//------------------
	$query ="CREATE TEMP TABLE tmp_agr (cubic_switch_id varchar(100), cubic_parent_switch_id varchar(100), cubic_switch_role varchar(100), cubic_switch_agr_id varchar(100), level integer); INSERT INTO tmp_agr WITH RECURSIVE tmp_agr ( cubic_switch_id, cubic_parent_switch_id, cubic_switch_role, cubic_parent_switch_agr_id , LEVEL ) AS (SELECT T1.cubic_switch_id , T1.cubic_parent_switch_id , T1.cubic_switch_role , T1.cubic_parent_switch_id as cubic_parent_switch_agr_id , 1 FROM ".$selectedCity.".".$selectedCity."_switches T1 WHERE T1.cubic_parent_switch_role = 'agr' union select T2.cubic_switch_id, T2.cubic_parent_switch_id, T2.cubic_switch_role,tmp_agr.cubic_parent_switch_agr_id ,LEVEL + 1 FROM ".$selectedCity.".".$selectedCity."_switches T2 INNER JOIN tmp_agr ON( tmp_agr.cubic_switch_id = T2.cubic_parent_switch_id) ) select * from tmp_agr  ORDER BY cubic_parent_switch_agr_id; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_switch_agr_id = tmp_agr.cubic_switch_agr_id FROM tmp_agr WHERE ".$selectedCity."_switches.cubic_switch_id = tmp_agr.cubic_switch_id; UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_switch_agr_id = null WHERE ".$selectedCity."_switches.cubic_switch_id not in (select distinct cubic_switch_id from tmp_agr where cubic_switch_id is not null);";

	echo $query.'<hr>';
	$postgresSwitches -> dbConnect($query, false, true);
	///cable channel pits tables update
	$postgresCableChannels = new dbConnSetClass;

	$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET pit_district =".$selectedCity."_coverage.notes FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null; update ".$selectedCity.".".$selectedCity."_cable_channel_pits set archive_link = 'http://10.112.129.170/qgis-ck/tmp/archive/".$selectedCity."/topology/pits/'||pit_id||'/';";
	echo $query.'<hr>';
	$postgresCableChannels -> dbConnect($query, false, true);
	//---------------------
	//add points from line
	
	$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1_geom = ST_StartPoint(channel_geom), pit_2_geom = ST_EndPoint(channel_geom) WHERE pit_1_geom IS NULL AND pit_2_geom IS NULL; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1 = ".$selectedCity."_cable_channel_pits.pit_number, pit_id_1 = ".$selectedCity."_cable_channel_pits.pit_id, she_n_1 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_1 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_1_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ST_Equals(pit_1_geom, ".$selectedCity."_cable_channel_pits.geom)  AND ".$selectedCity."_cable_channels_channels.pit_1_geom IS NOT NULL AND ".$selectedCity."_cable_channel_pits.geom IS NOT NULL AND ".$selectedCity."_cable_channels_channels.pit_id_1 IS NULL; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2 = ".$selectedCity."_cable_channel_pits.pit_number , pit_id_2 = ".$selectedCity."_cable_channel_pits.pit_id, she_n_2 = ".$selectedCity."_cable_channel_pits.pit_district, microdistrict_2 = ".$selectedCity."_cable_channel_pits.microdistrict, pit_2_geom = ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ST_Equals(pit_2_geom, ".$selectedCity."_cable_channel_pits.geom) AND ".$selectedCity."_cable_channels_channels.pit_2_geom IS NOT NULL AND ".$selectedCity."_cable_channel_pits.geom IS NOT NULL  AND ".$selectedCity."_cable_channels_channels.pit_id_2 IS NULL;";
	echo $query.'<hr>';
	$postgresCableChannels -> dbConnect($query, false, true);
	//this should be at the end

	$query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET she_1 ='ПГС№'||".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_1_geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null; UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET she_2 ='ПГС№'||".$selectedCity."_coverage.coverage_zone FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_2_geom) and ".$selectedCity.".".$selectedCity."_coverage.geom_area is not null;";
	echo $query.'<hr>';
	$postgresCableChannels -> dbConnect($query, false, true);
	//add json data-----------------------
	$query = "create temp table t1 as select distinct on(pit_id) geom, pit_id, archive_link from ".$selectedCity.".".$selectedCity."_cable_channel_pits; create temp table t2 as select pit_1_geom, pit_2_geom, channel_geom, pit_id_1, pit_id_2 from ".$selectedCity.".".$selectedCity."_cable_channels_channels; create temp table tmp as (select tj1.pit_id as id, tj1.archive_link, tj1.parents, tj2.children from (select t1.pit_id, t1.archive_link, array_agg(t2.pit_id_2) as parents from t1 right join t2 on t1.pit_id = t2.pit_id_1 where pit_id is not null group by t1.pit_id,t1.archive_link) tj1 join (select t1.pit_id, array_agg(t2.pit_id_1) as children from t1 left join t2 on t1.pit_id = t2.pit_id_2 where pit_id is not null group by t1.pit_id) tj2 on tj1.pit_id = tj2.pit_id) union (select tj1.pit_id as id, tj1.archive_link, tj1.parents , tj2.children from (select t1.pit_id, t1.archive_link, array_agg(t2.pit_id_2) as parents from t1 left join t2 on t1.pit_id = t2.pit_id_1 where pit_id is not null group by t1.pit_id, t1.archive_link) tj1 join (select t1.pit_id, array_agg(t2.pit_id_1) as children from t1 right join t2 on t1.pit_id = t2.pit_id_2 where pit_id is not null group by t1.pit_id) tj2 on tj1.pit_id = tj2.pit_id); create temp table tmp_fixed as (select * from tmp where parents !='{null}') union (select id, archive_link, children as parents, parents as children  from tmp where parents = '{null}'); update tmp_fixed set parents = case when parents = '{NULL}' then NULL else parents end , children = case when children = '{NULL}' then NULL else children end; update ".$selectedCity.".".$selectedCity."_cable_channel_pits set json_data = row_to_json(tmp_fixed) from tmp_fixed where tmp_fixed.id = ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_id;";
	echo $query.'<hr>';
	$postgresCableChannels -> dbConnect($query, false, true);
	//------add file names to json_data column in _cable_channel_pits table
	$query = "SELECT DISTINCT pit_id, json_data FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE pit_id IS NOT NULL;";
	$queryArrayKeys = array('pit_id', 'json_data');
	echo $query;
	$retuenedArray = $postgresCableChannels-> dbConnect($query, $queryArrayKeys, true);
	$sumObjectsArray = $retuenedArray;
	$separator = $file_names_values = '';
	foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
	  //print_r($objectArray);
	  $description = array(
	      'cubic_name' => 'pits',
	      'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['pit_id'],
	      'rootDir' => '/var/www/QGIS-Web-Client-master/site/tmp/archive/',
	      'subDirType' => '/topology/',
	      'json_data' => json_decode($sumObjectsArray[$sumObjectsArrayKey]['json_data'])
	    );
	  if($description['cubic_name'] !==null){
	    topologyDirCreate($description, $selectedCity);
	    if($description['json_data'] !== null){
	      if(dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/') ){ $file_names = dir_files_names($description['rootDir'].$selectedCity.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'].'/');
	          $description['json_data']->file_names = $file_names;
	          $file_names_values .= $separator."('".$description['cubic_code']."','".json_encode($description['json_data'] )."')";  
	          $separator =",";
	      }
	    }
	  }
	}
	if ($file_names_values != ''){
	  $query = "CREATE TEMP TABLE tmp(pit_id varchar(100), json_data text); INSERT INTO tmp VALUES ".$file_names_values.";UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET json_data = tmp.json_data FROM tmp WHERE tmp.pit_id::int8 = ".$selectedCity."_cable_channel_pits.pit_id ;";
	  echo $query;
	  $postgresCableChannels -> dbConnect($query, false, true);
	}

//-------------------------------------

}
?>