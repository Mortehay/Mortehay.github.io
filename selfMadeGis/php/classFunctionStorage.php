<?php
//ini_set('display_errors', 1);
//classes///////////////////////////////////////////////////////////////////////////////////
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
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//functions///////////////////////////////////////////////////////////////////////////////////////////////
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
  } else {$group_value['pdfFile'] =  '-'; }
  if (file_exists($imgFile)) {
    $group_value['imgFile'] =  '+';
  } else {$group_value['imgFile'] =  '-'; }
  return $group_value;
 } 
//////////////////////////////////////////////////////////////////////////////////////////////////////////
/*$obj = new dbConnSetClass;
print_r( $obj-> getProp('dbConnSet'));

$setNewdbConnSet = array(
    "host"=>"host=127.0.0.1",
    "port"=>"port=5432",
    "dbname"=>"dbname=postgres",
    "user"=>"user=postgres",
    "password"=>"password=Xjrjkzlrf30"
    );
echo '<hr>';
$obj -> setProp('dbConnSet',$setNewdbConnSet);
print_r( $obj-> getProp('dbConnSet'));
echo '<hr>';
echo $obj ->getProp('selectedCity');
echo '<hr>';
$obj -> setProp('selectedCity','bilatserkva');
echo '<hr>';


echo '<hr>';
print_r( $obj-> getProp('queryArrayKeys'));
echo '<hr>';

print_r( $obj-> getProp('queryArrayKeys'));
echo $obj ->getProp('selectedCity');
echo '<hr>';
$queryArrayKeys = array('cubic_city',  'cubic_street',  'cubic_house', 'cubic_flat',  'cubic_code',  'cubic_name',  'cubic_pgs_addr',  'cubic_ou_op_addr',  'cubic_ou_code', 'cubic_date_reg',  'cubic_coment',  'cubic_uname', 'cubic_net_type',  'cubic_house_id');
$obj -> setProp('queryArrayKeys',$queryArrayKeys);

$query = "Select ".implode(", ",$queryArrayKeys)." from bilatserkva.bilatserkva_ctv_topology limit 5;";
//echo '<hr>';
//echo '<hr>';
//print_r($obj -> dbConnect($query, $queryArrayKeys,true));
//echo '<hr>';
//$arr_response['response'] = $obj -> dbConnect($query, $queryArrayKeys,true);
//print json_encode($arr_response);
$query1 = "create temp table tmp(id serial, value varchar(100));";
$obj -> dbConnect($query1, false, false);
$query2 = "insert into tmp(value) values ('one'),('two'),('three');";
$obj -> dbConnect($query2, false, false);
$query3 = "select * from tmp";
$queryArrayKeys = array('id','value');
print_r($obj -> dbConnect($query3, $queryArrayKeys, true));

print_r($obj -> dbConnect($query, $queryArrayKeys, true));*/
?>