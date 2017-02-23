<?php
ini_set('display_errors', 1);
$test = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),REGION character varying(100),DISTR_NEW character varying(100),STREET character varying(100),HOUSE character varying(100),COMM character varying(100),CSD character varying(100),HOUSE_TYPE character varying(100),USO character varying(100),LNAME character varying(100),LADRESS character varying(100),HPNAME character varying(100),HPADRESS character varying(100),HPCODE character varying(100),FREQ character varying(100),DATE_BUILDING character varying(100),DATE_BUILDING_ETH character varying(100),DATE_CT character varying(100),SEGMENT character varying(100),DIGITAL_SEGMENT character varying(100),DIGITAL_STAGE character varying(100),DIGITAL_DATE character varying(100),SUBDEP character varying(100),BOX_TYPE character varying(100),HOUSE_ID character varying(100),SECTOR_CNT character varying(100),CNT character varying(100),PARNET character varying(100),SERV_PARNET character varying(100),NETTYPE character varying(100),CNT_ATV character varying(100),CNT_VBB character varying(100),CNT_ETH character varying(100),CNT_DOCSIS character varying(100),CNT_KTV character varying(100),CNT_ACTIVE_CONTR character varying(100),MAX_SPEED_ETHERNET character varying(100),MAX_SPEED_DOCSIS character varying(100),REPORT_DATE character varying(100)); copy temp(CITY,REGION,DISTR_NEW,STREET,HOUSE,COMM,CSD,HOUSE_TYPE,USO,LNAME,LADRESS,HPNAME,HPADRESS,HPCODE,FREQ,DATE_BUILDING,DATE_BUILDING_ETH,DATE_CT,SEGMENT,DIGITAL_SEGMENT,DIGITAL_STAGE,DIGITAL_DATE,SUBDEP,BOX_TYPE,HOUSE_ID,SECTOR_CNT,CNT,PARNET,SERV_PARNET,NETTYPE,CNT_ATV,CNT_VBB,CNT_ETH,CNT_DOCSIS,CNT_KTV,CNT_ACTIVE_CONTR,MAX_SPEED_ETHERNET,MAX_SPEED_DOCSIS,REPORT_DATE) from  '/var/www/QGIS-Web-Client-master/site/csv/cubic/_buildings/volochisk_buildings.csv' csv header delimiter ',' encoding 'UTF-8' ; select * from temp;";
	


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
	}
?>