<?php
//ini_set('display_errors', 1);

$restriction=NULL;
//$option = '';
include('login.php'); 
$option = '';
$accordion='';
$tools ='';
$user='simpleuser';
 $restriction = $_GET["restriction"];
 //echo $restriction;
  if ($restriction != NULL) {
  $restriction=json_encode($restriction);
  print "<script type='text/javascript'>console.log(".$restriction.");</script>";
  }
  $host        = "host=127.0.0.1";
           $port        = "port=5432";
           $dbname      = "dbname=postgres";
           $credentials = "user=postgres password=Xjrjkzlrf30";
           $city_array = array();
           $db = pg_connect( "$host $port $dbname $credentials"  );
             if(!$db){
                echo "Error : Unable to open database\n";
             } else {
                //echo "Opened database successfully\n";
             
            $restriction = $_GET["restriction"];
              if ($restriction != NULL && $restriction =="admin") {
              
              //echo $restriction;
              
              //echo $sql;
              }
            $sql = "SELECT id, city, links, links_description, city_eng   FROM public.links WHERE links IS NOT NULL ORDER BY city";

           

              $ret = pg_query($db, $sql);
              $rows = pg_num_rows($ret);

				//echo $rows . " row(s) returned.\n";
              
              if(!$ret){
                  echo pg_last_error($db);
              } else {
                while ($row = pg_fetch_row($ret))  {
                      $city_array[] = $row[4];
                }
             }
             $sql_new='REVOKE ALL ON DATABASE postgres FROM public; GRANT CONNECT ON DATABASE postgres TO '.$user.'; GRANT USAGE ON SCHEMA public TO '.$user.';GRANT ALL ON ALL TABLES IN SCHEMA public TO '.$user.'; GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA public GRANT ALL ON TABLES TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA public GRANT ALL ON SEQUENCES TO '.$user.'; ';
             print_r($city_array);
			foreach ($city_array as $key => $value) {
			    $sql_new .='GRANT USAGE ON SCHEMA '.$city_array[$key].' to '.$user.'; GRANT ALL ON ALL TABLES IN SCHEMA '.$city_array[$key].' TO '.$user.'; GRANT ALL ON ALL SEQUENCES IN SCHEMA '.$city_array[$key].' TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key].' GRANT ALL ON TABLES TO '.$user.'; ALTER DEFAULT PRIVILEGES FOR ROLE '.$user.' IN SCHEMA '.$city_array[$key].' GRANT ALL ON SEQUENCES TO '.$user.';  ';
			}
			//echo $sql;
			$query = pg_query($db, $sql_new);
          }

     pg_close($db); // Closing Connection     
     print($sql_new);

         
?>