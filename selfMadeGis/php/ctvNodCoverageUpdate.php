<?php
//ini_set('display_errors', 1);

          
	$selectedCity= $_POST['ctv_city_nod_eng'];  
	
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

	$sql = "DELETE FROM ".$selectedCity.".".$selectedCity."_nod_coverage;  INSERT INTO ".$selectedCity.".".$selectedCity."_nod_coverage(cubic_lname) SELECT DISTINCT cubic_lname FROM ".$selectedCity.".".$selectedCity."_buildings ; UPDATE ".$selectedCity.".".$selectedCity."_nod_coverage SET coverage_geom = (SELECT ST_MakePolygon(g.geom)  FROM (SELECT ST_AddPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom))), ST_StartPoint(ST_MakeLine(ST_MakeValid(ST_Boundary(building_geom)))))  AS geom FROM (SELECT building_geom FROM ".$selectedCity.".".$selectedCity."_buildings WHERE  ".$selectedCity.".".$selectedCity."_buildings.cubic_lname = ".$selectedCity.".".$selectedCity."_nod_coverage.cubic_lname ) as geom) g);";
 print( $sql);
  $ret = pg_query($db, $sql);
  $beautify ="UPDATE ".$selectedCity.".".$selectedCity."_nod_coverage SET beauty_geom = st_buffer(st_buffer(coverage_geom,0.00008),0.00008) WHERE coverage_geom IS NOT NULL AND cubic_lname<>'не опр'";
  $ret_beauty = pg_query($db, $beautify);
  print( $beautify);
	pg_close($db); // Closing Connection
	
?>

