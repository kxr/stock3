
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

	//DELME $new_invoice_id="";

	//Add Invoice
	if (	!empty($_POST['submit_inv'])
			&&
			!empty($_POST['inv_date_val'])
			&&
			$_POST['inv_areceived'] > 0
		) {

		//invoice is submitted, check if it has at least one valid record
		$valid_entry="no";
		foreach ( $_POST['invr_itemid'] as $ix => $value ) {
			//a row with itemid and qty counts as valid
			if ( $_POST['invr_itemid'][$ix] > 0 && $_POST['invr_qty'][$ix] > 0 ) {
				$valid_entry="yes";
				break;
			}
		}

		//Submitted invoice has atleast one valid record, so lets add invoice
		if ( $valid_entry == "yes") {
			//add invoice
			$mysql_q = "INSERT INTO sale_invoices
							(sale_invoice_id, date, invoice_title, invoice_note, amount_received, raw_data, timestamp)
							VALUES(
								NULL,
								'".$_POST['inv_date_val']."',
								'".$_POST['inv_title']."',
								'".$_POST['inv_note']."',
								'".$_POST['inv_areceived'].".',
								'xyz',
								'".time()."'
							)"; //replace xyz -> ".print_r($_POST,true)."
			$dbhi->query($mysql_q) or die($dbhi->error);
			$new_invoice_id = $dbhi->insert_id;
			//add sale items in sale_transaction table
			foreach ( $_POST['invr_itemid'] as $ix => $value ) {
				if ($_POST['invr_itemid'][$ix] > 0 && $_POST['invr_qty'][$ix] > 0) {
					$mysql_q = "INSERT INTO sale_transactions
									(sale_trans_id, date, item_id, invoice_no, uprice, qty, amount_received, comments, timestamp)
									VALUES(
										NULL,
										'".$_POST['inv_date_val']."',
										'".$_POST['invr_itemid'][$ix]."',
										'KTSS".$new_invoice_id."',
										'".$_POST['invr_uprice'][$ix]."',
										'".$_POST['invr_qty'][$ix]."',
										'".$_POST['invr_uprice'][$ix]*$_POST['invr_qty'][$ix]."',
										'".$_POST['invr_comments'][$ix]."',
										'".time()."'
									)";
					$dbhi->query($mysql_q) or die($dbhi->error);
				}
			}

			$file_msg['invoice_id']=$new_invoice_id;
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
		//if ( $file_msg_rx['print_receipt'] == "yes" )
		//	echo "Printing Receipt # ".$file_msg_rx['invoice_no'];
		if ( $file_msg_rx['print_invoice'] == "yes" )
			echo
			"
				<script>
						$(this).printPage({url: 'invoice.php?op=print&type=sale&invoice_id=".$file_msg_rx['invoice_id']."'});
				</script>
			";
	}
?>

	
	<script type="text/javascript" charset="utf-8">

		$.fn.f_update_total=function(indx) {

			//update row total
			$('[name="invr_total[]"]').eq(indx).val($('[name="invr_qty[]"]').eq(indx).val()*$('[name="invr_uprice[]"]').eq(indx).val());

			//indicate red if uprice is less than cost price
			if ( Number($('[name="invr_uprice[]"]').eq(indx).val()) <= Number($('[name="invr_cprice[]"]').eq(indx).val()) ) {
				$('[name="invr_uprice[]"]').eq(indx).css({'background-color' : 'pink'});
			}
			else {
				$('[name="invr_uprice[]"]').eq(indx).css({'background-color' : 'white'});
			}

			//update grand total
			var inv_sum=0;
			$('[name="invr_uprice[]"]').each(function() {
				var index= $('[name="invr_uprice[]"]').index(this);
				inv_sum+=$('[name="invr_qty[]"]').eq(index).val()*$('[name="invr_uprice[]"]').eq(index).val();
			});
			inv_sum=(inv_sum).toFixed(2);
			$('#did_gtotal').html('<?php echo $Currency;?> '+inv_sum);
			$('[name=inv_areceived]').val(inv_sum);

		}
	
		$.fn.f_add_sale_row= function () {
			$("#tid_sale").append (
				'\
					<tr name="invr_trow[]">\
						<td>\
							<a href="#">\
								<img src="imgs/rm.png" name="invr_delimg[]" height="15" width="15"></img>\
							</a>\
						</td>\
						<td valign="top">\
							<input name="invr_itemid[]" type="text" size=3 readonly required/>\
						</td>\
						<td>\
							<input  placeholder="Item Name" name="invr_iname[]" type=text size=40 required/>\
							<a href="#" style="text-decoration: none;" onclick="return false;">\
								<span name="invr_span[]">*</span>\
							</a>\
							<br />\
							<textarea style="display:none; width:100%" placeholder=" Description" rows=1 name="invr_comments[]" />\
						</td>\
						<td>\
							<input name="invr_uprice[]" type="number" value="0" step="any" min="0.1" class="input-num-4" />\
							<input type=hidden name="invr_cprice[]"/>\
						</td>\
						<td>\
							<input name="invr_qty[]" type="number" value="1" step="any" min="0.001" class="input-num-3" /> \
						</td>\
						<td>\
							<?php echo $Currency;?>\
							<input name="invr_total[]" type="number" step="any" min="0.001" class="input-num-5" disabled/>\
						</td>\
					</tr>\
				'
			).find('input[name="invr_iname[]"]').focus();
		}
	
		//On Doc Ready, add an invoice row
		$(document).on('ready', function() {
			$(this).f_add_sale_row();
		});

		//On Green Add button click, add an invoice row
		$(document).on('click', '[name="inv_addimg"]', function() {
			$(this).f_add_sale_row();
		});

		//On Red Del button click, remove invoice row
		$(document).on('click', '[name="invr_delimg[]"]', function() {
			var index=$('[name="invr_delimg[]"]').index(this);
			$('[name="invr_trow[]"]').eq(index).remove();
			$(this).f_update_total(index);
		});

		//Open Item description span when * is clicked on
		$(document).on('click', '[name="invr_span[]"]', function() {
			var index=$('[name="invr_span[]"]').index(this);
			$('[name="invr_comments[]"]').eq(index).toggle();
			$('[name="invr_comments[]"]').eq(index).val('');
		});

		//On uprice or qty change, call the f_update_total fucntion
		$(document).on('change', '[name="invr_uprice[]"], [name="invr_qty[]"]', function() {
			//get the array id (either of invr_uprice or invr_qty)
			var index = $('input[name="invr_uprice[]"]').index(this) >= 0 ? $('input[name="invr_uprice[]"]').index(this) : $('input[name="invr_qty[]"]').index(this);
			//call the update total function
			$(this).f_update_total(index);
		});

		//Select everything in the input when clicked/focused
		$(document).on('focus, click', '[name="invr_uprice[]"], [name="invr_qty[]"], [name="inv_areceived"]', function() {
			$(this).select();
		})

		//Auto Complete Item Name
		$(document).on('focus', '[name="invr_iname[]"]', function() {
			$(this).autocomplete({
				source:'suggest_items.php',
				minLength:1,
				change: function( event, ui ) {
					if ( !ui.item ) {
						$(this).val('');
					}
				},
				select: function( event, ui ) {
					var index=$('[name="invr_iname[]"]').index(this);
					$('[name="invr_itemid[]"]').eq(index).val(ui.item.id);
					$(this).prop('readonly','true');
					$('[name="invr_itemid[]"]').eq(index).show();
					$('[name="invr_uprice[]"]').eq(index).val(ui.item.saleprice);
					$('[name="invr_uprice[]"]').eq(index).select();
					$('[name="invr_cprice[]"]').eq(index).val(ui.item.costprice);
					$(this).f_update_total(index);
				}
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li>" )
				.append( item.label )
				.appendTo( ul );
			};

		});

		//Submit the form when click submit
		$(document).on('click', '[name="submit_inv"]', function() {
			//TODO: Form validation
			$('#sale_invoice').submit();
		});

		//Pressing - (minus) will remove the row
		$(document).on('keypress', '[name="invr_trow[]"]', function(key) {
			if(key.which==0 && key.keyCode==27) {
				$(this).remove();
			}
		});

		//Pressing + (plus) will add a new row
		$(document).on( 'keypress', function(key) {
			switch(key.which) {
				case 43: 
					key.preventDefault();
					$(this).f_add_sale_row();
				break;
			}
		});

		// View Invoice
		$(document).on( 'click', '[name="inv_disp_row[]"]', function() {
			var index=$('[name="inv_disp_row[]"]').index(this);
			var invid=$('[name="inv_disp_id[]"]').eq(index).html();
			var page = "invoice.php?op=view&type=sale&invoice_id="+invid;
			$('#v_inv_div').html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
							.dialog({
								autoOpen: false,
								modal: true,
								height: 500,
								width: 850,
								position: ['middle',20],
								closeOnEscape: true,
								dialogClass: 'noDialogTitle',
								title: "Invoice # "+invid
							}).dialog('open');
		});
		$(document).on('click', '.ui-widget-overlay', function() {
			$('#v_inv_div').dialog('close');
		});

		$(function() {
			//Invoice Date
			$('[name="inv_date_disp"]').datepicker({
				dateFormat: "dd M yy",
				altField: '[name="inv_date_val"]',
				altFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange: "2013:2023"

			});
			$('[name="inv_date_disp"]').datepicker("setDate", new Date);

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
	
	<form id="sale_invoice" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<table id="tid_sale" border=0 >
			<thead>
				<tr>
					<th style="border:0px;" colspan=2>
						<input type="text" name="inv_date_disp" size=10 readonly required/>
						<input type="hidden" name="inv_date_val" />
					</th>
					<th style="border:0px;" colspan="3">
						<table id="tid_hidden_tight" border=0 >
							<thead>
							<tr>
								<th rowspan=2>
									<input style="border: 0px" type="text" name="inv_title" placeholder="Cash Sale Invoice" size=30 />
								</th>
								<th>
									<div style="display:inline;">
										&nbsp;Print:&nbsp;
										<input type="checkbox" name="print_invoice" />
									</div>
								</th>
							</tr>
							</thead>
						</table>
					</th>
					<th style="border:0px;" align="right">
						<a href="#"><img src="imgs/add.png" name="inv_addimg" height="25" width="25" /></a>
					</th>
				</tr>			
			</thead>
			<thead>
				<tr>
					<th style="border-right:0px;" ></th>
					<th style="border-left:0px;" >Id</th> 
					<th>Item</th> 
					<th>Unit</th>
					<th>Qty</th>
					<th>Total</th>
				</tr>
			</thead>
	
			<tbody>
			</tbody>
	
			<tfoot>
				<tr>
					<th style="padding:0px" colspan=6>
						<table id="tid_hidden_tight" cellspacing=0 cellpadding=0 margin="0" width="100%" border=0>
							<tr>
								<th style="padding:0;" valign=top rowspan="2" width="45%">
									<textarea placeholder="Invoice Note" style="background-color:#FFFFAA; width:100%; margin:0; border:0px;" rows="2" name="inv_note"></textarea>
								</th>
								<th style="" align="right">
									Grand Total:
								</th>
								<th align="right">
									<div id="did_gtotal">
										<?php echo $Currency; ?>
									</div>
								</th>
								<th style="" rowspan="2">
									<input type="submit" name="submit_inv" value="Submit">
								</th>
							</tr>
							<tr>
								<th style="" align="right">
									Amount Received:
								</th>
								<th align="right">
									<?php echo $Currency; ?>
									<input name="inv_areceived" style="padding:0;margin:0" type="text" size="6" />
								</th>
							</tr>
						</table>
					</th>
				</tr>
			</tfoot>
		</table>
	</form>



	<div id="s_table_container">
		<table id="inv_table" class="blue_table_tight" border=1>
			<tr>
				<th>Date</th>
				<th>Inv#</th>
				<th>Total</th>
			</tr>
			<?php
				$mysql_q = "SELECT * FROM sale_invoices";
							//:	WHERE date='".date('Y-m-d')."'";
				$query_res = $dbhi->query($mysql_q);
				while($row = $query_res->fetch_assoc() ) {
					echo
					"
					<tr name='inv_disp_row[]'>
						<td>".$row['date']."</td>
						<td name='inv_disp_id[]' title='".$row['invoice_title']."'>
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
