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