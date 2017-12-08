<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
$checkerLinkArray = array(
  array('_coverage.qgs', '_customer_heatmap.qgs','_full.qgs','_luch.qgs'),
  array('map_coverage','map_heatmap','map_full','map_luch')
); 
function checkerList($linkArray,$descriptioArray, $checkerLinkArray){
  $checkerList ='';
  foreach ($checkerLinkArray[0] as $checkerLinkArrayKey => $checkerLinkArrayValue) {
  
    foreach ($linkArray as $key => $value) {

      if(strpos($linkArray[$key],$checkerLinkArray[0][$checkerLinkArrayKey])){ 
        $linkState = 'checked';
        $checkerList .='<input type="checkbox" id="'.$checkerLinkArray[1][$checkerLinkArrayKey].'" class="map_links" data-link="'.$linkArray[$key].'" '.$linkState.'><label  class="map_links" for="'.$checkerLinkArray[1][$checkerLinkArrayKey].'">'.$descriptioArray[$key].'</label><br>';
      } else if(strpos(implode($linkArray), $checkerLinkArray[0][$checkerLinkArrayKey]) == false){    
        $linkState ='';
        $checkerList .='<input type="checkbox" id="'.$checkerLinkArray[1][$checkerLinkArrayKey].'" class="map_links" data-link="'.$linkArray[$key].'" '.$linkState.'><label  class="map_links" for="'.$checkerLinkArray[1][$checkerLinkArrayKey].'">'.$checkerLinkArray[1][$checkerLinkArrayKey].'</label><br>';
      }

    }
  }
  return $checkerList;
}
$newDBrequest = new dbConnSetClass;
$query = "SELECT city, links, links_description, region, prime_city, city_eng, map_window from public.links order by city;";
$queryArrayKeys = array('city', 'links', 'links_description', 'region', 'prime_city', 'city_eng', 'map_window');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('response' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'city' => $sumObjectsArray[$sumObjectsArrayKey]['city'],
    'links' => checkerList(postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['links']),postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['links_description']),$checkerLinkArray),
    'region' => $sumObjectsArray[$sumObjectsArrayKey]['region'],
    'prime_city' => $sumObjectsArray[$sumObjectsArrayKey]['prime_city'],
    'city_eng' => $sumObjectsArray[$sumObjectsArrayKey]['city_eng'],
    'map_window' => '<input type="text" id="'.$sumObjectsArray[$sumObjectsArrayKey]['city'].'_coords" value="'.$sumObjectsArray[$sumObjectsArrayKey]['map_window'].'">'
  );
  array_push($arr_response['response'], $arr ); 
}
print json_encode($arr_response);
  //print( $sql);
?>

