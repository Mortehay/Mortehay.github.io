<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
      $selectedCity= $_POST['city_building_OSM_data_eng'];    
      if (file_exists("/tmp/".$selectedCity."_buildings_osm.csv")) {
        $linkStorage = "'/tmp/".$selectedCity."_buildings_osm.csv'";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_buildings_osm.csv'" ;
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
                          if ($str_file == $selectedCity."_buildings_osm.csv") {
                            
                         $insert = "CREATE temp TABLE tmp (id serial, temp text,openstreet_id_rel varchar(100),openstreet_doggy_id_rel  varchar(100),openstreet_addr_housenumber varchar(100),openstreet_addr_street varchar(100), openstreet_amenity varchar(100),openstreet_building_type varchar(100), openstreet_building_levels varchar(100)); select copy_for_testuser('tmp (temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels)', ".$linkStorage.", ';', 'windows-1251') ;  INSERT INTO ".$selectedCity.".".$selectedCity."_buildings(temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels) SELECT  temp, openstreet_id_rel, openstreet_doggy_id_rel,openstreet_addr_housenumber, openstreet_addr_street, openstreet_amenity, openstreet_building_type, openstreet_building_levels FROM tmp WHERE openstreet_id_rel NOT IN(SELECT openstreet_id_rel FROM ".$selectedCity.".".$selectedCity."_buildings WHERE openstreet_id_rel IS NOT NULL);DROP TABLE tmp;";
                          $ret = pg_query($db, $insert);
                          $update_geom = "UPDATE ".$selectedCity.".".$selectedCity."_buildings SET building_geom = ST_GeomFromText(temp, 32636) WHERE building_geom IS NULL;";
                          $geo_ret = pg_query($db, $update_geom);
                         

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

