<?php

	//Add Vendor
	if (	!empty($_POST['add_vendor'])
		&&
		!empty($_POST['vname'])
	) {
		$mysql_q="INSERT INTO vendors
											(vendor_id, vendor_name, vendor_phone, vendor_fax, vendor_email, vendor_details )
						VALUES (
									'',
									'".$_POST['vname']."',
									'".$_POST['vphone']."',
									'".$_POST['vfax']."',
									'".$_POST['vemail']."',
									'".$_POST['vdetail']."'
								)";
		$dbhi->query($mysql_q);
		if ( $dbhi->affected_rows == 1 )
			header("Location: " . $_SERVER['PHP_SELF'] . "?success=yes&message=Vendor Added");
		else
			header("Location: " . $_SERVER['PHP_SELF'] . "?success=no&message=MYSQL ERR" . mysql_errno() );
	}
?>

<script>

	//Add Vendor popup
	$(function() {
		$( "#div_add_v" ).dialog( {
			autoOpen: false,
			height: 370,
			width: 500,
			modal: true,
			position: ['middle',20],
			close: function() {
			}
		});
	});
	$(document).on('click', '#b_add_v', function() {
		$('#div_add_v').dialog( "open" );
	});






</script>


<button style="margin: 10px; font-weight:bold; background-color:white;" id="b_add_v">Add Vendor</button>




<div id="div_add_v" style='display:none' title="Add Vendor">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>
					<label for="vname">Vendor Name:</label>
				</td>
				<td>
					<input type=text name="vname" id="vname" size=30 required>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vphone">Vendor Phone:</label>
				</td>
				<td>
					<input type=text name="vphone" id="vphone" size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vfax">Vendor Fax:</label>
				</td>
				<td>
					<input type=text name="vfax" id="vfax" size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vemail">Vendor Email:</label>
				</td>
				<td>
					<input type=text name="vemail" id="vemail" size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vdetail">Vendor Details:</label>
				</td>
				<td>
					<textarea name="vdetail" id="vdetail" rows=2 cols=24 ></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="add_vendor" value="Add Vendor" />
				</td>
			</tr>
		</table>
	</form>
</div>
