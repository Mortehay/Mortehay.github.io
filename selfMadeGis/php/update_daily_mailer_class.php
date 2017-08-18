<?php
include('classFunctionStorage.php');
ini_set('display_errors', 1);

$params = array(
	'dirPathMail' => '/var/www/QGIS-Web-Client-master/site/csv/cubic/daily_updates_mail/',
	'tableType' => '_ctv_topology',
	'tableTypeSufix' => 'changes',
  	'fileExtention' => '.csv',
  	'mail_to_query_type' => 'ctv_tolology_query'
	);
$queryModificator = array(
		'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)', 
		'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE', 
		'delimiter' =>"','" , 
		'encoding'=>"'utf-8'");
$csv_out = array(
	'delimiter' =>"';'", 
	'encoding'=>"'windows-1251'");

$newMail = new mailSender;
$newMail->setProp('params',$params);
$newMail->setProp('csv_out',$csv_out);
$newMail->setProp('queryModificator',$queryModificator);
//echo 'im here'.'<hr>';
//print_r($newMail->getProp('params') );
//echo 'im here'.'<hr>';
//print_r($newMail -> mail_cities_users());
$newMail -> mail_cities_users();
//echo 'im here'.'<hr>';
//print_r($newMail -> test());
//echo '<hr>'.$newMail -> dateReturn().'<hr>';

?>