<?php
 	require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>AviaAgent</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<!-- <link rel="stylesheet" href="style.css"> -->
	<style>
		body, html {
			padding: 0;
			margin: 0;
			font-size: 12px;
			padding-top: .5rem;
			background: url(img/background.jpg) repeat-y center 0;
			color: #212c5b;
		}
		h1 {
			margin-bottom: 1rem;
			padding-bottom: .5rem;
			color: #212c5b;
		}
		.btn {
			margin-bottom: 1rem;
		}
		.btn-orange {
			background: #fe9922;
			color: #fff;
		}

		.tax-number {
			font-weight: bold;
		}
		table .difference {
			font-weight: bold;
		}
		table .penalty-tax {
			font-weight: bold;
		}
		table th,
		table td {
			padding:0.3rem 1rem!important;
			color: #353535;
			border-color: #9ea3b7!important;
		}
		table tr {
			padding: 0;
		}

		.td-remove {
			border: none!important;
		}
		.table-farerule td {
			padding: 0!important;
		}
		#tax-table .ref-non-input {
			display: none;
		}
		.dif {
			color: green;
		}
		.pen {
			color: red;
		}
		.dif, .pen {
			font-weight: bold;
		}
		.farerule p {
			border: 1px solid #ccc;
    		padding: .9rem;
			height: 300px;
			max-width: 250px;
			overflow-y: scroll;

		}
	</style>
	
</head>
<body>
		<header>
			<img src="img/logo.png" alt="choco logo" class="d-block mx-auto">
		</header>
		<div class="user-info">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>User Info</h1>
							<table class="table" id="user-table">
								<tr>
									<th>ID</th>
									<th>Name Surname</th>
									<th>tiket-id</th>
									<th>Status</th>
									<th>Segment</th>
									<th>Fare-basis</th>
									<th>Total price</th>
									<th>Sum Tax</th>
									<th>Without tax</th>
									<th>Sum penalty</th>
									<th>refund</th>
									
								</tr>
								<?php foreach($data as $value):?>
								<tr>
									<td class="id"><?php echo $value['id']?></td><input type="hidden" name='id[]' value="<?=$value['id']?>"></td>
									<td class="name-surname"><?php echo $value['name_surname']?></td><input type="hidden" name='name_surname[]' value="<?=$value['name_surname']?>">
									<td class="ticket-id"><?=$value['ticket_id']?></td><input type="hidden" name='ticket_id[]' value="<?=$value['ticket_id']?>">
									<td class="status"><?=$value['status']?></td><input type="hidden" name='status[]' value="<?=$value['status']?>">
									<td class="segment"><?=$value['loc']?></td><input type="hidden" name='segment[]' value="<?=$value['segment']?>">
									<td class="fare-basis"><?=$value['fare_basis']?></td><input type="hidden" name='fare_basis[]' value="<?=$value['fare_basis']?>">
									<td class="ticket-price"><?=$value['total_price']?></td><input type="hidden" name='total_price[]' value="<?=$value['total_price']?>">
									<td class="sum_tax pen"><?=$value['sum_tax']?></td><input type="hidden" name='sum_tax[]' value="<?=$value['sum_tax']?>">
									<td class="without_tax dif"><?=$value['without_tax']?></td><input type="hidden" name='without_tax[]' value="<?=$value['without_tax']?>">
									<td class="sum_penalty pen"><?=$value['sum_penalty']?></td><input type="hidden" name='sum_penalty[]' value="<?=$value['sum_penalty']?>">
									<td class="refund dif"><?=$value['refund']?></td><input type="hidden" name='refund[]' value="<?=$value['refund']?>">
								</tr>
								<?php endforeach; ?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td class="total-price-ticket tax-number"></td>
									<td class="total-penalty-tax pen"></td>
									<td class="total-price-ticket-1 dif"></td>
									<td class="total-penalty-farerule pen"></td>
									<td class="result dif"></td>			
								</tr>
							</table>		
						
					</div>
				</div>
			</div>
		</div>
		<div class="tax-list">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>Tax</h1>
							<table class="table table-bordered" id="tax-table">
								<tr>
									<th>User_id</th>
									<th>Name</th>
									<th>Price</th>
									<th>Tax_nature</th>
									<th>Country_code</th>
									<th>Ref-result</th>
								</tr>
								<?php foreach($tax as $arr):?>
								<tr>
									<td class="user-id"><?=$arr['user_id']?></td>
									<td class="name-surname-1"><?=$arr['name_surname']?></td>
									<td class="price"><?=$arr['price']?></td>
									<td class="tax-nature"><?=$arr['tax_nature']?></td>
									<td class="country-code"><?=$arr['country_code']?></td>
									<td class="ref-result"><?=$arr['Refund']?></td>
									<input type="hidden" value="<?=$arr['Refund']?>" name="ref_result[]">
								</tr>
								<?php endforeach;?>
							</table>
							<!-- <input type="submit" class="btn btn-secondary btn-count" value="Count"> -->
						
					</div>
				</div>
			</div>
		</div>
		<div class="rule-wrapper">
			<div class="container">
				<div class="row align-items-end">
					<div class="farerule d-flex">
					<?php foreach($rule as $key => $arr):?>
						<div class="fare-text mr-3">
							<h4><?php echo $arr['fare']?></h4>	
							<p class="mr-2"><?=nl2br($arr['rule_text'])?></p>	
						</div>
						
					<?php endforeach;?>
					</div>
					<div class="result-penalty-wrapper">
						<?php foreach ($penalty as $key => $value):?>
						<div class="result-penalty">
							<span>Penalty percentage:</span>
							<span class="tax-number"><?=$value['penalty']?>%</span>
						</div>
						<div class="result-penalty">
							<span>Penalty price:</span>
							<span class="tax-number"><?=$value['penalty_price']?></span>
						</div>
						<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<form action="checkdata.php" method="POST" class="d-flex justify-content-end w-75">
				<input type="submit" class="btn btn-danger mr-3" value="Cancel" name = "cancel">
				<input type="submit" class="btn btn-success"  value="Approve" name= "approve">
			</form>
		</footer>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<!-- <script src="js/main.js"></script> -->
	
	<script>
				
	</script>
</body>
</html>