<?php

function postgres_to_php_array($postgresArray) {

   $postgresStr = trim($postgresArray,"{}");
    $elmts = explode(",",$postgresStr);
    return $elmts;

  }

//-------------------------------------------
header("Access-Control-Allow-Origin: *");
ini_set('display_errors', 1);
   $host        = "host=127.0.0.1";
   $port        = "port=5432";
   $dbname      = "dbname=postgres";
   $credentials = "user=postgres password=postgres";

 $db = pg_connect( "$host $port $dbname $credentials"  );
   if(!$db){
      echo "Error : Unable to open database\n";
   } else {
      //echo "Opened database successfully\n";
   }
 $sql = "SELECT id, city, links, links_description   FROM public.links WHERE links IS NOT NULL ORDER BY city";
 $ret = pg_query($db, $sql);
   if(!$ret){
      echo pg_last_error($db);
   } else {
      //echo "Records created successfully\n";
   }

   
   //$arr = pg_fetch_all($ret);

      // $geojson = array( 'type' => 'FeatureCollection', 'features' => array());
       $json = array();
    
       
         while ($row = pg_fetch_row($ret))  {

          
              $arr = array(
                  
                    'id' => (int)$row[0],
                    'city'=>$row[1],
                    'links'=> postgres_to_php_array($row[2]), //$row[2],postgres_to_php_array($row[2])
                    'links_description'=>postgres_to_php_array($row[3]),
                   
              );
              //echo $row[2] ;
            array_push($json, $arr);

          
              
        }
   

   print json_encode($json);

   pg_close($db);
?>