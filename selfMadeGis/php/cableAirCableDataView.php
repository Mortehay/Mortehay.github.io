<?php
//ini_set('display_errors', 1);

 	$selectedCity= $_POST['cable_air_cable_dataView_city_eng'];  

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

 
                 $sql = "SELECT table_id , cable_progect_link , cable_mount_date ,  cable_type , cable_short_type_description , cable_description ,  progect_number ,  cable_purpose , cubic_start_street , cubic_start_house_num,  cubic_start_house_entrance_num ,    cubic_end_street , cubic_end_house_num ,  cubic_end_house_entrance_num , total_cable_length   FROM ".$selectedCity.".".$selectedCity."_cable_air WHERE cable_type IS NOT NULL AND cable_short_type_description IS NOT NULL AND geom_cable IS NOT NULL AND  progect_number  IS NOT NULL ORDER BY trim(leading 't_' from table_id)::int;  ";
                  $ret = pg_query($db, $sql);
                  $arr_response = array('response' => array());
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
                              'table_id' => $row[0],
                              'cable_progect_link' => $row[1],
                              'cable_mount_date' => $row[2],
                              'cable_type' => $row[3],
                              'cable_short_type_description' => $row[4],
                              'cable_description' => $row[5],
                              'progect_number' => $row[6],
                              'cable_purpose' => $row[7],
                              'rote_description' => $row[8].",буд.№ ".$row[9].",під.№ ".$row[10]." - ".$row[11].",буд.№ ".$row[12].",під.№ ".$row[13] ,
                              'total_cable_length' => $row[14]
                              //AIRcablesList:['id кабеля', 'Посилання на архів','Дата монтажа кабелю','Тип кабелю','Волоконність/Тип','Марка кабелю','№проекту', 'Призначення','Опис маршруту', 'Довжина, км']
                            );
                          //print_r( $arr);
                          array_push($arr_response['response'], $arr );
                          //array_push($arr_response, $arr);
                        }
                  }

     
      
     

	
	print json_encode($arr_response);
	pg_close($db); // Closing Connection
	//print( $sql);
?>

