<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
          
	$selectedCity= $_POST['city_roads_OSM_data_eng'];  
	$linkStorageOSM = "'/tmp/".$selectedCity."_roads_osm.csv'";

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
                          if ($str_file == $selectedCity."_roads_osm.csv") {
                            
                         $insert = "CREATE temp TABLE tmp (id serial, wkt_geom text, geom geometry, osm_id varchar(100),  name varchar(100), highway varchar(100), maxspeed varchar(100), surface varchar(100), oneway varchar(100), bridge varchar(100), lanes varchar(100)); select copy_for_testuser('tmp (wkt_geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes)', ".$linkStorageOSM.", ';', 'windows-1251') ;  UPDATE tmp SET geom = ST_GeomFromText(wkt_geom, 32636); INSERT INTO ".$selectedCity.".".$selectedCity."_roads(geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes) SELECT geom, osm_id,  name, highway, maxspeed, surface, oneway, bridge, lanes FROM tmp WHERE osm_id NOT IN(SELECT osm_id FROM ".$selectedCity.".".$selectedCity."_roads WHERE osm_id IS NOT NULL);DROP TABLE tmp;";
                          $ret = pg_query($db, $insert);
                                                  

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

