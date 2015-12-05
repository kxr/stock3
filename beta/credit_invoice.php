
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-language" content="en-US" />
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link href="css/table_box.css" rel="stylesheet" type="text/css">
	<title><?php echo $Company_Name;?> POS</title>
	<script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.printPage.js" type="text/javascript" charset="utf-8"></script>

<?php
	include_once('config.php');

	// MYSQL Connection and Database Selection

	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}

	$new_invoice_id="";

	if ( !empty($_POST['submit_sale']) ) {
		$item_id=array();
		$item_desc=array();
		$uprice=array();
		$qty=array();

		//convert the ass array to proper index-tabled array
		foreach ( $_POST as $key => $value ) {
			if (strpos($key, "itemid_") === 0) {
				$indx=array_pop(explode("_",$key));
				//itemid, quantity and uprice should be numeric and > 0 for a valid record 
				if (	is_numeric($_POST['itemid_'.$indx]) &&
						$_POST['itemid_'.$indx] > 0 &&
						is_numeric($_POST['qty_'.$indx]) &&
						$_POST['qty_'.$indx] > 0 &&
						is_numeric($_POST['uprice_'.$indx]) &&
						$_POST['uprice_'.$indx] >= 0
					) {
					array_push($item_id, $_POST['itemid_'.$indx]);
					array_push($item_desc, $_POST['idesc_'.$indx]);
					array_push($uprice, $_POST['uprice_'.$indx]);
					array_push($qty, $_POST['qty_'.$indx]);
				}
			}
		}
		//something is there to process
		if ( array_sum($qty) > 0 ){
			//add invoice
			$mysql_q = "INSERT INTO sale_invoices
							VALUES(
								NULL,
								'".$_POST['sale_date']."',
								'".$_POST['invoice_title']."',
								'".$_POST['invoice_note']."',
								'".$_POST['areceived'].".',
								'xyz',
								'".time()."'
							)"; //replace xyz -> ".print_r($_POST,true)."
			$dbhi->query($mysql_q) or die($dbhi->error);
			$new_invoice_id = $dbhi->insert_id;
			//add sale items in sale_transaction table
			foreach ( $item_id as $key => $value ) {
				$mysql_q = "INSERT INTO sale_transactions
								VALUES(
									NULL,
									'".$_POST['sale_date']."',
									'".$item_id[$key]."',
									'".$new_invoice_id."',
									'".$uprice[$key]."',
									'".$qty[$key]."',
									'0',
									'".$item_desc[$key]."',
									'".time()."'
								)";
				$dbhi->query($mysql_q) or die($dbhi->error);
			}

			$file_msg['invoice_no']=$new_invoice_id;
			if ( $_POST['print_receipt'] == "on" )
				$file_msg['print_receipt']="yes";
			else
				$file_msg['print_receipt']="no";
			if ( $_POST['print_invoice'] == "on" )
				$file_msg['print_invoice']="yes";
			else
				$file_msg['print_invoice']="no";
			file_put_contents('invoice_creation_success.msg', serialize($file_msg) );
			header("Location: " . $_SERVER['PHP_SELF'] );
		}
	}
	elseif ( file_exists('invoice_creation_success.msg' ) ) {
		$file_msg_rx=unserialize(file_get_contents('invoice_creation_success.msg'));
		echo "<div class='successbox' style='margin:0;display:none' id='sadd_msg_success'>Invoice Created: ".$file_msg_rx['invoice_no']."</div>";
		unlink('invoice_creation_success.msg');
		if ( $file_msg_rx['print_receipt'] == "yes" )
			echo "Printing Receipt # ".$file_msg_rx['invoice_no'];
		if ( $file_msg_rx['print_invoice'] == "yes" )
			echo
			"
				<script>
						$(this).printPage({url: 'print_sale_invoice.php?invoice_id=".$file_msg_rx['invoice_no']."'});
				</script>
			";
	}
?>

	
	<script type="text/javascript" charset="utf-8">
	
		var sale_serial=1;
	
		$.fn.f_update_total=function(obj) {
		
			var uprice=$("input[name=uprice_"+obj+"]").val();
			var qty=$("input[name=qty_"+obj+"]").val();
			$("#ipid_row_total_"+obj).val((uprice*qty).toFixed(2));

			if ( Number($("input[name=uprice_"+obj+"]").val()) <= Number($("input[name=cprice_"+obj+"]").val()) ) {
				$("input[name=uprice_"+obj+"]").css({'background-color' : 'pink'});
			}
			else {
				$("input[name=uprice_"+obj+"]").css({'background-color' : 'white'});
			}
	
			var gtotal=0;
			$('[id^=ipid_row_total_]').each( function() {
				if ($(this).val()) {
					gtotal = parseFloat($(this).val()) + gtotal;
				}
			});
			gtotal=(gtotal).toFixed(2);
			$('#did_gtotal').html('<?php echo $Currency;?> '+gtotal);
			$('#inid_areceived').val(gtotal);
		}
	
		$.fn.f_add_sale_row= function () {
			$("#tid_sale").append (
				'<tr id="rid_sale_row_'+sale_serial+'">' +
					'<td>'+
						'<a href="#">'+
							'<img src="imgs/rm.png" id="iid_del_sale_row_'+sale_serial+'" "height="15" width="15"></img>'+
						'</a>'+
					'</td>'+
					'<td valign="top">'+
						'<input name="itemid_'+sale_serial+'" type="text" size=3 readonly/>'+
					'</td>'+
					'<td>'+
						'<input  placeholder="Item Name" name="iname_'+sale_serial+'" type=text size=40/>'+
						'<a href="#" style="text-decoration: none;" onclick="return false;">'+
						'<span id="sid_span_'+sale_serial+'">*</span>'+
						'</a>'+
						'<br />'+
						'<textarea style="display:none; width:100%" id="taid_'+sale_serial+'" placeholder=" Description" rows=1 name="idesc_'+sale_serial+'" />'+
					'</td>'+
					'<td>'+
						'<input name="uprice_'+sale_serial+'" type=text size=4/> '+
						'<input type=hidden name="cprice_'+sale_serial+'"/> '+
					'</td>' +
					'<td><input name="qty_'+sale_serial+'" type=text size=2 value="1" /> </td>' +
					'<td>'+
							'<?php echo $Currency;?> '+
							'<input id="ipid_row_total_'+sale_serial+'" size=4 disabled/> '+
					'</td>'+
				'</tr>'
			);
			$('input[name=iname_'+sale_serial+']').focus();
			sale_serial=sale_serial+1;
		}
	
	
		$(document).on('ready', function() {
			$(this).f_add_sale_row();
		});
	
		$(document).on('click', '#iid_add_sale_row', function() {
			$(this).f_add_sale_row();
		});
	
		$(document).on('click', '[id^=iid_del_sale_row_]', function() {
			var caller_name=$(this).prop('id');
			var caller_index=caller_name.split("_").pop();
			$('#rid_sale_row_'+caller_index).remove();
			$(this).f_update_total(caller_index);
		});
	
		$(document).on('click', '[id^=sid_span_]', function() {
			var caller_name=$(this).prop('id');
			var caller_index=caller_name.split("_").pop();
			$('#taid_'+caller_index).toggle();
			$('#taid_'+caller_index).val('');
		});
	
		$(document).on('change', 'input[name^=uprice_], input[name^=qty_]', function() {
			var caller_name=$(this).prop('name');
			var caller_index=caller_name.split("_").pop();
			$(this).f_update_total(caller_index);
		});
	
		$(document).on('focus, click', 'input[name^=uprice_], input[name^=qty_], input[name=areceived]', function() {
			$(this).select();
		})
	
		$(document).on('focus', 'input[name^=iname_]', function() {
			$(this).autocomplete({
				source:'suggest_items.php',
				minLength:1,
				change: function( event, ui ) {
					if ( !ui.item ) {
						$(this).val('');
					}
				},
				select: function( event, ui ) {
					var caller_name=$(this).prop('name');
					var caller_index=caller_name.split("_").pop();
					$('input[name=itemid_'+caller_index+']').val(ui.item.id);
					$(this).prop('readonly','true');
					$('input[name=itemid_'+caller_index+']').show();
					$('input[name=uprice_'+caller_index+']').val(ui.item.saleprice);
					$('input[name=uprice_'+caller_index+']').select();
					$('input[name=cprice_'+caller_index+']').val(ui.item.costprice);
					$(this).f_update_total(caller_index);
				}
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li>" )
				.append( item.label )
				.appendTo( ul );
			};

		});
	
		$(document).on('click', '#submit_sale', function() {
			$('#sale_form').submit();
		});

		$(document).on('keypress', '[id^=rid_sale_row_]', function(key) {
			if(key.which==0 && key.keyCode==27) {
				$(this).remove();
			}
		});

		$(document).on( 'keypress', function(key) {
			switch(key.which) {
				case 43: 
					key.preventDefault();
					$(this).f_add_sale_row();
				break;
			}
		});

		$(document).on( 'click', '[id^=tr_inv_]', function() {
			var caller_name=$(this).prop('id');
			var caller_index=caller_name.split("_").pop();
			var page = "invoice_view.php?type=sale&invoice_id="+caller_index;
			$('#v_inv_div').html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
							.dialog({
								autoOpen: false,
								modal: true,
								height: 500,
								width: 850,
								closeOnEscape: true,
								dialogClass: 'noDialogTitle',
								title: "Invoice # "+caller_index
							}).dialog('open');
		});
		$(document).on('click', '.ui-widget-overlay', function() {
			$('#v_inv_div').dialog('close');
		});
	
		$(function() {
			$("#inid_sale_date_display").datepicker({
				dateFormat: "dd M yy",
				altField: "#inid_sale_date_submitvalue",
				altFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange: "2013:2023"

			});
			$("#inid_sale_date_display").datepicker("setDate", new Date);

			//disable form submit on enter key press
			$(window).keydown(function(event) {
				if(event.keyCode == 13) {
					event.preventDefault();
					return false;
				}
			});
		});
		//Handling message bubbles
		$('document').ready(function() {
			$('[id^=sadd_msg_]').fadeIn(1000);
			$('[id^=sadd_msg_]').delay(5000).fadeOut(1000);
		});

	</script>

</head>

<body bgcolor="silver">

	<?php include('panel.php'); ?>
	
	<form id="sale_form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table id="tid_sale" border=0 >
			<thead>
				<tr>
					<th colspan=2>
						<input type="text" id="inid_sale_date_display" size=10 readonly/>
						<input type="hidden" name="sale_date" id="inid_sale_date_submitvalue" size=10 />
					</th>
					<th colspan="3">
						<table id="tid_hidden_tight" border=0 >
							<thead>
							<tr>
								<th style="width:300px" rowspan=2>
									<?php echo get_clientname($_GET['cid']);?>
								</th>
								<th>
									<div style="display:inline; font-size:0.7em">
										&nbsp;Print:&nbsp;
										<label>
											<input type="checkbox" name="print_receipt"/>
											DO
										</label>
										<label>
											<input type="checkbox" name="print_invoice" checked />
											Invoice
										</label>
									</div>
								</th>
							</tr>
							<tr>
								<th>
									<label style="font-size:0.7em" for="cus_email">Email:</label>
									<input id="cus_email" name="cus_email" style="padding:0;margin:0;font-size:0.7em;" ></input>
								</th>
							</tr>
							</thead>
						</table>
					</th>
					<th align="right">
						<a href="#"><img src="imgs/add.png" id="iid_add_sale_row" height="25" width="25"></img></a>
					</th>
				</tr>			
			</thead>
			<thead>
				<tr>
					<th></th>
					<th>Id</th> 
					<th>Item</th> 
					<th>Unit</th>
					<th>Qty</th>
					<th>Total</th>
				</tr>
			</thead>
	
			<tbody>
			</tbody>
	
			<thead>
				<tr>
					<th style="border-top: 2px solid #6678b1; border-bottom: 0px; padding:0px" colspan=6>
						<table cellspacing=0 cellpadding=0 margin="0" width="100%" border=0>
							<tr>
								<th style="padding:0;" valign=top rowspan="2" width="45%">
									<textarea placeholder="Invoice Note" style="background-color:#FFFFAA; width:100%; margin:0; border:0px;" rows=4 name="invoice_note"></textarea>
								</th>
								<th style="border-left:2px solid #6678b1;" align="right">
									Grand Total:
								</th>
								<th align="right">
									<div id="did_gtotal">
										<?php echo $Currency; ?>
									</div>
								</th>
								<th style="border-left:2px solid #6678b1;" rowspan="2">
									<input type="submit" name="submit_sale" id="submit_sale" value="Submit">
								</th>
							</tr>
							<tr>
								<th style="border-left:2px solid #6678b1;" align="right">
									Amount Received:
								</th>
								<th align="right">
									<input name="areceived" style="padding:0;margin:0" id="inid_areceived" type="text" size="6" />
									<?php echo $Currency; ?>
								</th>
							</tr>
						</table>
					</td>
				</tr>
			</thead>
		</table>
	</form>



	<div id="s_table_container">
		<table class="blue_table_tight" border=1>
			<tr>
				<th>
					Inv#
				</th>
				<th>
					Total
				</th>
			</tr>
			<?php
				$mysql_q = "SELECT * FROM credit_invoices
								WHERE client_id='".$_GET['cid']."'";
							//:	WHERE date='".date('Y-m-d')."'";
				$query_res = $dbhi->query($mysql_q);
				while($row = $query_res->fetch_assoc() ) {
					echo
					"
					<tr id='tr_inv_".$row['sale_invoice_id']."'>
						<td title='".$row['invoice_title']."'>
							".$row['sale_invoice_id']."
						</td>
						<td>
							$Currency ".clean_num($row['amount_received'])."
						</td>
						</a>
					</tr>
					";
				}
			?>
		</table>
	

<div id="v_inv_div"></div>

</body>

</html>

<?php
?>
