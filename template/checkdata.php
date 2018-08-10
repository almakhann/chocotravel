<?php
	require 'db.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	
		$drop_tax = "UPDATE tax_data SET Refund = 'refund' WHERE Refund is not null";
		$conn->query($drop_tax);

		$drop_pen = "truncate penalty_data";
		$conn->query($drop_pen);

		$drop_user = "UPDATE user_data SET sum_tax = 0, sum_penalty = 0 WHERE Refund is not null";
		$conn->query($drop_user);
		
		foreach ($data as $key => $value) {
			$id = $value['id'];
			$total_price = $value['total_price'];
			
			$drop_user = "UPDATE user_data SET without_tax = $total_price, refund = $total_price
			WHERE id = '$id'";

			$conn->query($drop_user);
		}
    	
    	if(isset($_POST['approve'])) {
    		foreach ($data as $i => $value) {
    			$name_surname = $value['name_surname'];
    			$ticket_id = $value['ticket_id'];
    			$loc = $value['loc'];
    			$total_price = $value['total_price'];
    			$sum_tax = $value['sum_tax'];
    			$without_tax = $value['without_tax'];
    			$sum_penalty = $value['sum_penalty'];
    			$refund = $value['refund'];
    		
	    		$send_data = "INSERT INTO tickets_data (name_surname, ticket_id, loc, total_price, sum_tax, without_tax, sum_penalty, refund) 
	    			VALUES ('$name_surname', '$ticket_id', '$loc', $total_price', '$sum_tax', '$without_tax', '$sum_penalty', '$refund')";	

				$conn->query($send_data);	
    		}


    		$segment = $penalty[0]['segment'];
    		$fare_basis = $penalty[0]['fare_basis'];
    		$country_code = $penalty[0]['country_code'];
    		$penalty_1 = $penalty[0]['penalty'];
    		$penalty_price = $penalty[0]['penalty_price'];
    		
    		/////////////////////////////////////////////////////////////////////////////////////
			$send_save = "INSERT INTO save_data (segment, fare_basis, country_code , penalty, penalty_price) 
						VALUES ('$segment', '$fare_basis', '$country_code', '$penalty_1', '$penalty_price')";
			
			$conn->query($send_save);	
		
    	}
    	header('Location:index.php');
    	exit;
	}

?>