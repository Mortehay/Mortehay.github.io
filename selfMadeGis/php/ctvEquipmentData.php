<?php
//ini_set('display_errors', 1);
        
 	//$selectedCity= $_POST['city'];  
       //$cubic_code = $_POST['cubic_code'];

        if ($_POST['city']) {
        $selectedCity= $_POST['city'];
      } else {
        $selectedCity = $_REQUEST["city"];
      } 
       if ($_POST['cubic_code']) {
        $cubic_code= $_POST['cubic_code'];
      } else {
        $cubic_code = $_REQUEST["cubic_code"];
      } 
       //$selectedCity= 'ukrainka';  
       //$cubic_code = "'4508106'";
        function groupSelect($cubic_name){
            $group_value = array(0, '#DC143C',null,null);
            if ($cubic_name == 'Оптический узел') { $group_value = array( 1, '#ff9900', 60, 'nod');}
            if ($cubic_name == 'Оптичний приймач') { $group_value = array(2, '#663300', 60, 'op');}
            if ($cubic_name == 'Магістральний оптичний вузол') { $group_value = array( 3, '#3333cc', 90, 'mnod');}
            if ($cubic_name == 'Передатчик оптический') { $group_value = array( 4, '#333399', 90, 'ot');}
            if ($cubic_name == 'Магистральный распределительный узел') { $group_value = array( 5, '#ff0000', 80, 'mdod');}
            if ($cubic_name == 'Кросс-муфта') { $group_value = array( 6, '#ff0066', 60, 'cc');}
        return $group_value;
      }
         function checkIfFileExist($selectedCity, $cubic_name, $cubic_code, $archiveLink, $imgLink){
          $group_value = array();
          $cubic_name = groupSelect($cubic_name)[3];
          $xlsFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xls';
          $xlsxFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xlsx';
          $imgFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.png';
          //echo '$xlsFile - '.$xlsFile.'<hr>';
          //echo '$xlsxFile - '.$xlsxFile.'<hr>';
          //echo '$imgFile - '.$imgFile.'<hr>';
          if (file_exists($xlsxFile) || file_exists($xlsFile)) {
            $group_value['archiveLink'] =  $archiveLink;
          } else {$group_value['archiveLink'] =  null; }
          if (file_exists($imgFile)) {
            $group_value['imgLink'] =  $imgLink;
          } else {$group_value['imgLink'] =  null; }
          return $group_value;
         } 
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
            $json = array( 'equipment' => array());

                $sql = "SELECT cubic_city, cubic_street, cubic_house, cubic_name, cubic_code, cubic_ou_code, cubic_ou_name, cubic_ou_street, cubic_ou_house,  cubic_coment, archive_link, link   FROM ".$selectedCity.".".$selectedCity."_ctv_topology WHERE  cubic_code ='".$cubic_code."';";
                $ret = pg_query($db, $sql);
                  
                  if($ret) {

                         while ($row = pg_fetch_row($ret))  {

                          $arr = array(
                              'cubic_city' => $row[0],
                              'cubic_coment' => $row[9],
                              'cubic_name' => $row[3],
                              'cubic_code' => $row[4],
                              'cubic_street' => $row[1],
                              'cubic_house' => $row[2],
                              'cubic_ou_name' => $row[6],
                              'cubic_ou_code' => $row[5],
                              'cubic_ou_street' => $row[7],
                              'cubic_ou_house' => $row[8], 
                              'archive_link' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['archiveLink']/*$row[10]*/, 
                              'link' => checkIfFileExist($selectedCity, $row[3], $row[4], $row[10], $row[11])['imgLink'] /*$row[11]*/
                            );
                          
                        }
                      }


                          array_push($json['equipment'], $arr);
                 }   

	pg_close($db); // Closing Connection
	//print( $sql);
       print json_encode($json);
?>

