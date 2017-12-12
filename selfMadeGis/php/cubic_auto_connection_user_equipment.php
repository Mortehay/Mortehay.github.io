
<?php
ini_set('display_errors', 1);
//include('restriction.php');
include('classFunctionStorage.php');
include('cityVocabulary.php'); 
//header('Content-type: text/plain; charset=utf-8');
mb_internal_encoding("UTF-8");
//downloading data from Cubic to our server and sore it in csv files

$userHybridTopology = new dbOrConnSetClass;
$userHybridTopology->setProp('query_type','_hybrids');
$userHybridTopology->setProp('query',"SELECT * FROM GIS_USER_EQUIPMENT WHERE technology_name = 'OTT Hybrid' AND status ='Подключено' ");
$requestuserHybridTopology = $userHybridTopology->dbOrConnect($cities);

$userModemTopology = new dbOrConnSetClass;
$userModemTopology->setProp('query_type','_modems');
$userModemTopology->setProp('query',"SELECT * FROM GIS_USER_EQUIPMENT WHERE technology_name in( 'D3', 'Euro D2', 'D2', 'Euro D3')  AND status ='Подключено' ");
$requestuserModemTopology = $userModemTopology->dbOrConnect($cities);
////////////////////////////////////
echo 'that is all';
?>