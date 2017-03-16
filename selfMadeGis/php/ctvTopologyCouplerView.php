<?php
//ini_set('display_errors', 1);
if ($_POST['ctv_city_couplers_eng']) {
		$selectedCity= $_POST['ctv_city_couplers_eng'];
} else {
        	$selectedCity = $_REQUEST['ctv_city_couplers_eng'];
}
if ($_POST['she']) {
	$selectedShe= $_POST['she'];
} else {
        	$selectedShe= $_REQUEST['she'];
} 
//echo $selectedShe;

if($selectedShe == 'виберіть ПГС') {$deleteNotselectedShe =''; $selectedShe = '';} 
else {
	$selectedShe = " AND cubic_pgs_addr  ='".$selectedShe."'  ";
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
 function checkIfFileExist($selectedCity, $cubic_name, $cubic_code, $archiveLink, $imgLink){
  $group_value = array();
  $cubic_name = groupSelect($cubic_name)[3];
  $xlsFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xls';
  $xlsxFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xlsx';
  $dwgFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.dwg';
  $pdfFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.pdf';
  $imgFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.png';
  //echo '$xlsFile - '.$xlsFile.'<hr>';
  //echo '$xlsxFile - '.$xlsxFile.'<hr>';
  //echo '$imgFile - '.$imgFile.'<hr>';
  if (file_exists($xlsxFile) || file_exists($xlsFile) || file_exists($dwgFile)|| file_exists($pdfFile)) {
    $group_value['archiveLink'] =  $archiveLink;
  } else {$group_value['archiveLink'] =  '-'; }
  if (file_exists($imgFile)) {
    $group_value['imgLink'] =  '/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.png';
  } else {$group_value['imgLink'] =  '-'; }
  if (file_exists($xlsxFile)) {
    $group_value['xlsxFile'] =  '+';
  } else {$group_value['xlsxFile'] =  '-'; }
  if (file_exists($xlsFile)) {
    $group_value['xlsFile'] =  '+';
  } else {$group_value['xlsFile'] =  '-'; }
  if (file_exists($dwgFile)) {
    $group_value['dwgFile'] =  '+';
  } else {$group_value['dwgFile'] =  '-'; }
  if (file_exists($pdfFile)) {
    $group_value['pdfFile'] =  '+';
  } else {$group_value['pdfFile'] =  '-'; }
  if (file_exists($imgFile)) {
    $group_value['imgFile'] =  '+';
  } else {$group_value['imgFile'] =  '-'; }
  return $group_value;
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
 	$coupler_selection = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_ou_name, cubic_ou_street, cubic_ou_house,  cubic_coment, archive_link, link, cubic_pgs_addr from ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name in ('Кросс-муфта','Оптический узел','Оптичний приймач') ".$selectedShe.";";
    $coupler_selection_query = pg_query($db, $coupler_selection);
   if($coupler_selection_query) {
    $arr_response = array('response' => array());
     while ($row = pg_fetch_row($coupler_selection_query) )  {

      $arr = array(
          'cubic_city' => $row[0],
          'cubic_coment' => $row[9],
          'cubic_pgs_addr' => $row[12],
          'cubic_name' => $row[3],
          'cubic_code' => $row[4],
          'cubic_street' => $row[1],
          'cubic_house' => $row[2],
          'cubic_ou_name' => $row[6],
          'cubic_ou_code' => $row[5],
          'cubic_ou_street' => $row[7],
          'cubic_ou_house' => $row[8], 
          'archive_link' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['archiveLink'], //$row[10]
          'link' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['imgLink'],//$row[11]
          'xlsxFile' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['xlsxFile'],
          'xlsFile' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['xlsFile'], 
          'dwgFile' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['dwgFile'], 
          'pdfFile' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['pdfFile'],
          'imgFile' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['imgFile'],    

        );
      //print_r( $arr);
      array_push($arr_response['response'], $arr );
      //array_push($arr_response, $arr);
    }

  } else {
     $arr[0] =  'no new ethernet equipment';
    array_push($arr_response['response'], $arr );
  }
}
print json_encode($arr_response);
pg_close($db); // Closing Connection


?>