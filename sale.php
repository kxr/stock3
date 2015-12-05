<?php

	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}


	// If addform is posted, insert values in database
	if (	$_POST['t_itemid'] > 0
			&&
			!empty($_POST['t_date'])
			&&
			!empty($_POST['t_invoiceno'])
			&&
			$_POST['t_uprice'] > 0
			&&
			$_POST['t_qty'] > 0
		) {

		$sql_q="INSERT INTO sale_transactions
						( sale_trans_id, date, item_id, invoice_no, uprice, qty, amount_received, comments, timestamp )
				VALUES	(
						'',
						'".$_POST['t_date']."',
						'".$_POST['t_itemid']."',
						'".$_POST['t_invoiceno']."',
						'".$_POST['t_uprice']."',
						'".$_POST['t_qty']."',
						'".clean_num($_POST['t_uprice']*$_POST['t_qty'])."',
						'".$_POST['t_comments']."',
						'".time()."'
						);
				";

			$dbhi->query($sql_q);
			if ( $dbhi->affected_rows == 1 )
				header("Location: ".$_SERVER['PHP_SELF']."?success=yes&message=Sale Record Added");
			else
				header("Location: ".$_SERVER['PHP_SELF']."?&success=no&message=MYSQL ERROR");

		}

	if ( !empty($_GET['success']) ) {
		if ( $_GET['success'] == "yes" )
			echo "<div style='display:none' class='successbox' id='vadd_msg_success'>".$_GET['message'].": Success</div>";
		else
			echo "<div style='display:none' class='errorbox' id='vadd_msg_failure'>".$_GET['message'].": Failed</div>";
	}
?>
<html>
<head>
	<title><?php echo $Company_Name;?> Stock :: Purchase</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/jquery-ui.js'></script>
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link href="css/table_box.css" rel="stylesheet" type="text/css">


	<script>

		//Handling Success and Failure Messages
		$('document').ready(function() {
			$('[id^=vadd_msg_]').fadeIn(1000);
			$('[id^=vadd_msg_]').delay(2000).fadeOut(1000);
		});

		$(function() {
			$("#t_date_display").datepicker({
				dateFormat: "dd M yy",
				altField: "#t_date_submitvalue",
				altFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange: "2013:2023"
			});
			$("#t_date_display").datepicker("setDate", new Date);
		});	

		$(document).on('click', '#add_button', function() {
			$('#addform').submit();
		});


		//Auto Complete
		$(document).on('focus', 'input[name=t_iname]', function() {
			$(this).autocomplete({
				source:'suggest_items.php',
				minLength:1,
				change: function( event, ui ) {
					if ( !ui.item ) {
						$(this).val('');
					}
				},
				select: function( event, ui ) {
					$('input[name=t_itemid]').val(ui.item.id);
					$(this).prop('readonly',true);
					$('input[name=tinvoiceno]').select();
				}
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li>" )
				.append( item.label )
				.appendTo( ul );
			};

		});

		//Pressing Esc should clear/reset the form
		$(document).keypress(function(e) {
			if (e.keyCode == 27) {
				$('input[name=t_iname]').val('');
				$('input[name=t_itemid]').val('');
				$('input[name=tuprice]').val('');
				$('input[name=tqty]').val('');
				$('input[name=ttotal]').val('');
				$('input[name=t_iname]').prop('readonly',false);
			}
		});

		//Update total
		$(document).on('click, change, focus, blur', 'input[name=t_qty], input[name=t_uprice]', function() {
			$('input[name=t_total]').val($('input[name=t_qty]').val()*$('input[name=t_uprice]').val());
		});

		// View Invoice
		$(document).on( 'click', '[name=a_invid]', function() {
			var invid=$(this).text();
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

	</script>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>

<b>Sale</b>
<br>
<br>


<center>

	<table border="1" class="tclass_std">
		<thead>		
			<tr>
				<th>Date</th>
				<th>Item</th>
				<th>Invoice No.</th>
				<th>Unit Price</th>
				<th>Qty</th>
				<th>Total</th>
				<th>Comments</th>
			</tr>

			<form id="addform" name="addform" method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">		
				<tr>
					<th>
						<input type="text" id="t_date_display" size=10 readonly required/>
						<input type="hidden" name="t_date" id="t_date_submitvalue" size=10 />
					</th>
					<th>
						<input  placeholder="Item Name" name="t_iname" type=text size=30 required/>
						<input type="hidden" name="t_itemid" value="" />
					</th>
					<th>
						<input name="t_invoiceno" type="text" size="15" required />
					</th>
					<th>
						<input name="t_uprice" value="0" type="number" step="any" min="0.1" class="input-num-4" />
						<?php echo $Currency?>
					</th>
					<th>
						<input name="t_qty" value="1" type="number" step="any" min="0.001" class="input-num-3" />
					</th>
					<th>
						<input name="t_total" value="0" type="number" step="any" min="0.001" class="input-num-5" disabled/>
						<?php echo $Currency?>
					</th>
					<th>
						<textarea name="t_comments" rows=1 cols=24 ></textarea>
						<input type="image"  height=35 width=35 src="imgs/add.png" />
					</th>
				</tr>
			</form>
		</thead>

		<tbody>

				<?php
					$mysql_q = "
								SELECT sale_trans_id, date, item_id, invoice_no, uprice, qty, amount_received, comments, timestamp
								FROM sale_transactions
								ORDER BY sale_trans_id DESC
								";
					$query_res = $dbhi->query($mysql_q) or die($dbhi->error);

					while($row = $query_res->fetch_assoc() ) {

						$item_info = get_item_info($row['item_id'] ,$dbhi);


						$row_style='';

						//if amount received is diff than total (uprice*qty), then show it
						$am_rec="";
						if ( clean_num($row['uprice'] * $row['qty']) != clean_num($row['amount_received']) )
							$am_rec="(".clean_num($row['amount_received']).")";

						//if invoice is KTSS* then provide link to the invoice
						if ( substr($row['invoice_no'],0,4) === 'KTSS' ){
							$invid = substr($row['invoice_no'],4);
							$invoice_html="<a name='a_invid' href='javascript:;'>$invid</a>";
						}
						else
							$invoice_html=$row['invoice_no'];
						echo
						"
							<tr>
								<td $row_style>".$row['date']."</td>

								<td $row_style>
									<a href='transactions.php?iid=".$row['item_id']."'>
										".$item_info['name']."
									</a>
								</td>
								<td $row_style>".$invoice_html."</td>
								<td $row_style>".clean_num($row['uprice'])."</td>
								<td $row_style>".clean_num($row['qty'])."</td>
								<td $row_style>".clean_num($row['uprice'] * $row['qty']).$am_rec."</td>
								<td $row_style>
									<pre style='display:inline;'>".$row['comments']."</pre>
								</td>
							</tr>
						";
					}
				?>	
		</tbody>
	</table>

</center>

<div id="v_inv_div"></div>

</body>
