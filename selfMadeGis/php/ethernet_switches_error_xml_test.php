<?php
ini_set('display_errors', 1);
include('restriction.php'); 
//include('classFunctionStorage');


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

  	//xmlPrint($xmlPorts, $city[1], 'ports');
  	xmlPrint($xmlSwitches, $city[1], 'switches');
  	//xmlPrint($xmlSwitches);
}

?>
