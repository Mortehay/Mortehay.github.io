<?php
	$dir    = '/var/www/QGIS-Web-Client-master/projects/';
	$files = scandir($dir);

	//print_r($files);
	foreach($files as $file) {
		$str_file = (string)$file;
		//print_r($str_file);
		$oldMessage = '77.121.192.25';
		$deletedFormat = '127.0.0.1';
		if ($str_file !== '.' && $str_file !== '..') {
			//print($str_file);
			//chmod($dir.$str_file, 0666);
			$str=file_get_contents($dir.$str_file);
			//print($str);
		
			$str=str_replace("$oldMessage", "$deletedFormat",$str);
			//print($str);

			//print($file);
			file_put_contents($dir.$str_file, $str);
		}
	 		
	}
?>