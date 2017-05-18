<?php
//ini_set('display_errors', 1);

$restriction=NULL;
//$option = '';
include('login.php'); 
$option = '';
$accordion='';
$tools ='';
$user='simpleuser';
$reader = 'simplereader';
 $restriction = $_GET["restriction"];

include('restriction.php');
$newDBrequest = new dbConnSetClass;
$conn = $newDBrequest -> setProp('dbConnSet', $connLSetings);
if(($key = array_search('вибери місто', $city_array)) !== false) {
    unset($city_array[$key]);
}
print_r($city_array);
//--------------------------------
if($restriction =='admin'){
    $query = 'REVOKE ALL ON DATABASE postgres FROM public; GRANT CONNECT ON DATABASE postgres TO '.$user.'; GRANT TEMPORARY ON DATABASE postgres TO '.$user.';GRANT CONNECT ON DATABASE template1 TO '.$user.';GRANT USAGE ON SCHEMA public TO '.$user.';GRANT TEMPORARY ON DATABASE template1 TO '.$user.'; GRANT ALL ON ALL TABLES IN SCHEMA public TO '.$user.'; GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA public GRANT ALL ON TABLES TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA public GRANT ALL ON SEQUENCES TO '.$user.'; ';
    $query .='GRANT CONNECT ON DATABASE postgres TO '.$reader.';';
  $queryArrayKeys = false;
  echo $query;
  $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
  //--------------------------------
  foreach ($city_array as $key => $value) {
    $query = 'GRANT USAGE ON SCHEMA '.$city_array[$key].' to '.$user.'; GRANT ALL ON ALL TABLES IN SCHEMA '.$city_array[$key].' TO '.$user.'; GRANT ALL ON ALL SEQUENCES IN SCHEMA '.$city_array[$key].' TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key].' GRANT ALL ON TABLES TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key].' GRANT ALL ON SEQUENCES TO '.$user.';';
    $query .= 'GRANT USAGE ON SCHEMA '.$city_array[$key].' to '.$reader.';'.'GRANT SELECT ON ALL TABLES IN SCHEMA '.$city_array[$key].' TO '.$reader.';';
    $queryArrayKeys = false;
    echo $query;
    $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
  }
}



?>