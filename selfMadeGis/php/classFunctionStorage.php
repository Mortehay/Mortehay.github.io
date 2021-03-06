<?php
//ini_set('display_errors', 1);
//echo 'im here';
//usefull variables/////////////////////////////////////////////////////////////////////////
$connLSetings = array(
    "host"=>"host=127.0.0.1",
    "port"=>"port=5432",
    "dbname"=>"dbname=postgres",
    "user"=>"user=postgres ",
    "password"=>"password=Xjrjkzlrf30"
    );
//classes///////////////////////////////////////////////////////////////////////////////////
///postgresql///////////////////////////////////////////////////////////////////////////////
class dbConnSetClass{
  private $outerIp = '10.112.129.170';
  private $shareAddress = '//10.112.129.165/share/';
  private $dbConnSet = array(
    "host"=>"host=127.0.0.1",
    "port"=>"port=5432",
    "dbname"=>"dbname=postgres",
    "user"=>"user=simpleuser",
    "password"=>"password=proleEmploymentPassword"
    );
  private $dbConnSetReader = array(
    "host"=>"host=127.0.0.1",
    "port"=>"port=5432",
    "dbname"=>"dbname=postgres",
    "user"=>"user=simplereader",
    "password"=>"password=ghostUserPassword"
    );
  private $selectedCity = 'none';
  private $queryArrayKeys = array();
  public function setProp($prop,$newValue){
    if (property_exists($this,$prop)){
      $this->$prop = $newValue;
    } else {
      echo "Setting of Undefined Property";
    }
  }
  public function getProp($prop){
    if (property_exists($this,$prop)){
      return $this->$prop;
    } else {
      echo "Gettin of Undefined Property";
    }
  }
  public function dbConnect($query,$queryArrayKeys,$dbClose){
    $db = pg_connect( implode(" ", $this->dbConnSet));
    if (!$db) {
      echo "database Opening failed\n";
    } else {
      $result = pg_query($db, $query);
      if ($queryArrayKeys) {
        $arr_response = array();
        if($result){
            while ($row = pg_fetch_row($result)){
              $arr = array();
              foreach ($row as $key => $value) {
                $arr[$queryArrayKeys[$key]] = $row[$key];
              }
              array_push($arr_response, $arr );
            }
          } else {
            $arr[0] =  'no new data or equipment';
            array_push($arr_response, $arr );
          }
        //print json_encode($arr_response);
        return $arr_response;
      }
      
    }
    if ($dbClose) {
      pg_close($db); // Closing Connection
    }
  }
  public function siteLogin($e_mail, $password){
    $arr_response = self::dbConnect("SELECT e_mail, md5, restriction, map_links, file_links, user_type FROM public.access WHERE e_mail= '".$e_mail."';", array('e_mail', 'md5', 'restriction','map_links','file_links', 'user_type'), true);
    if(md5($password)===$arr_response[0]['md5']){
      self::dbConnect("INSERT INTO public.login(e_mail, login_time,ip_address) VALUES ('".$e_mail."',now(),'".$_SERVER['REMOTE_ADDR']."');",false,true);
      //login success
      session_start();
      $_SESSION['user_logged_in'] = true;
      $_SESSION['user_map_links'] =$arr_response[0]['map_links'];
      $_SESSION['user_file_links'] =$arr_response[0]['file_links'];
      $_SESSION['user_type'] = $arr_response[0]['user_type'];

      $folderTypes = array('/cc/','/air/','/she/','/topology/','/help/');
      self::htaccessFilesGeneration($folderTypes, 'allow');
      //store other stuff in the session like user settings and data
      header("location: main_page.php?restriction=".$arr_response[0]['restriction']."&e_mail=".$e_mail); // Redirecting To Other Page
      return true;
    } else {
       $msg = "wrong e-mail or password";
      header("location: ../index.php?msg=$msg");
    }
  }
  public function htaccessFilesGeneration($folderTypes, $allowedIps){
    $newDBrequest = new dbConnSetClass;
    $query = "select u_n.city, array_agg(u_n.ip_address) as ips from (select  distinct unnest(i_n.cities) as city,  i_n.ip_address from (select l.e_mail, l.login_time::date, l.ip_address, a.restriction, case when a.restriction = 'admin' then (select array_agg(city_eng) from public.links where links is not null) when a.restriction = 'full' then (select array_agg(city_eng) from public.links  where links is not null) when a.restriction in(select prime_city from public.links) then (select array_agg(city_eng) from public.links where links is not null and prime_city = a.restriction) when a.restriction = 'central' then (select array_agg(city_eng) from public.links  where links is not null and region = 'central') when a.restriction = 'eastern' then (select array_agg(city_eng) from public.links  where links is not null and region = 'eastern') when a.restriction = 'western' then (select array_agg(city_eng) from public.links  where links is not null and region = 'western') when a.restriction in(select distinct city_eng from public.links where city_eng is not null) then (select array_agg(city_eng) from public.links  where links is not null and city_eng = a.restriction) else null end as cities from public.login l join public.access a on l.e_mail = a.e_mail where l.login_time::date = now()::date group by l.e_mail, l.ip_address, l.login_time::date, a.restriction) i_n group by i_n.cities, i_n.ip_address) u_n group by u_n.city;";
    //echo $query;

    $queryArrayKeys = array('city', 'ips');
    
    $sumObjectsArray = self::dbConnect($query, $queryArrayKeys, true);
    //print_r($sumObjectsArray);
    $arr_response = array('response' => array());
    
    $query = "select 'city' as city, array_agg(sel.ip_address) from (select distinct ip_address from public.login where now()::date = login_time::date) sel group by city;";

    $queryArrayKeys = array('city', 'ips');
    
    $sumObjectsArrayHelp = self::dbConnect($query, $queryArrayKeys, true);

    foreach ($folderTypes as $folderType) {
      if($folderType == '/help/') {

        self::htaccessTextGeneration($sumObjectsArrayHelp,$allowedIps,$folderType);
      } else {
        self::htaccessTextGeneration($sumObjectsArray,$allowedIps,$folderType);
      }
               
      
    }
  }
  public function htaccessTextGeneration($sumObjectsArray,$allowedIps,$folderType){

    foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
      if($folderType == '/help/') {
        $folderLink = '/var/www/QGIS-Web-Client-master/site/tmp'.$folderType;
      } else {
        $folderLink = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$sumObjectsArray[$sumObjectsArrayKey]['city'].$folderType;
        //echo '<hr>'.$folderLink;
      }
      $ipsList ='';
        //$folderLink = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$sumObjectsArray[$sumObjectsArrayKey]['city'].$folderType;
        if(file_exists($folderLink)){
          //echo '<hr>';
          //echo $folderLink.'<br>';
          //print_r(postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['ips']));
          if($allowedIps == 'deny'){
            $ipsList ="Deny from all\n";
          } else if($allowedIps == 'allow')  {
            $ipsList ="Deny from all\n";
            foreach (postgres_to_php_array($sumObjectsArray[$sumObjectsArrayKey]['ips']) as $allowedIp) {
              $ipsList .="Allow from ".$allowedIp. "\n";
            }
          }
          //echo '<br>'.$ipsList.'<hr>';
          $accessFileTemplate = "RewriteEngine On
            RewriteBase /
            Options +Indexes
            Options +FollowSymLinks
            IndexOptions Charset=UTF8
            $ipsList
            <Files ~ '^.*\.([Hh][Tt][Aa])'>
              order allow,deny\n
              deny from all\n
            </Files>\n
            <IfModule mod_autoindex.c>
              IndexOptions IgnoreCase FancyIndexing FoldersFirst NameWidth=* DescriptionWidth=* XHTML HTMLtable SuppressHTMLPreamble SuppressRules SuppressLastModified
              IndexOrderDefault Ascending Name
              HeaderName /путь/dirlist_header.shtml
              ReadmeName /путь/dirlist_footer.shtml
              IndexIgnore .htaccess .ftpquota .DS_Store
            </IfModule>";
          //echo $accessFileTemplate.'<br>';
          //echo '<hr>';
          if(file_exists($folderLink.'.htaccess')){unlink($folderLink.'.htaccess');}
          $accessFile = fopen($folderLink.".htaccess", "w") or die("Unable to open file!");
          fwrite($accessFile, $accessFileTemplate);
          fclose($accessFile);
        }
    }
  } 

}
//////mail sender/////////////////////////////////////////////////////////////////////////////////////////
class mailSender{
  //private $mails = array();
  //private $cities = array();
  private $params = array();
  private $csv_out = array();
  private $queryModificator = array();
  public function setProp($prop,$newValue){
    if (property_exists($this,$prop)){
      $this->$prop = $newValue;
    } else {
      echo "Setting of Undefined Property";
    }
  }
  public function getProp($prop){
    if (property_exists($this,$prop)){
      return $this->$prop;
    } else {
      echo "Gettin of Undefined Property";
    }
  }
  public function test(){
    return $this->params;
  }
  public function mail_cities_users($_params){

      $today = self::dateReturn();
      
      $mailToArr = array();
      self::rrmdir($_params['mail_params']['dirPathMail']);

      $postgres = new dbConnSetClass;

      $query = "(select array_agg(links.city_eng) as city_eng, links.prime_city, access.e_mail as e_mail, access.mail_to as mail_to, access.restriction as restriction from public.links links   right join (select e_mail, restriction, mail_to from public.access) access on access.mail_to = links.prime_city where links.city_eng is not null group by links.prime_city, access.e_mail,access.mail_to, access.restriction) union (select array_agg(links.city_eng)  as city_eng, 'admin' as prime_city, access.e_mail as e_mail,access.mail_to as mail, access.restriction as restriction from public.access access, public.links links where access.mail_to in('admin') group by  access.e_mail, access.mail_to, access.restriction) union (select array_agg(links.city_eng) as city_eng, links.region as prime_city, access.e_mail as e_mail, access.mail_to as mail_to, access.restriction as restriction from public.links links   right join (select e_mail, restriction, mail_to from public.access) access on access.mail_to = links.region where links.city_eng is not null group by links.region,  access.e_mail,access.mail_to, access.restriction);";
      echo $query.'<hr>';
      $queryArrayKeys = array('city_eng', 'prime_city', 'e_mail', 'mail_to', 'restriction');
      $mailToArr = $postgres -> dbConnect($query, $queryArrayKeys, true);
      //print_r($mailToArr);
      foreach ($mailToArr as $mailToArrKey => $mailToArrValue) {
        $path = $_params['mail_params']['dirPathMail'].$mailToArrValue['prime_city'].'/';
        echo '<hr>'.$path.'<hr>';
        //print_r(self::postgres_to_php_array($mailToArrValue['city_eng']));
        $cities = self::postgres_to_php_array($mailToArrValue['city_eng']);
        self::newDirCreation($path);
        self::restriction_change($path);

        foreach ($cities as $selectedCity) {

          foreach($_params['query'] as $paramsKey =>$allParams) {


            
            if($paramsKey = '_ctv_topology'){
              $params = $_params['query'][$paramsKey]['query_params'];
              $queryModificator = $_params['query'][$paramsKey]['queryModificator'];
              $reserveDirPath = '/var/www/QGIS-Web-Client-master/site/csv/cubic/'.$paramsKey.'_daily_updates/'.$selectedCity.'/';
              self::newDirCreation($reserveDirPath);
              self::restriction_change($reserveDirPath);
              $linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$params['tableType']."/".$selectedCity.$params['tableType'].$params['fileExtention']."'";
              $query ="CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ;  create temp table csvTemp as (SELECT CITY,STREET, HOUSE, FLAT, CODE, NAME ,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID, 'missing' as state FROM temp WHERE CODE NOT IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ) UNION ALL (with data(CITY,STREET, HOUSE, FLAT, CODE, NAME,PGS_ADDR,OU_OP_ADDR,OU_CODE,DATE_REG,COMENT,UNAME,NET_TYPE,HOUSE_ID)  as (select CITY,STREET, HOUSE, FLAT, CODE, NAME, PGS_ADDR, OU_OP_ADDR, OU_CODE, DATE_REG, COMENT, UNAME, NET_TYPE, HOUSE_ID  from temp) select d.CITY, d.STREET, d.HOUSE, d.FLAT, d.CODE, d.NAME, d.PGS_ADDR, d.OU_OP_ADDR, d.OU_CODE, d.DATE_REG, d.COMENT, d.UNAME, d.NET_TYPE, d.HOUSE_ID, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_ctv_topology u where u.cubic_code = d.CODE and u.cubic_house_id = d.HOUSE_ID  and u.cubic_name = d.NAME) and CODE IN(SELECT cubic_code FROM ". $selectedCity.".".$selectedCity."_ctv_topology WHERE cubic_code IS NOT NULL) ) UNION ALL (with data(cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name,cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id)  as (select cubic_city, cubic_street, cubic_house, cubic_flat, cubic_code, cubic_name,cubic_pgs_addr, cubic_ou_op_addr, cubic_ou_code, cubic_date_reg, cubic_coment, cubic_uname, cubic_net_type, cubic_house_id  from ".$selectedCity.".".$selectedCity."_ctv_topology) select d.cubic_city, d.cubic_street, d.cubic_house, d.cubic_flat, d.cubic_code, d.cubic_name,d.cubic_pgs_addr, d.cubic_ou_op_addr, d.cubic_ou_code, d.cubic_date_reg, d.cubic_coment, d.cubic_uname, d.cubic_net_type, d.cubic_house_id, 'present state' as state from data d where not exists (select 1 from temp u where d.cubic_code = u.CODE and d.cubic_house_id = u.HOUSE_ID  and d.cubic_name = u.NAME) and cubic_code IN(SELECT CODE FROM temp WHERE CODE IS NOT NULL) ); update csvTemp set house = replace(house, '/', '\'); select copy_for_testuser_v2('csvTemp','TO','".$path.$selectedCity.$params['tableType'].'_'.$params['tableTypeSufix'].'_'.$today.$params['fileExtention']."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1); select copy_for_testuser_v2('csvTemp','TO','".$reserveDirPath.$selectedCity.$params['tableType'].'_'.$params['tableTypeSufix'].'_'.$today.$params['fileExtention']."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1);";
              echo $query.'<hr>';
              $postgres -> dbConnect($query, false, true);
            }
            if($paramsKey = '_switches'){
              $params = $_params['query'][$paramsKey]['query_params'];
              $queryModificator = $_params['query'][$paramsKey]['queryModificator'];
              $linkStorage ="'/var/www/QGIS-Web-Client-master/site/csv/cubic/".$params['tableType']."/".$selectedCity.$params['tableType'].$params['fileExtention']."'";
              $reserveDirPath = '/var/www/QGIS-Web-Client-master/site/csv/cubic/'.$paramsKey.'_daily_updates/'.$selectedCity.'/';
              self::newDirCreation($reserveDirPath);
              self::restriction_change($reserveDirPath);
              $query ="CREATE TEMP TABLE temp( ".$queryModificator['var']."); select copy_for_testuser('temp( ".$queryModificator['val']." )', ".$linkStorage.", ".$queryModificator['delimiter'].", ".$queryModificator['encoding'].") ;  create temp table csvTemp as (SELECT ID, MAC_ADDRESS,IP_ADDRESS, SERIAL_NUMBER,HOSTNAME, DEV_FULL_NAME, VENDOR_MODEL, SW_MODEL, SW_ROLE, HOUSE_ID,DOORWAY,LOCATION,FLOOR, SW_MON_TYPE, SW_INV_STATE,VLAN, DATE_CREATE, DATE_CHANGE, IS_CONTROL, IS_OPT82,PARENT_ID,PARENT_MAC, PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT,PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN, CITY_ID, CITY, CITY_CODE, REPORT_DATE, 'missing' as state FROM temp WHERE ID NOT IN(SELECT cubic_switch_id FROM ". $selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL) ) UNION ALL (with data(ID,MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,DEV_FULL_NAME,VENDOR_MODEL,SW_MODEL,SW_ROLE,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_MON_TYPE,SW_INV_STATE,VLAN,DATE_CREATE,DATE_CHANGE,IS_CONTROL,IS_OPT82,PARENT_ID,PARENT_MAC,PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT,PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN, CITY_ID, CITY, CITY_CODE, REPORT_DATE)  as (select ID,MAC_ADDRESS,IP_ADDRESS,SERIAL_NUMBER,HOSTNAME,DEV_FULL_NAME,VENDOR_MODEL,SW_MODEL,SW_ROLE,HOUSE_ID,DOORWAY,LOCATION,FLOOR,SW_MON_TYPE,SW_INV_STATE,VLAN,DATE_CREATE,DATE_CHANGE,IS_CONTROL,IS_OPT82,PARENT_ID,PARENT_MAC,PARENT_PORT, CHILD_ID, CHILD_MAC, CHILD_PORT,PORT_NUMBER, PORT_STATE, CONTRACT_CNT, CONTRACT_ACTIVE_CNT, GUEST_VLAN, CITY_ID, CITY, CITY_CODE, REPORT_DATE  from temp) select d.ID, d.MAC_ADDRESS, d.IP_ADDRESS, d.SERIAL_NUMBER, d.HOSTNAME, d.DEV_FULL_NAME, d.VENDOR_MODEL, d.SW_MODEL, d.SW_ROLE, d.HOUSE_ID, d.DOORWAY, d.LOCATION, d.FLOOR, d.SW_MON_TYPE, d.SW_INV_STATE, d.VLAN, d.DATE_CREATE, d.DATE_CHANGE, d.IS_CONTROL, d.IS_OPT82, d.PARENT_ID, d.PARENT_MAC, d.PARENT_PORT, d.CHILD_ID, d.CHILD_MAC, d.CHILD_PORT,d.PORT_NUMBER, d.PORT_STATE, d.CONTRACT_CNT, d.CONTRACT_ACTIVE_CNT, d.GUEST_VLAN, d.CITY_ID, d.CITY, d.CITY_CODE, d.REPORT_DATE, 'reused code' as rcode from data d where not exists (select 1 from ".$selectedCity.".".$selectedCity."_switches u where u.cubic_switch_id = d.ID and u.cubic_house_id = d.HOUSE_ID) and ID IN(SELECT cubic_switch_id FROM ". $selectedCity.".".$selectedCity."_switches WHERE cubic_switch_id IS NOT NULL) ); select copy_for_testuser_v2('csvTemp','TO','".$path.$selectedCity.$params['tableType'].'_'.$params['tableTypeSufix'].'_'.$today.$params['fileExtention']."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1); select copy_for_testuser_v2('csvTemp','TO','".$reserveDirPath.$selectedCity.$params['tableType'].'_'.$params['tableTypeSufix'].'_'.$today.$params['fileExtention']."', ';','CSV HEADER','windows-1251') WHERE (select true from csvTemp limit 1);";
              echo $query.'<hr>';
              $postgres -> dbConnect($query, false, true);
            }
          }
        }
        if (is_dir_empty($path) ){
            echo '<hr>it is empty<hr>';
          } else {

            $mail_filename = $mailToArrValue['prime_city'].'_'.$params['tableTypeSufix'].'_'.$today.'.zip';
            $mail_file_path = $path.$mail_filename;
            echo '<hr>'.$mail_filename.'<hr>';
            echo '<hr>'.$mail_file_path.'<hr>';
            //sleep(12*count($cities));
            self::restriction_change($path);
            self::zip_folder($path,$mail_file_path);
            self::mail_attachment( $mailToArrValue['e_mail'] , '', $mailToArrValue['prime_city'].'_'.$params['tableTypeSufix'].'_'.$today , $mailToArrValue['prime_city'].'_'.$params['tableTypeSufix'].'_'.$today, $mail_file_path, $mail_filename);
        }
      }
    

    return $mailToArr;
  }
  public function dateReturn(){
    date_default_timezone_set("Europe/Kiev");
    $today = getdate();
    $today = $today['hours'].'-'.$today['minutes'].'__'.$today['mday'].'-'.$today['month'].'-'.$today['year'];
    return $today;
  }
  public function newDirCreation($path){
    if (!file_exists($path )) {
      $oldmask = umask(0);
      mkdir($path , 0777, true);
      umask($oldmask);
      return true;
    }
  }
  public function postgres_to_php_array($postgresArray) {
      $postgresStr = trim($postgresArray,"{}");
      $elmts = explode(",",$postgresStr);
      return $elmts;
  }
  public function csv_creation(){
    return true;
  }
  public function make_dir_empty($dirPathMail){
    foreach(glob($dirPathMail.'*') as $file){ // iterate files
      if(is_file($file))
        unlink($file); // delete file
    }
  }
  public function rrmdir($dir) {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir."/".$object) == "dir") 
             self::rrmdir($dir."/".$object); 
          else unlink   ($dir."/".$object);
        }
      }
      reset($objects);
      rmdir($dir);
    }
   }
   public function zip_folder($dirPath,$zipFilePath){
    $zip = new ZipArchive;
    $zip->open($zipFilePath, ZipArchive::CREATE);
    foreach (glob($dirPath.'*') as $file) {
      $new_filename = end(explode('/',$file));
        $zip->addFile($file,$new_filename);
    }
    $zip->close();
  }
  public function restriction_change($path){
    if (!file_exists($path )) {
          $oldmask = umask(0);
          mkdir($path , 0777, true);
          umask($oldmask);
      }
  }
  public function mail_attachment( $to, $from,$subject , $message, $path, $filename){
 
    $to = $to;
    $from = $from;
    $subject = $subject;
    $message = $message; //Текст письма
    $boundary = "---"; //Разделитель

    $headers = "From: $from\nReply-To: $from\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
   
    if(($path !='') or ($filename !='')){
      $body = "--$boundary\n";

      $body .= "Content-type: text/html; charset='utf-8'\n";
      $body .= "Content-Transfer-Encoding: quoted-printablenn";
      $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
      $body .= mb_convert_encoding($message, 'UTF-8', 'auto')."\n";
      $body .= "--$boundary\n";
      $file = fopen($path, "r"); //Открываем файл
      $text = fread($file, filesize($path)); //Считываем весь файл
      fclose($file); //Закрываем файл
      $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n";
      $body .= "Content-Transfer-Encoding: base64\n";
      $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
      $body .= chunk_split(base64_encode($text))."\n";
      $body .= "--".$boundary ."--\n";
    } else {
      $body = $message;
    }
    

    
    mail($to, $subject, $body, $headers); //Отправляем письмо
  }

}
///file UPLOAD////////////////////////////////////////////////////////////////////////////////////////////
class fileUpload {
  private $restriction = 'none';
  public function dirCreate($city, $target_file, $file_name,$fileType){
    $newDirPath = false;
    if($fileType == 'csv'){ $newDirPath = '/var/www/QGIS-Web-Client-master/site/csv/archive/'.$city.'/';}
    if($fileType == 'gpx'){ $newDirPath = '/var/www/QGIS-Web-Client-master/site/gpx/archive/'.$city.'/';}
    if($fileType == 'kml'){ $newDirPath = '/var/www/QGIS-Web-Client-master/site/kml/archive/'.$city.'/';}           
    if($fileType == 'qgs'){ $newDirPath = '/var/www/QGIS-Web-Client-master/projects/';}
    if($fileType == 'qfts'){ $newDirPath = '/var/www/QGIS-Web-Client-master/searchfiles/';} 
    if((in_array($fileType, array('zip','rar'))) && (strpos(strtolower($file_name),'help_')!== false) && (in_array($city, array('', null, 'null','NULL')))){$newDirPath = '/var/www/QGIS-Web-Client-master/site/tmp/help/';} 
    if ($newDirPath) {
      if (!file_exists($newDirPath )) {
      $oldmask = umask(0);
          mkdir($newDirPath , 0777, true);
          umask($oldmask);
      }
      chmod($target_file, 0666);
      copy($target_file, $newDirPath . $file_name);
      $conSettings = new dbConnSetClass;
      if($fileType == 'qgs'){
        self::textExchange($conSettings->getProp('outerIp'),str_replace('host=', '', $conSettings->getProp('dbConnSet')['host']),$newDirPath . $file_name);
        self::textExchange('C:/темп/',$conSettings->getProp('shareAddress'),$newDirPath . $file_name);
        self::textExchange('C:/quickfinder/',$conSettings->getProp('shareAddress'),$newDirPath . $file_name);
      }
      
      //echo $dirPath;
      $newFilePath = $newDirPath.$file_name;
      
    }
    return $newFilePath;
  }
  public function textExchange($oldText,$newText,$targeFile){
    $str=file_get_contents($targeFile);
    $str=str_replace($oldText, $newText,$str);
    file_put_contents($targeFile, $str);
    return true;
  }
  public function fileReplace($fromDir,$toDir){
    scandir($fromDir);
  }
  public function newDirCreation($path){
    if (!file_exists($path )) {
      $oldmask = umask(0);
      mkdir($path , 0777, true);
      umask($oldmask);
      return true;
    }
  }
  public function maps_link_reloader(){
    include_once('cityVocabulary.php');
    $postgres = new dbConnSetClass;
    $fileNames = dir_files_names('/var/www/QGIS-Web-Client-master/projects/');
    $links =array('linkText' => array(), 'linkLink' => array(), 'city' => array());
    //print_r($fileNames);
    $insert_values = '';
    foreach ($cities as $city) {
      $linkText = '{';
      $linkLink = '{';
      foreach ($fileNames as $value) {
        if(strpos($value, $city[1]) !== false){

          if((substr_count($value,'_') >1 ) and (strpos($value, 'full') !== false) ){
            if(substr($linkText, -1) != '{'){ $linkText .= ',';}
            $linkText .='"'.'Повна карта міста - '.' '.$city[2].'"';
            if(substr($linkLink, -1) != '{'){ $linkLink .= ',';}
            $linkLink .='qgiswebclient.php?map=/var/www/QGIS-Web-Client-master/projects/'.$value;
            //echo 'Повна карта міста '.' '.$city[2].' - покриття'.'<hr>';
          } else if((substr_count($value,'_') >1 ) and (strpos($value, 'coverage') !== false) ){
            if(substr($linkText, -1) != '{'){ $linkText .= ',';}
            $linkText .='"'.'Карта покриття міста - '.' '.$city[2].'"';
            if(substr($linkLink, -1) != '{'){ $linkLink .= ',';}
            $linkLink .='qgiswebclient.php?map=/var/www/QGIS-Web-Client-master/projects/'.$value;
            //echo 'Повна карта міста '.' '.$city[2].' - покриття'.'<hr>';
          } else if((substr_count($value,'_') >1 ) and (strpos($value, 'customer_heatmap') !== false) ){
            if(substr($linkText, -1) != '{'){ $linkText .= ',';}
            $linkText .='"'.'Карта heatmap міста - '.' '.$city[2].'"';
            if(substr($linkLink, -1) != '{'){ $linkLink .= ',';}
            $linkLink .='qgiswebclient.php?map=/var/www/QGIS-Web-Client-master/projects/'.$value;
            //echo 'Повна карта міста '.' '.$city[2].' - покриття heatmap'.'<hr>';
          } else if((substr_count($value,'_') >1 ) and (strpos($value, 'luch') !== false) ){
            if(substr($linkText, -1) != '{'){ $linkText .= ',';}
            $linkText .='"'.'Карта лінійно-кабельного обліку міста -'.' '.$city[2].'"';
            if(substr($linkLink, -1) != '{'){ $linkLink .= ',';}
            $linkLink .='qgiswebclient.php?map=/var/www/QGIS-Web-Client-master/projects/'.$value;
            //echo 'Повна карта міста '.' '.$city[2].' - лініно-кабельний облік'.'<hr>';
          } else if((substr_count($value,'_') == 1 ) ){
            if(substr($linkText, -1) != '{'){ $linkText .= ',';}
            $linkText .='"'.'(файл для налагодження) Повна карта міста - '.' '.$city[2].'"';
            if(substr($linkLink, -1) != '{'){ $linkLink .= ',';}
            $linkLink .='qgiswebclient.php?map=/var/www/QGIS-Web-Client-master/projects/'.$value;
            //echo 'Повна карта міста '.' '.$city[2].'<hr>';
          }  
        }
      }
      if(substr($linkText, -1) != '{'){
        if(substr($linkText, -1) == ','){ $linkText = rtrim($linkText, ','); $linkText .= '}';} else { $linkText .= '}';}
        if(substr($linkLink, -1) == ','){ $linkText = rtrim($linkLink, ','); $linkLink .= '}';} else { $linkLink .= '}';}
        array_push($links['linkText'], $linkText); array_push($links['linkLink'], $linkLink); array_push($links['city'], $city[1]);
        $insert_values .="('".$city[1]."','".$linkLink."','".$linkText."'),";
      }
    }
    //echo '<hr>'.substr($insert_values, -1).'<hr>';
    if(substr($insert_values, -1) == ','){ $insert_values = rtrim($insert_values, ',');}
    //echo $linkText.'<hr>';
    //print_r($links);
    //echo '<hr>'.$insert_values.'<hr>';
    //query creation---------------------------------------------------------------------------------------------
    $query = 'CREATE TEMP TABLE tmp(city varchar(50), links text[], links_description text[]);'.'INSERT INTO tmp(city, links,links_description) VALUES '.$insert_values.';UPDATE public.links SET links = NULL, links_description = NULL; UPDATE public.links SET links = CASE WHEN tmp.city IS NOT NULL THEN tmp.links ELSE NULL END, links_description = CASE WHEN tmp.city IS NOT NULL THEN tmp.links_description ELSE NULL END FROM tmp WHERE tmp.city = links.city_eng;';

    //echo '<hr>'.$query;
    $postgres -> dbConnect($query, false, true);
    return true;
  }
  public function unArchive($path, $fileName, $fileType , $cities, $tableTypes){
    $fileName = explode('.',$fileName)[0];
    echo '<hr>'.$fileName.'<hr>';
    if(explode('_',$fileName)){
      $fileNameArr = explode('_',$fileName);
      print_r($fileNameArr);
      if(array_intersect($cities,$fileNameArr) and array_intersect($tableTypes,$fileNameArr)){
        $city = $fileNameArr[0];
        array_shift($fileNameArr);
        $tableType = '_'.implode('_',$fileNameArr);
        echo $city.$tableType;
        if ($fileType == 'zip'){
          $zip = new ZipArchive;
          $res = $zip -> open($path.$fileName.'.'.$fileType);
          if ( $res === true ) {
            $zip -> extractTo($path.$fileName);
            $zip -> close();
          }
          chmod($path.$fileName, 0777);
        } else if ($fileType == 'rar'){
          echo '<script type="text/javascript">alert("please use zip archive, rar archive is not supported :(");</script>';
         /* echo '<hr>'.$path.$fileName.'.'.$fileType.'<hr>';
          $rar_file = rar_open($path.$fileName.'.'.$fileType) or die("Failed to open Rar archive");
          $list = rar_list($rar_file);
          foreach($list as $file) {
              $entry = rar_entry_get($rar_file, $file->getName()) or die("Failed to find such entry");
              echo $file->getName().'<hr>';
              $entry->extract($path.$fileName); // extract to the current dir
          }
          
          rar_close($rar_file);*/
          

        } else {echo 'error on on unarchive';}
      } 
      else 'wrong file name, file name should be like - "city"."tableType".".zip/.rar"';
    }
    
    


   /* */
  }
  public function csvFromQuery($destinationPath,$query_type, $city, $header,$table_array){
    if ($header && $table_array) {
        $dirPath = $destinationPath;
            if (!file_exists($dirPath )) {
                $oldmask = umask(0);
                    mkdir($dirPath , 0777, true);
                    umask($oldmask);
            }
            $filePath = $destinationPath.$city.$query_type.'.csv';
            echo $filePath.'<hr>';
            print_r($header);
            echo '<hr>';
            print_r($table_array);
            touch($filePath);
            chmod($filePath, 0666);
            $file = fopen($filePath, 'w');
            fputcsv($file, $header, ',', '"');
            foreach ($table_array as $row_key => $row) {
                   fputcsv($file, $row, ',', '"');
            }
            fclose($file);
            echo '<hr>it works<hr>';
    } else {echo '<hr>wrong header or table_array<hr>';}
            
            return true;
  }
  public function gpxCoordToArray($filePath){
    $gpx=simplexml_load_file($filePath) or die("Error: Cannot create object");
    print_r($gpx);
    $coords = array();
    foreach($gpx->children() as $node){
       echo  $node->{'ele'}.'<hr>';
      //print_r($node);
      //echo '<hr>'.$node->attributes()->lon.'<hr>';
      //echo '<hr>';
      if($node->attributes()->lon && $node->attributes()->lat){
        array_push($coords,array('lon' => (string) $node->attributes()->lon, 'lat' => (string) $node->attributes()->lat, 'ele'=>$node->{'ele'}));
      }
    }
    if(!empty($coords)){
      return $coords;
    } else {return false;}

  }
  public function kmlCoordToArray($filePath){
    $kml=simplexml_load_file($filePath) or die("Error: Cannot create object");
    print_r($kml);
    echo '<hr>';
    $coords = array();
    foreach($kml->children()->children() as $node){
      print_r($node);
      echo '<hr>';
      echo '<hr>'.$node->Point->coordinates.'<hr>';
      if (($node->Point->coordinates) || ($node->Point->coordinates !='') ){
        array_push($coords, explode(',', str_replace(' ', '',$node->Point->coordinates)));
      }
      //echo '<hr>'.$node->attributes()->lon.'<hr>';
      //echo '<hr>';
    }
    if(!empty($coords)){
      return $coords;
    } else {return false;}
  }
  public function dir_files_names($dir_path){
    if(file_exists($dir_path)){
      if(count(scandir($dir_path)) == 2){
        return false;
      } else {return array_values(array_diff(scandir($dir_path),['.','..']));}
    }
    
  }
  public function cities_names_from_files($path){
    $cities_list = array();
    foreach(self::dir_files_names($path) as $file_name){
      if (!in_array(explode('_',substr($file_name,0,-4))[1],$cities_list)){
        array_push($cities_list, explode('_',substr($file_name,0,-4))[1]);
      }
    }
    if(!empty($cities_list)){
      return $cities_list;
    } else {return false;}
  }
  public function sshFileReplaceToShare($address, $port, $login, $password, $locationPath, $destinationPath){
    $connection = ssh2_connect($address, $port);
    ssh2_auth_password($connection, $login, $password);
    ssh2_scp_send($connection, $locationPath, $destinationPath, 0644);
    // Add this to flush buffers/close session 
    ssh2_exec($connection, 'exit'); 
  }
  public function upload($restriction,$login_user,$button_id){
    $target_dir = "/tmp/";
    $target_file = $target_dir . basename($_FILES[$button_id]['name']);
    $file_name = $_FILES[$button_id]['name'];
    if(substr($file_name,0,stripos($file_name, '_'))!='qgis'){
      $selectedCity = substr($file_name,0,stripos($file_name, '_'));
    } else {
      echo $file_name.'<hr>';
      //$selectedCity = substr(substr($file_name,5),0,-4);
      $selectedCity = explode('_', substr($file_name,0,-4))[1];
      echo $selectedCity.'<hr>';
    }
    
    //$uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $uploadReturnRespond ='';
    //$fileTypes = array('csv','qgs');
    $file_logger = new dbConnSetClass;
    // Check file size
    session_start();
    //city restriction
    //print_r($_SESSION['city_array'] ); 
    $admin_full_users = array();
    $query = "select e_mail from public.access where restriction in('admin', 'full');";
    $respond = $file_logger -> dbConnect($query, array('e_mail'), true);
    foreach($respond as $res){
      array_push($admin_full_users,$res['e_mail']);
    }
    //print_r($admin_full_users);
    //echo '<hr>';
    //echo $login_user;
    //echo '<hr>';
    if(in_array($login_user, $admin_full_users)){
      $cities_array = self::cities_names_from_files('/var/www/QGIS-Web-Client-master/projects/');
      //print_r($cities_array);
      //echo '<hr>';
    } else { $cities_array = $_SESSION['city_array'];}
    //$cities_array = $_SESSION['city_array'];
    if (in_array($selectedCity, $cities_array ) || (in_array($restriction, array('admin','full') ) ) ){
      //print_r($cities_array);
      //echo '<hr>';

      if ($_FILES[$button_id]['size'] <= 512000000) {
        if($fileType == 'csv'){
          if (move_uploaded_file($_FILES[$button_id]["tmp_name"], $target_file)) {
              echo "The file ". basename( $_FILES[$button_id]["name"]). " has been uploaded.";
              chmod($target_file, 0666);
              self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
              $query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
              $file_logger -> dbConnect($query, false, true);

             header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          } else {
              echo "Sorry, there was an error uploading your file.";
          }
        } else if(strtolower($fileType) == 'qgs' ){//and strtolower($restriction) =='admin'
          if (move_uploaded_file($_FILES[$button_id]['tmp_name'], $target_file)) {
              echo "The file ". basename( $_FILES[$button_id]['name']). " has been uploaded.";
              chmod($target_file, 0666);
              self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
              $query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
              $file_logger -> dbConnect($query, false, true);
              self::maps_link_reloader();
             header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          } else {
              echo "Sorry, there was an error uploading your file.";
              //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          }
        } else if(strtolower($fileType) == 'qfts' ){//and strtolower($restriction) =='admin'
          if (move_uploaded_file($_FILES[$button_id]['tmp_name'], $target_file)) {
              echo "The file ". basename( $_FILES[$button_id]['name']). " has been uploaded.";
              chmod($target_file, 0666);
              $locationPath = self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
              $destinationPath = '/mnt/samba/share/'.$file_name;
              $query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
              $file_logger -> dbConnect($query, false, true);
              self::sshFileReplaceToShare('10.112.129.165', 5432, 'yshpylovyi', 'yshpylovyi2017', $locationPath, $destinationPath);
             header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          } else {
              echo "Sorry, there was an error uploading your file.";
              //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          }
        } else if((strtolower($fileType) == 'gpx') || (strtolower($fileType) == 'kml') ){//and strtolower($restriction) =='admin'
          if (move_uploaded_file($_FILES[$button_id]['tmp_name'], $target_file)) {
              echo "The file ". basename( $_FILES[$button_id]['name']). " has been uploaded.";
              chmod($target_file, 0666);
              echo '<hr>'.$selectedCity.'<hr>';
              echo '<hr>'.$target_file.'<hr>';
              $file_name = basename( $_FILES[$button_id]['name']);
              
              $query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
              $file_logger -> dbConnect($query, false, true);
              $coordsFilePath = '/var/www/QGIS-Web-Client-master/site/csv/archive/'.$selectedCity.'/';
              $table_type = str_replace(array($selectedCity),'',explode('.', $file_name)[0]);
              //echo '<hr>'.explode('.', $file_name)[0].'<hr>';
              //echo '<hr>'.$table_type.'<hr>';
              $extention = '.csv';
              if (strpos($table_type, '_poles_coords') !== false) {
                self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
                if(strtolower($fileType) == 'gpx'){
                  self::csvFromQuery($coordsFilePath, $table_type, $selectedCity, array('lon','lat','height'), self::gpxCoordToArray($target_file));
                } else if(strtolower($fileType) == 'kml') {
                  self::csvFromQuery($coordsFilePath, $table_type, $selectedCity, array('lon','lat','height'), self::kmlCoordToArray($target_file));
                } 
                
                $query = "CREATE TEMP TABLE temp(id serial, lon varchar(100), lat varchar(100), height varchar(100), geom geometry); select copy_for_testuser('temp( lon, lat, height )', '".$coordsFilePath.$selectedCity.$table_type.$extention."', ',', 'UTF-8');UPDATE temp SET geom = ST_Transform(ST_SetSRID(ST_MakePoint(lon::real,lat::real), 4326),32636);INSERT INTO ".$selectedCity.".".$selectedCity."_cable_air_poles(geom) select geom from temp WHERE geom NOT IN(SELECT DISTINCT geom FROM ".$selectedCity.".".$selectedCity."_cable_air_poles WHERE geom IS NOT NULL); UPDATE ".$selectedCity.".".$selectedCity."_cable_air_poles SET table_id = 'p_'||id where table_id is null;";
                echo $query;
                $file_logger -> dbConnect($query, false, true);
                $query = "UPDATE ".$selectedCity.".".$selectedCity."_cable_air_poles SET pole_street = ".$selectedCity."_roads.name FROM ".$selectedCity.".".$selectedCity."_roads WHERE ST_Intersects(".$selectedCity.".".$selectedCity."_roads.geom, ST_Buffer(".$selectedCity.".".$selectedCity."_cable_air_poles.geom,20)) and ".$selectedCity.".".$selectedCity."_roads.geom is not null AND ".$selectedCity.".".$selectedCity."_cable_air_poles.pole_street IS NULL;UPDATE ".$selectedCity.".".$selectedCity."_cable_air_poles SET pole_micro_district = ".$selectedCity."_microdistricts.micro_district FROM ".$selectedCity.".".$selectedCity."_microdistricts WHERE ST_Contains(".$selectedCity."_microdistricts.coverage_geom, ".$selectedCity."_cable_air_poles.geom) and ".$selectedCity.".".$selectedCity."_microdistricts.coverage_geom is not null  AND ".$selectedCity.".".$selectedCity."_cable_air_poles.pole_micro_district IS NULL;";
                $file_logger -> dbConnect($query, false, true);

                echo $query;

              }
              

             header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          } else {
              echo "Sorry, there was an error uploading your file.";
              //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
          }
        } else if(in_array(strtolower($fileType), array('zip','rar'))){
          if (move_uploaded_file($_FILES[$button_id]['tmp_name'], $target_file)) {
              echo "The file ". basename( $_FILES[$button_id]['name']). " has been uploaded.";
              chmod($target_file, 0666);
              if(strpos(strtolower($file_name),'help_')!== false) {
                self::dirCreate('', $target_file, $file_name,$fileType);
              } else {
                $postgres = new dbConnSetClass;
                $query = "select nspname from pg_catalog.pg_namespace where nspname not like '%pg_%' and nspname not in('information_schema', '_city_hlam', 'topology','public');";
                $queryArrayKeys = array('nspname');
                //echo $query;
                $cities = array();
                $sumObjectsArray = $postgres -> dbConnect($query, $queryArrayKeys, true);
                foreach ($sumObjectsArray as $sumObjectsArrayKey => $objectArray) {
                  array_push($cities, $objectArray['nspname']);
                }
                $tableTypes = array('ctv','topology', 'switches','cable','channels','air');

                //print_r($cities);print_r($tableTypes);
                self::unArchive('/tmp/',  basename( $_FILES[$button_id]['name']), $fileType , $cities, $tableTypes);
                //self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
                //$query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
                //$file_logger -> dbConnect($query, false, true);
              }
              

             header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
           } else {
              echo "Sorry, there was an error uploading your file.";
              //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
           }
        } else {
          echo 'your file have restricted type please try zip or rar with name like "help_..."';
          //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page*/
        }
      } else {
          echo 'Sorry, your file is too big';
          //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
      }
    }
  }
}
////oracle////////////////////////////////////////////////////////////////////////////////////////////////
class dbOrConnSetClass{
  private $dbConnSet = array(
    "host"=>"10.10.16.70:1521/DTV",
    "encoding"=>"AL32UTF8",
    "user"=>"puma_qgis",
    "password"=>"vjDjA3JkcKdD"
    );
  private $selectedCity = 'none';
  private $queryArrayKeys = array();
  private $destinationPath ='/var/www/QGIS-Web-Client-master/site/csv/cubic/';
  private $query_type = 'none';
  private $query = '';
  public function setProp($prop,$newValue){
    if (property_exists($this,$prop)){
      $this->$prop = $newValue;
    } else {
      echo "Setting of Undefined Property";
    }
  }
  public function getProp($prop){
    if (property_exists($this,$prop)){
      return $this->$prop;
    } else {
      echo "Gettin of Undefined Property";
    }
  }
  public function dbOrConnect($cities){
    $conn = oci_connect($this->dbConnSet['user'], $this->dbConnSet['password'], $this->dbConnSet['host'],$this->dbConnSet['encoding']);
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    } else {
      if (is_array($cities)) {
        foreach ($cities as $city => $city_name) {
          self::cityTablesCreate($city_name, $conn);
        }
      } elseif (is_string($cities)) {
        return self::cityTablesCreateBrowser($cities,$conn);
      }
      
      
    }
  }
  public function csvFromQuery($query_type, $city, $header,$table_array){
    if ($header) {
        $dirPath = $this->destinationPath.$query_type;
            if (!file_exists($dirPath )) {
                $oldmask = umask(0);
                    mkdir($dirPath , 0777, true);
                    umask($oldmask);
            }
            $filePath = $this->destinationPath.$query_type.'/'.$city.$query_type.'.csv';
            touch($filePath);
            chmod($filePath, 0666);
            $file = fopen($filePath, 'w');
            fputcsv($file, $header, ',', '"');
            foreach ($table_array as $row_key => $row) {
                   fputcsv($file, $row, ',', '"');
            }
            fclose($file);
    }
            
            return true;
  }
  public function cityTablesCreate($city, $conn){
        //$stid = oci_parse($conn, $this->query. " WHERE CITY ='$city[2]' ");//AND ROWNUM <=1000
        $header = array();
        if(strpos(strtolower($this->query),'where')) {
          $stid = oci_parse($conn, $this->query. " AND CITY ='$city[2]' ");
        } else { $stid = oci_parse($conn, $this->query. " WHERE CITY ='$city[2]' ");}
        if (oci_execute($stid)) {
            $headder = array();
            if ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                 foreach (array_keys($row)  as $row_name_key => $row_name) {
                    $header[] = $row_name;
                }
            }
            $table_array = array();
            while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                array_push($table_array, $row);
            }
            self::csvFromQuery($this->query_type, $city[1], $header,$table_array);
        }
        
    }
  public function cityTablesCreateBrowser($city, $conn){
      if(strpos(strtolower($this->query),'where')) {
        $stid = oci_parse($conn, $this->query. " AND CITY ='$city' ");
      } else { $stid = oci_parse($conn, $this->query. " WHERE CITY ='$city' ");}
      //$stid = oci_parse($conn, $this->query. " WHERE CITY ='$city' ");//AND ROWNUM <=1000
      $table_array = array();
      if (oci_execute($stid)) {
        
        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
              array_push($table_array, $row);
          }
      }
      //print_r($table_array);
      return $table_array;
  }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//functions///////////////////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////
function postgres_to_php_array($postgresArray) {
   $postgresStr = trim($postgresArray,"{}");
    $elmts = explode(",",$postgresStr);
    return $elmts;
}
/////////////////////////////////////////////////////////////
function groupSelect($cubic_name){
      switch (mb_convert_case((string)$cubic_name, MB_CASE_LOWER, "UTF-8")){
        ////ctv equipment
        case 'оптический узел':
          $group_value = array('group' =>  1,'color' =>  '#ff9900', 8 ,'value' =>16,'label' => 'nod');
          break;
        case 'оптичний приймач':
          $group_value = array('group' => 2,'color' => '#663300', 8,'value' => 16,'label' =>  'op');
          break;
        case 'магістральний оптичний вузол':
          $group_value = array('group' =>  3,'color' => '#3333cc', 18,'value' => 36,'label' =>  'mnod');
          break;
        case 'передатчик оптический':
          $group_value = array('group' =>  4,'color' => '#333399', 18,'value' => 36,'label' => 'ot');
          break;
        case 'магистральный распределительный узел':
          $group_value = array('group' =>  5,'color' => '#ff0000', 15,'value' => 30,'label' =>  'mdod');
          break;
        case 'кросс-муфта':
          $group_value = array('group' =>  6,'color' => '#ff0066', 11,'value' => 22,'label' =>  'cc');
          break;
          ////ethernet equipment
        case 'корневой коммутатор':
          $group_value = array('group' =>  4,'color' => '#333399', 18,'value' => 36,'label' => 'core');
          break;
        case 'undef':
          $group_value = array('group' =>  6,'color' => '#999966', 11,'value' => 22,'label' =>  'undef');
          break;
        case 'неопределен':
          $group_value = array('group' =>  6,'color' => '#999966', 11,'value' => 22,'label' =>  'undef');
          break;  
        case 'agr':
          $group_value = array('group' =>  3,'color' => '#3333cc', 18,'value' => 36,'label' =>  'agr');
          break;
        case 'коммутатор агрегации':
          $group_value = array('group' =>  3,'color' => '#3333cc', 18,'value' => 36,'label' =>  'agr');
          break;  
        case 'sbagr':
          $group_value = array('group' =>  5,'color' => '#ff0000', 15,'value' => 30,'label' =>  'sbagr');
          break;
          case 'коммутатор суб-агрегации':
          $group_value = array('group' =>  5,'color' => '#ff0000', 15,'value' => 30,'label' =>  'sbagr');
          break;
        case 'acc':
          $group_value = array('group' =>  1,'color' =>  '#ff9900', 8 ,'value' =>16,'label' => 'acc');
          break;
        case 'коммутатор доступа':
          $group_value = array('group' =>  1,'color' =>  '#ff9900', 8 ,'value' =>16,'label' => 'acc');
          break;  
          ////default
        default:
          $group_value = array('group' =>  0,'color' =>  '#666633', 1 ,'value' =>1,'label' => 'not assigned');
          break;
      }
  return $group_value;
}
//check toplogy files existence////////////////////////////////////////////////////////////////////////////////////////
 function checkIfFileExist($selectedCity, $cubic_name, $cubic_code, $archiveLink, $imgLink){
  $group_value = array();
  $cubic_name = groupSelect($cubic_name)['label'];
  $xlsFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xls';
  $xlsxFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.xlsx';
  $dwgFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.dwg';
  $pdfFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.pdf';
  $imgFile = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.png';

  //echo '$xlsFile - '.$xlsFile.'<hr>';
  //echo '$xlsxFile - '.$xlsxFile.'<hr>';
  //echo '$imgFile - '.$imgFile.'<hr>';
  if (file_exists($xlsxFile) || file_exists($xlsFile) || file_exists($dwgFile)|| file_exists($pdfFile)) {
    $group_value['archiveLink'] =  $archiveLink;
  } else {$group_value['archiveLink'] =  '-'; }
  if (file_exists($imgFile)) {
    $group_value['imgLink'] =  '/tmp/archive/'.$selectedCity.'/topology/'.$cubic_name.'/'.$cubic_code.'/'.$cubic_code. '_wiring.png';
  } else {$group_value['imgLink'] =  '-'; }
  if (file_exists($xlsxFile)) {
    $group_value['xlsxFile'] =  '+';
  } else {$group_value['xlsxFile'] =  '-'; }
  if (file_exists($xlsFile)) {
    $group_value['xlsFile'] =  '+';
  } else {$group_value['xlsFile'] =  '-'; }
  if (file_exists($dwgFile)) {
    $group_value['dwgFile'] =  '+';
  } else {$group_value['dwgFile'] =  '-';}
  if (file_exists($pdfFile)) {
    $group_value['pdfFile'] =  '+';
    $group_value['pdfFileCreDate'] =  gmdate("Y-m-d",filemtime($dwgFile));
    $group_value['pdfFileCreDateFull'] =  gmdate("l H:i",filemtime($dwgFile));
    $group_value['pdfFileModDate'] =  gmdate("Y-m-d",filectime($pdfFile));
    //$group_value['pdfFileModDateFull'] =  gmdate("Y-m-d l H:i:s",stat($pdfFile)['mtime']);
    $group_value['pdfFileModDateFull'] =  gmdate("l H:i",filectime($pdfFile));
  } else {$group_value['pdfFile'] =  '-'; $group_value['pdfFileModDate'] = '-'; $group_value['pdfFileModDateFull'] = '-';$group_value['pdfFileCreDate'] = '-'; $group_value['pdfFileCreDateFull'] = '-';}
  if (file_exists($imgFile)) {
    $group_value['imgFile'] =  '+';
  } else {$group_value['imgFile'] =  '-'; }
  return $group_value;
 }
////////////// check where file exists////////////////////////////////////////////////////////////////////
function fileExistenceCheck($promeLink, $secondaryLink, $selectedCity, $fileExtention){
  if (file_exists($promeLink.$selectedCity.$fileExtention)) {
      $linkStorage = "'".$promeLink.$selectedCity.$fileExtention."'";
      $dir = sys_get_temp_dir();
      $files = scandir($dir);  
    } else {
      $linkStorage = "'".$secondaryLink.$selectedCity."/".$selectedCity.$fileExtention."'" ;
      $dir = $secondaryLink.$selectedCity."/";
      $files = scandir($dir);
    }
  return array('files' =>$files, 'linkStorage' =>$linkStorage);
}
////////////// check where file exists////////////////////////////////////////////////////////////////////
function fileExistenceCheckAuto($promeLink, $secondaryLink, $tableType, $selectedCity, $fileExtention){
  $manualUpdateLink = $promeLink.$selectedCity."/".$selectedCity.$tableType.$fileExtention;
  $autoUpdateLink = $secondaryLink.$tableType."/".$selectedCity.$tableType.$fileExtention;
  $manualUpdateTime = stat($manualUpdateLink)[9];
  $autoUpdateTime = stat($autoUpdateLink)[9];
  if (file_exists($manualUpdateLink) && ($manualUpdateTime > $autoUpdateTime) ) {
      $linkStorage = "'".$manualUpdateLink."'";
      $dir = $promeLink.$selectedCity."/";
      $files = scandir($dir);
      $updateState = 'manual';  
    } else {
      $linkStorage = "'".$autoUpdateLink."'" ;
      $dir = $secondaryLink.$tableType."/";
      $files = scandir($dir);
      $updateState = 'auto';  
    }
  return array('files' =>$files, 'linkStorage' =>$linkStorage, 'manualUpdateTime' =>$manualUpdateTime, 'autoUpdateTime'=> $autoUpdateTime, 'manualUpdateLink' =>$manualUpdateLink, 'autoUpdateLink' => $autoUpdateLink, 'updateState' => $updateState);
}
///////replace element position with other element in named array //////////////////////////////////////////
function array_swap($key1, $key2, $array) {
    $newArray = array ();
    foreach ($array as $key => $value) {
        if ($key == $key1) {
            $newArray[$key2] = $array[$key2];
        } elseif ($key == $key2) {
            $newArray[$key1] = $array[$key1];
        } else {
            $newArray[$key] = $value;
        }
    }
    return $newArray;
}
//////////////////////adds directories if not exist///////////////////////////////////////////////////////////
 function topologyCsvDirCreate($city, $target_file, $file_name){
              
  $newDirPath = '/var/www/QGIS-Web-Client-master/site/csv/archive/'.$city.'/';
  if (!file_exists($newDirPath )) {
    $oldmask = umask(0);
        mkdir($newDirPath , 0777, true);
        umask($oldmask);
  }
  chmod($target_file, 0666);
  copy($target_file, $newDirPath . $file_name);

  //echo $dirPath;
  return true;

}
///////////////////////////////////////
//creates directory for topology element/////////////////////////////////////////////////////////////////
/*function topologyDirCreate($description, $city){
  if($description['cubic_name'] !='not assigned'){
    $dirPath = '/var/www/QGIS-Web-Client-master/site/tmp/archive/'.$city.'/topology/'.$description['cubic_name'].'/'.$description['cubic_code'];
    if (!file_exists($dirPath )) {
      $oldmask = umask(0);
          mkdir($dirPath , 0777, true);
          umask($oldmask);
    }
    //echo $dirPath;
  }            
  
  return true;
}*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////

function topologyDirCreate($description, $city){
  if($description['cubic_name'] !='not assigned'){
    $dirPath = $description['rootDir'].$city.$description['subDirType'].$description['cubic_name'].'/'.$description['cubic_code'];
    if (!file_exists($dirPath )) {
      $oldmask = umask(0);
          mkdir($dirPath , 0777, true);
          umask($oldmask);
    }
    //echo $dirPath;
  }            
  
  return true;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function fileDate($folderLink, $checkList, $responseValue){
    $files = scandir($folderLink);
    $fileDate = '';
    /*foreach ($files as $fileKey => $filename) {
        if(strpos(mb_convert_case((string)$filename, MB_CASE_LOWER, "UTF-8") , $checkList['check'][array_search($responseValue, $checkList['response'])]) !== false){
           //$fileDate = '<br>'.'<span style ="color:blue">'. gmdate("Y-m-d",stat($folderLink.$filename)['mtime']).'</span>';
            $fileDate = gmdate("Y-m-d",stat($folderLink.$filename)['mtime']);
           //echo $folderLink.$filename.'---'.$fileDate.'<br>';
        }
    }*/
      foreach ($checkList['response'] as $listKey=>$value) {
        if($responseValue == $value){
          foreach ($files as $key => $filename) {
              $lower = mb_convert_case((string)$filename, MB_CASE_LOWER, "UTF-8");
              if(strpos($lower, $checkList['check'][$listKey])) {
                  //$fileDate = gmdate("Y-m-d",stat($folderLink.$filename)['mtime']).$checkList['check'][$listKey];
                  $fileDate = gmdate("Y-m-d",stat($folderLink.$filename)['mtime']);
                  //echo $folderLink.$filename.'---'.$fileDate.'---'.$value.'---'.$checkList['response'][$listKey].'<br>';
              }
            }
        }
       
      }
    return $fileDate;
}
///////////////////////////////////////////////////////////////
function jsConsolLog($message){
  print "<script type='text/javascript'>console.log(".json_encode($message).");</script>";
}
//////check whether directory is empty/////////////////////////////////
 function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}
///// sends mail with attachment///////////////////////////////////////
function mail_attachment( $to, $from,$subject , $message, $path, $filename){
 
  $to = $to;
  $from = $from;
  $subject = $subject;
  $message = $message; //Текст письма
  $boundary = "---"; //Разделитель

  $headers = "From: $from\nReply-To: $from\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
  $body = "--$boundary\n";

  $body .= "Content-type: text/html; charset='utf-8'\n";
  $body .= "Content-Transfer-Encoding: quoted-printablenn";
  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
  $body .= mb_convert_encoding($message, 'UTF-8', 'auto')."\n";
  $body .= "--$boundary\n";
  $file = fopen($path, "r"); //Открываем файл
  $text = fread($file, filesize($path)); //Считываем весь файл
  fclose($file); //Закрываем файл

  $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n";
  $body .= "Content-Transfer-Encoding: base64\n";
  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
  $body .= chunk_split(base64_encode($text))."\n";
  $body .= "--".$boundary ."--\n";
  mail($to, $subject, $body, $headers); //Отправляем письмо
}
//////////changes restriction to folder///////////////////////////
function restriction_change($path){
  if (!file_exists($path )) {
        $oldmask = umask(0);
        mkdir($path , 0777, true);
        umask($oldmask);
    }
}
//////////make dir empty//////////////////////////////////////////
function make_dir_empty($dirPathMail){
  foreach(glob($dirPathMail.'*') as $file){ // iterate files
    if(is_file($file))
      unlink($file); // delete file
  }
}
//////// zip files in folder//////////////////////////////////////
function zip_folder($dirPath,$zipFilePath){
  $zip = new ZipArchive;
  $zip->open($zipFilePath, ZipArchive::CREATE);
  foreach (glob($dirPath.'*') as $file) {
    $new_filename = end(explode('/',$file));
      $zip->addFile($file,$new_filename);
  }
  $zip->close();
}
///////size_detaction/////////////////////////////////////////
function size_detection($file_params){
  if( $file_params['size'] > filesize($file_params['path'])){
    $params = array('path'=> $file_params['path'], 'name' => $file_params['name']);
  } else { $params = array('path' => '/var/www/QGIS-Web-Client-master/site/img/vguh.png', 'name' => 'vguh.png');}
  return $params;
}
//////file name array in dir//////////////////////
function dir_files_names($dir_path){
  if(file_exists($dir_path)){
    if(count(scandir($dir_path)) == 2){
      return false;
    } else {return array_values(array_diff(scandir($dir_path),['.','..']));}
  }
  
}
///functions for xml's
function stateUpdateInDatabase($stateArray, $city, $stateArrayType){
  echo '<hr>'.$stateArrayType.'<hr>'.$city.'<hr>';
  //echo '<hr>';
  //  print_r($stateArray);
  //  echo '<hr>';
  $postgres = new dbConnSetClass;
  if($stateArrayType == 'ports'){
    $tmpCreate = "CREATE TEMP TABLE tmp(tid serial, id  varchar(100), port varchar(100),switch_local varchar(100),switch_remote varchar(100),type varchar(100),update_time varchar(100),up_time varchar(100),down_time varchar(100),alarm_state varchar(100),port_state varchar(100),errs_in varchar(100),errs_out varchar(100),ip_addr varchar(100),mac varchar(100),dev_state varchar(100),inventary_state varchar(100),to_num varchar(100));";
    $tmpInsert = 'INSERT INTO tmp(id , port ,switch_local ,switch_remote ,type ,update_time ,up_time , down_time ,alarm_state ,port_state ,errs_in ,errs_out ,ip_addr ,mac ,dev_state ,inventary_state,to_num) ';
      $tmpInsertValues = 'VALUES ';
      foreach ($stateArray as $arr) {
        $tmpInsertValues .= '('." '".$arr ['id']."',"." '".$arr ['port']."',"." '".$arr ['switch_local']."',"." '".$arr ['switch_remote']."',"." '".$arr ['type']."',"." '".$arr ['update_time']."',"." '".$arr ['up_time']."',"." '".$arr ['down_time']."',"." '".$arr ['alarm_state']."',"." '".$arr ['port_state']."',"." '".$arr ['errs_in']."',"." '".$arr ['errs_out']."',"." '".$arr ['ip_addr']."',"." '".$arr ['mac']."',"." '".$arr ['dev_state']."',"." '".$arr ['inventary_state']."',"." '".$arr ['to_num']."'".')';
        if (next($stateArray )) {
          $tmpInsertValues .= ','; // Add comma for all elements instead of last
        }
      
      }
    $tmpInsertValues .= ';'; 
    $tmpCityUpdate = "UPDATE ".$city.".".$city."_switches set   update_time =tmp.update_time, up_time =tmp.up_time, down_time = tmp.down_time , alarm_state = tmp.alarm_state , port_state = tmp.port_state, errs_in =tmp.errs_in , errs_out = tmp.errs_out , dev_state = tmp.dev_state , inventary_state = tmp.inventary_state , to_num = tmp.to_num from tmp where tmp.ip_addr = ".$city."_switches.cubic_ip_address;";
    $query = $tmpCreate.' '.$tmpInsert.' '.$tmpInsertValues.' '.$tmpCityUpdate;

  } else if ($stateArrayType =='switches'){
    //echo '<hr>';
    //print_r($stateArray);
    //echo '<hr>';
    $tmpCreate = "CREATE TEMP TABLE tmp(tid serial,  ip_addr varchar(100),  calc_switches_cnt varchar(100),  mon_traffic_state varchar(100),  mon_ports_state varchar(100),  update_time varchar(100) ,  up_time varchar(100),  down_time varchar(100),  last_down_time_subst varchar(100),  to_num varchar(100),  mon_ping_state varchar(100),  mon_ping_ignore varchar(100));";
    $tmpInsert = 'INSERT INTO tmp(ip_addr,calc_switches_cnt,mon_traffic_state,mon_ports_state,update_time,up_time,down_time,last_down_time_subst,to_num,mon_ping_state,mon_ping_ignore) ';
    $tmpInsertValues = 'VALUES ';
    foreach ($stateArray as $arr) {
      $tmpInsertValues .= '('." '".$arr ['ip_addr']."',"." '".$arr ['calc_switches_cnt']."',"." '".$arr ['mon_traffic_state']."',"." '".$arr ['mon_ports_state']."',"." '".$arr ['update_time']."',"." '".$arr ['up_time']."',"." '".$arr ['down_time']."',"." '".$arr ['last_down_time_subst']."',"." '".$arr ['to_num']."',"." '".$arr ['mon_ping_state']."',"." '".$arr ['mon_ping_ignore']."'".')';
      if (next($stateArray )) {
        $tmpInsertValues .= ','; // Add comma for all elements instead of last
      }
    
    }
    $tmpInsertValues .= ';'; 
    $tmpCityUpdate = "UPDATE ".$city.".".$city."_switches set calc_switches_cnt = null, mon_traffic_state = null, mon_ports_state = null ,switch_update_time= null, switch_up_time = null , switch_down_time = null , switch_last_down_time_subst = null, to_num = null, mon_ping_state = null, mon_ping_ignore = null;UPDATE ".$city.".".$city."_switches set calc_switches_cnt = tmp.calc_switches_cnt, mon_traffic_state = tmp.mon_traffic_state, mon_ports_state = tmp.mon_ports_state ,switch_update_time= tmp.update_time, switch_up_time = tmp.up_time , switch_down_time = tmp.down_time , switch_last_down_time_subst = tmp.last_down_time_subst, to_num =tmp.to_num, mon_ping_state = tmp.mon_ping_state, mon_ping_ignore = tmp.mon_ping_ignore  from tmp where tmp.ip_addr = ".$city."_switches.cubic_ip_address;UPDATE ".$city.".".$city."_switches_working set calc_switches_cnt = null, mon_traffic_state = null, mon_ports_state = null ,update_time= null, up_time = null , down_time = null , last_down_time_subst = null, to_num = null, mon_ping_state = null, mon_ping_ignore = null;UPDATE ".$city.".".$city."_switches_working set calc_switches_cnt = tmp.calc_switches_cnt, mon_traffic_state = tmp.mon_traffic_state, mon_ports_state = tmp.mon_ports_state ,update_time= tmp.update_time, up_time = tmp.up_time , down_time = tmp.down_time , last_down_time_subst = tmp.last_down_time_subst, to_num =tmp.to_num, mon_ping_state = tmp.mon_ping_state, mon_ping_ignore = tmp.mon_ping_ignore  from tmp where tmp.ip_addr = ".$city."_switches_working.ip_address;";
    $query_unique_alerts = "INSERT INTO ".$city.".".$city."_switches_unique_alert_log (cubic_house_id, cubic_street, cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_id, cubic_ip_address, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, alert_time, switch_geom,   switch_update_time ,  switch_uptime ,  switch_down_time ,  switch_last_down_time_subst ) select d.cubic_house_id, d.cubic_street, d.cubic_house_num, d.cubic_house_floor, d.cubic_switch_location, d.cubic_switch_id, d.cubic_ip_address, d.cubic_switch_contract_cnt, d.cubic_switch_contract_active_cnt,  d.mon_traffic_state,  d.mon_ping_state, d.mon_ports_state, d.mon_ping_ignore, d.alert_time, d.switches_geom ,   d.switch_update_time ,  d.switch_up_time ,  d.switch_down_time ,  d.switch_last_down_time_subst FROM (SELECT cubic_house_id, cubic_street,  cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_id, cubic_ip_address, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, date_trunc('minutes', cast (now() as timestamp(0))) as alert_time, switches_geom,   switch_update_time ,  switch_up_time ,  switch_down_time ,  switch_last_down_time_subst  FROM ".$city.".".$city."_switches WHERE mon_traffic_state IS NOT NULL OR mon_ping_state IS NOT NULL OR mon_ports_state IS NOT NULL OR mon_ping_ignore IS NOT NULL) as d WHERE NOT EXISTS (SELECT 1 FROM ".$city.".".$city."_switches_unique_alert_log alert_log WHERE alert_log.cubic_switch_id = d.cubic_switch_id and alert_log.cubic_ip_address = d.cubic_ip_address and alert_log.mon_traffic_state = d.mon_traffic_state and alert_log.mon_ping_state = d.mon_ping_state and alert_log.mon_ports_state = d.mon_ports_state and alert_log.mon_ping_ignore = d.mon_ping_ignore and alert_log.switch_down_time = d.switch_down_time and date_trunc('days', cast (alert_log.alert_time as timestamp(0))) = date_trunc('days', cast (d.alert_time as timestamp(0))));";
    $delete_old_query_alerts = "DELETE FROM ".$city.".".$city."_switches_alert_log WHERE switch_down_time IN(SELECT DISTINCT switch_down_time from ".$city.".".$city."_switches_alert_log WHERE alert_time < NOW() - INTERVAL '30 days');"; //  OR switch_down_time IS NULL
    //$query_alerts = "INSERT INTO ".$city.".".$city."_switches_alert_log (cubic_house_id, cubic_street, cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_id, cubic_ip_address, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, alert_time, switch_geom ,   switch_update_time ,  switch_uptime ,  switch_down_time ,  switch_last_down_time_subst ,   cubic_house_entrance_num ,  cubic_switch_role , cubic_switch_model ) SELECT cubic_house_id, cubic_street,  cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_id, cubic_ip_address, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, date_trunc('minutes', cast (now() as timestamp(0))) as alert_time, switches_geom, switch_update_time ,  switch_up_time ,  switch_down_time ,  switch_last_down_time_subst,   cubic_house_entrance_num ,  cubic_switch_role , cubic_switch_model   FROM ".$city.".".$city."_switches WHERE mon_traffic_state IS NOT NULL OR mon_ping_state IS NOT NULL OR mon_ports_state IS NOT NULL OR mon_ping_ignore IS NOT NULL;";
    $query_alerts = "INSERT INTO ".$city.".".$city."_switches_alert_log (cubic_house_id, cubic_street, cubic_house_num, cubic_house_floor, cubic_switch_location, cubic_switch_id, cubic_ip_address, cubic_switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, alert_time, switch_geom ,   switch_update_time ,  switch_uptime ,  switch_down_time ,  switch_last_down_time_subst ,   cubic_house_entrance_num ,  cubic_switch_role , cubic_switch_model ) SELECT house_id, street,  cubic_house_num, floor, cubic_switch_location, switch_id, ip_address, switch_contract_cnt, cubic_switch_contract_active_cnt,  mon_traffic_state,  mon_ping_state, mon_ports_state, mon_ping_ignore, date_trunc('minutes', cast (now() as timestamp(0))) as alert_time, switches_geom, update_time ,  up_time ,  down_time ,  last_down_time_subst,   DOORWAY ,  switch_role , switch_model   FROM ".$city.".".$city."_switches_working WHERE mon_traffic_state IS NOT NULL OR mon_ping_state IS NOT NULL OR mon_ports_state IS NOT NULL OR mon_ping_ignore IS NOT NULL;";
    $query = $tmpCreate.' '.$tmpInsert.' '.$tmpInsertValues.' '.$tmpCityUpdate.$query_alerts.$delete_old_query_alerts.$query_unique_alerts;
  }
  
  
  echo $query.'<hr>';
  $postgres -> dbConnect($query, false, true);
}
function xmlPrint($xml, $city, $stateArrayType) {
  $stateArray = array();
  //echo '<hr>';
  //echo '<hr>'.$stateArrayType.'<hr>';
  foreach($xml->children() as $child)
    {
    //echo $child->getName() . ": " . $child . "<br>";
      foreach($child->children() as $child_inner)
      {
        $arr = array();
      //echo $child_inner->getName() . ": " . $child_inner. "<br>";
        foreach($child_inner->children() as $child_inner_inner)
        {
          $arr [$child_inner_inner->getName() ] = strtolower( $child_inner_inner.'');

          //echo $child_inner_inner->getName() . ": " . $child_inner_inner. "<br>";
        }
        //print_r($arr);
        array_push($stateArray , $arr );
      }
    }
    //echo '<hr>';
   if (!empty($stateArray)) {
    //echo '<br>'.$city.'<br>';
    //echo '<hr>';
    //print_r($stateArray);
    //echo '<br>';
    //echo '<hr>';
    stateUpdateInDatabase($stateArray, $city, $stateArrayType);
  }
}
function csvFromXml($xml, $city, $type){
  date_default_timezone_set('Europe/Kiev');
  $date = date('Y-m-d_h-i-sa');
  $dirPath = '/tmp/alerts/'.$city;
  if (!file_exists($dirPath )) {
    $oldmask = umask(0);
        mkdir($dirPath , 0777, true);
        umask($oldmask);
  }
  $filePath = '/tmp/alerts/'.$city.'/'.$city.'_'.$type.'_alerts_'.$date.'.csv';
  touch($filePath);
  chmod($filePath, 0666);
  $file = fopen($filePath, 'w');
   $headers = array(); 
    // loop through the first set of fields to get names
    foreach($xml->children() as $child)
    {
      foreach($child->children() as $child_inner)
      {
        if ($child_inner->getName() =='key_0') {
          foreach($child_inner->children() as $field)
        {
        // put the field name into array
                  $headers[] = $field->getName();
         }  
        }
        
      }
    }
    // print headers to CSV
      fputcsv($file, $headers, ',', '"');
    foreach($xml->children() as $child)
      {
      foreach($child->children() as $child_inner)
      {

              fputcsv($file, get_object_vars($child_inner), ',', '"');
      }
    }
  fclose($file);
  //delate older files
  $folderName =  '/tmp/alerts/'.$city.'/';
  if (file_exists($folderName)) {
    foreach (new DirectoryIterator($folderName) as $fileInfo) {
        if ($fileInfo->isDot()) {
        continue;
        }
        if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= 7*24*60*60) {
            unlink($fileInfo->getRealPath());
        }
    }
  }

  return true;
}
//-----------check whether substring(from array) in string
function substrArrayInString($stringtext, $substrArray){
  $state = '';
  foreach ($substrArray as $substr) {
    //echo 'string------'.$string.'<hr>';
    if (strpos($stringtext, str_replace('"','',$substr))) {
        $state .= $stringtext;
    }
  }
  //echo 'state------'.$stringtext.'<hr>';
  return $state;
}
//--------------------------------------------------------
//operations under $cities array
function cityVocabulary($cities, $field, $value) {
     foreach($cities as $key => $city)
     {
        if ( $city[$field] === $value )
           return $key;
     }
     return false;
  }
// creates pg array from php array----------------------------------------------  
function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}  
?>