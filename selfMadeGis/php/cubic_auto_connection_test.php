
<?php
ini_set('display_errors', 1);
//include('restriction.php');
//include('classFunctionStorage.php'); 
if ($_POST['selectedCity']) {
  $selectedCity= $_POST['selectedCity'];
} else {
  $selectedCity = $_REQUEST['selectedCity'];
}
if ($_POST['date']) {
  $date= $_POST['date'];
} else {
  $date = $_REQUEST['date'];
}    
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");


//$cityTopology = new dbOrConnSetClass;

//$cityTopology->setProp('query',"select rr.*  from puma_qgis.gis_nodesreestr_noku rr");
//$requestcityTopology = $cityTopology->dbOrConnect($selectedCity);
//print_r($requestcityTopology);
$dbConnSet = array(
    "host"=>"10.10.16.70:1521/DTV",
    "encoding"=>"AL32UTF8",
    "user"=>"puma_qgis",
    "password"=>"vjDjA3JkcKdD"
    );

$conn = oci_connect($dbConnSet['user'], $dbConnSet['password'],$dbConnSet['host'],$dbConnSet['encoding']);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else {

	 $stid = oci_parse($conn, "select * from puma_qgis.gis_nodesreestr_noku where trunc(report_date) > to_date('$date', 'dd.mm.yyyy') and city = '$selectedCity'");//AND ROWNUM <=1000
	$table_array = array();
	if (oci_execute($stid)) {

	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
	      array_push($table_array, $row);
	  }
	}
	print_r($table_array);
}

echo 'that is all';
?>