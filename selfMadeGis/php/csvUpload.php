<?php
include('restriction.php'); 
//$restriction = $_GET["restriction"];
ini_set('display_errors', 1);
$target_dir = "/tmp/";
$target_file = $target_dir . basename($_FILES["csv_file_upload"]["name"]);

$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
$csvUploadReturnRespond ='';
//echo $fileType;
// Check if image file is a actual image or fake image
$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','csv');
if(in_array($_FILES["csv_file_upload"]["type"],$mimes) ){
    $csvUploadReturnRespond .= "File is an CSV ";
    echo $csvUploadReturnRespond;
        $uploadOk = 1;
  // do something
} else {
    $csvUploadReturnRespond .= "File is not an CSV.";
    echo $csvUploadReturnRespond;
        $uploadOk = 0;
}

// Check file size
if ($_FILES["csv_file_upload"]["size"] > 50000000) {
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
        header("location: main_page.php?restriction=admin"); // Redirecting To Other Page
    } else {
        $csvUploadReturnRespond .= "Sorry, there was an error uploading your file.";
        echo $csvUploadReturnRespond;
    }
}
?>