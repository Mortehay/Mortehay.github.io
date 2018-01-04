
<?php
ini_set('display_errors', 1);
//include('restriction.php');
include('cityVocabulary.php');
include('classFunctionStorage.php');
$postgres = new dbConnSetClass;
if($_SERVER['SERVER_ADDR']) {
	$server_address = $_SERVER['SERVER_ADDR']; 
} else {
	$server_address = $postgres->getProp('outerIp');
}
//$server_address = $_SERVER['SERVER_ADDR']; 
echo  $server_address;

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
 	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_code AS SELECT DISTINCT CODE FROM temp WHERE CODE IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code NOT IN(SELECT CODE FROM alien_cubic_code); create temp table reused_cubic_code as (with data(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name,cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id) as (select cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name,cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id from ".$selectedCity.".".$selectedCity."_ctv_topology) select d.cubic_city, d.cubic_street, d.cubic_house, d.cubic_flat, d.cubic_code, d.cubic_name,d.cubic_pgs_addr, d.cubic_ou_op_addr, d.cubic_ou_code, d.cubic_date_reg, d.cubic_coment, d.cubic_uname, d.cubic_net_type, d.cubic_house_id, 'present state' as state from data d where not exists (select 1 from temp u where d.cubic_code = u.CODE and d.cubic_house_id = u.HOUSE_ID and d.cubic_name = u.NAME) and cubic_code IN(SELECT CODE FROM temp WHERE CODE IS NOT NULL) ); 	DELETE FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT cubic_code FROM reused_cubic_code); INSERT INTO " . $selectedCity.".".$selectedCity."_ctv_topology(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name, cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id) SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = CASE WHEN temp.COMENT LIKE '% № %' THEN replace(temp.COMENT, ' № ', '№') ELSE temp.COMENT END, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = temp.HOUSE_ID and ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = temp.NAME; (SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, 'missing' as state FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ) UNION ALL (with data(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG, COMENT,UNAME,NET_TYPE,HOUSE_ID)  as (select CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID  from temp) select d.CITY, d.STREET, d.HOUSE, d.FLAT, d.CODE, d.NAME, d.PGS_ADDR, d.OU_OP_ADDR, d.OU_CODE, d.DATE_REG, d.COMENT, d.UNAME, d.NET_TYPE, d.HOUSE_ID, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_ctv_topology u where u.cubic_code = d.CODE and u.cubic_house_id = d.HOUSE_ID  and u.cubic_name = d.NAME) and CODE IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ); ";
    echo $query.'<hr>';
    $postgresCtvTopology -> dbConnect($query, false, true);
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = CASE "." WHEN cubic_name LIKE '%Магистральный распределительный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Магістральний оптичний вузол%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Оптический узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Оптичний приймач%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint"." WHEN cubic_name LIKE '%Передатчик оптический%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Порт ОК%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Домовой узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Ответвитель магистральный%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." WHEN cubic_name LIKE '%Распределительный стояк%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Магистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint"." WHEN cubic_name LIKE '%Субмагистральный узел%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint"." WHEN cubic_name LIKE '%Кросс-муфта%' THEN ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint"." END FROM  ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_code, equipment_geom, cubic_name, cubic_street, cubic_house FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom,  cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;  UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL;";
	$postgresCtvTopology -> dbConnect($query, false, true);
	echo $query.'<hr>';
	$query = "CREATE TEMP TABLE tmp AS SELECT cubic_name, cubic_street, cubic_house, cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IN (SELECT DISTINCT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IS NOT NULL) ;UPDATE  ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_ou_name = tmp.cubic_name, cubic_ou_street = tmp.cubic_street, cubic_ou_house = tmp.cubic_house FROM tmp WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;";
	$postgresCtvTopology -> dbConnect($query, false, true); 	
	echo $query.'<hr>';

	$link_left_part = 'https://'.$server_address.'/qgis-ck/tmp/archive/';
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

    ///coverage update
    $postgresCoverage = new dbConnSetClass;
    // zero and emptynes detection :)
    $query = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET cubic_cnt = case when cubic_cnt ~ '^[0-9\.]+$' then cubic_cnt else '0' end, cubic_cnt_docsis = case when cubic_cnt_docsis ~ '^[0-9\.]+$' then cubic_cnt_docsis else '0' end , cubic_cnt_ktv = case when cubic_cnt_ktv ~ '^[0-9\.]+$' then cubic_cnt_ktv else '0' end , cubic_cnt_atv = case when cubic_cnt_atv ~ '^[0-9\.]+$' then cubic_cnt_atv else '0' end, cubic_cnt_vbb = case when cubic_cnt_vbb ~ '^[0-9\.]+$' then cubic_cnt_vbb else '0' end, cubic_cnt_eth = case when cubic_cnt_eth ~ '^[0-9\.]+$' then cubic_cnt_eth else '0' end , cubic_cnt_active_contr = case when cubic_cnt_active_contr ~ '^[0-9\.]+$' then cubic_cnt_active_contr else '0' end WHERE cubic_house_id IS NOT NULL;";
    $queryArrayKeys = false;
    //echo $query.'<hr>';
    $postgresCoverage -> dbConnect($query, $queryArrayKeys, true);
    //she coverage update/
    $query = "CREATE TEMP TABLE temp AS SELECT cubic_hpname,  array_agg(cubic_house_id) as  agg_cubic_house_id, st_astext(ST_ConvexHull(ST_union(ST_makevalid(building_geom)))) as beauty_geom, sum(cubic_cnt::integer) as cubic_cnt, sum(cubic_cnt_docsis::integer) as cubic_cnt_docsis, sum(cubic_cnt_ktv::integer) as cubic_cnt_ktv, sum(cubic_cnt_atv::integer) as cubic_cnt_atv, sum(cubic_cnt_vbb::integer) as cubic_cnt_vbb, sum(cubic_cnt_eth::integer) as cubic_cnt_eth, sum(cubic_cnt_active_contr::integer) as cubic_cnt_active_contr from (select distinct on (cubic_house_id) * from ".$selectedCity.".".$selectedCity."_buildings ) as city where cubic_hpadress is not null group by cubic_hpname order by cubic_cnt desc; UPDATE ".$selectedCity.".".$selectedCity."_coverage SET cubic_cnt = temp.cubic_cnt, cubic_cnt_docsis = temp.cubic_cnt_docsis , cubic_cnt_ktv = temp.cubic_cnt_ktv , cubic_cnt_atv = temp.cubic_cnt_atv , cubic_cnt_vbb = temp.cubic_cnt_vbb, cubic_cnt_eth = temp.cubic_cnt_eth , cubic_cnt_active_contr = temp.cubic_cnt_active_contr FROM temp WHERE ".$selectedCity."_coverage.coverage_zone = temp.cubic_hpname and geom_area is not null;";
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
    $query = "create temp table tmp as( WITH RECURSIVE included_parts(cubic_code, cubic_ou_code, cubic_name, cubic_house_id) AS (SELECT cubic_code, cubic_ou_code, cubic_name, cubic_house_id FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name in(  'Ответвитель магистральный','Распределительный стояк', 'Магистральный узел','Домовой узел') UNION SELECT p.cubic_code, p.cubic_ou_code, p.cubic_name, pr.cubic_house_id FROM included_parts pr, ".$selectedCity.".".$selectedCity."_ctv_topology p WHERE p.cubic_code = pr.cubic_ou_code ) SELECT  distinct on(i_p.cubic_house_id )  i_p.cubic_name, i_p.cubic_code, i_p.cubic_ou_code, i_p.cubic_house_id as houses, city.cubic_cnt_docsis as cubic_cnt_docsis, city.cubic_cnt as cubic_cnt, city.cubic_cnt_atv as cubic_cnt_atv, city.cubic_cnt_ktv as cubic_cnt_ktv, city.cubic_cnt_vbb as cubic_cnt_vbb, city.cubic_cnt_active_contr as cubic_cnt_active_contr, city.cubic_cnt_eth as cubic_cnt_eth , i_p.cubic_house_id as house_count, city.building_geom FROM included_parts i_p right join ".$selectedCity.".".$selectedCity."_buildings city on(i_p.cubic_house_id = city.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_ctv_topology equipment on(equipment.cubic_code = i_p.cubic_code) where i_p.cubic_name in ('Порт ОК') );  create temp table nod as select cubic_street, cubic_house, cubic_pgs_addr, cubic_code, cubic_name, cubic_coment from ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_name = 'Оптический узел'; create temp table tmp_fixed as select tmp.cubic_name, tmp.cubic_code, tmp.cubic_ou_code, array_agg(tmp.houses) as houses, sum(tmp.cubic_cnt_docsis::int8) as cubic_cnt_docsis, sum(tmp.cubic_cnt::int8) as cubic_cnt, sum(tmp.cubic_cnt_ktv::int8) as cubic_cnt_ktv, sum(tmp.cubic_cnt_atv::int8) as cubic_cnt_atv, sum(tmp.cubic_cnt_active_contr::int8) as cubic_cnt_active_contr, sum(tmp.cubic_cnt_vbb::int8) as cubic_cnt_vbb, sum(tmp.cubic_cnt_eth::int8) as cubic_cnt_eth, ST_ConvexHull(ST_union(ST_makevalid(tmp.building_geom))) as beauty_geom, nod.cubic_coment, nod.cubic_street, nod.cubic_house, nod.cubic_pgs_addr from tmp  right join nod on(tmp.cubic_ou_code = nod.cubic_code) group by tmp.cubic_name, tmp.cubic_code, tmp.cubic_ou_code, nod.cubic_coment, nod.cubic_street, nod.cubic_house, nod.cubic_pgs_addr;  DELETE FROM ".$selectedCity.".".$selectedCity."_rp_coverage; INSERT INTO ".$selectedCity.".".$selectedCity."_rp_coverage(cubic_code, cubic_cnt_docsis, cubic_cnt,	cubic_cnt_ktv,	cubic_cnt_atv,	cubic_cnt_active_contr,	cubic_cnt_vbb,	cubic_cnt_eth, beauty_geom, cubic_coment, cubic_street, cubic_house, cubic_pgs_addr) SELECT cubic_code, cubic_cnt_docsis,	cubic_cnt,	cubic_cnt_ktv,	cubic_cnt_atv,	cubic_cnt_active_contr,	cubic_cnt_vbb,	cubic_cnt_eth, beauty_geom, cubic_coment, cubic_street, cubic_house, cubic_pgs_addr from tmp_fixed;";	
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
    $query ="CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_switch_id AS SELECT DISTINCT ID FROM temp WHERE ID IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id NOT IN(SELECT ID FROM alien_cubic_switch_id) ; 
	create temp table reused_cubic_switch_id as  (with data(ID,MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,DEV_FULL_NAME,VENDOR_MODEL,SW_MODEL,SW_ROLE,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_MON_TYPE,SW_INV_STATE,VLAN,DATE_CREATE,DATE_CHANGE,IS_CONTROL,IS_OPT82,PARENT_ID,PARENT_MAC,PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT,PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN, CITY_ID, CITY, CITY_CODE, REPORT_DATE) as (select ID,MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,DEV_FULL_NAME,VENDOR_MODEL,SW_MODEL,SW_ROLE,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_MON_TYPE,SW_INV_STATE,VLAN,DATE_CREATE,DATE_CHANGE,IS_CONTROL,IS_OPT82,PARENT_ID,PARENT_MAC,PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT,PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN, CITY_ID, CITY, CITY_CODE, REPORT_DATE from temp) select d.ID, d.MAC_ADDRESS, d.IP_ADDRESS, d.SERIAL_NUMBER, d.HOSTNAME, d.DEV_FULL_NAME, d.VENDOR_MODEL, d.SW_MODEL, d.SW_ROLE, d.HOUSE_ID, d.DOORWAY, d.LOCATION, d.FLOOR, d.SW_MON_TYPE, d.SW_INV_STATE, d.VLAN, d.DATE_CREATE, d.DATE_CHANGE, d.IS_CONTROL, d.IS_OPT82, d.PARENT_ID, d.PARENT_MAC, d.PARENT_PORT, d.CHILD_ID, d.CHILD_MAC, d.CHILD_PORT,d.PORT_NUMBER, d.PORT_STATE, d.CONTRACT_CNT, d.CONTRACT_ACTIVE_CNT, d.GUEST_VLAN, d.CITY_ID, d.CITY, d.CITY_CODE, d.REPORT_DATE, 'reused code' as rcode from data d where not exists (select 1 from chernivtsi.chernivtsi_switches u where u.cubic_switch_id = d.ID and u.cubic_house_id = d.HOUSE_ID) and ID IN(SELECT cubic_switch_id FROM chernivtsi.chernivtsi_switches WHERE cubic_switch_id IS NOT NULL) );
	DELETE FROM ".$selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IN(SELECT id FROM reused_cubic_switch_id) ;
    UPDATE ".$selectedCity.".".$selectedCity."_switches SET cubic_mac_address = temp.MAC_ADDRESS,cubic_ip_address = temp.IP_ADDRESS,cubic_hostname = temp.HOSTNAME,cubic_switch_model = temp.SW_MODEL,cubic_switch_role = temp.SW_ROLE,cubic_house_id = temp.HOUSE_ID,cubic_house_entrance_num = temp.DOORWAY,cubic_monitoring_method = temp.SW_MON_TYPE,cubic_inventary_state = temp.SW_INV_STATE,cubic_vlan = temp.VLAN, cubic_parent_down_port = temp.PARENT_PORT,cubic_parent_mac_address = temp.PARENT_MAC,cubic_up_port = temp.PORT_NUMBER,cubic_rgu = temp.CONTRACT_CNT FROM  temp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_switch_id = temp.ID;UPDATE ". $selectedCity.".".$selectedCity."_switches SET switches_geom = null  where cubic_switch_id in(select switches.cubic_switch_id from ". $selectedCity.".".$selectedCity."_switches switches  right join ". $selectedCity.".".$selectedCity."_buildings buildings on (switches.cubic_house_id= buildings.cubic_house_id) where ST_Contains(st_buffer(buildings.building_geom,1), switches.switches_geom) = false)  OR cubic_switch_id IN(select switches.cubic_switch_id from ".$selectedCity.".".$selectedCity."_switches switches right join ".$selectedCity.".".$selectedCity."_buildings buildings on(switches.cubic_house_id=buildings.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_entrances entrances on (switches.cubic_house_id||'p'||switches.cubic_house_entrance_num = entrances.cubic_entrance_id) where switches.cubic_switch_id is not null and st_equals(switches.switches_geom,entrances.geom) = false);";
    echo $query.'<hr>';
    $postgresSwitches -> dbConnect($query, false, true);
	//$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_switches.switches_geom IS NULL AND ".$selectedCity.".".$selectedCity."_switches.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
	$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET switches_geom = CASE WHEN summ.geom IS NOT NULL THEN summ.geom WHEN summ.geom IS NULL THEN summ.building_geom_thirdpoint  END FROM (select switches.cubic_switch_id, switches.switches_geom, switches.cubic_house_id, switches.cubic_house_entrance_num, buildings.building_geom_thirdpoint, entrances.cubic_entrance_id, entrances.geom, st_equals(switches.switches_geom,entrances.geom)  from ".$selectedCity.".".$selectedCity."_switches switches right join ".$selectedCity.".".$selectedCity."_buildings buildings on(switches.cubic_house_id=buildings.cubic_house_id) right join ".$selectedCity.".".$selectedCity."_entrances entrances on (switches.cubic_house_id||'p'||switches.cubic_house_entrance_num = entrances.cubic_entrance_id) where switches.cubic_switch_id is not null) summ Where summ.cubic_switch_id = ".$selectedCity.".".$selectedCity."_switches.cubic_switch_id ;";
	echo $query.'<hr>';
	$postgresSwitches -> dbConnect($query, false, true);
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
	$query ="UPDATE ".$selectedCity.".".$selectedCity."_switches SET work_link ='http://work.volia.net/w2/eth/switch_info/act.current.php?ip='||cubic_ip_address||'&company=".$cities[cityVocabulary($cities, 1, $selectedCity)][0]."' WHERE cubic_ip_address IS NOT NULL;";

	echo $query.'<hr>';
	$postgresSwitches -> dbConnect($query, false, true);

	//user equipment edition ;)
		//--user equipment update (hybrids/modems)-----------------------------------------------------------------
	/////modems tables update
	$tableType = "_modems";
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
	
	$queryModificator = array(
      'var' => 'id serial, CONTRACT_ID varchar(100), CONTRACT_CODE  varchar(100), CONTRACT_TYPE varchar(100), DISTRICT  varchar(100), STREET  varchar(100),HOUSE  varchar(100), SECTOR varchar(100), FLAT  varchar(100), TARIFF_TYPE  varchar(100), MAC  varchar(100), STATUS varchar(100), STATUS_DATE  varchar(100), CITY  varchar(100), NETTYPE  varchar(100), STATUS_AO  varchar(100), HOUSE_ID  varchar(100), SERVICE  varchar(100), NAME  varchar(100), SERIAL varchar(100), DATE_FROM varchar(100), EQUIPMENT_ID  varchar(100), DATE_CHANGE  varchar(100), TECHNOLOGY_NAME  varchar(100), TYPE_NAME  varchar(100), REPORT_DATE  varchar(100)', 
      'val'=> 'CONTRACT_ID,CONTRACT_CODE,CONTRACT_TYPE,DISTRICT,STREET,HOUSE,SECTOR,FLAT,TARIFF_TYPE,MAC,STATUS,STATUS_DATE,CITY,NETTYPE,STATUS_AO,HOUSE_ID,SERVICE,NAME,SERIAL,DATE_FROM,EQUIPMENT_ID,DATE_CHANGE,TECHNOLOGY_NAME,TYPE_NAME,REPORT_DATE', 
      'delimiter' =>"','" , 
      'encoding'=>"'utf-8'");
 	$postgresUserEquipmentTopology = new dbConnSetClass;
 	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_code AS SELECT DISTINCT SERIAL FROM temp WHERE SERIAL IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_modems WHERE serial NOT IN(SELECT SERIAL FROM alien_cubic_code); 	INSERT INTO " . $selectedCity.".".$selectedCity."_modems(".$queryModificator['val'].") SELECT ".$queryModificator['val']." FROM temp WHERE serial NOT IN(SELECT serial FROM ". $selectedCity.".".$selectedCity."_modems WHERE serial IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_modems SET contract_id = temp.CONTRACT_ID,contract_code = temp.CONTRACT_CODE,contract_type = temp.CONTRACT_TYPE,district = temp.DISTRICT,street = temp.STREET,house = temp.HOUSE,sector = temp.SECTOR,flat = temp.FLAT,tariff_type = temp.TARIFF_TYPE,mac = temp.MAC,status = temp.STATUS,status_date = temp.STATUS_DATE,city = temp.CITY,nettype = temp.NETTYPE,status_ao = temp.STATUS_AO,house_id = temp.HOUSE_ID,service = temp.SERVICE,name = temp.NAME,serial = temp.SERIAL,date_from = temp.DATE_FROM,equipment_id = temp.EQUIPMENT_ID,date_change = temp.DATE_CHANGE,technology_name = temp.TECHNOLOGY_NAME,type_name = temp.TYPE_NAME,report_date = temp.REPORT_DATE FROM  temp WHERE ".$selectedCity.".".$selectedCity."_modems.serial = temp.serial and ".$selectedCity.".".$selectedCity."_modems.house_id = temp.HOUSE_ID and ".$selectedCity.".".$selectedCity."_modems.name = temp.NAME; ";
    echo $query.'<hr>';
    $postgresUserEquipmentTopology -> dbConnect($query, false, true);

    $query = "UPDATE ". $selectedCity.".".$selectedCity."_modems SET equipment_geom = ".$selectedCity."_entrances.geom FROM ".$selectedCity.".".$selectedCity."_entrances WHERE ".$selectedCity."_entrances.geom IS NOT NULL AND ".$selectedCity."_entrances.cubic_entrance_id = ".$selectedCity."_modems.house_id||'p'||".$selectedCity."_modems.sector;";
    echo $query.'<hr>';
    $postgresUserEquipmentTopology -> dbConnect($query, false, true);
    /////hybrids tables update
	$tableType = "_hybrids";
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
	
	$queryModificator = array(
      'var' => 'id serial, CONTRACT_ID varchar(100), CONTRACT_CODE  varchar(100), CONTRACT_TYPE varchar(100), DISTRICT  varchar(100), STREET  varchar(100),HOUSE  varchar(100), SECTOR varchar(100), FLAT  varchar(100), TARIFF_TYPE  varchar(100), MAC  varchar(100), STATUS varchar(100), STATUS_DATE  varchar(100), CITY  varchar(100), NETTYPE  varchar(100), STATUS_AO  varchar(100), HOUSE_ID  varchar(100), SERVICE  varchar(100), NAME  varchar(100), SERIAL varchar(100), DATE_FROM varchar(100), EQUIPMENT_ID  varchar(100), DATE_CHANGE  varchar(100), TECHNOLOGY_NAME  varchar(100), TYPE_NAME  varchar(100), REPORT_DATE  varchar(100)', 
      'val'=> 'CONTRACT_ID,CONTRACT_CODE,CONTRACT_TYPE,DISTRICT,STREET,HOUSE,SECTOR,FLAT,TARIFF_TYPE,MAC,STATUS,STATUS_DATE,CITY,NETTYPE,STATUS_AO,HOUSE_ID,SERVICE,NAME,SERIAL,DATE_FROM,EQUIPMENT_ID,DATE_CHANGE,TECHNOLOGY_NAME,TYPE_NAME,REPORT_DATE', 
      'delimiter' =>"','" , 
      'encoding'=>"'utf-8'");

 	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ; CREATE TEMP TABLE alien_cubic_code AS SELECT DISTINCT SERIAL FROM temp WHERE SERIAL IS NOT NULL ;DELETE FROM ".$selectedCity.".".$selectedCity."_hybrids WHERE serial NOT IN(SELECT SERIAL FROM alien_cubic_code); INSERT INTO ". $selectedCity.".".$selectedCity."_hybrids(".$queryModificator['val'].") SELECT ".$queryModificator['val']." FROM temp WHERE serial NOT IN(SELECT serial FROM ". $selectedCity.".".$selectedCity."_hybrids WHERE serial IS NOT NULL);UPDATE ".$selectedCity.".".$selectedCity."_hybrids SET contract_id = temp.CONTRACT_ID,contract_code = temp.CONTRACT_CODE,contract_type = temp.CONTRACT_TYPE,district = temp.DISTRICT,street = temp.STREET,house = temp.HOUSE,sector = temp.SECTOR,flat = temp.FLAT,tariff_type = temp.TARIFF_TYPE,mac = temp.MAC,status = temp.STATUS,status_date = temp.STATUS_DATE,city = temp.CITY,nettype = temp.NETTYPE,status_ao = temp.STATUS_AO,house_id = temp.HOUSE_ID,service = temp.SERVICE,name = temp.NAME,serial = replace(replace(temp.SERIAL, ':',''),' ',''),date_from = temp.DATE_FROM,equipment_id = temp.EQUIPMENT_ID,date_change = temp.DATE_CHANGE,technology_name = temp.TECHNOLOGY_NAME,type_name = temp.TYPE_NAME,report_date = temp.REPORT_DATE, link = 'http://sc.volia.net/tvmon/index.php?mac='||replace(replace(temp.SERIAL, ':',''),' ','') FROM  temp WHERE ".$selectedCity.".".$selectedCity."_hybrids.serial = temp.serial and ".$selectedCity.".".$selectedCity."_hybrids.house_id = temp.HOUSE_ID and ".$selectedCity.".".$selectedCity."_hybrids.name = temp.NAME; ";
    echo $query.'<hr>';
    $postgresUserEquipmentTopology -> dbConnect($query, false, true);

    $query = "UPDATE ". $selectedCity.".".$selectedCity."_hybrids SET equipment_geom = ".$selectedCity."_entrances.geom FROM ".$selectedCity.".".$selectedCity."_entrances WHERE ".$selectedCity."_entrances.geom IS NOT NULL AND ".$selectedCity."_entrances.cubic_entrance_id = ".$selectedCity."_hybrids.house_id||'p'||".$selectedCity."_hybrids.sector  and ".$selectedCity."_hybrids.equipment_geom is null; UPDATE ". $selectedCity.".".$selectedCity."_hybrids SET equipment_geom = ".$selectedCity."_buildings.building_geom_fourthpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity."_buildings.building_geom_fourthpoint IS NOT NULL AND ".$selectedCity."_buildings.cubic_house_id = ".$selectedCity."_hybrids.house_id and ".$selectedCity."_hybrids.equipment_geom is null;";
    echo $query.'<hr>';
    $postgresUserEquipmentTopology -> dbConnect($query, false, true);

    //equipment statistics collecting :)
    $postgresEquipmentTopology = new dbConnSetClass;

    $query = "create temp table eq_temp as (select cubic_name as equipment_name, 'ctv' as equipment_state, count(*) as equipment_count, to_char(now(), 'YYYY-MM-DD')::date as update_time from ".$selectedCity.".".$selectedCity."_ctv_topology group by cubic_name) union (select cubic_switch_model as equipment_name, 'switches' as equipment_state, count(*), to_char(now(), 'YYYY-MM-DD')::date as update_time from ".$selectedCity.".".$selectedCity."_switches group by cubic_switch_model, cubic_inventary_state) union (select name as equipment_name, 'hybrid' as equipment_state, count(*) as equipment_name, to_char(now(), 'YYYY-MM-DD')::date as update_time from ".$selectedCity.".".$selectedCity."_hybrids group by name) union (select name as equipment_name, 'modems' as equipment_state, count(*) as equipment_name, to_char(now(), 'YYYY-MM-DD')::date as update_time from ".$selectedCity.".".$selectedCity."_modems group by name); insert into ".$selectedCity.".".$selectedCity."_equipment_statistics(equipment_name, equipment_state, equipment_count, update_time) select  distinct on(equipment_name) * from eq_temp o_t where not exists(select 1 from ".$selectedCity.".".$selectedCity."_equipment_statistics i_n where o_t.equipment_name = i_n.equipment_name and o_t.equipment_state = i_n.equipment_state and o_t.update_time = i_n.update_time); delete from ".$selectedCity.".".$selectedCity."_equipment_statistics where equipment_name not in(select distinct equipment_name from eq_temp where equipment_name is not null);";
    echo $query.'<hr>';
    $postgresEquipmentTopology -> dbConnect($query, false, true);
}
?>