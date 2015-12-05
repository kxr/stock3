<?php
	include_once('config.php');

	// MYSQL Connection and Database Selection

	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}
	$invoice_row = "";
	$transaction_rows = array();

	if ( !(
			( !empty($_GET['invoice_id']) && is_numeric($_GET['invoice_id']) )
			&&
			( $_GET['op']=='print' || $_GET['op']=='view' )
			&&
			( $_GET['type']=='sale' || $_GET['type']=='purchase' )
		 )
		)
		die('ERROR: Bad invoice call');

	// We have proper invoice_id, op, and type
	$invoice_id=$_GET['invoice_id'];
	$type=$_GET['type'];
	$operation=$_GET['op'];


	//SALE INVOICE
	if ($type=='sale') {
		$mysql_q="  SELECT *
					FROM sale_invoices
					WHERE sale_invoice_id = '$invoice_id'
				";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows > 0) {
			$invoice_row = $query_res->fetch_assoc();
			$mysql_q="	SELECT *
						FROM sale_transactions
						WHERE invoice_no = 'KTSS$invoice_id'
					";
			$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
			if ( $query_res->num_rows > 0)
				while( $row=$query_res->fetch_assoc() )
					array_push($transaction_rows, $row);
			else
				die("ERROR: No records found for $type invoice id=$invoice_id");
		}
		else
			die("ERROR: $type invoice not found id=$invoice_id");

		$inv_title=$Company_Title;
		$inv_phone=$Company_Phone;
		$inv_fax=$Company_Fax;
		$inv_email=$Company_Email;
		$inv_web=$Company_Web;
		$inv_details="";
	}
	//PURCHASE INVOICE
	if ($type=='purchase') {
		$mysql_q="  SELECT *
						FROM purchase_invoices
						WHERE p_invoice_id = '$invoice_id'
					";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows > 0) {
			$invoice_row = $query_res->fetch_assoc();
			$mysql_q="	SELECT *
							FROM purchase_transactions
							WHERE invoice_id = '$invoice_id'
						";
			$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
			if ( $query_res->num_rows > 0)
				while( $row=$query_res->fetch_assoc() )
					array_push($transaction_rows, $row);
			else
				die("ERROR: No records found for $type invoice id=$invoice_id");
		}
		else
			die("ERROR: $type invoice not found id=$invoice_id");

		$vend_info = get_vendor_info($invoice_row['vendor_id'],$dbhi);
		$inv_title = $vend_info['name'];
		$inv_phone = $vend_info['phone'];
		$inv_fax = $vend_info['fax'];
		$inv_email = $vend_info['email'];
		$inv_web = "";
		$inv_details = $vend_info['details'];
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<link href="css/table_box.css" rel="stylesheet" type="text/css" />
	
</head>
<body>
	<center>
	   <table id='invoice_p_head' border=0>
		<tr>
			<td colspan="3" align="center" >
				<span style="font-size:1.4em; letter-spacing: 3px;">
					<b><?php echo $inv_title; ?></b>
				</span>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<table width="100%" style="font-size:0.7em">
					<tr>
						<td align="center">
							Phone: <?php echo $inv_phone; ?>
						</td>
						<td align="center">
							Fax: <?php echo $inv_fax; ?>
						</td>
						<td align="center">
							Web: <?php echo $inv_web; ?>
						</td>
						<td align="center">
							Email: <?php echo $inv_email; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr> <td> <br> </td> </tr>
		<tr>
			<td>
				<?php echo date('d M Y',strtotime($invoice_row['date'])); ?>
			</td>
			<td>
				<?php
						echo strtoupper($type)." INVOICE";
						if ($type=='sale') {
							if ($invoice_row['invoice_title'])
								echo ' ['.$invoice_row['invoice_title'].']';
						}
						elseif ($type=='purchase')
							echo ' ['.$invoice_row['vendor_invoice_no'].']';
				 ?>
			</td>
			<td align="right">
				<font color="red">
				<b>Inv# <?php
							if ($type=='sale')
								echo $invoice_row['sale_invoice_id'];
							elseif ($type=='purchase')
								echo $invoice_row['p_invoice_id'];
						?>
				</b>
				</font>
			</td>
		</tr>
	   </table>
	<br>
		<table cellspacing=0 id='invoice_p' >
				<tr>
					<th>#</th>
					<th>Item</th>
					<th>Unit</th>
					<th>Qty</th>
					<th>Total</th>
				</tr>

				<?php
					$counter=1;
					$grand_total=0;
					foreach ( $transaction_rows as $row_num => $row_value) {
						$item_info=get_item_info($row_value['item_id'],$dbhi);
						$row_total=$row_value['uprice']*$row_value['qty'];
						$grand_total=$grand_total+$row_total;
						echo
						"
							<tr>
								<td>
									$counter
								</td>
								<td class='itembox'>
									(".$row_value['item_id'].")
									".$item_info['name']."
						";
						if ( !empty($row_value['comments']) ) {
						echo
						"
									<br />
									<span style='font-size:0.8em'>".$row_value['comments']."</span>
						";
						}
						echo
						"
								</td>
								<td>".clean_num($row_value['uprice'])."</td>
								<td>".clean_num($row_value['qty'])."</td>
								<td>
									$Currency ".clean_num($row_total)."
								</td>
							</tr>
						";
					$counter=$counter+1;
					}
				?>

		</table>
		<br>
		<table id="invoice_p_foot" border="0">
			<tr>
				<td>
					<div style="font-size:0.8em;">
						<?php echo $invoice_row['invoice_note']; ?>
					</div>
				</td>
				<td align="right">
					<b>
						Grand Total:
						<?php echo "$Currency ".clean_num($grand_total); ?>
					</b>
					<?php
						if ($operation=='view' && $type=='sale') {
							echo '<br>';
							echo 'Amount Received:';
							echo "$Currency ".clean_num($invoice_row['amount_received']);
						}
						elseif ($type=='purchase' && $grand_total!=$invoice_row['invoice_total'] ) {
							echo '<br>';
							echo 'Invoice Total:';
							echo "$Currency ".clean_num($invoice_row['invoice_total']);
						}
					?>
				</td>
			</tr>
			<?php
				if ($type=='sale') {
					echo '
						<tr>
							<td colspan="2" style="font-size:0.8em;">
							Note: '.$Company_SalePolicy.'
							</td>
						<tr>
					';
				}
			?>
		</table>
	</center>
</body>
</html>
