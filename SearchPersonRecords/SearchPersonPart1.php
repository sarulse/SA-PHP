<?php
/* 
   Search for a person record using a valid US phone number
   Results of the file can be viewed at 
   http://www.sarulsel.us/PHPProjects/SearchPersonPart1.php	

*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
	function validatePhone(){  
            var num = document.forms["personSearch"]["phone"].value;    
            var pattern = /^\(?(\d{3})\)?[-. ]?(\d{3})[-. ]?(\d{4})$/;  
            var result = pattern.test(num); 
            var mesg = "Enter a valid US Phone Number Format:\n(123)-4567890\n (or) 1234567890\n (or) 123-456-7890\n (or) 123-4567890";
            if (result == false) {
                alert(mesg);
                return false;                   
            }              
        } 
</script>
<link rel="stylesheet" type="text/css" href="formstyle.css">
</head>
<body>
       <form name="personSearch"  method="POST"  accept-charset="UTF-8" target="_self">
           	<fieldset align="center">
                    <legend>Search Person Record </legend>
                    <br/>
                    Enter Phone Number to search a person:<br>
                    <input type="tel" name="phone" id="tel" required >
                    <br/><br/>
             </fieldset>
             <br/>
             <input type="submit" value="Submit" onclick=" return validatePhone()">
      </form>
<?php
	//Get the Phone Number
	function getPhoneNo () {
        $ph_num = '';
		if(isset($_POST['phone']))
		{	
			$phone_num = $_POST['phone'];
			//remove special characters from phone_number
			$ph_num = preg_replace('/[^A-Za-z0-9\$]/', '', $phone_num);
			if (!(is_numeric($ph_num))) 
				die ("Entered phone number is not a number\n");	
		}
		return $ph_num;		
	}
	// Create XML Request
	function xmlRequest() {
		
		include_once("urlParams.php");		
		try {
				$phone_number = getPhoneNo();
				// Check number of digits in the phone number
				$numberOfDigits = strlen($phone_number);	
				if ($numberOfDigits != 10) 
					throw new Exception ("Enter a valid US Phone Number Format:\n(123)-4567890\n (or) 1234567890\n (or) 123-456-7890\n (or) 123-4567890");
		}
		catch(Exception $e) {
				echo "Error in the phone number entered:</br> ".$e->getMessage();
		}
		$area_code = substr($phone_number,0,3);		
		$tel_number = substr($phone_number,3,7);		
		// Get cURL resource
		$curl = curl_init();	
		$url = $urlParams1.'&areacode=';
		$url.= $area_code;
		$url.= '&phone=';
		$url.= $tel_number;
		
		// Set some options - 
		curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url    
				));
		// Send the request & save response to $data
		$data = curl_exec($curl);
		if(!$data){
			//die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			exit ("Could not connect to the XML server<br/>");
		}
		else
		{
			$data = simplexml_load_string($data);		
		}	
		// Close request to clear up some resources
		curl_close($curl);
		return $data;
	}
	//Print Search Results
	function printSearchResults() {
		$phone_number = getPhoneNo();
       	$xml_data = xmlRequest();

		if ($xml_data === false) {
			echo "Failed loading XML: ";
			foreach(libxml_get_errors() as $error) {
				 echo "<br>", $error->message;
			 }
		}
		else
		{
			//$count counts the no. of records in the xml file
			//By default the empty record counts as 1 record
			$count = count($xml_data->record);
			if ($count == 1) {
			
				echo "<p align=\"CENTER\">No person record can be found for the entered phone number</p>";       			
			}	
			if ($count>1)
			{
				//echo "Total number of Records in the address book: ".$count."</br>";
				echo "<h2>"."Search Results</h2>";
				echo "<h4>--------------------------------------------------------------------</h4>";
				$match_found = 0;
				for ($i=0; $i<$count; $i++){                   
					//echo "\nPhone number of: ".$xml_data->record[$i]->lastname." is\t".$xml_data->record[$i]->phone;                   
					$ph =  preg_replace('/[^A-Za-z0-9\$]/', '', ($xml_data->record[$i]->phone));
					if ($ph == $phone_number)
					{
						$givenName = $xml_data->record[$i]->firstname;
						$lastName =  $xml_data->record[$i]->lastname;
						$state =  $xml_data->record[$i]->state;
						?>
						<a href="displayDetail.php?firstname=<?php echo $givenName; ?>&amp;lastname=<?php echo $lastName; ?>&amp;state=<?php echo $state; ?>" >
						<?php
							echo "<h4>";
							echo $givenName." ".$xml_data->record[$i]->middlename." ".$lastName ;
							echo "\tState: ". $state."\n";
							echo "\tPhone #:".$xml_data->record[$i]->phone."\n";
							$match_found++;
							echo "</h4>";
					}                   
				}
						?>
						</a>
						<?php 
							echo "<h4>--------------------------------------------------------------------</h4>";
							echo "<h4>Number of Matched Records: ".$match_found."</h4>";
			}
      		}
	}
	printSearchResults();	
?>
</body>
</html>
