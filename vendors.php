<?php
	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}

$vendorID=$_GET['vendorid'];
$vendorName=get_vendorname($vendorID, $dbhi);

	//If Invoices are selected for payments, generate the payment form/div
	if ( isset ($_POST['invpay_check']) ) {
		echo "
			<div style='display:none;padding:0px' name='div_addpayment'>
				<table style='margin:0px;' class='tclass_std' border='1'>
					<form name='add_payment' method='post' action='".$_SERVER['PHP_SELF']."?vendorid=$vendorID'>

						<tr>
							<th colspan='2'>
								Adding Payment for $vendorName
							</th>
						</tr>
						<tr>
							<td>Date: </td>
							<td>
								<input type='text' name='vp_date_disp'' size=10 readonly/>
 								<input type='hidden' name='vp_date_val' />
							</td>
						</tr>
						<tr>
							<td>Invoices: </td>
							<td>
				";
								foreach ( $_POST['invpay_check'] as $invid_topay){
									echo "
										<div class='wrap_box'>
											".get_vendor_invoice($invid_topay, $dbhi)."
										</div>
									";
								}
								foreach ( $_POST['invpay_check'] as $invid_topay)
									echo "<input type='hidden' name='vp_invids[]' value='$invid_topay' />";

			echo "
							</td>
						</tr>
						<tr>
							<td>Payment Type: </td>
							<td>
								<select name='vp_payment_t'>
				";

								foreach ($Payment_Types as $_paymentt ){
									echo "<option value=\"$_paymentt\">$_paymentt</option>";
								}
			echo "
								</select>
							</td>
						</tr>
						<tr>
							<td>Reference #: </td>
							<td>
								<input type='text' name='vp_refno' />
							</td>
						</tr>
						<tr>
							<td>Comments: </td>
							<td>
								<textarea name='vp_comment' rows=1 cols=24 ></textarea>
							</td>
						</tr>
						<tr>
							<td colspan='2' align='center'>
								<input type='submit' name='vp_submit' value='Add Payment' />
							</td>
						</tr>
					</form>
				</table>
			</div>
		";
	}

	//If the payment form is submitted, add the payment
	if ( $_POST['vp_submit'] == 'Add Payment' ) {

		//Add Payment and Get the PaymentID
		$mysql_q="	INSERT INTO payment_out
						(payment_id, date, payment_type, ref_no, comments, timestamp)
					VALUES (
							'',
							'".$_POST['vp_date_val']."',
							'".$_POST['vp_payment_t']."',
							'".$_POST['vp_refno']."',
							'".$_POST['vp_comment']."',
							'".time()."'
							)
		";
		$dbhi->query($mysql_q) or die($dbhi->error);
		$new_payment_id = $dbhi->insert_id;

		//Add the paymentID to all the invoices
		foreach ($_POST['vp_invids'] as $payed_invid){
			$mysql_q="	UPDATE purchase_invoices
						SET payment_id='$new_payment_id'
						WHERE p_invoice_id='$payed_invid'
					";
			$dbhi->query($mysql_q) or die($dbhi->error);
		}


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
	<title><?php echo $Company_Name;?> Stock :: Vendors</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/jquery-ui.js'></script>
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link href="css/table_box.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" charset="utf-8">

		//Handling Success and Failure Messages
		$('document').ready(function() {
			$('[id^=vadd_msg_]').fadeIn(1000);
			$('[id^=vadd_msg_]').delay(2000).fadeOut(1000);
		});

		// Invoice Payment Form Submit
		$(document).on('click', '#b_add_payment', function() {
			$('[name="inv_pay_form"]').submit();
		});

		// Payment Div Popup
		$(function() {
			$( '[name=div_addpayment]' ).dialog( {
				autoOpen: true,
				height: 'auto',
				width: 'auto',
				modal: true,
				position: ['middle',20],
				dialogClass: 'compact_popup',
				close: function() {
				},
				open: function() {
					$(this).parent().css("padding", "0px")
				}
			});
		});

		//Payment Date:
		$(function() {
			$('[name="vp_date_disp"]').datepicker({
				dateFormat: "dd M yy",
				altField: '[name="vp_date_val"]',
				altFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange: "2013:2023"
			});
			$('[name="vp_date_disp"]').datepicker("setDate", new Date);
		});

		// View Invoice
		$(document).on( 'click', '[name="a_veninv[]"]', function() {

			var index=$('[name="a_veninv[]"]').index(this);
			var invid=$('[name="val_invid[]"]').eq(index).val();
			var page = "invoice.php?op=view&type=purchase&invoice_id="+invid;
			$('#v_inv_div').html('<iframe style="border: 0px; " src="' + page + '" width="100%" height="100%"></iframe>')
				.dialog({
					autoOpen: false,
					modal: true,
					height: 500,
					width: 850,
					position: ['middle',20],
					closeOnEscape: true,
					dialogClass: 'compact_popup_wtitle',
					title: "Invoice # "+invid
				}).dialog('open');
		});
		$(document).on('click', '.ui-widget-overlay', function() {
			$('#v_inv_div').dialog('close');
		});



	</script>





</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
<?php include_once('include/add_vendor.php'); ?>
<?php include_once('include/add_purchase_invoice.php'); ?>
<center>

	<?php
	// If vendorid is not there show the list of vendors
	if ( ! ( is_numeric($_GET['vendorid']) && $_GET['vendorid'] > 0 ) ) {
		echo "
				<table border='1' class='tclass_std' >
					<thead>
						<tr>
							<th>Vendor Name</th>
							<th>Pending Payments</th>
						</tr>
					</thead>
					<tbody>
			";
		$mysql_q="SELECT vendor_id, vendor_name FROM vendors";
		$query_res = $dbhi->query($mysql_q) or die ($dbhi->error);
		while($row = $query_res->fetch_assoc() ) {
			echo "
					<tr>
						<td>
							<a href='".$_SERVER['PHP_SELF']."?vendorid=".$row['vendor_id']."'>
							".$row['vendor_name']."
							</a>
						</td>
						<td>
							$Currency ".get_vendor_pending($row['vendor_id'], $dbhi)."
						</td>
					</tr>
				";
		}
		echo "
					</tbody>
				</table>
			";
		exit;
	}
	?>



	<table border="1" class="tclass_std">
		<thead>
			<tr>
				<th colspan="4" align="center">
					<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
						<select style='background: transparent; font-size:1.5em; border:none' name="vendorid" onchange="this.form.submit()">
							<?php
							$mysql_q="SELECT vendor_id, vendor_name FROM vendors";
							$query_res = $dbhi->query($mysql_q) or die ($dbhi->error);
							while($row = $query_res->fetch_assoc() ) {
								$selected='';
								if ( $row['vendor_id'] == $vendorID )
									$selected='selected';
								echo "	<option value='".$row['vendor_id']."' $selected>
									".$row['vendor_name']."
								</option>
									";
							}
							?>
						</select>
					</form>
				</th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Invoice No.</th>
				<th>Total Amount</th>
				<th><button id="b_add_payment">Add Payment</button></th>
			</tr>
		</thead>

		<tbody>
			<form name="inv_pay_form" method="post" action="<?php echo $_SERVER['PHP_SELF']."?vendorid=$vendorID"; ?>">
			<?php
				$mysql_q="	SELECT p_invoice_id, date, vendor_invoice_no, invoice_note, invoice_total, payment_id
							FROM purchase_invoices
							WHERE vendor_id='$vendorID'
							ORDER BY p_invoice_id DESC
				";
				$query_res = $dbhi->query($mysql_q) or die ($dbhi->error);
				while( $row = $query_res->fetch_assoc() ) {
					//If Invoice total and calculated total don't match, mention it
					$p_calc_inv_total=get_purchase_invoice_total($row['p_invoice_id'], $dbhi);
					if ( $p_calc_inv_total != $row['invoice_total'])
						$mention_calc_total="($p_calc_inv_total)";
					echo "
							<tr>
								<td>
									".$row['date']."
								</td>
								<td>
									<a name='a_veninv[]' href='javascript:;'>".$row['vendor_invoice_no']."
									<input type='hidden' name='val_invid[]' value='".$row['p_invoice_id']."' />
									</a>
								</td>
								<td>
									$Currency ".clean_num($row['invoice_total'])."$mention_calc_total
								</td>
					";
					//If payed get payment else display check box
					if ( $row['payment_id'] > 0 ) {
						$m_q="	SELECT payment_type, ref_no
								FROM payment_out
								WHERE payment_id=".$row['payment_id'];
						$q_r=$dbhi->query($m_q) or die ($dbhi->error);
						$rw=$q_r->fetch_assoc();
						echo "
								<td>
									<a name='a_payment[]' href='javascript:;'>
									".$rw['payment_type']."# ".$rw['ref_no']."
									</a>
								</td>
							</tr>
						";
					}
					else {
						echo "
								<td align='center'>
									<input type='checkbox' value='".$row['p_invoice_id']."' name='invpay_check[]' />
								</td>
							</tr>
						";

					}
				}
			?>
		</form>
		</tbody>


	</table>



</center>



<div id="v_inv_div"></div>



</body>