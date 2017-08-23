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
$_params = array(
	'mail_params' => array(
		'dirPathMail' => '/var/www/QGIS-Web-Client-master/site/csv/cubic/daily_updates_mail/',
		),
	'query' => array(
		'_ctv_topology' => array(
			'query_params' => array(
				'tableType' => '_ctv_topology',
				'tableTypeSufix' => 'changes',
			  	'fileExtention' => '.csv',
			  	'mail_to_query_type' => 'ctv_tolology_query'
				),
			'csv_out' => array(
				'delimiter' =>"';'", 
				'encoding'=>"'windows-1251'"
				),
			'queryModificator' => array(
				'var' => 'id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),OU_CODE character varying(100),HOUSE_ID character varying(100), REPORT_DATE character varying(100)', 
				'val'=> 'CITY, STREET, HOUSE ,FLAT ,CODE ,NAME ,PGS_ADDR ,OU_OP_ADDR ,DATE_REG ,COMENT ,UNAME ,NET_TYPE ,OU_CODE ,HOUSE_ID, REPORT_DATE', 
				'delimiter' =>"','" , 
				'encoding'=>"'utf-8'"
				),
		),
		'_switches' => array(
			'query_params' => array(
				'tableType' => '_switches',
				'tableTypeSufix' => 'changes',
			  	'fileExtention' => '.csv',
			  	'mail_to_query_type' => 'switches_query'
				),
			'csv_out' => array(
				'delimiter' =>"';'", 
				'encoding'=>"'windows-1251'"
				),
			'queryModificator' => array(
			    'var' => 'idt serial, ID character varying(100),MAC_ADDRESS character varying(100),IP_ADDRESS character varying(100),SERIAL_NUMBER character varying(100),HOSTNAME character varying(100),DEV_FULL_NAME  text,VENDOR_MODEL character varying(100),SW_MODEL character varying(100),SW_ROLE character varying(100),HOUSE_ID character varying(100),DOORWAY character varying(100),LOCATION character varying(100),FLOOR character varying(100),SW_MON_TYPE character varying(100),SW_INV_STATE character varying(100),VLAN character varying(100),DATE_CREATE character varying(100),DATE_CHANGE character varying(100),IS_CONTROL character varying(100),IS_OPT82 character varying(100),PARENT_ID character varying(100), PARENT_MAC  character varying(100),PARENT_PORT character varying(100),CHILD_ID character varying(100),CHILD_MAC character varying(100),CHILD_PORT character varying(100),PORT_NUMBER character varying(100),PORT_STATE character varying(100),CONTRACT_CNT character varying(100),CONTRACT_ACTIVE_CNT character varying(100),GUEST_VLAN character varying(100),CITY_ID character varying(100),CITY character varying(100),CITY_CODE character varying(100),REPORT_DATE character varying(100)', 
			    'val'=> 'ID, MAC_ADDRESS, IP_ADDRESS, SERIAL_NUMBER, HOSTNAME, DEV_FULL_NAME, VENDOR_MODEL, SW_MODEL, SW_ROLE, HOUSE_ID, DOORWAY, LOCATION, FLOOR, SW_MON_TYPE, SW_INV_STATE,VLAN, DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82, PARENT_ID, PARENT_MAC, PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT, PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN,CITY_ID, CITY, CITY_CODE, REPORT_DATE',
			    'delimiter' =>"','" , 
			    'encoding'=>"'utf-8'"
				),
			)
		)
	
	);
$newMail = new mailSender;
//$newMail->setProp('params',$params);
//$newMail->setProp('csv_out',$csv_out);
//$newMail->setProp('queryModificator',$queryModificator);
//echo 'im here'.'<hr>';
//print_r($newMail->getProp('params') );
//echo 'im here'.'<hr>';
//print_r($newMail -> mail_cities_users());
$newMail -> mail_cities_users($_params);
//echo 'im here'.'<hr>';
//print_r($newMail -> test());
//echo '<hr>'.$newMail -> dateReturn().'<hr>';

?>