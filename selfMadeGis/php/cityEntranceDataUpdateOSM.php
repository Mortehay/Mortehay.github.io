<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
          
	$selectedCity= $_POST['building_entrance_OSM_data_update_city_eng'];  
	$linkStorageOSM = "'/tmp/".$selectedCity."_entrances_osm.csv'";
      $linkStorageCUBIC = "'/tmp/".$selectedCity."_entrances_cubic.csv'";
      $dir = sys_get_temp_dir();
      $files = scandir($dir);
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
                          if ($str_file == $selectedCity."_entrances_osm.csv") {
                            
                         $insert = "CREATE temp TABLE tmp (id serial, openstreet_wkt text,openstreet_id_rel varchar(100),openstreet_entrance varchar(100),openstreet_addr_flats varchar(100),openstreet_entrance_ref varchar(100)); select copy_for_testuser('tmp (openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref)', ".$linkStorageOSM.", ';', 'windows-1251') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_entrances(openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref) SELECT openstreet_wkt, openstreet_id_rel, openstreet_entrance, openstreet_addr_flats,openstreet_entrance_ref FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_entrances WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
                          $ret = pg_query($db, $insert);
                          $update_geom = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET geom = ST_GeomFromText(openstreet_wkt, 32636) WHERE geom IS NULL;";
                          $ret = pg_query($db, $update_geom);
                          $add_house_id = 'UPDATE '.$selectedCity.'.'.$selectedCity.'_entrances SET cubic_house_id = '.$selectedCity.'_buildings.cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE '.$selectedCity.'_entrances.cubic_house_id IS NULL AND ST_Contains(ST_Buffer('.$selectedCity.'_buildings.building_geom,0.5), '.$selectedCity.'_entrances.geom) is true and '.$selectedCity.'_buildings.cubic_house_id IN (SELECT cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE building_geom IS NOT NULL AND cubic_house_id IS NOT NULL);' ;
                          $ret = pg_query($db, $add_house_id);
                          $update_entrance_id = "UPDATE ".$selectedCity.".".$selectedCity."_entrances SET cubic_entrance_id = cubic_house_id||'p'||openstreet_entrance_ref WHERE cubic_house_id IS NOT NULL  AND  cubic_entrance_id  IS NULL;";
                          $ret = pg_query($db, $update_entrance_id);


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
