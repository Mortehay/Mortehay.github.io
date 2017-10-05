<?php
ini_set('display_errors', 1);
include('restriction.php'); 
//include('classFunctionStorage');

$newalertDBrequest = new dbConnSetClass;

foreach ($cities as $city) {
	$content = utf8_encode(file_get_contents('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmSwitches&companyCode='.$city[0] ));
	$xmlSwitches = simplexml_load_string($content);
	$content = utf8_encode(file_get_contents('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmPorts&companyCode='.$city[0]));
	$xmlPorts = simplexml_load_string($content);
	//$xmlSwitches= new SimpleXMLElement('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmSwitches&companyCode='.$city , 0 , true);
	//$xmlPorts= new SimpleXMLElement('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmPorts&companyCode='.$city , 0 , true);
	//------------------------------CSV creation-------------------------------------------
	csvFromXml($xmlPorts, $city[1],'ports');
  	csvFromXml($xmlSwitches, $city[1],'switches');
  	//-------------------------------------------------------------------------------------------

  	//xmlPrint($xmlPorts, $city[1], 'ports');
  	xmlPrint($xmlSwitches, $city[1], 'switches');
  	//xmlPrint($xmlSwitches);
  	$query ="CREATE TEMP TABLE tmp AS select cubic_street, cubic_house_num, cubic_house_entrance_num,cubic_house_floor, cubic_switch_location, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt, cubic_ip_address, cubic_switch_id, cubic_switch_role, cubic_switch_model, count(*) as unique_alert_counter, (max(alert_time) - min(alert_time)) as alert_time_length, max(alert_time) as switch_last_update_time, switch_down_time, switch_geom, mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore from ".$city[1].".".$city[1]."_switches_alert_log where switch_down_time is not null group by cubic_ip_address , switch_down_time, cubic_street, cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt, switch_geom,mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, cubic_switch_id,cubic_switch_role, cubic_switch_model, cubic_house_entrance_num;  	INSERT INTO ".$city[1].".".$city[1]."_switches_alert_log_counter(cubic_street, cubic_house_num, cubic_house_entrance_num,cubic_house_floor, cubic_switch_location, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt, cubic_ip_address, cubic_switch_id, cubic_switch_role, cubic_switch_model, unique_alert_counter, alert_time_length,  switch_last_update_time, switch_down_time, switch_geom, mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore) SELECT cubic_street, cubic_house_num, cubic_house_entrance_num, cubic_house_floor, cubic_switch_location, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt, cubic_ip_address, cubic_switch_id, cubic_switch_role, cubic_switch_model, unique_alert_counter, alert_time_length,  switch_last_update_time, switch_down_time, switch_geom, mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore FROM tmp t WHERE NOT EXISTS (SELECT 1 FROM ".$city[1].".".$city[1]."_switches_alert_log_counter co WHERE co.cubic_switch_id = t.cubic_switch_id and co.switch_down_time = t.switch_down_time);  	UPDATE ".$city[1].".".$city[1]."_switches_alert_log_counter SET cubic_street = tmp.cubic_street, cubic_house_num = tmp.cubic_house_num, cubic_house_entrance_num = tmp.cubic_house_entrance_num,cubic_house_floor = tmp.cubic_house_floor, cubic_switch_location = tmp.cubic_switch_location, cubic_switch_contract_cnt = tmp.cubic_switch_contract_cnt, cubic_switch_contract_active_cnt = tmp.cubic_switch_contract_active_cnt, cubic_ip_address = tmp.cubic_ip_address, cubic_switch_id = tmp.cubic_switch_id, cubic_switch_role = tmp.cubic_switch_role, cubic_switch_model = tmp.cubic_switch_model, unique_alert_counter = tmp.unique_alert_counter, alert_time_length = tmp.alert_time_length,  alert_time_length_text = tmp.alert_time_length::text,switch_last_update_time = tmp.switch_last_update_time , switch_down_time = tmp.switch_down_time, switch_geom = tmp.switch_geom, mon_traffic_state = tmp.mon_traffic_state,  mon_ping_state = tmp.mon_ping_state, mon_ports_state = tmp.mon_ports_state, mon_ping_ignore = tmp.mon_ping_ignore FROM  tmp WHERE ".$city[1]."_switches_alert_log_counter.cubic_switch_id = tmp.cubic_switch_id and ".$city[1]."_switches_alert_log_counter.switch_down_time = tmp.switch_down_time and ".$city[1]."_switches_alert_log_counter.mon_traffic_state = tmp.mon_traffic_state  and ".$city[1]."_switches_alert_log_counter.mon_ping_state = tmp.mon_ping_state and ".$city[1]."_switches_alert_log_counter.mon_ports_state = tmp.mon_ports_state  and ".$city[1]."_switches_alert_log_counter.mon_ping_ignore = tmp.mon_ping_ignore;DELETE FROM ".$city[1].".".$city[1]."_switches_alert_log_counter WHERE switch_last_update_time IN(SELECT DISTINCT switch_last_update_time from ".$city[1].".".$city[1]."_switches_alert_log_counter WHERE switch_last_update_time < NOW() - INTERVAL '90 days');";

	echo $query;
	$newalertDBrequest -> dbConnect($query, false, true);
}

?>
