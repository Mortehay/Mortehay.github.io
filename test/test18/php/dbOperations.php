<?php

ini_set('display_errors', 1);
//mb_internal_encoding('windows-1251');
mb_internal_encoding('UTF-8'); 

class mySqlConnect {

	private  $dbConnSet = array(
    "host"=>"127.0.0.1",
    "user"=>"root",
    "password"=>"",
    "dbname"=>"test"
    );
    public function __get($property) {
	    if (property_exists($this, $property)) {
	      	return $this->$property;
	    }
	}
  	public function __set($property, $value) {
	    if (property_exists($this, $property)) {
	      	$this->$property = $value;
	    }

	    return $this;
	}
    public function myQuery($query,$queryArrayKeys,$dbClose){
    	//echo implode(" ,", $this->dbConnSet).'<hr>';
    	$conn = mysqli_connect($this->dbConnSet['host'], $this->dbConnSet['user'], $this->dbConnSet['password'], $this->dbConnSet['dbname']);
    	if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		} else {
			if ($queryArrayKeys) {
				$arr_response = array();
				if ($result=mysqli_query($conn,$query)) {
        			while ($row=mysqli_fetch_row($result)){
						$arr = array();
			            foreach ($row as $key => $value) {
			                if(is_string($row[$key])){
					            $arr[$queryArrayKeys[$key]] = utf8_encode($row[$key]);
					        } else {$arr[$queryArrayKeys[$key]] = $row[$key];}
			            }
			            array_push($arr_response, $arr );
					}
			    	//echo "New record created successfully";
			    	return $arr_response;
			    }
			} else {
			    echo "Error: " . $query . "<br>" . mysqli_error($conn);
			}
			if($dbClose){$conn->close();}
		}
    }
    public function queryCache($query,$queryArrayKeys,$dbClose, $timer, $filename){

		if (file_exists($filename)){
			if (time()-filemtime($filename) > $timer) {
			  $arr_response = self::myQuery($query,$queryArrayKeys,$dbClose);
    			if(is_array($arr_response)){
    				$fp = fopen($filename, 'w');
					fwrite($fp, json_encode($arr_response));
					fclose($fp);
    			}
    			echo json_encode($arr_response);
			} else {
				$stored_file = fopen($filename, "r") or die("Unable to open file!");
				echo fgets($stored_file);
			}
		} else {
			$arr_response = self::myQuery($query,$queryArrayKeys,$dbClose);
			if(is_array($arr_response)){
				$fp = fopen($filename, 'w');
				fwrite($fp, json_encode($arr_response));
				fclose($fp);
			}
			echo json_encode($arr_response);
		}
		
    }
}

$connection = new mySqlConnect;

$queryArrayKeys = array('table_id',  'tu_number' , 'tu_date' , 'rental_contract_new_num' , 'rental_contract_new_date' , 'rental_contract_new_add_num' , 'rental_contract_new_add_date' , 'acceptance_act_num' , 'acceptance_act_date' , 'cartogram_num' , 'cartogram_date' , 'cable_mount_date' , 'rental_contract_old_num' , 'rental_contract_old_date' , 'rental_contract_old_add_num' , 'rental_contract_old_add_date' , 'approval_cartogram_num' , 'approval_cartogram_date' , 'cable_Ukrtelefon_id' , 'cable_type' , 'cable_short_type_description' , 'cable_description' , 'cable_description_fact' , 'cable_diameter' ,  'progect_number' , 'executive_doc_state' , 'notes1' , 'contract_start_address' , 'contract_start_pit' ,   'contract_end_address' , 'contract_end_pit' ,  'contract_chanel_length' , 'cable_length_house' , 'other_contract_channel_length' , 'total_cable_length' ,  'notes2' , 'rezerve1' , 'rezerve2' , 'rezerve3');
$query = "select table_id,  tu_number , tu_date , rental_contract_new_num , rental_contract_new_date , rental_contract_new_add_num , rental_contract_new_add_date , acceptance_act_num , acceptance_act_date , cartogram_num , cartogram_date , cable_mount_date , rental_contract_old_num , rental_contract_old_date , rental_contract_old_add_num , rental_contract_old_add_date , approval_cartogram_num , approval_cartogram_date , cable_Ukrtelefon_id , cable_type , cable_short_type_description , cable_description , cable_description_fact , cable_diameter ,  progect_number , executive_doc_state , notes1 , contract_start_address , contract_start_pit ,   contract_end_address , contract_end_pit ,  contract_chanel_length , cable_length_house , other_contract_channel_length , total_cable_length ,  notes2 , rezerve1 , rezerve2 , rezerve3 from table8;";
$dbClose = true;
$timer = 3600;
$filename = 'results.json';
//$return_array = $connection -> myQuery($query,$queryArrayKeys,$dbClose);

//echo json_encode($return_array);
$cached_json = $connection -> queryCache($query,$queryArrayKeys,$dbClose, $timer, $filename);
?>