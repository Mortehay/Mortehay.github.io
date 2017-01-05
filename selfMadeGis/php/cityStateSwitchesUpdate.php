<?php
ini_set('display_errors', 1);
include('restriction.php'); 
date_default_timezone_set('Europe/Kiev');
$dir_link    = '/tmp/alerts/';

foreach ($cities as $city ) {
	$dir = $dir_link.$city[1].'/';
	$files = scandir($dir);
	foreach($files as $file) {
		$str_file = (string)$file;
		//print_r($str_file);
		if ($str_file !== '.' && $str_file !== '..') {
			$file_link = $dir.$str_file;
			if (filesize($file_link) >1) {
				if (time()-filemtime($file_link) < 600) {
					echo $file_link;
					echo '<br>';
				} else {
					//echo 'all files are older then 10 minutes';
				  
				}
				//print($str_file);
				//echo '<br>';
			}
		}
	 		
	}
}
//echo '<br>';
//print_r($dir);
?>
