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
  private $dbConnSet = array(
    "host"=>"host=127.0.0.1",
    "port"=>"port=5432",
    "dbname"=>"dbname=postgres",
    "user"=>"user=simpleuser",
    "password"=>"password=simplepassword"
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
      echo "Opened database successfully\n";
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
            $arr[0] =  'no new ethernet equipment';
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
    $arr_response = self::dbConnect("SELECT e_mail, md5, restriction FROM public.access WHERE e_mail= '".$e_mail."';", array('e_mail', 'md5', 'restriction'), true);
    if(md5($password)===$arr_response[0]['md5']){
      self::dbConnect("INSERT INTO public.login(e_mail, login_time) VALUES ('".$e_mail."',now());",false,true);
      header("location: main_page.php?restriction=".$arr_response[0]['restriction']."&e_mail=".$e_mail); // Redirecting To Other Page
      return true;
    } else {
       $msg = "wrong e-mail or password";
      header("location: ../index.php?msg=$msg");
    }
  }

}
///file UPLOAD////////////////////////////////////////////////////////////////////////////////////////////
class fileUpload {
  private $restriction = 'none';
  public function dirCreate($city, $target_file, $file_name,$fileType){
    if($fileType =='csv'){ $newDirPath = '/var/www/QGIS-Web-Client-master/site/csv/archive/'.$city.'/';}        
    if($fileType =='qgs'){ $newDirPath = '/var/www/QGIS-Web-Client-master/projects/';}  
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
  public function upload($restriction,$login_user,$button_id){
    $target_dir = "/tmp/";
    $target_file = $target_dir . basename($_FILES[$button_id]['name']);
    $file_name = $_FILES[$button_id]['name'];
    if(substr($file_name,0,stripos($file_name, '_'))!='qgis'){
      $selectedCity = substr($file_name,0,stripos($file_name, '_'));
    } else {
      $selectedCity = substr(substr($file_name,5));
    }
    
    //$uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $uploadReturnRespond ='';
    //$fileTypes = array('csv','qgs');
    $file_logger = new dbConnSetClass;
    // Check file size
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
      } else
      if($fileType == 'qgs' and $restriction =='admin'){
        if (move_uploaded_file($_FILES[$button_id]['tmp_name'], $target_file)) {
            echo "The file ". basename( $_FILES[$button_id]['name']). " has been uploaded.";
            chmod($target_file, 0666);
            self::dirCreate($selectedCity, $target_file, $file_name, $fileType);
            $query = "INSERT INTO public.file_upload(user_name, file_name, file_type ,time_upload) VALUES ('".$login_user."','".$file_name."','".$fileType."',now());";
            $file_logger -> dbConnect($query, false, true);

           header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
        } else {
            echo "Sorry, there was an error uploading your file.";
            //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
        }
      } else {
        echo 'your file have restricted type please try qgs or csv';
        //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
      }

    } else {
        echo 'Sorry, your file is too big';
        //header("location: main_page.php?restriction=".$restriction."&e_mail=".$login_user); // Redirecting To Other Page
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
        $stid = oci_parse($conn, $this->query. " WHERE CITY ='$city[2]' ");//AND ROWNUM <=1000
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
      $stid = oci_parse($conn, $this->query. " WHERE CITY ='$city' ");//AND ROWNUM <=1000
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
  } else {$group_value['dwgFile'] =  '-'; }
  if (file_exists($pdfFile)) {
    $group_value['pdfFile'] =  '+';
    $group_value['pdfFileModDate'] =  gmdate("Y-m-d",stat($pdfFile)['mtime']);
  } else {$group_value['pdfFile'] =  '-'; $group_value['pdfFileModDate'] = '-'; }
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
function topologyDirCreate($description, $city){
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
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function fileDate($folderLink, $checkList, $responseValue){
    $files = scandir($folderLink);
    $fileDate = '';
    foreach ($files as $fileKey => $filename) {
        if(strpos(mb_convert_case((string)$filename, MB_CASE_LOWER, "UTF-8") , $checkList['check'][array_search($responseValue, $checkList['response'])]) !== false){
           $fileDate = '<br>'.'<span style ="color:blue">'. gmdate("Y-m-d",stat($folderLink.$filename)['mtime']).'</span>';
           //echo $folderLink.$filename.'---'.$fileDate.'<br>';
        }
    }
    return $fileDate;
}
///////////////////////////////////////////////////////////////
function jsConsolLog($message){
  print "<script type='text/javascript'>console.log(".json_encode($message).");</script>";
}
?>