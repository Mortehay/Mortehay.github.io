<?php
ini_set('display_errors', 1);
$test = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100)); copy temp(CITY,REGION) from  '/tmp/volochisk_buildings.csv' csv header delimiter ',' encoding 'UTF-8' ; select * from temp;";
#$test1 = "COPY temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE) from  '/sl/1.csv' csv header delimiter ',';";

           $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=Xjrjkzlrf30";
           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
             echo "Opened database successfully\n";

             	  $ret = pg_query($db, $test);


	                      if($ret) {

	                         while ($row = pg_fetch_row($ret))  {


			print_r($row);


	                        }

	                      }
	
	
	
#	             	  $ret1 = pg_query($db, $test1);
#
#
#	                      if($ret1) {
#
#	                         while ($row1 = pg_fetch_row($ret1))  {
#
#
#			print_r($row1);
#
#
#                    }
#
#	                      }
	
	
	
	
	}
?>