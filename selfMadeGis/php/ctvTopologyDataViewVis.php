<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
if ($_POST['ctv_topology_dataView_city_eng']) {$selectedCity= $_POST['ctv_topology_dataView_city_eng'];} else {$selectedCity = $_REQUEST["ctv_topology_dataView_city_eng"];}
$arr_response = array('nodes' => array(),'links' => array(),'mdods' => array()); 
$newDBrequest = new dbConnSetClass;
$query = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_coment, cubic_pgs_addr   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел');";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house', 'cubic_name', 'cubic_code', 'cubic_ou_code', 'cubic_coment', 'cubic_pgs_addr');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'title' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house'].'  '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'group' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['group'],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['color'],
    'label' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['label'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house'],
    'comment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'she' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_pgs_addr']
  );
  array_push($arr_response['nodes'], $arr ); 
}

$query = "SELECT cubic_code, cubic_ou_code, cubic_name,  cubic_pgs_addr   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_ou_code IN(SELECT  cubic_code FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL)  AND cubic_name NOT IN('Блок питания', 'Домовой узел', 'Магистральный узел', 'Ответвитель магистральный', 'Распределительный стояк', 'Порт ОК', 'Ответвитель домовой', 'Субмагистральный узел')".";";
//echo $query;
$queryArrayKeys = array('cubic_code', 'cubic_ou_code', 'cubic_name',  'cubic_pgs_addr');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'from' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'to' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_code'],
    'value' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['value'],
    'color' => groupSelect($sumObjectsArray[$sumObjectsArrayKey]['cubic_name'])['color'],
    'she' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_pgs_addr']
  );
  array_push($arr_response['links'], $arr ); 
}
$query = "SELECT DISTINCT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_coment,  cubic_pgs_addr  FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name IN('Магистральный распределительный узел');";
//echo $query;
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house', 'cubic_name', 'cubic_code', 'cubic_ou_code', 'cubic_coment',  'cubic_pgs_addr');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'id' => (int)$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'title' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'].' - '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_street'].' , № '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_house'].'  '.$sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'comment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'she' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_pgs_addr']
  );
  array_push($arr_response['mdods'], $arr ); 
}
print json_encode($arr_response);
?>

