<?php
ini_set('display_errors', 1);
      if ($_POST['ctv_topology_dirCreate_city_eng']) {
        $selectedCity= $_POST['ctv_topology_dirCreate_city_eng'];
      } else {
        $selectedCity = $_REQUEST["selectedCity"];
      }
      function groupSelect($cubic_name){
            $group_value = array(0, '#DC143C',null,null);
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 60, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 60, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 90, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 90, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 80, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 60, 'cc');}
        return $group_value;
      }
      function topologyDirCreate($description, $city){
              
                $dirPath = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$city.'/topology/'.$description['cubic_name'].'/'.$description['cubic_code'];
                if (!file_exists($dirPath )) {
                  $oldmask = umask(0);
                      mkdir($dirPath , 0777, true);
                      umask($oldmask);
                }
        //echo $dirPath;
        return true;

      }


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
	

                $arr_response = array();
                 $description_sql = "SELECT  cubic_name, cubic_code  FROM ".$selectedCity.".".$selectedCity."_ctv_topology;";
                  $description_ret = pg_query($db, $description_sql);
                  
                  if($description_ret) {
                         while ($row = pg_fetch_row($description_ret))  {

                          $description = array(
                              'cubic_name' => groupSelect($row[0])[3],
                              'cubic_code' => $row[1],

                            );
                          if($description['cubic_name'] !==null){
                            array_push($arr_response, $description );
                            //print_r($description);
                           // echo'<br>';
                            topologyDirCreate($description, $selectedCity);
                            //echo'<hr>';
                          }
                          
                        }
                  }
                  $link_left_part = '"<a href="http://77.121.192.25/qgis-ck/tmp/archive/';
                  $link_right_part = '/" target="_blank">посилання на архів</a>"';
                  $archive_link_sql = "UPDATE $selectedCity"."."."$selectedCity"."_ctv_topology SET archive_link = CASE "."WHEN cubic_name like '%Магистральный распределительный узел%' THEN '$link_left_part"."$selectedCity"."/topology/mdod/"."'||cubic_code||'"."$link_right_part' "."WHEN cubic_name like '%Оптический узел%' THEN '$link_left_part"."$selectedCity"."/topology/nod/"."'||cubic_code||'"."$link_right_part' "."WHEN cubic_name like '%Оптичний приймач%' THEN '$link_left_part"."$selectedCity"."/topology/op/"."'||cubic_code||'"."$link_right_part' "."WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "."WHEN cubic_name like '%Передатчик оптический%' THEN '$link_left_part"."$selectedCity"."/topology/ot/"."'||cubic_code||'"."$link_right_part' "."END;";
                  $archive_link_ret = pg_query($db, $archive_link_sql);
                 echo $archive_link_sql;
                 
                  
	//print json_encode($arr_response);
      //print_r($arr_response);
      }
	pg_close($db); // Closing Connection
	//print( $sql);
?>

