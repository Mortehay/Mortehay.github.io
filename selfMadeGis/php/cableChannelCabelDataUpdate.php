<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['cable_channel_cable_data_city_eng'];  
	$linkArchive = "'".'<a href='.'"'.'http://10.112.129.170/qgis-ck/tmp/archive/'.$selectedCity.'/cc/'."'".'||tmp_x.table_id||'."'".'" target="_blank">посилання на архів</a>'."'";

      if (file_exists("/tmp/".$selectedCity."_cable_channels.csv'")) {
        $linkStorage = "'/tmp/".$selectedCity."_cable_channels.csv''";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_cable_channels.csv'" ;
        $dir = "/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/";
        $files = scandir($dir);
      }
      //echo $linkStorage;
      //echo '<hr>'. file_exists($linkStorage);


           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=simpleuser password=simplepassword";

           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
            // echo "Opened database successfully\n";
	}

    if ($files) {
    foreach($files as $file) {
      $str_file = (string)$file;
      if ($str_file !== '.' && $str_file !== '..') {
            print_r($str_file);
            if ($str_file == $selectedCity."_cable_channels.csv") {
                 $sql = "CREATE TEMP TABLE tmp_x(id serial, summ_tu varchar(400), summ_contract_sum varchar(100), summ_sub_contract varchar(100), summ_acceptance_act varchar(100), summ_approval_cartogram varchar(100), summ_route_description text, summ_cable_type varchar(100), summ_archive_link varchar(300), table_id varchar(100), cable_progect_link varchar(100), tu_number varchar(300), tu_date varchar(100), rental_contract_new_num varchar(100), rental_contract_new_edoc varchar(100), rental_contract_new_add_num varchar(100), rental_contract_new_add_edoc varchar(100), acceptance_act_num varchar(100), acceptance_act_date varchar(100), cartogram_num varchar(100), cartogram_date varchar(100), cable_mount_date varchar(100), rental_contract_old_num varchar(100), rental_contract_old_edoc varchar(100), rental_contract_old_add_num varchar(100), rental_contract_old_add_edoc varchar(100),approval_cartogram_num varchar(100),   approval_cartogram_date varchar(100), cable_Ukrtelefon_id varchar(100), cable_type varchar(100), cable_short_type_description varchar(100), cable_description varchar(100), cable_description_fact varchar(100), cable_diameter varchar(100), cable_rental_price_k varchar(100), progect_number varchar(100),executive_doc_state varchar(100), notes1 varchar(100), cubic_start_house_id varchar(100), cubic_start_street varchar(100), cubic_start_house_num varchar(100), cubic_start_house_entrance_num varchar(100), contract_start_address varchar(100), contract_start_pit varchar(100), link_fiber_welding_start varchar(100),geom_start_pit geometry, cubic_end_house_id varchar(100), cubic_end_street varchar(100), cubic_end_house_num varchar(100), cubic_end_house_entrance_num varchar(100), contract_end_address varchar(100), contract_end_pit varchar(100), link_fiber_welding_end varchar(100), geom_end_pit geometry, contract_chanel_length varchar(100),   cable_length_house varchar(100), other_contract_channel_length varchar(100), total_cable_length varchar(100), geom_cable_channel geometry, geom_cable geometry, cable_rental_price varchar(100), notes2 varchar(100), rezerve1 varchar(100),  rezerve2 varchar(400),  rezerve3 varchar(100),  rezerve4 varchar(100),  rezerve5 varchar(100), rezerve6 varchar(100),   rezerve7 varchar(100),rezerve8 varchar(100), rezerve9 varchar(100), rezerve10 varchar(100)); select copy_for_testuser('tmp_x (summ_tu, summ_contract_sum , summ_sub_contract, summ_acceptance_act, summ_approval_cartogram, summ_route_description, summ_cable_type, summ_archive_link, table_id, cable_progect_link , tu_number , tu_date , rental_contract_new_num , rental_contract_new_edoc , rental_contract_new_add_num , rental_contract_new_add_edoc , acceptance_act_num , acceptance_act_date , cartogram_num , cartogram_date , cable_mount_date , rental_contract_old_num , rental_contract_old_edoc , rental_contract_old_add_num , rental_contract_old_add_edoc , approval_cartogram_num , approval_cartogram_date , cable_Ukrtelefon_id , cable_type , cable_short_type_description , cable_description , cable_description_fact , cable_diameter , cable_rental_price_k , progect_number , executive_doc_state , notes1 , cubic_start_house_id ,  cubic_start_street , cubic_start_house_num , cubic_start_house_entrance_num , contract_start_address , contract_start_pit ,   link_fiber_welding_start , geom_start_pit , cubic_end_house_id , cubic_end_street , cubic_end_house_num ,   cubic_end_house_entrance_num ,  contract_end_address , contract_end_pit , link_fiber_welding_end , geom_end_pit , contract_chanel_length , cable_length_house , other_contract_channel_length , total_cable_length , geom_cable_channel , geom_cable ,  cable_rental_price , notes2 , rezerve1 , rezerve2 , rezerve3 ,  rezerve4 , rezerve5 , rezerve6 , rezerve7 , rezerve8 , rezerve9 , rezerve10 )', ".$linkStorage.", ';', 'windows-1251'); UPDATE ".$selectedCity.".".$selectedCity."_cable_channels SET summ_tu = tmp_x.summ_tu, summ_contract_sum = tmp_x.summ_contract_sum, summ_sub_contract = tmp_x.summ_sub_contract, summ_acceptance_act = tmp_x.summ_acceptance_act, summ_approval_cartogram = tmp_x.summ_approval_cartogram, summ_route_description = tmp_x.summ_route_description, summ_cable_type = tmp_x.summ_cable_type, summ_archive_link =".$linkArchive.", table_id = tmp_x.table_id, cable_progect_link = tmp_x.cable_progect_link, tu_number = tmp_x.tu_number, tu_date = tmp_x.tu_date, rental_contract_new_num = tmp_x.rental_contract_new_num, rental_contract_new_edoc = tmp_x.rental_contract_new_edoc, rental_contract_new_add_num = tmp_x.rental_contract_new_add_num, rental_contract_new_add_edoc = tmp_x.rental_contract_new_add_edoc, acceptance_act_num = tmp_x.acceptance_act_num, acceptance_act_date = tmp_x.acceptance_act_date, cartogram_num = tmp_x.cartogram_num, cartogram_date = tmp_x.cartogram_date, cable_mount_date = tmp_x.cable_mount_date, rental_contract_old_num = tmp_x.rental_contract_old_num, rental_contract_old_edoc = tmp_x.rental_contract_old_edoc, rental_contract_old_add_num = tmp_x.rental_contract_old_add_num, rental_contract_old_add_edoc = tmp_x.rental_contract_old_add_edoc, approval_cartogram_num = tmp_x.approval_cartogram_num,  approval_cartogram_date = tmp_x.approval_cartogram_date, cable_Ukrtelefon_id = tmp_x.cable_Ukrtelefon_id, cable_type = tmp_x.cable_type,  cable_short_type_description = tmp_x.cable_short_type_description, cable_description = tmp_x.cable_description, cable_description_fact = tmp_x.cable_description_fact,  cable_diameter = tmp_x.cable_diameter, cable_rental_price_k = tmp_x.cable_rental_price_k, progect_number = tmp_x.progect_number,  executive_doc_state = tmp_x.executive_doc_state, notes1 = tmp_x.notes1, cubic_start_house_id = tmp_x.cubic_start_house_id, cubic_start_street = tmp_x.cubic_start_street, cubic_start_house_num = tmp_x.cubic_start_house_num, cubic_start_house_entrance_num = tmp_x.cubic_start_house_entrance_num, contract_start_address = tmp_x.contract_start_address, contract_start_pit = tmp_x.contract_start_pit, link_fiber_welding_start = tmp_x.link_fiber_welding_start, cubic_end_house_id = tmp_x.cubic_end_house_id, cubic_end_street = tmp_x.cubic_end_street, cubic_end_house_num = tmp_x.cubic_end_house_num, cubic_end_house_entrance_num = tmp_x.cubic_end_house_entrance_num, contract_end_address = tmp_x.contract_end_address, contract_end_pit = tmp_x.contract_end_pit, link_fiber_welding_end = tmp_x.link_fiber_welding_end, contract_chanel_length = tmp_x.contract_chanel_length, cable_length_house = tmp_x.cable_length_house, other_contract_channel_length = tmp_x.other_contract_channel_length, total_cable_length = tmp_x.total_cable_length, cable_rental_price = tmp_x.cable_rental_price, notes2 = tmp_x.notes2, rezerve1 = tmp_x.rezerve1, rezerve2 = tmp_x.rezerve2, rezerve3 = tmp_x.rezerve3, rezerve4 = tmp_x.rezerve4, rezerve5 = tmp_x.rezerve5, rezerve6 = tmp_x.rezerve6, rezerve7 = tmp_x.rezerve7, rezerve8 = tmp_x.rezerve8, rezerve9 = tmp_x.rezerve9, rezerve10 = tmp_x.rezerve10 FROM  tmp_x WHERE " . $selectedCity.".".$selectedCity."_cable_channels.table_id = tmp_x.table_id;DROP TABLE tmp_x;  ";
                  $ret = pg_query($db, $sql);
                  } else {
                    echo ' error cannot update data ';
                  }
            }
          } 

        }

	pg_close($db); // Closing Connection
	print( $sql);
?>

