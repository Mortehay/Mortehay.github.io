
<?php
ini_set('display_errors', 1);
include('restriction.php');
include('classFunctionStorage.php'); 
if ($_POST['selectedCity']) {
  $selectedCity= $_POST['selectedCity'];
} else {
  $selectedCity = $_REQUEST['selectedCity'];
}  
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");


$cityTopology = new dbOrConnSetClass;

$cityTopology->setProp('query',"SELECT * FROM puma_qgis.gis_nodesreestr_noku uk");
$requestcityTopology = $cityTopology->dbOrConnect($selectedCity);
print_r($requestcityTopology);
echo 'that is all';
?>