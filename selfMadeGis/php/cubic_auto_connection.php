
<?php
ini_set('display_errors', 1);
include('restriction.php'); 
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");

function csvFromQuery($query_type, $city, $header,$table_array){
    if ($header) {
        //$dirPath = '/tmp/cubic/'.$query_type;
        $dirPath = '/var/www/QGIS-Web-Client-master/site/csv/cubic/'.$query_type;
            if (!file_exists($dirPath )) {
                $oldmask = umask(0);
                    mkdir($dirPath , 0777, true);
                    umask($oldmask);
            }

            //$filePath = '/tmp/cubic/'.$query_type.'/'.$city.$query_type.'.csv';
            $filePath = '/var/www/QGIS-Web-Client-master/site/csv/cubic/'.$query_type.'/'.$city.$query_type.'.csv';
            touch($filePath);
            chmod($filePath, 0666);
            $file = fopen($filePath, 'w');
            fputcsv($file, $header, ',', '"');
            foreach ($table_array as $row_key => $row) {
                   fputcsv($file, $row, ',', '"');
            }
            fclose($file);
    }
            
            return true;
}
function cityTablesCreate($city, $conn){
        $stid = oci_parse($conn, "SELECT * FROM puma_qgis.gis_spread_net_distr WHERE CITY ='".$city[2]."' ");//AND ROWNUM <=1000
        if (oci_execute($stid)) {
            //echo '<hr>';
            //echo "<table border='1'>\n";
            //print_r(array_keys($row) );
            $headder = array();
            //$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
            //echo '<tr>';
            if ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                 foreach (array_keys($row)  as $row_name_key => $row_name) {
                    //echo '<th>'.$row_name.'</th>';
                    $header[] = $row_name;

                }
            }

                //echo '</tr>';
            $table_array = array();
            while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                //echo '<tr>';
                //foreach ($row as $row_key => $key_value) {
                    //echo '<td>'.$key_value.'</td>';
                //}
                array_push($table_array, $row);
                //print_r( $row);
                //echo '</tr>';
            }
           //echo "</table>\n";
            //print_r($table_array);
            csvFromQuery('_buildings', $city[1], $header,$table_array);
            }
        
    }

$db='10.10.16.171:1521/pol';
$conn = oci_connect('puma_qgis', 'vjDjA3JkcKdD', $db,'AL32UTF8');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else {
    //$cities_array = array();
    //$stid_cities = oci_parse($conn, "SELECT DISTINCT CITY FROM puma_qgis.gis_spread_net_distr");
    //oci_execute($stid_cities);
    //while ($cityes = oci_fetch_array($stid_cities, OCI_ASSOC+OCI_RETURN_NULLS)) {
        foreach ($cities as $city => $city_name) {
            //$cities_array[] = $city_name;
            cityTablesCreate($city_name, $conn);
        }
        
    //}
    //print_r($cities_array);
}
echo 'that is all';

?>