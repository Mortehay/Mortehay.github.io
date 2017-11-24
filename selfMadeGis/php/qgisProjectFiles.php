<?php
	//ini_set('display_errors', 1);
	include_once('classFunctionStorage.php');
	$fileEdit = new fileUpload;
	//if ($_POST['qgisProjectFiles']) {$selectedCity= $_POST['qgisProjectFiles'];} else {$selectedCity = $_REQUEST["qgisProjectFiles"];}
	$arr_response = array('response' => array());
	$qgisProjectFilesDir = '/var/www/QGIS-Web-Client-master/projects/';
	$qgisProjectLocalFilesDir = '/var/www/QGIS-Web-Client-master/site/tmp/projects-local/';
	$qgisProjectFiles = array_diff(scandir($qgisProjectFilesDir), array('.','..'));

	session_start();
	$city_array = $_SESSION['city_array'];
	$user_type = $_SESSION['user_type'];
	$file_links = postgres_to_php_array($_SESSION['user_file_links']);


	foreach($qgisProjectFiles as $filename){
		copy($qgisProjectFilesDir.$filename, $qgisProjectLocalFilesDir.$filename);
		$fileEdit -> textExchange('127.0.0.1','10.112.129.170',$qgisProjectLocalFilesDir.$filename);
		if($user_type = 'reader'){
			$fileEdit -> textExchange('simpleuser','simplereader',$qgisProjectLocalFilesDir.$filename);
			$fileEdit -> textExchange('simplepassword','readerpassword',$qgisProjectLocalFilesDir.$filename);
		}
		foreach ($file_links as $file_link) {
			if(strpos($filename,$file_link) !== false){
				if(in_array(str_replace('.qgs','',explode('_',$filename)[1]),$city_array)){
					$arr = array(
			            'project_file_name' => $filename,
			            'project_file_date' => gmdate("Y-m-d h:i:s \G\M\T",stat($qgisProjectFilesDir.$filename)['mtime']),
			            'project_file_link' => 'http://10.112.129.170/qgis-ck/tmp/projects-local/'.$filename
			            //'project_file_dchema_state' => ''
			         ); 
		        	array_push($arr_response['response'], $arr );
				}
			}
		}
		
		
	}
	print json_encode($arr_response);
?>