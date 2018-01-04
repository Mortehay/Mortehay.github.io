<?php
	ini_set('display_errors', 1);
	include('classFunctionStorage.php');
	$relocation = new mailSender;
	//$selectedCity = 'kiev';
	$cities = array('chernivtsi','cherkassy');
	
	
	foreach ($cities as $selectedCity) {
		$prime_folder = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/cc/';
		$temp_folder = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/cc_temp/';
		$folder_list = array_diff(scandir($prime_folder),array('.','..','.htaccess'));
		foreach ($folder_list as $folder_list_key => $folder_list_value) {

			//echo $folder_list_value.'--'.strlen(substr((string)$folder_list_value,2));
			if(strlen(substr((string)$folder_list_value,2)) == 1){
				$new_folder_list_value = 't_0000'.substr((string)$folder_list_value,2);
			} else if(strlen(substr((string)$folder_list_value,2)) == 2){
				$new_folder_list_value = 't_000'.substr((string)$folder_list_value,2);
			} else if(strlen(substr((string)$folder_list_value,2)) == 3){
				$new_folder_list_value = 't_00'.substr((string)$folder_list_value,2);
			} else if(strlen(substr((string)$folder_list_value,2)) == 4){
				$new_folder_list_value = 't_0'.substr((string)$folder_list_value,2);
			} else if(strlen(substr((string)$folder_list_value,2)) == 5){
				$new_folder_list_value = 't_'.substr((string)$folder_list_value,2);
			}
			echo $folder_list_value.' '.$new_folder_list_value.'<hr>';
			$relocation -> newDirCreation($temp_folder.$new_folder_list_value.'/');
			$files_list = array_diff(scandir($prime_folder.$folder_list_value.'/'),array('.','..','.htaccess'));
			foreach ($files_list as $file_key => $file_name) {
				
				$new_file_name = str_replace($folder_list_value, $new_folder_list_value,$file_name);
				echo $new_file_name.'<br>';
				copy($prime_folder.$folder_list_value.'/'.$file_name, $temp_folder.$new_folder_list_value.'/'.$new_file_name);
			}
		}
	}
	
	//print_r($folder_list);

?>