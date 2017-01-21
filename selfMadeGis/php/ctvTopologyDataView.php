<?php
//ini_set('display_errors', 1);
      if ($_POST['ctv_topology_dataView_city_eng']) {
        $selectedCity= $_POST['ctv_topology_dataView_city_eng'];
      } else {
        $selectedCity = $_REQUEST["selectedCity"];
      }
      function groupSelect($cubic_name){
            $group_value = array(0, '#DC143C');
<<<<<<< HEAD
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 30, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 30, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 80, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 80, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 60, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 50, 'cc');}
=======
<<<<<<< HEAD
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 60, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 60, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 90, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 90, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 80, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 60, 'cc');}
=======
            if ($cubic_name == 'Оптический узел') { $group_value[0] = 1; $group_value[1] = '#ffff00';}
            if ($cubic_name == 'Оптичний приймач') { $group_value[0] = 2; $group_value[1] = '#663300';}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value[0] = 3; $group_value[1] = '#3333cc';}
            if ($cubic_name == 'Передатчик оптический') { $group_value[0] = 4; $group_value[1] = '#333399';}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value[0] = 5; $group_value[1] = '#ff0000';}
            if ($cubic_name == 'Кросс-муфта') { $group_value[0] = 6; $group_value[1] = '#ff0066';}
>>>>>>> origin/master
>>>>>>> origin/master
        return $group_value;
      }
      //$selectedCity = 'ukrainka';
 	//$selectedCity = $_POST['ctv_topology_dataView_city_eng'];  
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
	

                $arr_response = array(
                    'nodes' => array(),
                    'links' => array()
                    );
                 $description_sql = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_coment   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел');";
                  $description_ret = pg_query($db, $description_sql);
                  
                  if($description_ret) {
                         while ($row = pg_fetch_row($description_ret))  {

                          $description = array(
                              'id' => (int)$row[4],
                              //'cubic_city' => $row[0],
                              //'cubic_street' => $row[1],
                              //'cubic_house' => $row[2],
<<<<<<< HEAD
                              'name' => $row[3],
                              'street' => $row[1],
                              'number' => $row[2],
                              //'name' => $row[3].' - '.$row[1].' , № '.$row[2].'  '.$row[6],
                              'group' => groupSelect($row[3])[0],
                              'color' => groupSelect($row[3])[1],
                              'equipment' => groupSelect($row[3])[3],
=======
                              'name' => $row[3].' - '.$row[1].' , № '.$row[2].'  '.$row[6],
                              'group' => groupSelect($row[3])[0],
                              'color' => groupSelect($row[3])[1],
<<<<<<< HEAD
                              'equipment' => groupSelect($row[3])[3],
=======
>>>>>>> origin/master
>>>>>>> origin/master
                              'coment' => $row[6]
                            );
                          array_push($arr_response['nodes'], $description );
                        }
                  }
                  //---------------------------------------/*блохи*/
                  $topologyBug = '';
                  if ($selectedCity == 'chernivtsi') { 
                    $topologyBug =   "AND cubic_ou_code NOT IN('4055118')";
                  }
                  if ($selectedCity == 'kiev') { 
                    $topologyBug =   "AND cubic_ou_code NOT IN('1113580')";
                  }
                  //-------------------------------------------------------
<<<<<<< HEAD
                  $links_sql = "SELECT cubic_code, cubic_ou_code, cubic_name   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".$topologyBug.";"; 
=======
<<<<<<< HEAD
                  $links_sql = "SELECT cubic_code, cubic_ou_code, cubic_name   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".$topologyBug.";"; 
=======
                  $links_sql = "SELECT cubic_code, cubic_ou_code   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".$topologyBug.";"; 
>>>>>>> origin/master
>>>>>>> origin/master

                  $links_ret = pg_query($db, $links_sql);
                  
                  if($links_ret) {
                         while ($row = pg_fetch_row($links_ret))  {
                          $connections = array(
                              'source' => (int)$row[0],
                              'target' => (int)$row[1],
<<<<<<< HEAD
                              'value' => groupSelect($row[2])[2],
                              'color' => groupSelect($row[2])[1]
=======
<<<<<<< HEAD
                              'value' => groupSelect($row[2])[2],
                              'color' => groupSelect($row[2])[1]
=======
                              'value' => (int)'1'
>>>>>>> origin/master
>>>>>>> origin/master
                            );

                          array_push($arr_response['links'], $connections );
                        }
                  }
	print json_encode($arr_response);
      }
	pg_close($db); // Closing Connection
	//print( $sql);
?>

