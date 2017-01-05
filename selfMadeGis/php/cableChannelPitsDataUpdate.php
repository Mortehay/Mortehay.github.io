<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['cable_channel_pits_city_eng'];  

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
            $sql = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET microdistrict =".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET district =".$selectedCity."_microdistricts.district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) ;" .  "UPDATE ".$selectedCity.".".$selectedCity."_cable_channel_pits SET pit_district =".$selectedCity."_coverage.notes FROM ".$selectedCity.".".$selectedCity."_coverage WHERE ST_Contains(".$selectedCity.".".$selectedCity."_coverage.geom_area, ".$selectedCity.".".$selectedCity."_cable_channel_pits.geom) and kiev.kiev_coverage.geom_area is not null;" ;

            $ret = pg_query($db, $sql);

	}

 
                 

	pg_close($db); // Closing Connection
	print( $sql);
?>

