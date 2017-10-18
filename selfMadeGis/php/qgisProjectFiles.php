<?php
	ini_set('display_errors', 1);
	include_once('classFunctionStorage.php');
	//if ($_POST['qgisProjectFiles']) {$selectedCity= $_POST['qgisProjectFiles'];} else {$selectedCity = $_REQUEST["qgisProjectFiles"];}
	$arr_response = array('response' => array());
	$qgisProjectFilesDir = '/var/www/QGIS-Web-Client-master/projects/';
	$qgisProjectFiles = array_diff(scandir($qgisProjectFilesDir), array('.','..'));
	//print_r($qgisProjectFiles);
	session_start();
	$city_array = $_SESSION['city_array'];
	//print_r($_SESSION['city_array']);
	//print_r($city_array);

	foreach($qgisProjectFiles as $filename){
		//echo str_replace('.qgs','',explode('_',$filename)[1]).'<hr>';
		if(in_array(str_replace('.qgs','',explode('_',$filename)[1]),$city_array)){
			$arr = array(
	            'project_file_name' => $filename,
	            'project_file_date' => gmdate("Y-m-d h:i:s \G\M\T",stat($qgisProjectFilesDir.$filename)['mtime']),
	            //'project_file_dchema_state' => ''
	         ); 
        	array_push($arr_response['response'], $arr );
		}
		
	}
	print json_encode($arr_response);
	//create temp table tmp as select schema_name from information_schema.schemata where schema_name  not similar to '%(pg_|public|_city|information_|topology)%';select case when 'obukhiv'  in (select * from tmp) then true else false end as schema_state from tmp group by schema_state;
?>