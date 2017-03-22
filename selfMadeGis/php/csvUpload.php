<?php
include('restriction.php'); 
include('classFunctionStorage.php');
///////////////////////////////////////

//$restriction = $_GET["restriction"];
ini_set('display_errors', 1);
$target_dir = "/tmp/";
$target_file = $target_dir . basename($_FILES["csv_file_upload"]["name"]);
$file_name = $_FILES["csv_file_upload"]["name"];
$selectedCity = substr($file_name,0,stripos($file_name, '_'));
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$csvUploadReturnRespond ='';
//echo $fileType;

// Check file size
if ($_FILES["csv_file_upload"]["size"] > 512000000) {
    $csvUploadReturnRespond .= "Sorry, your file is too large.";
    echo $csvUploadReturnRespond;
    $uploadOk = 0;
}
// Allow certain file formats
if($fileType !== "csv" ) {
    $csvUploadReturnRespond .= "Sorry, only CSV";
    echo $csvUploadReturnRespond;
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $csvUploadReturnRespond .= "Sorry, your file was not uploaded.";
    echo $csvUploadReturnRespond;
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["csv_file_upload"]["tmp_name"], $target_file)) {
        $csvUploadReturnRespond .= "The file ". basename( $_FILES["csv_file_upload"]["name"]). " has been uploaded.";
        echo $csvUploadReturnRespond;
        chmod($target_file, 0666);
        topologyCsvDirCreate($selectedCity, $target_file, $file_name);

       header("location: main_page.php?restriction=admin"); // Redirecting To Other Page
    } else {
        $csvUploadReturnRespond .= "Sorry, there was an error uploading your file.";
        echo $csvUploadReturnRespond;
    }
}
?>