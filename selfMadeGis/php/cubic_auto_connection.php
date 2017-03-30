
<?php
ini_set('display_errors', 1);
include('restriction.php');
include('classFunctionStorage.php'); 
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");

$cityBuildings = new dbOrConnSetClass;
$cityBuildings->setProp('query_type','_buildings');
$cityBuildings->setProp('query',"SELECT * FROM puma_qgis.gis_spread_net_distr ");
$requestcityBuildings = $cityBuildings->dbOrConnect($cities);

$cityTopology = new dbOrConnSetClass;
$cityTopology->setProp('query_type','_ctv_topology');
$cityTopology->setProp('query',"SELECT * FROM puma_qgis.gis_nodesreestr_noku ");
$requestcityBuildings = $cityTopology->dbOrConnect($cities);

echo 'that is all';
?>