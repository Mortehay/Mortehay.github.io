<?php

ini_set('display_errors', 1);


//$data = array('geschleht' => "'1'", "'_alter'"  => '1', "'producte'" => "'1'", 'monatliche_kosten' => "'1'", 'checker'  => "'1'", 'bewertung' => "'1'", 'wonhort' => "'1'");
$data = array('geschleht' => "'".$_POST['geschleht']."'", "'_alter'"  => "'".$_POST['alter']."'", "'producte'" => "'".$_POST['producte']."'", 'monatliche_kosten' => "'".$_POST['monatliche_kosten']."'", 'checker'  => "'".$_POST['checker']."'", 'bewertung' => "'".$_POST['bewertung']."'", 'wonhort' => "'".$_POST['wonhort']."'");
class mySqlConnect {

	private  $dbConnSet = array(
    "host"=>"127.0.0.1",
    "user"=>"root",
    "password"=>"",
    "dbname"=>"test"
    );
    public function myQuery($query){
    	//echo implode(" ,", $this->dbConnSet).'<hr>';
    	$conn = mysqli_connect($this->dbConnSet['host'], $this->dbConnSet['user'], $this->dbConnSet['password'], $this->dbConnSet['dbname']);
    	if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		} else {
			if (mysqli_query($conn,$query) === TRUE) {
			    echo "New record created successfully";
			} else {
			    echo "Error: " . $query . "<br>" . mysqli_error($conn);
			}
			$conn->close();
		}
    }
}

$inserter = new mySqlConnect;

$query = 'INSERT INTO test.request(geschleht, _alter, producte, monatliche_kosten, checker, bewertung, wonhort, timer) VALUES ('.implode(' ,',$data).',now() )';
//echo $data;
$inserter -> myQuery($query);

?>