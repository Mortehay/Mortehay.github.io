<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['switches_state_city_eng'];  
	//$linkArchive = "'".'<a href='.'"'.'http://77.121.192.25/qgis-ck/tmp/archive/'.$selectedCity.'/air/'."'".'||tmp.progect_number||'."'".'" target="_blank">посилання на архів</a>'."'";
      //$linkStorage = "'/tmp/".$selectedCity."_cable_air.csv'";
//$selectedCity= 'chernivtsi'; 
$linkStorage = "'/tmp/".$selectedCity."_state_switches.csv'";
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
            if ($str_file == $selectedCity."_state_switches.csv") {
                /* $sql = "CREATE temp TABLE tmp (id serial,ip_sw varchar(50), mac varchar(50), state varchar(50), state_time varchar(50) ); select copy_for_testuser('tmp (ip_sw , mac , state ,  state_time)', ".$linkStorage.", ';', 'utf8') ;UPDATE tmp SET mac= substring(mac from 1 for 2)||substring(mac from 4 for 2)||substring(mac from 7 for 2)||substring(mac from 10 for 2)||substring(mac from 13 for 2)||substring(mac from 16 for 2) WHERE position(':' in mac) <> 0;  UPDATE ".$selectedCity.".".$selectedCity."_switches SET online_status   = tmp.state, online_last_date_in_network = tmp.state_time FROM tmp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_mac_address = tmp.mac;DROP TABLE tmp;";*/
                 $sql = "CREATE temp TABLE tmp (id serial,ip_sw varchar(50), mac varchar(50), state varchar(50), state_time varchar(50) ); select copy_for_testuser('tmp (ip_sw , mac , state ,  state_time)', ".$linkStorage.", ';', 'utf8') ;UPDATE tmp SET state_time = to_timestamp(cast(state_time as double precision)); UPDATE ".$selectedCity.".".$selectedCity."_switches SET online_status   = tmp.state, online_last_date_in_network = tmp.state_time FROM tmp WHERE " . $selectedCity.".".$selectedCity."_switches.cubic_ip_address = tmp.ip_sw;DROP TABLE tmp;";
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