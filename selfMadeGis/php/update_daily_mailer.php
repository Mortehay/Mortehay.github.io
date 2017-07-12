<?php
include('classFunctionStorage.php');
ini_set('display_errors', 1);
date_default_timezone_set("Europe/Kiev");


$postgres = new dbConnSetClass;
$query = "SELECT city_eng FROM public.links where links IS NOT NULL;";
$queryArrayKeys = array('city_eng');
//echo $query.'<hr>';
$postgresCitiesArray = $postgres -> dbConnect($query, $queryArrayKeys, true);
//header('Content-type: text/plain; charset=utf-8');
$city_array = $postgresCitiesArray;
$today = getdate();
$today = $today['hours'].'-'.$today['minutes'].'__'.$today['mday'].'-'.$today['month'].'-'.$today['year'];
//echo $today;

$dirPathMail = '/var/www/QGIS-Web-Client-master/site/csv/cubic/_ctv_topology_daily_updates_mail/';
make_dir_empty($dirPathMail);
//print_r($city_array);
foreach($city_array as $city){
	$selectedCity = $city['city_eng'];
	$tableType = "_ctv_topology";
	$tableTypeSufix = 'changes';
  	$fileExtention =".csv";
	$linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$tableType."/".$selectedCity.$tableType.$fileExtention."'";
	$dirPath = '/var/www/QGIS-Web-Client-master/site/csv/cubic/_ctv_topology_daily_updates/'.$city['city_eng'].'/';
	//------------------------------
	restriction_change($dirPath);
	restriction_change($dirPathMail);
	//------------------------------
	$queryModificator = array(
		'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)', 
		'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE', 
		'delimiter' =>"','" , 
		'encoding'=>"'utf-8'");
	$csv_out = array(
    'delimiter' =>"';'", 
    'encoding'=>"'windows-1251'");

	$query = "CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ;  create temp table csvTemp as (SELECT CITY,STREET, HOUSE, FLAT, CODE, NAME ,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, 'missing' as state FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ) UNION ALL (with data(CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)  as (select CITY,STREET, HOUSE, FLAT, CODE, NAME, PGS_ADDR, OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID  from temp) select d.CITY, d.STREET, d.HOUSE, d.FLAT, d.CODE, d.NAME, d.PGS_ADDR, d.OU_OP_ADDR, d.OU_CODE, d.DATE_REG, d.COMENT, d.UNAME, d.NET_TYPE, d.HOUSE_ID, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_ctv_topology u where u.cubic_code = d.CODE and u.cubic_house_id = d.HOUSE_ID) and CODE IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ); update csvTemp set house = replace(house, '/', '\'); select copy_for_testuser_v2('csvTemp','TO','".$dirPath.$selectedCity.$tableType.'_'.$tableTypeSufix.'_'.$today.$fileExtention."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1); select copy_for_testuser_v2('csvTemp','TO','".$dirPathMail.$selectedCity.$tableType.'_'.$tableTypeSufix.'_'.$today.$fileExtention."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1);";
	echo $query.'<hr>';
	$queryArrayKeys = false;
	$topology_changes = $postgres -> dbConnect($query, $queryArrayKeys, true);
}

//echo '<hr>'.$dirPathMail.'<hr>';
sleep(12*count($city_array));
if (is_dir_empty($dirPathMail) ){
	echo '<hr>it is empty<hr>';
} else {
	echo '<hr>'.$dirPathMail.'<hr>';
	///---------------------------------------
	$mail_filename = $tableType.'_'.$tableTypeSufix.'_'.$today.'.zip';
	
    $mail_file_path = $dirPathMail.$mail_filename;
	zip_folder($dirPathMail,$mail_file_path);
    $mail_title = ' зміни в  топології КТВ за  ' . $today;
    $mail_text = $today;
    $query = "SELECT DISTINCT e_mail from public.access where restriction = 'admin';";
    //echo $query.'<hr>';
    $queryArrayKeys = array('e_mail');
    $mail_to = $postgres -> dbConnect($query, $queryArrayKeys, true);
    foreach( $mail_to as $mail){
    	 mail_attachment( $mail['e_mail'], '', $mail_title, $mail_text, $mail_file_path, $mail_filename);
    }
}

?>