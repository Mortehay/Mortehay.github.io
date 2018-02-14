<?php
ini_set('display_errors', 1);
include('classFunctionStorage.php');
$folderTypes = array('/cc/','/air/','/she/','/topology/','/help/');
$tester = new dbConnSetClass;
$tester->htaccessFilesGeneration($folderTypes, 'allow');
?>