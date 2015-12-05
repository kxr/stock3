<?php

	if ( !isset($_GET['iid']) )
		die("_GET[iid] not found");

	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}


	// ItemID from GET
	$current_itemid=$_GET['iid'];
	$current_iteminfo = get_item_info ( $current_itemid , $dbhi );
		$current_itemname = $current_iteminfo['name'];
		$current_itemsaleprice = $current_iteminfo['sale_price'];
		$current_itemdetail = $current_iteminfo['detail'];
	$current_stockinfo = get_stock_info ( $current_itemid, $dbhi );
		$current_totalsale = $current_stockinfo['total_sale'];
		$current_totalpurchase = $current_stockinfo['total_purchase'];

	// If addform is posted, insert values in database
	if (	!empty($_POST['t_date_val'])
			&&
			( !empty($_POST['tinvoiceno']) || $_POST['trans_t'] == 'Purchase' )
			&&
			$_POST['tuprice'] > 0
			&&
			$_POST['tqty'] > 0
		) {
		echo 'hellooo';
			if ( $_POST['trans_t'] == 'Sale' )
				$sql_q="INSERT INTO sale_transactions
								( sale_trans_id, date, item_id, invoice_no, uprice, qty, amount_received, comments, timestamp )
						VALUES  (
								'',
								'".$_POST['t_date_val']."',
								'".$_POST['iid']."',
								'".$_POST['tinvoiceno']."',
								'".$_POST['tuprice']."',
								'".$_POST['tqty']."',
								'".$_POST['tuprice']*$_POST['tqty']."',
								'".$_POST['tcomments']."',
								'".time()."'
								);
						";
			elseif ( $_POST['trans_t'] == 'Purchase' )
				$sql_q="INSERT INTO purchase_transactions
								( p_trans_id, date, item_id, vendor_id, invoice_id, uprice, qty, comments, timestamp )
						VALUES	(
								'',
								'".$_POST['t_date_val']."',
								'".$_POST['iid']."',
								'',
								'',
								'".$_POST['tuprice']."',
								'".$_POST['tqty']."',
								'".$_POST['tcomments']."',
								'".time()."'
								);
						";

			$dbhi->query($sql_q) or die ($dbhi->error);
			if ( $dbhi->affected_rows == 1 )
				header("Location: ".$_SERVER['PHP_SELF']."?iid=".$_POST['iid']."&submit_success=yes&message=".$_POST['trans_t']." Added");
			else
				header("Location: ".$_SERVER['PHP_SELF']."?iid=".$_POST['iid']."&submit_success=no&message=MYSQL_ERR_NOTONERET" );

		}

	if ( !empty($_GET['submit_success']) ) {
		if ( $_GET['submit_success'] == "yes" )
			echo "<div style='display:none' class='successbox' id='vadd_msg_success'>".$_GET['message'].": Success</div>";
		else
			echo "<div style='display:none' class='errorbox' id='vadd_msg_failure'>".$_GET['message'].": Failed</div>";
	}
?>
<html>
<head>
	<title><?php echo $Company_Name;?> Stock :: Items Access</title>
	<script type="text/javascript" src='js/jquery.js'></script>
	<script type="text/javascript" src='js/jquery-ui.js'></script>
	<link rel="stylesheet" href="css/jquery-ui.css" />
	<link href="css/table_box.css" rel="stylesheet" type="text/css">


	<script>

		//Handling Success and Failure Messages
		$('document').ready(function() {
			$('[id^=vadd_msg_]').fadeIn(1000);
			$('[id^=vadd_msg_]').delay(5000).fadeOut(1000);
		});

		//Date
		$(function() {
			$('[name=t_date_display]').datepicker({
				dateFormat: "dd M yy",
				altField: '[name=t_date_val]',
				altFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange: "2013:2023"
			});
			$('[name=t_date_display]').datepicker("setDate", new Date);
		});	

		//Submit
		$(document).on('click', '#add_button', function() {
			$('#addform').submit();
		});

		// View Invoice
		$(document).on( 'click', '[name=s_invid_link], [name=p_invid_link]', function() {
			var invid=$(this).text();
			if (this.name == 's_invid_link')
				var opr='sale';
			else if (this.name == 'p_invid_link')
				var opr='purchase';
			else
				alert('test');
			var page = "invoice.php?op=view&type="+opr+"&invoice_id="+invid;
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


		//Disable InvoiceNo. if transaction is Purchase
		$(document).on('change', '[name=trans_t]', function() {
			if ( $('[name=trans_t]').val() == 'Sale'  ){
				$('[name=tinvoiceno]').prop('disabled', false);
				$('[name=trans_t]').css({ 'background': 'white' });
			}
			else if ( $('[name=trans_t]').val() == 'Purchase' ) {
				$('[name=tinvoiceno]').prop('disabled', true);
				$('[name=trans_t]').css({ 'background': 'red' });
			}
		});

		//disable form submit on enter key press
		$(window).keydown(function(event) {
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});


	</script>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>

<center>
<br>
<br>
	<table border="1" class="tclass_std">
		<thead>		
			<tr>
				<th style="font-size:22px;color:black; border:0px" colspan=8 align=center>
					<table width="100%" border=0>
						<tr>
							<th style="border-top:0px; border-bottom:0px; font-size:22px" align=center>
							<!--	(<?php echo "$current_itemgroup";?>) !-->
								#<?php echo "$current_itemid"; ?>
								<?php echo "$current_itemname";?>
								<br>
								<font style="font-size:12px"><?php echo "$current_itemdetail";?></font>
							</th>
							<th style="border-top:0px; border-bottom:0px; color:#880000;" align=right>
								Sale: <?php echo $current_totalsale;?> Purchase: <?php echo $current_totalpurchase;?>
								<br>
								Stock: <?php echo $current_totalpurchase - $current_totalsale; ?>
							</th>
						</tr>
					</table>
				</th>
			</tr>
			<tr>
				<th>Date</th>
				<th>Transaction</th>
				<th>Invoice No.</th>
				<th>Unit Price</th>
				<th>Qty</th>
				<th>Total</th>
				<th>Comments</th>
			</tr>


			<form id="addform" name="addform" method="post"  action="<?php echo $_SERVER['PHP_SELF']."?iid=$current_itemid"; ?>">
				<input type="hidden" name="iid" value="<?php echo $current_itemid; ?>" />
				<tr>
					<th>
						<input type="text" name="t_date_display" size=10 readonly/>
						<input type="hidden" name="t_date_val" size=10 />
					</th>
					<th>
						<select name="trans_t">
						<?php
							foreach ($Transaction_Types as $_trans ){
								echo "<option value=\"$_trans\">$_trans</option>";
							}
						?>
						</select>
					</th>
					<th>
						<input name="tinvoiceno" type="text" size="20" />
					</th>
					<th>
						<input name="tuprice" value="0" type="text" size="5"/><?php echo $Currency?>
					</th>
					<th>
						<input name="tqty" value="1" type="text" size="5"/>
					</th>
					<th>
						<input name="ttotal" disabled="disabled" value="0" type="text" size="5" /><?php echo $Currency?>
					</th>
					<th>
						<textarea name="tcomments" rows=1 cols=24 ></textarea>
						<a href="javascript:;"><img id="add_button" height=35 width=35 src="imgs/add.png" /></a>
					</th>
				</tr>
			</form>

		</thead>

		<tbody>

				<?php
					$mysql_q = "
								(	SELECT 'Sale' as SPCH, date, invoice_no as inv, uprice, qty, amount_received, comments, timestamp
									FROM sale_transactions
									WHERE item_id = $current_itemid
								)
								UNION
								(	SELECT 'Purchase' as SPCH, date, invoice_id as inv, uprice, qty, null, comments, timestamp
									FROM purchase_transactions
									WHERE item_id = $current_itemid
								)
								ORDER BY date DESC, timestamp DESC
								";
					$query_res = $dbhi->query($mysql_q);

					while($row = $query_res->fetch_assoc() ) {

						if ( $row['SPCH'] == 'Purchase' )
							$row_style='style="color:#d00000"';
						else
							$row_style='';

						//Display Invoice link if purchase or (sale & KTSS)
						if ($row['SPCH'] == 'Purchase' && $row['inv'] > 0 )
							$inv_td_html='<a name="p_invid_link" href="javascript:;">'.$row['inv'].'</a>';
						elseif ( $row['SPCH'] == 'Sale' && substr($row['inv'],0,4) === 'KTSS') {
							$inv_td_html='<a name="s_invid_link" href="javascript:;">'.substr($row['inv'],4).'</a>';
						}
						else {
							$inv_td_html=$row['inv'];
						}


						echo
						"
							<tr>
								<td $row_style>".$row['date']."</td>
								<td $row_style>".$row['SPCH']."</td>
								<td $row_style>$inv_td_html</td>
								<td $row_style title='".clean_num($row['uprice'])."'>".price2code(clean_num($row['uprice']))."</td>
								<td $row_style>".clean_num($row['qty'])."</td>
								<td $row_style title='".clean_num($row['uprice'] * $row['qty'])."'>".price2code(clean_num($row['uprice'] * $row['qty']))."</td>
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
