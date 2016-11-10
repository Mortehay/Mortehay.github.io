<?php
//ini_set('display_errors', 1);

          
	$selectedCity= $_POST['city_supply_uso_eng'];  
	
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

	$sql = "DELETE FROM ".$selectedCity.".".$selectedCity."_uso_coverage;  INSERT INTO ".$selectedCity.".".$selectedCity."_uso_coverage(cubic_uso) SELECT DISTINCT cubic_uso FROM ".$selectedCity.".".$selectedCity."_buildings ; UPDATE ".$selectedCity.".".$selectedCity."_uso_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM (SELECT ST_AddPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom))), ST_StartPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom)))))  AS geom FROM (SELECT building_geom FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_uso = ".$selectedCity.".".$selectedCity."_uso_coverage.cubic_uso ) as geom) g);
 ";
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>

