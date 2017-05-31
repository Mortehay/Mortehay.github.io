<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
session_start();
$restriction = $_SESSION['restriction'];
$e_mail = $_SESSION['e_mail'];
if ($restriction === 'admin')  {
  $newDBrequest = new dbConnSetClass;
  if ($_POST['buttonId'] =='user_table') {
    $query = "SELECT e_mail, restriction FROM public.access;";
    //echo $query;
    $queryArrayKeys = array('e_mail', 'restriction');
    $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

    $sumObjectsArray = $retuenedArray;

    //print_r($sumObjectsArray);
    $arr_response = array('response' => array());
    array_push($arr_response['response'], array('e_mail'=>'<input type="text" id="addNewUserEmail" placeholder="e_mail" x-autocompletetype="e_mail" autocomplete="on" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">' , 'restriction' => '<input type="text" id="addNewUserRestriction" placeholder="доступ" x-autocompletetype="restriction" autocomplete="on">', 'pass' => '<input type="password" id="addNewUserPassword" autocomplete="new-password">','edit' => '<button id="addNewUser">додати користувача</button>' )); 
    foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {

      $arr = array(
        'e_mail' => $sumObjectsArray[$sumObjectsArrayKey]['e_mail'],
        'restriction' => $sumObjectsArray[$sumObjectsArrayKey]['restriction'],
        'pass' => '---',
        'edit' => '<button data-mail="'.$sumObjectsArray[$sumObjectsArrayKey]['e_mail'].'">редагувати</button>'
      );
      array_push($arr_response['response'], $arr ); 
    }
    print json_encode($arr_response);

  }
  
}


?>