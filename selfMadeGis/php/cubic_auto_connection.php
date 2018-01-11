
<?php
ini_set('display_errors', 1);
//include('restriction.php');
include('classFunctionStorage.php');
include('cityVocabulary.php'); 
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");
//downloading data from Cubic to our server and sore it in csv files
$cityBuildings = new dbOrConnSetClass;
$cityBuildings->setProp('query_type','_buildings');
$cityBuildings->setProp('query',"SELECT * FROM puma_qgis.gis_spread_net_distr ");
$requestcityBuildings = $cityBuildings->dbOrConnect($cities);

$cityTopology = new dbOrConnSetClass;
$cityTopology->setProp('query_type','_ctv_topology');
$cityTopology->setProp('query',"SELECT * FROM puma_qgis.gis_nodesreestr_noku ");
$requestcityTopology = $cityTopology->dbOrConnect($cities);

$ethernetTopology = new dbOrConnSetClass;
$ethernetTopology->setProp('query_type','_switches');
$ethernetTopology->setProp('query',"SELECT * FROM puma_qgis.gis_ethernet_topology ");
$requestethernetTopology = $ethernetTopology->dbOrConnect($cities);

$ethernetReestrWorking = new dbOrConnSetClass;
$ethernetReestrWorking->setProp('query_type','_switches_working');
$ethernetReestrWorking->setProp('query',"SELECT * FROM puma_qgis.gis_switch_reestr WHERE status like '%Работает%' ");
$requestethernetReestrWorking = $ethernetReestrWorking->dbOrConnect($cities);

$ethernetReestrAll = new dbOrConnSetClass;
$ethernetReestrAll->setProp('query_type','_switches_all');
$ethernetReestrAll->setProp('query',"SELECT * FROM puma_qgis.gis_switch_reestr ");
$requestethernetReestrAll = $ethernetReestrAll->dbOrConnect($cities);

////////////////////////////////////
echo 'that is all';
?>