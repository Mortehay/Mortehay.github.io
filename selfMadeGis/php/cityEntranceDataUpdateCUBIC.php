<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
          
	$selectedCity= $_POST['building_entrance_CUBIC_data_update_city_eng'];  


      if (file_exists("/tmp/".$selectedCity."_entrances_cubic.csv")) {
        $linkStorage = "'/tmp/".$selectedCity."_entrances_cubic.csv'";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_entrances_cubic.csv'" ;
        $dir = "/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/";
        $files = scandir($dir);
      }
      //echo $linkStorage;
      //echo '<hr>'. file_exists($linkStorage);

           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                if ($files) {
                  foreach($files as $file) {
                    $str_file = (string)$file;
                    if ($str_file !== '.' && $str_file !== '..') {
                          print_r($str_file);
                          if ($str_file == $selectedCity."_entrances_cubic.csv") {
                            
                         $insert = "CREATE temp TABLE tmp (id serial, cubic_entrance_id varchar(100), cubic_entrance_number varchar(100),cubic_entrance_floor_num varchar(100),cubic_entrance_flat_num varchar(100),cubic_house_id varchar(100)); select copy_for_testuser('tmp ( cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id)', ".$linkStorage.", ';', 'windows-1251') ;UPDATE tmp SET cubic_entrance_id = cubic_house_id||'p'||cubic_entrance_number;  INSERT INTO ".$selectedCity.".".$selectedCity."_entrances(cubic_entrance_id, cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id) SELECT cubic_entrance_id, cubic_entrance_number, cubic_entrance_floor_num, cubic_entrance_flat_num,cubic_house_id FROM tmp WHERE cubic_entrance_id NOT IN(SELECT cubic_entrance_id FROM ".$selectedCity.".".$selectedCity."_entrances WHERE cubic_entrance_id IS NOT NULL);DROP TABLE tmp;";
                          $ret = pg_query($db, $insert);
                          $update_geom = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET geom = building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_entrances.geom IS NULL AND ".$selectedCity.".".$selectedCity."_entrances.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
                          $ret = pg_query($db, $update_geom);
                         


                         } else {
                            echo ' error cannot update data ';
                          }
            }
          } 

        }

	}
  //-----------------------------------------------------------------
  



	pg_close($db); // Closing Connection
	print( $insert.$update_geom.$add_house_id.$update_entrance_id);
?>

