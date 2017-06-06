<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
session_start();
$restriction = $_SESSION['restriction'];
$login_user = $_SESSION['e_mail']; 
$newDBrequest = new dbConnSetClass;
$newDBrequest -> setProp('dbConnSet', $connLSetings);
$query = "SELECT city, city_eng   FROM public.links WHERE links IS NOT NULL ORDER BY city";
$queryArrayKeys =array('city', 'city_eng');
$city_array = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//$option = '';
//include('login.php'); 
$option = '';
$accordion='';
$tools ='';
$user='simpleuser';
$reader = 'simplereader';
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
    $query = 'GRANT USAGE ON SCHEMA '.$city_array[$key]['city_eng'].' to '.$user.'; GRANT ALL ON ALL TABLES IN SCHEMA '.$city_array[$key]['city_eng'].' TO '.$user.'; GRANT ALL ON ALL SEQUENCES IN SCHEMA '.$city_array[$key]['city_eng'].' TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key]['city_eng'].' GRANT ALL ON TABLES TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key]['city_eng'].' GRANT ALL ON SEQUENCES TO '.$user.';';
    $query .= 'GRANT USAGE ON SCHEMA '.$city_array[$key]['city_eng'].' to '.$reader.';'.'GRANT SELECT ON ALL TABLES IN SCHEMA '.$city_array[$key]['city_eng'].' TO '.$reader.';';
    $queryArrayKeys = false;
    echo $query;
    $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
  }
}



?>