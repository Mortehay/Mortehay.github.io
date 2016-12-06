<?php
//ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
          
	$selectedCity= $_POST['building_entrance_data_update_city_eng'];  
	
           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}
  //-----------------------------------------------------------------
  $sql = 'UPDATE '.$selectedCity.'.'.$selectedCity.'_entrances SET cubic_house_id = '.$selectedCity.'_buildings.cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE ST_Contains(ST_Buffer('.$selectedCity.'_buildings.building_geom,0.5), '.$selectedCity.'_entrances.geom) is true and '.$selectedCity.'_buildings.cubic_house_id IN (SELECT cubic_house_id FROM '.$selectedCity.'.'.$selectedCity.'_buildings WHERE building_geom IS NOT NULL AND cubic_house_id IS NOT NULL);';
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>

