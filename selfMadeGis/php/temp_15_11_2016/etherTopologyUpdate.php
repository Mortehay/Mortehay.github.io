<?php
//ini_set('display_errors', 1);

          
	$selectedCity= $_POST['ether_city_eng'];  
	
           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=postgres";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}

	$sql = "CREATE TEMP TABLE tmp AS SELECT * FROM ".$selectedCity.".".$selectedCity."_switches where cubic_mac_address IN (SELECT cubic_parent_mac_address FROM ".$selectedCity.".".$selectedCity."_switches);	UPDATE ".$selectedCity.".".$selectedCity."_switches SET parent_switches_geom = tmp.switches_geom FROM tmp WHERE ".$selectedCity."_switches.cubic_parent_mac_address = tmp.cubic_mac_address; DROP TABLE tmp;	 UPDATE ".$selectedCity.".".$selectedCity."_switches SET topology_line_geom = ST_MakeLine(parent_switches_geom, switches_geom) WHERE ".$selectedCity."_switches.parent_switches_geom IS NOT null AND ".$selectedCity."_switches.switches_geom IS NOT NULL;";
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>

