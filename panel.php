<?php
	$parts = explode('/', $_SERVER["PHP_SELF"]);
	$self_file = $parts[count($parts) - 1];

	$style[$self_file] =' style="color: white;" ';

?>

<table id="panetable" cellpadding="0" cellspacing="0">
	<tr>
		<td align="right" valign="top">
			<table cellpadding="5" cellspacing="0" bgcolor="#212121">
				<tr>
					<td align="center"><a class="panel" href="pos.php" <?php echo @$style['pos.php'];?>>
						&nbsp;&nbsp;POS&nbsp;&nbsp;
					</a></td>
					<td align="center"><a class="panel" href="sale.php" <?php echo @$style['sale.php'];?>>
						&nbsp;&nbsp;Sale&nbsp;&nbsp;
					</a></td>

					<td align="center"><a class="panel" href="purchase.php" <?php echo @$style['purchase.php'];?>>
						&nbsp;&nbsp;Purchase&nbsp;&nbsp;
					</a></td>
					<td align="center"><a class="panel" href="stock.php" <?php echo @$style['stock.php'];?>>
						&nbsp;&nbsp;Stock&nbsp;&nbsp;
					</a></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td align="center"><a class="panel" href="vendors.php" <?php echo @$style['vendors.php'];?>>
							&nbsp;&nbsp;Vendors&nbsp;&nbsp;
						</a></td>
					<td></td>
					<td></td>
				</tr>

			</table>
		</td>
	</tr>
</table>
