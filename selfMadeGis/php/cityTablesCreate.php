<?php
//ini_set('display_errors', 1);

$restriction=NULL;
include('classFunctionStorage.php');
include('login.php'); 
if ($_POST['tables_create_city_eng']) {$selectedCity= $_POST['tables_create_city_eng'];} else {$selectedCity = $_REQUEST['tables_create_city_eng'];}
//echo $restriction;
if ($restriction != NULL) {
  $restriction=json_encode($restriction);
  print "<script type='text/javascript'>console.log(".$restriction.");</script>";
}
$tables = array();
$index_id = array();
$index_geom = array();
$schema_function = array();
$schema_trigger = array();
$iterateed_sequence = array();
//------------------create city schema -------------------------------------------------------------------------------
 $tables[0] = 'CREATE SCHEMA IF NOT EXISTS '.$selectedCity.';';
//------------------------------------------------------------------------------------------------------------------------------
 $index_id[0] = '';
 $index_geom[0] = '';
//-----------------create buildings table -----------------------------------------------------------------------------
$tables[1] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_buildings(id serial,openstreet_id_rel character varying(100),openstreet_doggy_id_rel character varying(100),openstreet_addr_housenumber character varying(100),openstreet_addr_street character varying(100),openstreet_amenity character varying(100),openstreet_building_type character varying(100),openstreet_building_levels character varying(100),openstreet_business_type character varying(100),cubic_house_id character varying(100),admin_notes character varying(100),building_geom geometry,building_geom_firstpoint geometry,cubic_city character varying(100),cubic_district_old character varying(100),cubic_distr_new character varying(100),cubic_street character varying(100),cubic_house character varying(100),cubic_subdep character varying(100),cubic_uso character varying(100),cubic_lname character varying(100),cubic_ladress character varying(100),cubic_hpname character varying(100),cubic_hpadress character varying(100),cubic_network_type character varying(100),cubic_freq character varying(100),cubic_house_type character varying(100),cubic_csd character varying(100),cubic_cnt character varying(100),cubic_comm text,cubic_vbb character varying(100),cubic_vbb_eth character varying(100),cubic_vbb_docsis character varying(100),cubic_ctv character varying(100),cubic_atv character varying(100),cubic_cnt_active_contr character varying(100),cubic_date_building character varying(100),cubic_date_building_eth character varying(100),cubic_date_ct character varying(100),cubic_digital_segment character varying(100),cubic_segment character varying(100),cubic_digital_stage character varying(100),cubic_digital_date character varying(100),cubic_box_type character varying(100),cubic_parnet character varying(100),cubic_serv_parnet character varying(100),cubic_sector_cnt character varying(100),cubic_hpcode character varying(100),cubic_max_speed_ethernet character varying(100),cubic_max_speed_docsis character varying(100),temp text,temp1 text,building_geom_secondpoint geometry,building_geom_thirdpoint geometry,building_geom_fourthpoint geometry,cubic_region character varying(100),cubic_cnt_vbb character varying(100),cubic_cnt_eth character varying(100),cubic_cnt_docsis character varying(100),cubic_cnt_ktv character varying(100),cubic_cnt_atv character varying(100), upload_time TIMESTAMP WITH TIME ZONE ); ';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[1] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'id'".","."'".$selectedCity."_buildings_id'".", 'btree');";
 $index_geom[1] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'building_geom'".","."'".$selectedCity."_building_geom_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'building_geom_firstpoint'".","."'".$selectedCity."_building_geom_firstpoint_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'building_geom_secondpoint'".","."'".$selectedCity."_building_geom_secondpoint_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'building_geom_thirdpoint'".","."'".$selectedCity."_building_geom_thirdpoint_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_buildings'".","."'building_geom_fourthpoint'".","."'".$selectedCity."_building_geom_fourthpoint_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable air table-----------------------------------------------------------------------------
$tables[2] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_air(id serial,table_id character varying(100),cable_progect_link character varying(300),cable_mount_date character varying(100),cable_id character varying(100),cable_type character varying(100),cable_short_type_description character varying(100),cable_description character varying(100),progect_number character varying(100),executive_doc_state character varying(100),cable_purpose character varying(100),cubic_start_house_id character varying(100),cubic_start_street character varying(100),cubic_start_house_num character varying(100),cubic_start_house_entrance_num character varying(100),link_fiber_welding_start character varying(100),geom_start_point geometry,cubic_end_house_id character varying(100),cubic_end_street character varying(100),cubic_end_house_num character varying(100),cubic_end_house_entrance_num character varying(100),link_fiber_welding_end character varying(100),geom_end_point geometry,total_cable_length character varying(100),geom_cable geometry,notes2 character varying(100),rezerve1 character varying(100),rezerve2 character varying(100),rezerve3 character varying(100),rezerve4 character varying(100),rezerve5 character varying(100),rezerve6 character varying(100),rezerve7 character varying(100),rezerve8 character varying(100),rezerve9 character varying(100),rezerve10 character varying(100));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[2] =  "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air'".","."'id'".","."'".$selectedCity."_cable_air_id'".", 'btree');";
 $index_geom[2] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air'".","."'geom_cable'".","."'".$selectedCity."_cable_air_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable air geom table--------------------------------------------------------------------
$tables[3] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_air_cable_geom(geom geometry,table_id character varying(100),cable_type character varying(100),cable_short_type_description character varying(100),id serial,total_cable_length character varying(50));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[3] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air_cable_geom'".","."'id'".","."'".$selectedCity."_cable_air_cable_geom_id'".", 'btree');";
 $index_geom[3] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air_cable_geom'".","."'geom'".","."'".$selectedCity."_cable_air_cable_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable air poles table--------------------------------------------------------------------
$tables[4] ='CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_air_poles(geom geometry,table_id character varying(100),pole_number character varying(100),pole_short_description character varying(300),id serial);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[4] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air_poles'".","."'id'".","."'".$selectedCity."_cable_air_poles_id'".", 'btree');";
 $index_geom[4] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_air_poles'".","."'geom'".","."'".$selectedCity."_cable_air_poles_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable channels geom table-----------------------------------------------------------
$tables[5] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_channels_cable_geom(geom geometry,table_id character varying(100),cable_type character varying(100),cable_short_type_description character varying(100),id serial,total_cable_length character varying(50));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[5] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_cable_geom'".","."'id'".","."'".$selectedCity."_cable_channels_cable_geom_id'".", 'btree');";
 $index_geom[5] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_cable_geom'".","."'geom'".","."'".$selectedCity."_cable_channels_cable_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable channel pits table---------------------------------------------------------------
$tables[6] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_channel_pits(geom geometry,table_id character varying(100),pit_number character varying(100),pit_short_description character varying(300), pit_district character varying(100), pit_coupler character varying(100), microdistrict character varying(100), district character varying(100), id serial, pit_id serial);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[6] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channel_pits'".","."'id'".","."'".$selectedCity."_cable_channel_pits_id'".", 'btree');";
 $index_geom[6] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channel_pits'".","."'geom'".","."'".$selectedCity."_cable_channel_pits_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  cable channels table-------------------------------------------------------------------
$tables[7] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_channels(id serial,summ_tu character varying(400),summ_contract_sum character varying(100),summ_sub_contract character varying(100),summ_acceptance_act character varying(100),summ_approval_cartogram character varying(100),summ_route_description text,summ_cable_type character varying(100),summ_archive_link character varying(200),table_id character varying(100),cable_progect_link character varying(100),tu_number character varying(300),tu_date character varying(100),rental_contract_new_num character varying(100),rental_contract_new_edoc character varying(100),rental_contract_new_add_num character varying(100),rental_contract_new_add_edoc character varying(100),acceptance_act_num character varying(100),acceptance_act_date character varying(100),cartogram_num character varying(100),cartogram_date character varying(100),cable_mount_date character varying(100),rental_contract_old_num character varying(100),rental_contract_old_edoc character varying(100),rental_contract_old_add_num character varying(100),rental_contract_old_add_edoc character varying(100),approval_cartogram_num character varying(100),approval_cartogram_date character varying(100),cable_ukrtelefon_id character varying(100),cable_type character varying(100),cable_short_type_description character varying(100),cable_description character varying(100),cable_description_fact character varying(100),cable_diameter character varying(100),cable_rental_price_k character varying(100),progect_number character varying(100),executive_doc_state character varying(100),notes1 character varying(100),cubic_start_house_id character varying(100),cubic_start_street character varying(100),cubic_start_house_num character varying(100),cubic_start_house_entrance_num character varying(100),contract_start_address character varying(100),contract_start_pit character varying(100),link_fiber_welding_start character varying(100),geom_start_pit geometry,cubic_end_house_id character varying(100),cubic_end_street character varying(100),cubic_end_house_num character varying(100),cubic_end_house_entrance_num character varying(100),contract_end_address character varying(100),contract_end_pit character varying(100),link_fiber_welding_end character varying(100),geom_end_pit geometry,contract_chanel_length character varying(100),cable_length_house character varying(100),other_contract_channel_length character varying(100),total_cable_length character varying(100),geom_cable_channel geometry,geom_cable geometry,cable_rental_price character varying(100),notes2 character varying(100),rezerve1 character varying(100),rezerve2 character varying(100),rezerve3 character varying(100),rezerve4 character varying(100),rezerve5 character varying(100),rezerve6 character varying(100),rezerve7 character varying(100),rezerve8 character varying(100),rezerve9 character varying(100),rezerve10 character varying(100));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[7] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels'".","."'id'".","."'".$selectedCity."_cable_channels_id'".", 'btree');";
 $index_geom[7] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels'".","."'geom_cable'".","."'".$selectedCity."_cable_channels_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city coverage table----------------------------------------------------------------------
$tables[8] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_coverage(id serial,coverage_zone character varying(100),coverage_description character varying(100),geom_point geometry,geom_area geometry,cubic_house_id character varying(100),notes character varying(200));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[8] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_coverage'".","."'id'".","."'".$selectedCity."_coverage_id'".", 'btree');";
 $index_geom[8] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_coverage'".","."'geom_area'".","."'".$selectedCity."_coverage_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city ctv topology table------------------------------------------------------------------
$tables[9] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_ctv_topology(id serial,cubic_city character varying(100),cubic_street character varying(100),cubic_house character varying(100),cubic_flat character varying(100),cubic_code character varying(100),cubic_name character varying(100),cubic_pgs_addr character varying(100),cubic_ou_op_addr character varying(100),cubic_ou_code character varying(100), cubic_ou_name character varying(100), cubic_ou_street character varying(100), cubic_ou_house character varying(100), cubic_date_reg character varying(100),cubic_coment character varying(100),cubic_uname character varying(100),cubic_net_type character varying(100),cubic_house_id character varying(100),equipment_geom geometry,mother_equipment_geom geometry,topology_line_geom geometry, link text, archive_link text, flats text);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[9] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_ctv_topology'".","."'id'".","."'".$selectedCity."_ctv_topology_id'".", 'btree');";
 $index_geom[9] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_ctv_topology'".","."'equipment_geom'".","."'".$selectedCity."_ctv_topology_equipment_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_ctv_topology'".","."'mother_equipment_geom'".","."'".$selectedCity."_ctv_topology_mother_equipment_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_ctv_topology'".","."'topology_line_geom'".","."'".$selectedCity."_ctv_topology_topology_line_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city building entrances-----------------------------------------------------------------
$tables[10] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_entrances(id serial,openstreet_wkt character varying(200),openstreet_id_rel character varying(100),openstreet_entrance character varying(100),openstreet_addr_flats character varying(100),openstreet_entrance_ref character varying(100),cubic_house_id character varying(100),geom geometry,cubic_entrance_number character varying(100),cubic_entrance_floor_num character varying(100),cubic_entrance_flat_num character varying(100),cubic_entrance_id character varying(100),notes character varying(100));';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[10] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_entrances'".","."'id'".","."'".$selectedCity."_entrances_id'".", 'btree');";
 $index_geom[10] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_entrances'".","."'geom'".","."'".$selectedCity."_entrances_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city nod coverage------------------------------------------------------------------------
 $tables[11] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_nod_coverage(id serial,cubic_lname character varying,coverage_geom geometry,beauty_geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[11] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_nod_coverage'".","."'id'".","."'".$selectedCity."_nod_coverage_id'".", 'btree');";
 $index_geom[11] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_nod_coverage'".","."'coverage_geom'".","."'".$selectedCity."_nod_coverage_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_nod_coverage'".","."'beauty_geom'".","."'".$selectedCity."_nod_beauty_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city optical receiver coverage--------------------------------------------------------
 $tables[12] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_or_coverage(id serial,cubic_lname character varying,coverage_geom geometry,beauty_geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[12] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_or_coverage'".","."'id'".","."'".$selectedCity."_or_coverage_id'".", 'btree');";
 $index_geom[12] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_or_coverage'".","."'coverage_geom'".","."'".$selectedCity."_or_coveragee_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_or_coverage'".","."'beauty_geom'".","."'".$selectedCity."_or_coverage_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city optical couplers--------------------------------------------------------------------
$tables[13] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_optical_couplers(geom geometry,table_id character varying(100),coupler_number character varying(100),coupler_short_description character varying(300),coupler_location character varying(300),id serial);';
//-----------------------------------------------------------------------------------------------------------------------------
 $index_id[13] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_optical_couplers'".","."'id'".","."'".$selectedCity."_optical_couplers_id'".", 'btree');";
 $index_geom[13] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_optical_couplers'".","."'geom'".","."'".$selectedCity."_optical_couplers_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city roads-----------------------------------------------------------------------------------
$tables[14] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_roads(id serial,name character varying(100),highway character varying(100),osm_id character varying(100),surface character varying(100),lanes character varying(100),maxspeed character varying(100),oneway character varying(100),trolley_wire character varying(100),bridge character varying(100),geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[14] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_roads'".","."'id'".","."'".$selectedCity."_roads_id'".", 'btree');";
 $index_geom[14] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_roads'".","."'geom'".","."'".$selectedCity."_roads_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  city switches------------------------------------------------------------------------------
$tables[15] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_switches(id serial,cubic_city character varying(100),cubic_district character varying(100),cubic_street character varying(100),cubic_house_num character varying(100),cubic_house_entrance_num character varying(100),cubic_house_id character varying(100),cubic_ip_address character varying(100),cubic_mac_address character varying(100),cubic_hostname character varying(100),cubic_switch_role character varying(100),cubic_switch_model character varying(100),cubic_inventary_state character varying(100),cubic_snmp_community character varying(100),cubic_vlan character varying(100),cubic_monitoring_method character varying(100),cubic_up_port character varying(100),cubic_parent_mac_address character varying(100),  cubic_parent_switch_role character varying(100), cubic_parent_switch_model character varying(100) , cubic_parent_down_port character varying(100),cubic_rgu character varying(100),cubic_cascade_num character varying(100),switches_geom geometry,parent_switches_geom geometry,power_consumption real,UPS_type character varying(100),ups_reserve_time         integer,tech_status character varying(100),online_status character varying(100),online_snmp_get character varying(100),online_snmp_set character varying(100),online_last_date_in_network character varying(100),online_uplink_port_speed_mbs character varying(100),online_uplink_port character varying(100),online_cascad character varying(100),vlan_switch character varying(100),vlan_guest character varying(100),topology_line_geom geometry, alarm_state character varying(100), port_state character varying(100), errs_in character varying(100), errs_out character varying(100), dev_state character varying(100), inventary_state character varying(100), update_time character varying(100), up_time character varying(100), down_time character varying(100));';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[15] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_switches'".","."'id'".","."'".$selectedCity."_switches_id'".", 'btree');";
$index_geom[15] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_switches'".","."'switches_geom'".","."'".$selectedCity."_switches_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_switches'".","."'parent_switches_geom'".","."'".$selectedCity."_parent_switches_geom_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_switches'".","."'topology_line_geom'".","."'".$selectedCity."_topology_line_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  to coverage---------------------------------------------------------------------------------
 $tables[16] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_to_coverage(id serial,cubic_subdep character varying(100),coverage_geom geometry,beauty_geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[16] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_to_coverage'".","."'id'".","."'".$selectedCity."_to_coverage_id'".", 'btree');";
$index_geom[16] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_to_coverage'".","."'coverage_geom'".","."'".$selectedCity."_to_coveragee_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_to_coverage'".","."'beauty_geom'".","."'".$selectedCity."_to_coverage_geom_gist'".", 'gist');";
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  uso coverage------------------------------------------------------------------------------
$tables[17] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_uso_coverage(id serial,cubic_uso character varying(100),coverage_geom geometry,beauty_geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[17] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_uso_coverage'".","."'id'".","."'".$selectedCity."_uso_coverage_id'".", 'btree');";
$index_geom[17] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_uso_coverage'".","."'coverage_geom'".","."'".$selectedCity."_uso_coveragee_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_uso_coverage'".","."'beauty_geom'".","."'".$selectedCity."_uso_coverage_geom_gist'".", 'gist');";              
//-----------------------------------------------------------------------------------------------------------------------------
//-----------------create  microdistrict coverage------------------------------------------------------------------
$tables[18] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_microdistricts(id serial,micro_district character varying(100),district character varying(100),she character varying(200),coverage_geom geometry,point_geom geometry);';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[18] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_microdistricts'".","."'id'".","."'".$selectedCity."_microdistricts_id'".", 'btree');";
$index_geom[18] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_microdistricts'".","."'coverage_geom'".","."'".$selectedCity."_microdistricts_gist'".", 'gist');"; 
//-----------------create  microdistrict coverage------------------------------------------------------------------
$tables[19] = 'CREATE TABLE IF NOT EXISTS '.$selectedCity.'.'.$selectedCity.'_cable_channels_channels(id serial,pit_1 character varying(100),rm_1 character varying(100),she_1 character varying(100),entry_comment_1 character varying(100),cubic_house_id_1 character varying(100),microdistrict_1 character varying(100),temp_1 character varying(100),pit_2 character varying(100),rm_2 character varying(100),she_2 character varying(100),entry_comment_2 character varying(100),cubic_house_id_2 character varying(100),microdistrict_2 character varying(100),temp_2 character varying(100),distance character varying(100),pit_1_geom geometry,pit_2_geom geometry,channel_geom geometry, pit_id_1 integer, pi_id_2 integer);';
//-----------------------------------------------------------------------------------------------------------------------------
$index_id[19] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_channels'".","."'id'".","."'".$selectedCity."_cable_channels_channels_id'".", 'btree');";
$index_geom[19] = "SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_channels'".","."'pit_1_geom'".","."'".$selectedCity."_pit_1_geom_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_channels'".","."'pit_2_geom'".","."'".$selectedCity."_pit_2_geom_gist'".", 'gist');"."SELECT create_index("."'".$selectedCity."'".", "."'".$selectedCity."_cable_channels_channels'".","."'channel_geom'".","."'".$selectedCity."_channel_geom_gist'".", 'gist');"; 
//-------FUNCTIONS--------------//////////////////////////////////////////////////////////////////////////////////////
//------------------------FUNCTION --------geom cable channels relocation------------------------------
$schema_function[0] ='CREATE OR REPLACE FUNCTION '.$selectedCity.'_cable_channel_cable_geom_update() RETURNS TRIGGER AS  $BODY$ BEGIN IF  TG_OP = '."'INSERT'".' THEN  UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_channels SET  geom_cable =NEW.geom WHERE '.$selectedCity.'_cable_channels.table_id = NEW.table_id; RETURN NEW; ELSIF TG_OP = '."'UPDATE'".' THEN UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_channels SET  geom_cable =NEW.geom  WHERE '.$selectedCity.'_cable_channels.table_id = NEW.table_id; RETURN NEW; ELSIF TG_OP = '."'DELETE'".' THEN UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_channels SET  geom_cable =NULL WHERE '.$selectedCity.'_cable_channels.table_id = OLD.table_id; RETURN OLD; END IF; END; $BODY$  LANGUAGE plpgsql VOLATILE  COST 100;';
$schema_trigger[0] ='DROP TRIGGER IF EXISTS '.$selectedCity.'_cable_channels_geom_update ON '.$selectedCity.'.'.$selectedCity.'_cable_channels_cable_geom;'.' CREATE TRIGGER '.$selectedCity.'_cable_channels_geom_update AFTER INSERT OR DELETE OR UPDATE ON '.$selectedCity.'.'.$selectedCity.'_cable_channels_cable_geom FOR EACH ROW EXECUTE PROCEDURE '.$selectedCity.'_cable_channel_cable_geom_update();';
//------------------------FUNCTION --------geom cable air relocation---------------------------------------
$schema_function[1] ='CREATE OR REPLACE FUNCTION '.$selectedCity.'_cable_air_cable_geom_update() RETURNS TRIGGER AS  $BODY$ BEGIN IF  TG_OP = '."'INSERT'".' THEN  UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_air SET  geom_cable =NEW.geom WHERE '.$selectedCity.'_cable_air.table_id = NEW.table_id; RETURN NEW; ELSIF TG_OP = '."'UPDATE'".' THEN UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_air SET  geom_cable =NEW.geom  WHERE '.$selectedCity.'_cable_air.table_id = NEW.table_id; RETURN NEW; ELSIF TG_OP = '."'DELETE'".' THEN UPDATE '.$selectedCity.'.'.$selectedCity.'_cable_air SET  geom_cable =NULL WHERE '.$selectedCity.'_cable_air.table_id = OLD.table_id; RETURN OLD; END IF; END; $BODY$  LANGUAGE plpgsql VOLATILE  COST 100;';
$schema_trigger[1] ='DROP TRIGGER IF EXISTS '.$selectedCity.'_cable_air_geom_update ON '.$selectedCity.'.'.$selectedCity.'_cable_air_cable_geom;'.' CREATE TRIGGER '.$selectedCity.'_cable_air_geom_update AFTER INSERT OR DELETE OR UPDATE ON '.$selectedCity.'.'.$selectedCity.'_cable_air_cable_geom FOR EACH ROW EXECUTE PROCEDURE '.$selectedCity.'_cable_air_cable_geom_update();';
//-----------------------------------------------------------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///-----------insert sequence of iterated values////////////////////////////////////////////////////////////////
$iterateed_sequence[0] = "select input_table_id('".$selectedCity.".".$selectedCity."_cable_air',  'table_id', 4000);";
$iterateed_sequence[1] = "select input_table_id('".$selectedCity.".".$selectedCity."_cable_channels',  'table_id', 2000);";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//echo $restriction;
$length = count($tables);
$function_count =count($schema_function);
$tables_query = '';
$function_query = '';
$iterateed_sequence_query = '';
for ($i=0; $i < $length; $i++) { 
  $tables_query .=$tables[$i].' '.$index_id[$i].' '.$index_geom[$i];
}
for ($k=0; $k < $function_count; $k++) { 
  $function_query  .=$schema_function[$k].' '.$schema_trigger[$k].' ';
}

foreach ($iterateed_sequence as $key => $iterateed_sequence_value) {
  $iterateed_sequence_query .=$iterateed_sequence_value.' ';
}

//echo $sql;
$newDBrequest = new dbConnSetClass;
$conn = $newDBrequest -> setProp('dbConnSet', $connLSetings);
$ret = $newDBrequest -> dbConnect($tables_query, false, true);
$fun = $newDBrequest -> dbConnect($function_query, false, true);
$iter = $newDBrequest -> dbConnect($iterateed_sequence_query, false, true);
    
print($tables_query);
print($function_query);
print($iterateed_sequence_query);

         
?>
