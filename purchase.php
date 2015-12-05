<?php

	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}


	// Add Purchase Transaction
	if (	$_POST['t_itemid'] > 0
			&&
			!empty($_POST['t_date'])
			&&
			$_POST['tuprice'] > 0
			&&
			$_POST['tqty'] > 0
		) {
		$sql_q="INSERT INTO purchase_transactions
						( p_trans_id, date, item_id, uprice, qty, comments, timestamp )
				VALUES	(
						'',
						'".$_POST['t_date']."',
						'".$_POST['t_itemid']."',
						'".$_POST['tuprice']."',
						'".$_POST['tqty']."',
						'".$_POST['tcomments']."',
						'".time()."'
						);
				";

			$dbhi->query($sql_q) or die($dbhi->error);
			if ( $dbhi->affected_rows == 1 )
				header("Location: ".$_SERVER['PHP_SELF']."?success=yes&message=Purchase Added");
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

		//Submit Transations
		$(document).on('click', '#add_button_t', function() {
			$('#addform_t').submit();
		});

		//Auto Complete (Transaction)
		$(document).on('focus', 'input[name="t_iname"]', function() {
			$(this).autocomplete({
				source:'suggest_items.php',
				minLength:1,
				change: function( event, ui ) {
					if ( !ui.item ) {
						$(this).val('');
					}
				},
				select: function( event, ui ) {
					//var index = $('input[name="t_iname[]"]').index(this);
					//$('input[name="t_itemid[]"]').eq(index).val(ui.item.id);

					$('input[name="t_itemid"]').val(ui.item.id);
					$(this).prop('readonly',true);
				}
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li>" )
				.append( item.label )
				.appendTo( ul );
			};
		});



		//Pressing Esc should clear/reset the addTransaction form
		$(document).keypress(function(e) {
			if (e.keyCode == 27) {
				$('input[name=t_iname]').val('');
				$('input[name=t_itemid]').val('');
				$('input[name=tuprice]').val('0');
				$('input[name=tqty]').val('1');
				$('input[name=ttotal]').val('0');
				$('input[name=t_iname]').prop('readonly',false);
			}
		});

		//Update total (Transaction)
		$(document).on('click, change, focus, blur', 'input[name="tuprice"], input[name="tqty"]', function() {
			$('input[name="ttotal"]').val($('input[name="tqty"]').val()*$('input[name="tuprice"]').val());
		});


		// View Invoice
		$(document).on( 'click', '[name="a_invid[]"]', function() {
			var index=$('[name="a_invid[]"]').index(this);
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

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
<?php include_once('include/add_vendor.php'); ?>
<?php include_once('include/add_purchase_invoice.php'); ?>


<b>Purchases</b>
<center>

	<table border="1" class="tclass_std">
		<thead>		
			<tr>
				<th>Date</th>
				<th>Item</th>
				<th>Vendor</th>
				<th>Invoice No.</th>
				<th>Unit Price</th>
				<th>Qty</th>
				<th>Total</th>
				<th>Comments</th>
			</tr>

			<form id="addform_t" name="addform_t" method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<tr>
					<th>
						<input type="text" id="t_date_display" size=10 readonly required />
						<input type="hidden" name="t_date" id="t_date_submitvalue" size=10 />
					</th>
					<th>
						<input  placeholder="Item Name" name="t_iname" type=text size=30 required/>
						<input type="hidden" name="t_itemid" value="" />
					</th>
					<th>
					</th>
					<th>
					</th>
					<th>
						<input name="tuprice" value="0" type="number" step="any" min="0.1" class="input-num-4" /><?php echo $Currency?>
					</th>
					<th>
						<input name="tqty" value="1" type="number" step="any" min="0.001" class="input-num-3" />
					</th>
					<th>
						<input name="ttotal" disabled="disabled" value="0" type="number" step="any" class="input-num-5" />
						<?php echo $Currency?>
					</th>
					<th>
						<textarea name="tcomments" rows=1 cols=20 ></textarea>
						<input type="image"  height=35 width=35 src="imgs/add.png" />
					</th>
				</tr>
			</form>
		</thead>

		<tbody>

				<?php
					$mysql_q = "
								SELECT p_trans_id, date, item_id, vendor_id, invoice_id, uprice, qty, comments, timestamp
								FROM purchase_transactions
								ORDER BY p_trans_id DESC
								";
					$query_res = $dbhi->query($mysql_q);

					while($row = $query_res->fetch_assoc() ) {

						$item_info = get_item_info($row['item_id'] ,$dbhi);


						$row_style='style="color:#d00000"';



						if ( !empty($row['invoice_id'])) {
							$inv_no=get_vendor_invoice($row['invoice_id'],$dbhi);
							$invoice_html="	<a name='a_invid[]' href='javascript:;'>
												$inv_no
												<input type='hidden' name='val_invid[]' value='".$row['invoice_id']."' />
											</a>";
						}
						else
							$invoice_html="N/A";
						echo
						"
							<tr>
								<td $row_style>".$row['date']."</td>

								<td $row_style>
									<a href='transactions.php?iid=".$row['item_id']."'>
										".$item_info['name']."
									</a>
								</td>
								<td $row_style>
									<a href='vendors.php?vendorid=".$row['vendor_id']."'>
										".get_vendorname($row['vendor_id'],$dbhi)."
									</a>
								</td>
								<td $row_style>$invoice_html</td>
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
