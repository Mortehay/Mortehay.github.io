<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');

$newDBrequest = new dbConnSetClass;
$query = "with data(datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query)  as (select datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query from pg_stat_activity where query not like 'with data(datid, datname, pid, usesysid, usename, application_name,%') insert into public.pg_sessions_stat(datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query)  select d.datid, d.datname, d.pid, d.usesysid, d.usename, d.application_name, d.client_addr, d.client_hostname, d.client_port, d.backend_start, d.xact_start, d.query_start, d.state_change, d.waiting, d.state, d.backend_xid, d.backend_xmin, d.query from data d where not exists (select 1 from public.pg_sessions_stat u where u.pid = d.pid and u.query_start = d.query_start and u.client_port = d.client_port); select * from public.pg_sessions_stat order by backend_start;";
//echo $query;
$queryArrayKeys = array('datid', 'datname', 'pid', 'usesysid', 'usename', 'application_name', 'client_addr', 'client_hostname', 'client_port', 'backend_start', 'xact_start', 'query_start', 'state_change', 'waiting', 'state', 'backend_xid', 'backend_xmin', 'query');
$retuenedArray = $newDBrequest -> dbConnect($query, $queryArrayKeys, true);
$sumObjectsArray = $retuenedArray;
//print_r($sumObjectsArray);
$arr_response = array('pg_session_stat' => array());
foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
  $arr = array(
    'datid' => $sumObjectsArray[$sumObjectsArrayKey]['datid'],
    'datname' => $sumObjectsArray[$sumObjectsArrayKey]['datname'],
    'pid' => $sumObjectsArray[$sumObjectsArrayKey]['pid'],
    'usesysid' => $sumObjectsArray[$sumObjectsArrayKey]['usesysid'],
    'usename' => $sumObjectsArray[$sumObjectsArrayKey]['usename'],
    'application_name' => $sumObjectsArray[$sumObjectsArrayKey]['application_name'],
    'client_hostname' => $sumObjectsArray[$sumObjectsArrayKey]['client_hostname'],
    'client_port' => $sumObjectsArray[$sumObjectsArrayKey]['client_port'],
    'backend_start' => $sumObjectsArray[$sumObjectsArrayKey]['backend_start'],
    'xact_start' => $sumObjectsArray[$sumObjectsArrayKey]['xact_start'], 
    'query_start' => $sumObjectsArray[$sumObjectsArrayKey]['query_start'], 
    'state_change' => $sumObjectsArray[$sumObjectsArrayKey]['state_change'], 
    'waiting' => $sumObjectsArray[$sumObjectsArrayKey]['waiting'], 
    'state' => $sumObjectsArray[$sumObjectsArrayKey]['state'], 
    'backend_xid' => $sumObjectsArray[$sumObjectsArrayKey]['backend_xid'], 
    'backend_xmin' => $sumObjectsArray[$sumObjectsArrayKey]['backend_xmin'], 
    'query' => $sumObjectsArray[$sumObjectsArrayKey]['query']
  );
  array_push($arr_response['pg_session_stat'], $arr ); 
}
print json_encode($arr_response);
?>