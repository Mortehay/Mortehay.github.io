<?php
//ini_set('display_errors', 1);
date_default_timezone_set('Europe/Kiev');

include('classFunctionStorage.php');
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
$arr_response = array('response' => array());
$newDBrequest = new dbConnSetClass;
$query = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_ou_name, cubic_ou_street, cubic_ou_house,  cubic_coment, archive_link, link, cubic_pgs_addr from ".$selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_name in ('Кросс-муфта','Оптический узел','Оптичний приймач') ".$selectedShe.";";
$queryArrayKeys = array('cubic_city', 'cubic_street', 'cubic_house', 'cubic_name', 'cubic_code', 'cubic_ou_code', 'cubic_ou_name', 'cubic_ou_street', 'cubic_ou_house',  'cubic_coment', 'archive_link', 'link', 'cubic_pgs_addr');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
//print_r($retuenedArray);
$sumObjectsArray = $retuenedArray;
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'cubic_city' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_city'],
    'cubic_coment' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_coment'],
    'cubic_pgs_addr' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_pgs_addr'],
    'cubic_name' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],
    'cubic_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_code'],
    'cubic_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_street'],
    'cubic_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_house'],
    'cubic_ou_name' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_name'],
    'cubic_ou_code' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_code'],
    'cubic_ou_street' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_street'],
    'cubic_ou_house' => $sumObjectsArray[$sumObjectsArrayKey]['cubic_ou_house'],
    'archive_link' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['archiveLink'],
    'link' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['imgLink'],
    'xlsxFile' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['xlsxFile'],
    'xlsFile' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['xlsFile'],
    'dwgFile' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['dwgFile'],
    'pdfFile' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['pdfFile'],
    'imgFile' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['imgFile'],
    'pdfFileCre_date' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['pdfFileCreDate'],
    'pdfFileCre_date_dayTime' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['pdfFileCreDateFull'],
    'pdfFileMod_date' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['pdfFileModDate'],
    'pdfFileMod_date_dayTime' => checkIfFileExist($selectedCity, $sumObjectsArray[$sumObjectsArrayKey]['cubic_name'],$sumObjectsArray[$sumObjectsArrayKey]['cubic_code'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'], $sumObjectsArray[$sumObjectsArrayKey]['archive_link'])['pdfFileModDateFull']
  );
  array_push($arr_response['response'], $arr ); 
}

print json_encode($arr_response);

?>