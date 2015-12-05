<?php

// Add Invoice
if (	!empty($_POST['inv_date'])
	&&
	!empty($_POST ['inv_invoiceno'])
	&&
	$_POST['inv_venid'] > 0
) {

	//check if the array contains atleast one valid entry
	$valid_entry="no";
	foreach ( $_POST['invr_itemid'] as $ix => $value ) {
		if ( $_POST['invr_itemid'][$ix] > 0 && $_POST['invr_qty'][$ix] > 0 ) {
			$valid_entry="yes";
		}
	}

	if ( $valid_entry == "yes") {

		//enter invoice
		$sql_q="INSERT INTO purchase_invoices
											(
											p_invoice_id,
											date,
											vendor_id,
											vendor_invoice_no,
											invoice_note,
											invoice_total,
											payment_id,
											timestamp
											)
											VALUES
											(
											'',
											'".$_POST['inv_date']."',
											'".$_POST['inv_venid']."',
											'".$_POST['inv_invoiceno']."',
											'".$_POST['inv_note']."',
											'".$_POST['inv_total']."',
											'',
											'".time()."'
											)
				";
		$q_res=$dbhi->query($sql_q) or die($dbhi->error);
		$new_invoice_id = $dbhi->insert_id;

		//enter invoice records (purchase entries)
		foreach ( $_POST['invr_itemid'] as $ix => $value ) {
			if ( $_POST['invr_itemid'][$ix] > 0 && $_POST['invr_qty'][$ix] > 0 ) {

				$sql_q="INSERT INTO purchase_transactions
														(
														p_trans_id,
														date,
														item_id,
														vendor_id,
														invoice_id,
														uprice,
														qty,
														comments,
														timestamp
														)
														VALUES
														(
														'',
														'".$_POST['inv_date']."',
														'".$_POST['invr_itemid'][$ix]."',
														'".$_POST['inv_venid']."',
														'".$new_invoice_id."',
														'".$_POST['invr_uprice'][$ix]."',
														'".$_POST['invr_qty'][$ix]."',
														'".$_POST['invr_comments'][$ix]."',
														'".time()."'
														)
						";
				$q_res=$dbhi->query($sql_q) or die($dbhi->error);
			}
		}
	}
	else
		header("Location: ".$_SERVER['PHP_SELF']."?&success=no&message=No Valid Record in Invoice");


}
?>



<script>

	$.fn.f_add_inv_row= function () {
		$("#tid_inv").append (
			'\
				<tr> \
					<td>\
						<input  placeholder="Item Name" name="invr_iname[]" type=text size=30/>\
						<input type=hidden name="invr_itemid[]" value="" />\
					</td>\
					<td>\
						<input name="invr_uprice[]" value="0" type="number" step="any" class="input-num-4"/><?php echo $Currency?>\
                </td>\
                <td>\
                    <input name="invr_qty[]" value="1" type="number" step="any" class="input-num-3"/>\
                </td>\
                <td>\
                    <input name="invr_total[]" disabled="disabled" value="0" type="number" step="any" class="input-num-5" /><?php echo $Currency?>\
                </td>\
                <td>\
                    <textarea name="invr_comments[]" rows=1 cols=24 ></textarea>\
                </td>\
            </tr>\
        '
		);
	}
	$(document).on('ready', function() {
		$(this).f_add_inv_row();
	});

	$(document).on('click', '#add_inv_row', function() {
		$(this).f_add_inv_row();
	});

	$(document).on( 'keypress', function(key) {
		switch(key.which) {
			case 43:
				key.preventDefault();
				$(this).f_add_inv_row();
				break;
		}
	});
	$(function() {
		$("#inv_date_display").datepicker({
			dateFormat: "dd M yy",
			altField: "#inv_date",
			altFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true,
			yearRange: "2013:2023"
		});
		$("#inv_date_display").datepicker("setDate", new Date);
	});
	//Submit Invoice
	$(document).on('click', 'input[name=submit_purchase_inv]', function() {
		$('#addform_inv').submit();
	});
	//Auto Complete (Invoice)
	$(document).on('focus', 'input[name="invr_iname[]"]', function() {
		$(this).autocomplete({
			source:'suggest_items.php',
			minLength:1,
			change: function( event, ui ) {
				if ( !ui.item ) {
					$(this).val('');
				}
			},
			select: function( event, ui ) {
				var index = $('input[name="invr_iname[]"]').index(this);
				$('input[name="invr_itemid[]"]').eq(index).val(ui.item.id);
				$(this).prop('readonly',true);
				$('input[name="invr_uprice[]"]').eq(index).select();
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( item.label )
				.appendTo( ul );
		};
	});
	//Auto Complete Vendor
	$(document).on('focus', 'input[name=inv_venname]', function() {
		$(this).autocomplete({
			source:'suggest_vendors.php',
			minLength:1,
			change: function( event, ui ) {
				if ( !ui.item ) {
					$(this).val('');
				}
			},
			select: function( event, ui ) {
				$('input[name=inv_venid]').val(ui.item.id);
				$(this).prop('readonly',true);
				$('input[name=inv_invoiceno]').select();
			}
		});

	});
	//Add Invoice popup
	$(function() {
		$( "#div_add_inv" ).dialog( {
			autoOpen: false,
			height: 600,
			width: '80%',
			modal: true,
			position: [50,50],
			close: function() {
			}
		});
	});
	$(document).on('click', '#b_add_i', function() {
		$('#div_add_inv').dialog( "open" );
	});
	//Update total (Invoice)
	$(document).on('click, change, focus, blur', 'input[name="invr_uprice[]"], input[name="invr_qty[]"]', function() {
		var index = $('input[name="invr_uprice[]"]').index(this) >= 0 ? $('input[name="invr_uprice[]"]').index(this) : $('input[name="invr_qty[]"]').index(this);
		$('input[name="invr_total[]"]').eq(index).val($('input[name="invr_qty[]"]').eq(index).val()*$('input[name="invr_uprice[]"]').eq(index).val());

		var inv_sum=0;
		$('input[name="invr_uprice[]"]').each(function() {
			var indx = $('input[name="invr_uprice[]"]').index(this) >= 0 ? $('input[name="invr_uprice[]"]').index(this) : $('input[name="invr_qty[]"]').index(this);
			inv_sum+=$('input[name="invr_qty[]"]').eq(indx).val()*$('input[name="invr_uprice[]"]').eq(indx).val();
		});
		//$('#inv_calc_total').html('<?php echo $Currency;?> '+inv_sum);
		$('input[name=inv_total]').val(inv_sum);
	});


	//If this called from the vendor page, set the vendor before hand
	<?php
		if( !empty($vendorID) && !empty($vendorName) )
		echo "
			$(document).on('ready', function() {
				$('[name=inv_venname]').val('$vendorName');
				$('[name=inv_venname]').prop('readonly',true);
				$('[name=inv_venid]').val('$vendorID');
			});
		";
	?>



</script>



<button style="margin: 10px; font-weight:bold; background-color:white;" id="b_add_i">Add Invoice</button>




<div id="div_add_inv" style='display:none' title="Add Invoice">
	<center>
		<form id="addform_inv" name="addform_inv" method="post"  action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table class='tclass_std' id=tid_inv border=1>
				<thead>
				<tr>
					<th colspan=6 align=center>
						<table width=100%>
							<tr>
								<th>
									<input type="text" id="inv_date_display" size=10 readonly/>
									<input type="hidden" name="inv_date" id="inv_date" size=10 />
								</th>
								<th>
									<input  placeholder="Vendor Name" name="inv_venname" type=text size=20 required />
									<input type="hidden" name="inv_venid" value="" />
								</th>
								<th>
									<input placeholder="Invoice No." name="inv_invoiceno" type="text" size="15" required />
								</th>
								<th align=right width=100%>
									<a href="javascript:;"><img id="add_inv_row" height=35 width=35 src="imgs/add.png" /></a>
								</th>
							</tr>
						</table>
					</th>
				</tr>
				<tr>
					<th>
						Item Name
					</th>
					<th>
						Unit
					</th>
					<th>
						Qty
					</th>
					<th>
						Total
					</th>
					<th>
						Comments
					</th>
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
									<div id="inv_calc_total">
										<?php echo $Currency; ?>
										<input type="number" step="any" class="input-num-5" name="inv_total" />
									</div>
								</th>
								<th style="" rowspan="2">
									<input type="submit" name="submit_purchase_inv" value="Submit">
								</th>
							</tr>
						</table>
					</th>
				</tr>
				</tfoot>
			</table>
		</form>
	</center>
</div>
