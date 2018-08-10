<?php
 	require 'db.php';
 	$size = 0;
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
	<form action="template/senddata.php" method="POST">
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
									<td class="name-surname"><?php echo $value['name_surname']?></td>
									<td class="ticket-id"><?=$value['ticket_id']?></td>
									<td class="status"><?=$value['status']?></td>
									<td class="segment"><?=$value['loc']?></td>
									<td class="fare-basis"><?=$value['fare_basis']?></td>
									<td class="ticket-price"><?=$value['total_price']?></td>
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
				<div class="row">
					<div class="col-md-10">
						<h1 class="w-100">Rule</h1>
						<?php foreach($rule as $key => $arr):?>
						<div class="farerule d-flex flex-wrap mb-3">
							<div class="fare-text mr-3">
								<h4><?php echo $arr['fare']?></h4>	
								<p class="mr-2"><?=nl2br($arr['rule_text'])?></p>	
							</div>
							<div class="rule-wrapper">
								<div class="row align-items-end mb-2">
									<div class="col-md-2">
										<div class="form-group">
											<label for="percentage">Percentage: </label>
											<input type="text" class="form-control rule percentage">	
										</div>		
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="number">Price: </label>
											<input type="text" class="form-control rule number">	
										</div>		
									</div>
									<div class="col-md-3">
										<button class="btn btn-calculate btn-orange">Calculate</button>		
									</div>							
								</div>
								<table class="table table-bordered table-farerule" id=<?='table-farerule-' . $key?>>
									<tr>
										<th>ID</th>
										<th>Name Surname</th>
										<th>Price ticket(without tax)</th>
										<th>Percentage</th>
										<th>Penalty</th>
									</tr>
								</table>			
							</div>
						</div>
						<?php endforeach;?>
					</div>
					<div class="col-md-2" id="div-tax-ist-table">
						<h1>Tax list</h1>
						<table class="table table-bordered" id="tax-list-table">
							<tr>
								<th>Country code</th>
								<th>Ref / Non</th>
							</tr>
							<?php foreach($country_code as $arr):?>
							<tr>
								<td class="ref-non"><?=$arr['country_code']?></td>
								<td class="ref-non-select">
									<select class="tax-select" name=<?=$arr['country_code']?> id="">
										<option value="non-refundable">non-refundable</option>
										<option value="refundable">refundable</option>
									</select>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
						<button class="btn btn-ref-non btn-orange">Add</button>
					</div>
				</div>
			</div>
		</div>
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						
							<div class="w-100">
								<div class="input-result-wrapper">
									<input type="text" class="" name="segment" value="<?=$data[0]['loc']?>" >
									<input type="text" class="tax-val" name="tax-val">
									<input type="text" class="fare-val" name="fare-val">
									<input type="text" class="percentage-val" name="percentage-val">
									<input type="text" class="number-val" name="number-val">
									<input type="text" class="fare">
								</div>
								<input type="submit" class="btn btn-submit mb-5 d-block ml-auto btn-orange" value="Send">
							</div>	
					
					</div>
				</div>
			</div>
		</footer>
	</form>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<!-- <script src="js/main.js"></script> -->
	
	<script>
		$(document).ready(function() {
			//validation for empty to each input
			$.fn.isEmpty = function(formInput) {
				var empty = true;
				$.each(formInput, function() {
					if($(this).val() != '') {
						return empty = false;
					}
				});
				return empty;
			}
			$.fn.getTotal = function(sum) {
				var total = 0;
				$.each(sum, function() {
					total+=parseInt($(this).html());
				})
				return total;
			}
			$.fn.changeColor = function(ref_result) {
				$.each(ref_result, function() {
					
					if($(this).html().includes('non-refundable')) {
						$(this).closest('tr').find('.price').removeClass('dif');
						$(this).closest('tr').find('.price').addClass('pen');
					}
					else {
						$(this).closest('tr').find('.price').removeClass('pen');
						$(this).closest('tr').find('.price').addClass('dif');

					}
				});
			}
			var total_price = $(this).getTotal($('.ticket-price'));
			var total_tax = $(this).getTotal($('.sum_tax'));
			var total_without_tax = $(this).getTotal($('.without_tax'));
			var total_penalty = $(this).getTotal($('.sum_penalty'));
			var total_refund_price = $(this).getTotal($('.refund'));
			

			$('.total-price-ticket').text(total_price);
			$('.total-penalty-tax').text(total_tax);
			$('.total-price-ticket-1').text(total_without_tax);
			$('.total-penalty-farerule').text(total_penalty);
			$('.result').text(total_refund_price);

			$(this).changeColor($('.ref-result'));
			//tax-list result
			$('.btn-ref-non').click(function() {
				var ref_non_list = [];
				//save all tax-list as array
				var str ='';
				$.each($('select.tax-select'),function() {
					
					dict = {};
					dict['name'] = $(this).attr('name');
					dict['value'] = $(this).val();
					
					ref_non_list.push(dict);
					//saving data
					if($(this).val() == 'non-refundable') {
						str = str + $(this).attr('name') + ' '; 
					}
				});
				$('.tax-val').val(str);

				//insert result to table for each ref-result
				var count = 0;
				
				$.each($('.country-code'), function() {
					var thiss = $(this);
					//sometimes to tax-nature added exrtra spaces !!!! problem 
					str = thiss.html().replace(/\s/g, '');
					
					for(var i=0; i<ref_non_list.length; i++) {
						if(ref_non_list[i].name == str) {
							thiss.closest('tr').find('.ref-result').text(ref_non_list[i].value);
							thiss.closest('tr').find('.ref-result').next().val(ref_non_list[i].value);
						}
					}
					count++;
				});
				$(this).changeColor($('.ref-result'));
				
				
				$.each($('.id'), function() {
					var thiss = $(this);
					var sum_tax = 0;
					$.each($('.user-id'), function() {	
						var tr = $(this).closest('tr');
						if($(this).text() == thiss.text() && tr.find('.ref-result').text().includes('non-refundable')) { // 
							var price = parseInt($(this).closest('tr').find('.price').text());
							
							sum_tax+=price;
						}

					});
					var tr_id = thiss.closest('tr');
					var sum_ticket = parseInt(tr_id.find('.ticket-price').text());
					var sum_penalty = parseInt(tr_id.find('.sum_penalty').text());

					var sum_without_tax = sum_ticket - sum_tax;
					var refund = sum_without_tax - sum_penalty;

					tr_id.find('.sum_tax').text(sum_tax);
					tr_id.find('.sum_tax').next().val(sum_tax);
					tr_id.find('.without_tax').text(sum_without_tax);
					tr_id.find('.without_tax').next().val(sum_without_tax);
					tr_id.find('.refund').text(refund);
					tr_id.find('.refund').next().val(refund);
				});
		

				total_tax = $(this).getTotal($('.sum_tax'));
				total_without_tax = $(this).getTotal($('.without_tax'));
				total_penalty = $(this).getTotal($('.sum_penalty'));
				total_refund_price = $(this).getTotal($('.refund'));
				
				$('.total-penalty-tax').html(total_tax);
				$('.total-price-ticket-1').html(total_without_tax);
				$('.total-penalty-farerule').html(total_penalty);
				$('.result').html(total_refund_price);	
				
				return false; 
							
				
			});
			

			$('.btn-calculate').click(function() {


				var rule_wrapper = $(this).closest('.rule-wrapper');
				if(!$(this).isEmpty(rule_wrapper.find('input.rule'))) {
					
					//get value from form
					var percentage = rule_wrapper.find('.percentage').val();
					var price_penalty = rule_wrapper.find('.number').val();
					
					//add table for penalty
					$.each($('.id'), function() {
						var thiss = $(this); 
						var tr = thiss.closest('tr');
				
						var id = tr.find('.id').html(); 
						var name_surname = tr.find('.name-surname').html();
						var sum_without_tax = tr.find('.without_tax').html();
						var number =  percentage != '' ? parseInt( (parseInt(percentage) / 100) * sum_without_tax) : price_penalty;
						

						rule_wrapper.find('table').append("<tr><td class='user_id'>"+ id +"</td>><td class='name'>"+ 
						name_surname+"</td><td class='ticket_price'>"+sum_without_tax+"</td><td class='percentage-1'>"+ 
						percentage +"</td><td class='penalty-farerule pen'>"+ number
						+"</td></tr>");
					
					});
					//remove all button than add with new button 
					$('.btn-danger').closest('td').remove();
					$.each($('.user_id'), function() {
						if($(this).html() == 1) {
							$(this).closest('tr').append("<td class='td-remove'><button class='btn btn-danger m-0'>Delete</button></td>");
						}
					})


					var sum_penalty_arr = [];
					$.each($('.table-farerule'), function() {
						var dic = {};
						var sum_percentage = 0;
						var sum_penalty = 0;
						$.each($(this).find('.percentage-1'), function() {
							if($(this).closest('tr').find('.user_id').text() == 1) {
								if($(this).text() != ''){
									sum_percentage+=parseInt($(this).text());
								}
								else {
									sum_penalty+=parseInt($(this).closest('tr').find('.penalty-farerule').text());	
								}
							}						
						});

						dic['fare'] = $(this).closest('.farerule').find('h4').text();
						dic['sum_percentage'] = sum_percentage;
						dic['sum_penalty'] = sum_penalty;
						sum_penalty_arr.push(dic);
					});

					var max_percentage = 0;
					var max_penalty = 0;
					var fare_basis = '';
					$.each(sum_penalty_arr, function() {
						if($(this)[0].sum_percentage > max_percentage) {
							max_percentage = $(this)[0].sum_percentage; 	
							max_penalty = $(this)[0].sum_penalty;
							fare_basis = $(this)[0]['fare'];
						}
					});
					$('.percentage-val').val(max_percentage + '%');
					$('.number-val').val(max_penalty);
					$('.fare').val(fare_basis);

	
					$.each($('.id'), function() {
						var this_user = $(this);
						var sum_penalty = 0;
						//find max percentage's fare_base
						$.each($('h4'), function() {
							if($(this).html() == fare_basis) {
								var table = $(this).closest('.farerule').find('.user_id');
								//than run loop by id 
								$.each(table, function() {
									if($(this).html() == this_user.html()) {		
										sum_penalty+=parseInt($(this).closest('tr').find('.penalty-farerule').html());
									
									}
								});
							}
						});	
						var tr_id = this_user.closest('tr');
						var sum_without_tax = parseInt(tr_id.find('.without_tax').html()); 
						
						tr_id.find('.sum_penalty').text(sum_penalty);
						tr_id.find('.sum_penalty').next().val(sum_penalty);

						tr_id.find('.refund').text(sum_without_tax - sum_penalty);
						tr_id.find('.refund').next().val(sum_without_tax - sum_penalty);
					});
				
					total_tax = $(this).getTotal($('.sum_tax'));
					total_without_tax = $(this).getTotal($('.without_tax'));
					total_penalty = $(this).getTotal($('.sum_penalty'));
					total_refund_price = $(this).getTotal($('.refund'));
					
					$('.total-penalty-tax').html(total_tax);
					$('.total-price-ticket-1').html(total_without_tax);
					$('.total-penalty-farerule').html(total_penalty);
					$('.result').html(total_refund_price);

				};

				/*$('.percentage-val').val(sum_percentage + '%');*/
				////
				$(this).closest('.rule-wrapper').find('input').val('');
				////
				return false;
			});

			//remove row for click event
			$('body').on('click', '.btn-danger', function() {
				var tr_penalty = $(this).closest('tr');
				
				var arr = [];
				var next_tr = tr_penalty;
				

				//change each user's sum penalty
				//add all tr after delete button's tr and count each sum client's penalty

				//sum each column prices and total of user info's table
				$.each($('.id'), function() {
					var td_sum_penalty = $(this).closest('tr').find('.sum_penalty');
					var td_sum_without_tax = $(this).closest('tr').find('.without_tax');
					var td_sum_refund = $(this).closest('tr').find('.refund');

					var penalty_price = parseInt(next_tr.find('.penalty-farerule').text());
					
					var prev_sum_penalty = parseInt(td_sum_penalty.text());
					var sum_without_tax = parseInt(td_sum_without_tax.text());
					var sum_penalty = prev_sum_penalty - penalty_price;
					var sum_refund = sum_without_tax - sum_penalty;
				

					td_sum_penalty.text(sum_penalty);
					td_sum_penalty.next().val(sum_penalty);

					td_sum_refund.text(sum_refund);
					td_sum_refund.next().val(sum_refund);

					arr.push(next_tr);
					next_tr = next_tr.next();
					
				});
				//remove neccessary all tr after buttons' tr
				$.each(arr, function() {
					$(this).remove();
				});
				////
				var percentage = tr_penalty.find('.percentage-1');
				var number =  tr_penalty.find('.penalty-farerule');
				if(percentage.text() != '') {
					var prev_max = parseInt($('.percentage-val').val());
					$('.percentage-val').val(prev_max - parseInt(percentage.text()) + '%');
				}
				else {
					var prev_max = parseInt($('.number-val').val());
					$('.number-val').val(prev_max - parseInt(number.text()));

				}
				/////

				var sum_penalty_arr = [];
				$.each($('.table-farerule'), function() {
					var dic = {};
					var sum_percentage = 0;
					var sum_penalty = 0;
					$.each($(this).find('.percentage-1'), function() {
						if($(this).closest('tr').find('.user_id').text() == 1) {
							if($(this).text() != ''){
								sum_percentage+=parseInt($(this).text());
							}
							else {
								sum_penalty+=parseInt($(this).closest('tr').find('.penalty-farerule').text());	
							}
						}						
					});

					dic['fare'] = $(this).closest('.farerule').find('h4').text();
					dic['sum_percentage'] = sum_percentage;
					dic['sum_penalty'] = sum_penalty;
					sum_penalty_arr.push(dic);
				});

				var max_percentage = 0;
				var max_penalty = 0;
				var fare_basis = '';
				$.each(sum_penalty_arr, function() {
					if($(this)[0].sum_percentage > max_percentage) {
						max_percentage = $(this)[0].sum_percentage; 	
						max_penalty = $(this)[0].sum_penalty;
						fare_basis = $(this)[0]['fare'];
					}
				});
				$('.percentage-val').val(max_percentage + '%');
				$('.number-val').val(max_penalty);
				$('.fare').val(fare_basis);


				$.each($('.id'), function() {
					var this_user = $(this);
					var sum_penalty = 0;
					//find max percentage's fare_base
					$.each($('h4'), function() {
						if($(this).html() == fare_basis) {
							var table = $(this).closest('.farerule').find('.user_id');
							//than run loop by id 
							$.each(table, function() {
								if($(this).html() == this_user.html()) {		
									sum_penalty+=parseInt($(this).closest('tr').find('.penalty-farerule').html());
								
								}
							});
						}
					});	

					var sum_without_tax = parseInt(this_user.closest('tr').find('.without_tax').html()); 
					
					this_user.closest('tr').find('.sum_penalty').html(sum_penalty);
					this_user.closest('tr').find('.refund').html(sum_without_tax - sum_penalty);
				});
			


				/////
				total_tax = $(this).getTotal($('.sum_tax'));
				total_without_tax = $(this).getTotal($('.without_tax'));
				total_penalty = $(this).getTotal($('.sum_penalty'));
				total_refund_price = $(this).getTotal($('.refund'));
				
				$('.total-penalty-tax').html(total_tax);
				$('.total-price-ticket-1').html(total_without_tax);
				$('.total-penalty-farerule').html(total_penalty);
				$('.result').html(total_refund_price);
				
				return false;	
			});


			$('body').on('click', '.btn-submit', function(){
				//page for superviser
				$('#user-table-data').val($('#user-table').html());
				$('#tax-table-data').val($('#tax-table').html());
				$('#tax-list-table-data').val($('#tax-list-table').html());
				
				
				$.each($('.table-farerule-data'), function() {
					var input_id = $(this).attr('name').split('data-')[1];
					
					$(this).val($('#'+input_id).html());
				});
				//form for saving data	
			});
			var str_fare = '';
			$.each($('h4'), function() {
				str_fare+=$(this).text() + " ";
			});
			
			$('.fare-val').val(str_fare);
	});		
	</script>
</body>
</html>