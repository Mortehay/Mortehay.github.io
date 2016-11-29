<?php
//ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
          
	$selectedCity= $_POST['ctv_city_eng'];  
	
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
  $equipment_geom_update ="UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магистральный распределительный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магістральний оптичний вузол' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Оптический узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Оптичний приймач' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Передатчик оптический' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_firstpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Порт ОК' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_secondpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Домовой узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Ответвитель магистральный' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_fourthpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Распределительный стояк' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Магистральный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET equipment_geom = ".$selectedCity.".".$selectedCity."_buildings.building_geom_thirdpoint FROM ".$selectedCity.".".$selectedCity."_buildings WHERE ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_name = 'Субмагистральный узел' AND ".$selectedCity.".".$selectedCity."_ctv_topology.equipment_geom IS NULL AND ".$selectedCity.".".$selectedCity."_ctv_topology.cubic_house_id = ".$selectedCity.".".$selectedCity."_buildings.cubic_house_id;";
  $equipment_geometry_update_query =pg_query($db, $equipment_geom_update);
  print( $equipment_geom_update);
  //-----------------------------------------------------------------
	$sql = "CREATE TEMP TABLE tmp AS SELECT * FROM ".$selectedCity.".".$selectedCity."_ctv_topology where cubic_code IN (SELECT cubic_ou_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology);	UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET mother_equipment_geom = tmp.equipment_geom FROM tmp WHERE ".$selectedCity."_ctv_topology.cubic_ou_code = tmp.cubic_code; DROP TABLE tmp;	 UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET topology_line_geom = ST_MakeLine(mother_equipment_geom, equipment_geom) WHERE ".$selectedCity."_ctv_topology.mother_equipment_geom IS NOT null AND ".$selectedCity."_ctv_topology.equipment_geom IS NOT NULL;";
	$ret = pg_query($db, $sql);
	pg_close($db); // Closing Connection
	print( $sql);
?>
