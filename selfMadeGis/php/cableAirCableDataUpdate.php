<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['cable_air_cable_data_city_eng'];  
	$linkArchive = "'".'<a href='.'"'.'http://77.121.192.25/qgis-ck/tmp/archive/'.$selectedCity.'/air/'."'".'||tmp.progect_number||'."'".'" target="_blank">посилання на архів</a>'."'";
      $linkStorage = "'/tmp/".$selectedCity."_cable_air.csv'";
      $dir = sys_get_temp_dir();
      $files = scandir($dir);
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
            if ($str_file == $selectedCity."_cable_air.csv") {
                 $sql = "CREATE temp TABLE tmp (id serial,table_id varchar(100), cable_progect_link varchar(100), cable_mount_date varchar(100), cable_id varchar(100), cable_type varchar(100), cable_short_type_description varchar(100), cable_description varchar(100),  progect_number varchar(100), executive_doc_state varchar(100), cable_purpose varchar(100),  cubic_start_house_id varchar(100), cubic_start_street varchar(100), cubic_start_house_num varchar(100),  cubic_start_house_entrance_num varchar(100), link_fiber_welding_start varchar(100), geom_start_point geometry,  cubic_end_house_id varchar(100),  cubic_end_street varchar(100), cubic_end_house_num varchar(100),  cubic_end_house_entrance_num varchar(100),  link_fiber_welding_end varchar(100), geom_end_point geometry, total_cable_length varchar(100), geom_cable geometry, notes2 varchar(100), rezerve1 varchar(100),  rezerve2 varchar(100),  rezerve3 varchar(100),  rezerve4 varchar(100), rezerve5 varchar(100), rezerve6 varchar(100), rezerve7 varchar(100), rezerve8 varchar(100), rezerve9 varchar(100), rezerve10 varchar(100)  ); select copy_for_testuser('tmp (table_id , cable_progect_link , cable_mount_date ,  cable_id , cable_type , cable_short_type_description , cable_description ,  progect_number , executive_doc_state , cable_purpose , cubic_start_house_id , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num , link_fiber_welding_start ,  geom_start_point ,  cubic_end_house_id , cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , link_fiber_welding_end , geom_end_point , total_cable_length , geom_cable , notes2 , rezerve1 , rezerve2 , rezerve3 , rezerve4 , rezerve5 , rezerve6 , rezerve7 , rezerve8 , rezerve9 , rezerve10  )', ".$linkStorage.", ';', 'windows-1251') ;  UPDATE ".$selectedCity.".".$selectedCity."_cable_air SET  cable_progect_link  = ".$linkArchive.",cable_mount_date   = tmp.cable_mount_date  , cable_id   = tmp.cable_id  , cable_type   = tmp.cable_type  , cable_short_type_description   = tmp.cable_short_type_description  , cable_description   = tmp.cable_description  , progect_number   = tmp.progect_number  , executive_doc_state   = tmp.executive_doc_state  , cable_purpose  = tmp.cable_purpose , cubic_start_house_id   = tmp.cubic_start_house_id  , cubic_start_street   = tmp.cubic_start_street  , cubic_start_house_num   = tmp.cubic_start_house_num  , cubic_start_house_entrance_num   = tmp.cubic_start_house_entrance_num  , link_fiber_welding_start   = tmp.link_fiber_welding_start  ,  cubic_end_house_id   = tmp.cubic_end_house_id  , cubic_end_street   = tmp.cubic_end_street  , cubic_end_house_num   = tmp.cubic_end_house_num  , cubic_end_house_entrance_num   = tmp.cubic_end_house_entrance_num  , link_fiber_welding_end   = tmp.link_fiber_welding_end  ,  total_cable_length   = tmp.total_cable_length  ,  notes2   = tmp.notes2  , rezerve1   = tmp.rezerve1  , rezerve2   = tmp.rezerve2  , rezerve3   = tmp.rezerve3  , rezerve4   = tmp.rezerve4  , rezerve5   = tmp.rezerve5  , rezerve6   = tmp.rezerve6  , rezerve7   = tmp.rezerve7  , rezerve8   = tmp.rezerve8  , rezerve9   = tmp.rezerve9  , rezerve10   = tmp.rezerve10 FROM tmp WHERE " . $selectedCity.".".$selectedCity."_cable_air.table_id = tmp.table_id;DROP TABLE tmp;";
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