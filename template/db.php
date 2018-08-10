<?php 
  
  require 'connect.php';

  //select user_data with tax
  $query_select = "Select * From user_data Join dep_data 
                    On user_data.id = dep_data.user_id";
  
  $query_select_last = "Select * From last_data Join dep_data 
                    On last_data.id = dep_data.user_id";
  
  $result_select = $conn->query($query_select);
  $result_select_last = $conn->query($query_select_last);

  $data = [];

  if($result_select_last->num_rows > 0) {
    $i=0;
    while($row = $result_select_last->fetch_assoc()) {
      $data[$i]['id'] = $row['user_id']; 
      $data[$i]['name_surname'] = $row['name'] . ' ' . $row['surname'];
      $data[$i]['ticket_id'] = $row['ticket_id'];
      $data[$i]['total_price'] = $row['total_price'];
      $data[$i]['status'] = $row['status'];
      $data[$i]['loc'] = $row['loc'];
      $data[$i]['fare_basis'] = $row['fare_basis'];
      $data[$i]['sum_tax'] = $row['sum_tax'];
      $data[$i]['without_tax'] = $row['without_tax'];
      $data[$i]['sum_penalty'] = $row['sum_penalty'];
      $data[$i]['refund'] = $row['refund'];
      
      $i++;
    }
  }
  else {
    $i=0;
    while($row = $result_select->fetch_assoc()) {
      $data[$i]['id'] = $row['user_id']; 
      $data[$i]['name_surname'] = $row['name'] . ' ' . $row['surname'];
      $data[$i]['ticket_id'] = $row['ticket_id'];
      $data[$i]['total_price'] = $row['total_price'];
      $data[$i]['status'] = $row['status'];
      $data[$i]['loc'] = $row['loc'];
      $data[$i]['fare_basis'] = $row['fare_basis'];
      $data[$i]['sum_tax'] = $row['sum_tax'];
      $data[$i]['without_tax'] = $row['without_tax'];
      $data[$i]['sum_penalty'] = $row['sum_penalty'];
      $data[$i]['refund'] = $row['refund'];
      
      $i++;
    }
  }

  //select tax nature  
  $get_country_code = "Select DISTINCT country_code From tax_data";
  $result_select_1 = $conn->query($get_country_code);
  $country_code = [];
  if($result_select_1->num_rows > 0) {
    $i = 0;
    while($row = $result_select_1->fetch_assoc()) {
      $country_code[$i]['country_code'] = $row['country_code'];
      $i++;
    }
  }

  // user and tax data
  $get_tax = "Select * From tax_data Join user_data On user_data.id = tax_data.user_id";
  $result_select_2 = $conn->query($get_tax);
  $tax = [];
  if($result_select_2->num_rows > 0) {
    $i = 0;
    while($row = $result_select_2->fetch_assoc()) {
      $tax[$i]['user_id'] = $row['user_id'];
      $tax[$i]['name_surname'] = $row['name'] . ' ' . $row['surname'];
      $tax[$i]['price'] = $row['price'];
      $tax[$i]['tax_nature'] = $row['tax_nature'];
      $tax[$i]['country_code'] = $row['country_code'];
      $tax[$i]['Refund'] = $row['Refund'];
      $i++;
    }
  }


  // user and tax data
  $get_rule = "Select * From rule_data";
  $result_select_3 = $conn->query($get_rule);
  $rule = [];
  if($result_select_3->num_rows > 0) {
    $i = 0;
    while($row = $result_select_3->fetch_assoc()) {
      $rule[$i]['fare'] = $row['fare'];
      $rule[$i]['rule_text'] = $row['rule_text'];
      $i++;
    }
  }

    //get penalty percentage and price
    $select_penalty = "SELECT * FROM penalty_data";
    $result_select_penalty = $conn->query($select_penalty);
    $penalty = [];
    if($result_select_penalty->num_rows > 0) {
      $i = 0;
      while($row = $result_select_penalty->fetch_assoc()) {
        $penalty[$i]['id'] = $row['id'];
        $penalty[$i]['segment'] = $row['segment'];
        $penalty[$i]['fare_basis'] = $row['fare_basis'];
        $penalty[$i]['country_code'] = $row['country_code'];
        $penalty[$i]['penalty'] = $row['penalty'];
        $penalty[$i]['penalty_price'] = $row['penalty_price'];
        $i++;
      }
    }


?>