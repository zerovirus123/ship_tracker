		<?php
			
		    //MySQL connection information (private so no one sees it)
		    require("connection_info.php");
			$doc = new DOMDocument('1.0', 'utf-8');
			$shipArray = array(); 
				
			//establishes connection
			$connection = mysqli_connect($host, $username, $password, $database);
					
			//If connection fails
			if (!$connection){
				die('Could not connect ' . mysql_error());
			}				
					
			//Database selection from MAMP MySQL
			$db_selected = mysqli_select_db($connection, $database);
			//DB error indicator
			if (!$db_selected){
     			die('Could not use database.' . mysql_error());
	        }
	        
	        //Pick all the rows in the ship tracker database
	        $query = "SELECT * FROM markers WHERE 1";
			$result = mysqli_query($connection, $query);
			
			//Query error
			if (!$result) {
  				die('Invalid query. ' . mysql_error());
			}
			
			// Iterate through the rows, adding XML nodes for each
			while ($row = @mysqli_fetch_assoc($result)){
  			
				$ship = array(
							"name" => $row['name'],
							"lat" => $row['lat'],
							"lng" => $row['lng']
						);
				
				array_push($shipArray, $ship); 
			}
			
			echo json_encode($shipArray);
			
		?>