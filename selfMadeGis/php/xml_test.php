<?php
//ini_set('display_errors', 1);
include('restriction.php'); 
function stateUpdateInDatabase($stateArray, $city){

	  $host        = "host=127.0.0.1";
	  $port        = "port=5432";
	  $dbname      = "dbname=postgres";
	  $credentials = "user=simpleuser password=simplepassword";
	  $db = pg_connect( "$host $port $dbname $credentials"  );
	  if(!$db){
                echo "Error : Unable to open database\n";
                } else {
                //echo "Opened database successfully\n";
                	
		$tmpCreate = "CREATE TEMP TABLE tmp(tid serial, id  varchar(100), port varchar(100),switch_local varchar(100),switch_remote varchar(100),type varchar(100),update_time varchar(100),up_time varchar(100),down_time varchar(100),alarm_state varchar(100),port_state varchar(100),errs_in varchar(100),errs_out varchar(100),ip_addr varchar(100),mac varchar(100)/*,dev_name varchar(100)*/,dev_state varchar(100),inventary_state varchar(100),to_num varchar(100));";
		$tmpInsert = 'INSERT INTO tmp(id , port ,switch_local ,switch_remote ,type ,update_time ,up_time , down_time ,alarm_state ,port_state ,errs_in ,errs_out ,ip_addr ,mac/* ,dev_name*/ ,dev_state ,inventary_state,to_num) ';
                	$tmpInsertValues = 'VALUES ';
                	foreach ($stateArray as $arr) {
                		$tmpInsertValues .= '('." '".$arr ['id']."',"." '".$arr ['port']."',"." '".$arr ['switch_local']."',"." '".$arr ['switch_remote']."',"." '".$arr ['type']."',"." '".$arr ['update_time']."',"." '".$arr ['up_time']."',"." '".$arr ['down_time']."',"." '".$arr ['alarm_state']."',"." '".$arr ['port_state']."',"." '".$arr ['errs_in']."',"." '".$arr ['errs_out']."',"." '".$arr ['ip_addr']."',"." '".$arr ['mac']./*"',"." '".$arr ['dev_name'].*/"',"." '".$arr ['dev_state']."',"." '".$arr ['inventary_state']."',"." '".$arr ['to_num']."'".')';
                		if (next($stateArray )) {
			        $tmpInsertValues .= ','; // Add comma for all elements instead of last
			}
	  	
	  	}
	  	$tmpInsertValues .= ';'; 
		$tmpCityUpdate = "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'alarm_state',"."'alarm_state'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'port_state',"."'port_state'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'errs_in',"."'errs_in'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'errs_out',"."'errs_out'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'dev_state',"."'dev_state'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'errs_out',"."'errs_out'".");" . "SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'inventary_state',"."'inventary_state'".");"."SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'update_time',"."'update_time'".");" ."SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'up_time',"."'up_time'".");" ."SELECT  conditional_update("."'".$city."',"."'".$city."_switches',"."'down_time',"."'down_time'".");" ."DROP TABLE tmp;";
		$sql = $tmpCreate.' '.$tmpInsert.' '.$tmpInsertValues.' '.$tmpCityUpdate;
		$ret = pg_query($db, $sql);
		echo $sql;

	 }
	 pg_close($db); // Closing Connection
}
function xmlPrint($xml, $city){
	$stateArray = array();
	//echo '<hr>';
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
		stateUpdateInDatabase($stateArray, $city);
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

	return true;

}

foreach ($cities as $city) {
	$content = utf8_encode(file_get_contents('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmSwitches&companyCode='.$city[0] ));
	$xmlSwitches = simplexml_load_string($content);
	$content = utf8_encode(file_get_contents('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmPorts&companyCode='.$city[0]));
	$xmlPorts = simplexml_load_string($content);
	//$xmlSwitches= new SimpleXMLElement('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmSwitches&companyCode='.$city , 0 , true);
	//$xmlPorts= new SimpleXMLElement('http://sysapi_gis:v8v3ggfXVaSuW9nZ@work.volia.net/w2/api/ethernet.php?method=getAlarmPorts&companyCode='.$city , 0 , true);
	//------------------------------CSV creation-------------------------------------------
	csvFromXml($xmlPorts, $city[1],'ports');
  	csvFromXml($xmlSwitches, $city[1],'switches');
  	//-------------------------------------------------------------------------------------------

  	xmlPrint($xmlPorts, $city[1]);
  	//xmlPrint($xmlSwitches);
}

?>
