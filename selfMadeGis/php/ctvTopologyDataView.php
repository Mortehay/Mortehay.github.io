<?php
//ini_set('display_errors', 1);
/*      if ($_POST['ctv_topology_dataView_city_eng']) {
        $selectedCity= $_POST['ctv_topology_dataView_city_eng'];
      } else {
        $selectedCity = $_REQUEST["selectedCity"];
      }
      function groupSelect($cubic_name){
            $group_value = array(0, '#DC143C');
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 30, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 30, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 80, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 80, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 60, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 50, 'cc');}
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
                              'name' => $row[3].' - '.$row[1].' , № '.$row[2].'  '.$row[6],
                              'group' => groupSelect($row[3])[0],
                              'color' => groupSelect($row[3])[1],
                              'equipment' => groupSelect($row[3])[3],
                              'coment' => $row[6]
                            );
                          array_push($arr_response['nodes'], $description );
                        }
                  }
                  //---------------------------------------/блохи/
                  $topologyBug = '';
                  if ($selectedCity == 'chernivtsi') { 
                    $topologyBug =   "AND cubic_ou_code NOT IN('4055118')";
                  }
                  if ($selectedCity == 'kiev') { 
                    $topologyBug =   "AND cubic_ou_code NOT IN('1113580')";
                  }
                  //-------------------------------------------------------
                  $links_sql = "SELECT cubic_code, cubic_ou_code, cubic_name   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".$topologyBug.";"; 

                  $links_ret = pg_query($db, $links_sql);
                  
                  if($links_ret) {
                         while ($row = pg_fetch_row($links_ret))  {
                          $connections = array(
                              'source' => (int)$row[0],
                              'target' => (int)$row[1],
                              'value' => groupSelect($row[2])[2],
                              'color' => groupSelect($row[2])[1]
                            );

                          array_push($arr_response['links'], $connections );
                        }
                  }
	print json_encode($arr_response);
      }
	pg_close($db); // Closing Connection
	//print( $sql);
*/
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ctv_topology_dataView_city_eng']) {$selectedCity= $_POST['ctv_topology_dataView_city_eng'];} else {$selectedCity = $_REQUEST["ctv_topology_dataView_city_eng"];}
$arr_response = array('nodes' => array(),'links' => array()); 
$newDBrequest = new dbConnSetClass;
$query = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_coment   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел');";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house', 'cubic_name', 'cubic_code', 'cubic_ou_code', 'cubic_coment');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'name' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house'].'  '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'group' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])[0],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])[1],
    'equipment' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])[3],
    'coment' => $sumObjectsArray[$sumObjectsArrayKey]['coment']
  );
  array_push($arr_response['nodes'], $arr ); 
}
//---------------------------------------/блохи/-------------------------------
$topologyBug = '';
if ($selectedCity == 'chernivtsi') { 
  $topologyBug =   "AND cubic_ou_code NOT IN('4055118')";
}
if ($selectedCity == 'kiev') { 
  $topologyBug =   "AND cubic_ou_code NOT IN('1113580')";
}
//--------------------------------------------------------------------------------
$query = "SELECT cubic_code, cubic_ou_code, cubic_name   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".$topologyBug.";";
//echo $query;
$queryArrayKeys = array('cubic_code', 'cubic_ou_code', 'cubic_name');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'source' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'target' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_code'],
    'value' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])[2],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])[1]
  );
  array_push($arr_response['links'], $arr ); 
}
print json_encode($arr_response);
?>

