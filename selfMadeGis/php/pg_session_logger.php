<?php
//ini_set('display_errors', 1);
include('classFunctionStorage.php');

$newDBrequest = new dbConnSetClass;
$query = "with data(datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query)  as (select datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query from pg_stat_activity where query not like 'with data(datid, datname, pid, usesysid, usename, application_name,%') insert into public.pg_sessions_stat(datid, datname, pid, usesysid, usename, application_name, client_addr, client_hostname, client_port, backend_start, xact_start, query_start, state_change, waiting, state, backend_xid, backend_xmin, query, time)  select d.datid, d.datname, d.pid, d.usesysid, d.usename, d.application_name, d.client_addr, d.client_hostname, d.client_port, d.backend_start, d.xact_start, d.query_start, d.state_change, d.waiting, d.state, d.backend_xid, d.backend_xmin, d.query, now() from data d where not exists (select 1 from public.pg_sessions_stat u where u.pid = d.pid and u.query_start = d.query_start and u.client_port = d.client_port);";
//echo $query;

$newDBrequest -> dbConnect($query, false, true);

?>