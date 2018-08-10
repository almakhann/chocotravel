<?php
	require 'connect.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	//insert data to user_data
    	$id = $_POST['id'];
    	$sum_tax = $_POST['sum_tax'];
    	$sum_without_tax = $_POST['without_tax'];
    	$sum_penalty = $_POST['sum_penalty'];
    	$refund = $_POST['refund'];
		
		foreach ($id as $key => $value) {
			$update_user  = "UPDATE user_data 
			SET sum_tax='$sum_tax[$key]',without_tax='$sum_without_tax[$key]', sum_penalty='$sum_penalty[$key]', refund = '$refund[$key]'
			WHERE id='$value'";
			
			$conn->query($update_user);
		}
		$ref_result = $_POST['ref_result'];
		
		
		foreach ($ref_result as $key => $value) {
			$update_tax  = "UPDATE tax_data 
			SET Refund = '$value' WHERE id='$key'";
			$conn->query($update_tax);	
		}
		//send data to save_data

		$segment = $_POST['segment'];
		$fare_val = $_POST['fare-val'];
		$tax_val = $_POST['tax-val'];
		$percentage_val = $_POST['percentage-val'];
		$number_val = $_POST['number-val'];

		if($conn->query("SELECT * FROM penalty_data")->num_rows == 0){
			$send_1 = "INSERT INTO penalty_data (segment, fare_basis, country_code , penalty, penalty_price) VALUES ('$segment', '$fare_val', '$tax_val', '$percentage_val', '$number_val')";
			
		}
		else {
			
			$send_1 = "UPDATE penalty_data  SET segment = '$segment', fare_basis = '$fare_val', country_code = '$tax_val' , penalty = '$percentage_val', penalty_price = '$number_val' WHERE id = '1'";	
		}
		$conn->query($send_1);	
	
		
		header('Location: superviser.php');
		exit;
	}
?>