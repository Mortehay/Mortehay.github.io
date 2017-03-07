<?php
	ini_set('display_errors', 1);
	$dir    = '/var/www/QGIS-Web-Client-master/projects/';
	$files = scandir($dir);

	//print_r($files);
	foreach($files as $file) {
		$str_file = (string)$file;
		//print_r($str_file);
		$oldMessage = array('77.121.192.25','127.0.0.1');
		//$oldMessage[] = $_SERVER['SERVER_ADDR']; 
		$deletedFormat = '10.112.129.170';
		if ($str_file !== '.' && $str_file !== '..') {
			//print($str_file);
			//chmod($dir.$str_file, 0666);
			$newDirPath = '/var/www/QGIS-Web-Client-master/site/tmp/projects-local/';
		                if (!file_exists($newDirPath )) {
		                  $oldmask = umask(0);
		                      mkdir($newDirPath , 0777, true);
		                      umask($oldmask);
		                }
		             
		             copy($dir.$str_file , $newDirPath . $str_file);
		             chmod($newDirPath . $str_file, 0666);
			$str=file_get_contents($newDirPath .$str_file);
			//print($str);
			foreach ($oldMessage as $key => $oldMessageValue) {
				$str=str_replace("$oldMessageValue", "$deletedFormat",$str);
			}
			
			//print($str);

			//print($file);
			file_put_contents($newDirPath .$str_file, $str);
		}
	 		
	}
	$the_folder = $newDirPath ;
	$zip_file_name = 'archived_qgis_files.zip';

	class FlxZipArchive extends ZipArchive {
	        /** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
	    public function addDir($location, $name) {
	        $this->addEmptyDir($name);
	         $this->addDirDo($location, $name);
	     } // EO addDir;

	        /**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann * @access private   **/
	    private function addDirDo($location, $name) {
	        $name .= '/';         $location .= '/';
	      // Read all Files in Dir
	        $dir = opendir ($location);
	        while ($file = readdir($dir))    {
	            if ($file == '.' || $file == '..') continue;
	          // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
	            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
	            $this->$do($location . $file, $name . $file);
	        }
	    } 
	}

	$za = new FlxZipArchive;
	$res = $za->open($zip_file_name, ZipArchive::CREATE);
	if($res === TRUE)    {
	    $za->addDir($the_folder, basename($the_folder)); $za->close();
	}
	else  { echo 'Could not create a zip archive';}
?>