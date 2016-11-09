<?php
//ini_set('display_errors', 1);

          
	$selectedCity= $_POST['city_supply_to_eng'];  
	
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

	$sql = "DELETE FROM ".$selectedCity.".".$selectedCity."_to_coverage; INSERT INTO ".$selectedCity.".".$selectedCity."_to_coverage(cubic_subdep) SELECT DISTINCT cubic_subdep FROM ".$selectedCity.".".$selectedCity."_buildings ;UPDATE ".$selectedCity.".".$selectedCity."_to_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM ( SELECT ST_AddPoint(ST_MakeLine(building_geom_firstpoint), ST_StartPoint(ST_MakeLine(building_geom_firstpoint)),-1)  AS geom FROM (SELECT building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_subdep = ".$selectedCity.".".$selectedCity."_to_coverage.cubic_subdep ) as geom) g WHERE ST_NumPoints(g.geom)>3);
 ";
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>

