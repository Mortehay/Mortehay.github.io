<?php
//ini_set('display_errors', 1);
//ini_set('max_execution_time', 0);
          
	 $selectedCity= $_POST['ctv_city_topology_load_eng'];

      if (file_exists("/tmp/".$selectedCity."_ctv_topology.csv")) {
        $linkStorage = "'/tmp/".$selectedCity."_ctv_topology.csv'";
        $dir = sys_get_temp_dir();
        $files = scandir($dir);  
      } else {
        $linkStorage = "'/var/www/QGIS-Web-Client-master/site/csv/archive/".$selectedCity."/".$selectedCity."_ctv_topology.csv'" ;
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
	
            //-----------------------------------------------------------------
            if ($files) {
              foreach($files as $file) {
                $str_file = (string)$file;
                if ($str_file !== '.' && $str_file !== '..') {
                      //print_r($str_file);
                      if ($str_file == $selectedCity."_ctv_topology.csv") {
                        //print_r($str_file);
                        $data_upload = "CREATE TEMP TABLE temp(id serial, CITY character varying(100),STREET character varying(100),HOUSE character varying(100),FLAT character varying(100),CODE character varying(100),NAME character varying(100),PGS_ADDR character varying(100),OU_OP_ADDR character varying(100),OU_CODE character varying(100),DATE_REG character varying(100),COMENT character varying(100),UNAME character varying(100),NET_TYPE character varying(100),HOUSE_ID character varying(100)); select copy_for_testuser('temp(CITY, STREET, HOUSE, FLAT, CODE, NAME, PGS_ADDR, OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID)', ".$linkStorage.", ';', 'windows-1251') ; UPDATE ".$selectedCity.".".$selectedCity."_ctv_topology SET cubic_city = temp.CITY, cubic_street = temp.STREET, cubic_house = temp.HOUSE, cubic_flat = temp.FLAT, cubic_code = temp.CODE, cubic_name = temp.NAME, cubic_pgs_addr = temp.PGS_ADDR, cubic_ou_op_addr = temp.OU_OP_ADDR, cubic_ou_code = temp.OU_CODE, cubic_date_reg = temp.DATE_REG, cubic_coment = temp.COMENT, cubic_uname = temp.UNAME, cubic_net_type = temp.NET_TYPE, cubic_house_id = temp.HOUSE_ID FROM  temp WHERE " . $selectedCity.".".$selectedCity."_ctv_topology.cubic_code = temp.CODE; INSERT INTO " . $selectedCity.".".$selectedCity."_ctv_topology(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name, cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id) SELECT CITY,STREET,HOUSE,FLAT,CODE,NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL);";
                        $data_upload_query = pg_query($db, $data_upload);
                       print($data_upload);
                       
                      }
                  }
                }
              }

           
}

pg_close($db); // Closing Connection
	
?>

