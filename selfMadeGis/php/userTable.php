<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');
session_start();
$restriction = $_SESSION['restriction'];
$e_mail = $_SESSION['e_mail'];
if ($restriction === 'admin')  {
  $newDBrequest = new dbConnSetClass;
  if ($_POST['buttonId'] =='user_table') {
    $query = "SELECT e_mail, restriction, map_links, file_links, user_type FROM public.access order by restriction, e_mail;";
    //echo $query;
    $queryArrayKeys = array('e_mail', 'restriction', 'map_links','file_links', 'user_type');
    $retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);

    $sumObjectsArray = $retuenedArray;

    //print_r($sumObjectsArray);
    $arr_response = array('response' => array());
    array_push($arr_response['response'], array(
      'e_mail'=>'<input type="text" id="addNewUserEmail" placeholder="e_mail" x-autocompletetype="e_mail" autocomplete="on" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">' , 
      'restriction' => '<input type="text" id="addNewUserRestriction" placeholder="доступ" x-autocompletetype="restriction" autocomplete="on">', 
      'pass' => '<input type="password" id="addNewUserPassword" autocomplete="new-password">', 
      'map_links' => '<input type="checkbox" id="map_full" class="map_links" data-map="Повна карта міста"><label  class="map_links" for="map_full">Повна карта міста</label><br>'.
        '<input type="checkbox"  id="map_coverage" class="map_links" data-map="Карта покриття міста"><label  class="map_links" for="map_coverage">Карта покриття міста</label><br>'.
        '<input type="checkbox"  id="map_heatmap" class="map_links" data-map="Карта heatmap міста"><label  class="map_links" for="map_heatmap">Карта heatmap міста</label><br>'.
        '<input type="checkbox"  id="map_luch"  class="map_links" data-map="Карта лінійно-кабельного обліку міста"><label  class="map_links" for="map_luch">Карта лінійно-кабельного обліку міста</label><br>',
      'file_links' => '<input type="checkbox" id="file_full" class="file_links" data-file="full"><label  class="file_links" for="file_full">full</label><br>'.
        '<input type="checkbox"  id="file_coverage" class="file_links" data-file="coverage"><label  class="file_links" for="file_coverage">coverage</label><br>'.
        '<input type="checkbox"  id="file_heatfile" class="file_links" data-file="customer_heatmap"><label  class="file_links" for="file_heatfile">customer_heatmap</label><br>'.
        '<input type="checkbox"  id="file_luch"  class="file_links" data-file="luch"><label  class="file_links" for="file_luch">luch</label><br>',
      'user_type' => '<input type="radio" id="user_editor" class="user_type" name="usertype" data-user="editor"><label  class="user_type" for="user_editor">editor</label><br>'.
        '<input type="radio" id="user_reader" class="user_type" name="usertype" data-user="reader"><label  class="user_type" for="user_reader">reader</label><br>',
      'edit' => '<button id="addNewUser">додати користувача</button>' )); 
    foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {

      $arr = array(
        'e_mail' => $sumObjectsArray[$sumObjectsArrayKey]['e_mail'],
        'restriction' => $sumObjectsArray[$sumObjectsArrayKey]['restriction'],
        'pass' => '---',
        'map_links' => $sumObjectsArray[$sumObjectsArrayKey]['map_links'],
        'file_links' => $sumObjectsArray[$sumObjectsArrayKey]['file_links'],
        'user_type' => $sumObjectsArray[$sumObjectsArrayKey]['user_type'],
        'edit' => '<button class="invisible deleteUser" data-mail="'.$sumObjectsArray[$sumObjectsArrayKey]['e_mail'].'">видалити</button>'.'<input type="checkbox" class="deleteUser" data-mail="'.$sumObjectsArray[$sumObjectsArrayKey]['e_mail'].'">'
      );
      array_push($arr_response['response'], $arr ); 
    }
    print json_encode($arr_response);

  }
}


?>