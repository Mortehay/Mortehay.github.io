<?php
//ini_set('display_errors', 1);

          
	$selectedCity= $_POST['cable_channel_city_eng'];  
	
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

	$sql = "UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_1_geom =  ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_number = ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_1 AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_district  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.she_1  AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.microdistrict  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.microdistrict_1; "."UPDATE " .$selectedCity.".".$selectedCity."_cable_channels_channels SET pit_2_geom =  ".$selectedCity."_cable_channel_pits.geom FROM ".$selectedCity.".".$selectedCity."_cable_channel_pits WHERE ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_number = ".$selectedCity.".".$selectedCity."_cable_channels_channels.pit_2 AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.pit_district  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.she_2  AND ".$selectedCity.".".$selectedCity."_cable_channel_pits.microdistrict  = ".$selectedCity.".".$selectedCity."_cable_channels_channels.microdistrict_2; "."UPDATE ".$selectedCity.".".$selectedCity."_cable_channels_channels SET channel_geom = ST_MakeLine(pit_1_geom, pit_2_geom) WHERE pit_1_geom IS NOT NULL AND pit_2_geom IS NOT NULL;";
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>

