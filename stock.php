<?php
	require('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}

//	//Add Group
//	if ( !empty($_POST['add_group']) && !empty($_POST['gname']) ) {
//		$mysql_q="INSERT INTO item_groups
//					VALUES (
//								'',
//								'".$_POST['gname']."'
//							)";
//		$dbhi->query($mysql_q);
//		if ( $dbhi->affected_rows == 1 )
//			header("Location: " . $_SERVER['PHP_SELF'] . "?submit_success=yes&message=Group Added");
//		else
//			header("Location: " . $_SERVER['PHP_SELF'] . "?submit_success=no&message=MYSQL ERR" . mysql_errno() );
//	}


	//Add Item
	if (	!empty($_POST['add_item'])
			&&
			!empty($_POST['iname'])
		) {
		$mysql_q="INSERT INTO items
					(item_id, item_name, item_detail, item_saleprice)
					VALUES (
								'',
								'".$_POST['iname']."',
								'".$_POST['idetails']."',
								'".$_POST['isprice']."'
							)";
		$dbhi->query($mysql_q);
		if ( $dbhi->affected_rows == 1 )
			header("Location: " . $_SERVER['PHP_SELF'] . "?submit_success=yes&message=Item Added");
		else
			header("Location: " . $_SERVER['PHP_SELF'] . "?submit_success=no&message=MYSQL ERR" . mysql_errno() );
	}

	//Show Result Messages
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

	<script type="text/javascript" charset="utf-8">

		//Quick Filter
			//override :contains selector to ignore case
			//http://css-tricks.com/nippets/jquery/make-jquery-contains-case-insensitive/
			$.expr[":"].contains = $.expr.createPseudo(function(arg) {
				return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
				};
			});
		$(document).on( 'keyup', '#ipid_search', function() {

			if ( !$(this).val() ) {
				$('#tbodyid').find("tr").show();
			}
			var rows = $('#tbodyid').find("tr").hide();
			var data = this.value.split(" ");
			$.each(data, function (i,v) {
				if (v)
					rows=rows.filter(":contains('"+v+"')");
			});
			$(rows).show();
		});

		//inline editor for sale price
		$(document).on ( 'dblclick', '[id^=editable_]', function() {
			var caller=$(this).prop('id');
			var caller_index=caller.split("_").pop();	
			$("#editable_"+caller_index).hide();
			$("#editbox_"+caller_index).show();
			$("#editbox_"+caller_index).select();
		});
		$(document).on( 'blur', '[id^=editbox_]', function(event) {
			//alert(event.type);
			var caller=$(this).prop('id');
			var caller_index=caller.split("_").pop();
			$("#editable_"+caller_index).show();
			$("#editbox_"+caller_index).hide();
			$("#editbox_"+caller_index).val($('#editable_'+caller_index).text());
		});	
		$(document).on( 'keyup', '[id^=editbox_]', function(event) {
			if ( (event.type == 'keyup' && event.keyCode == 13) ) {
				var caller=$(this).prop('id');
				var caller_index=caller.split("_").pop();
				var new_saleprice=$("#editbox_"+caller_index).val();
				var old_saleprice=$('#editable_'+caller_index).text();

				if ( new_saleprice > -1  && caller_index > 0 ) {
					//alert (event.type+':'+old_saleprice + ' ' + new_saleprice);
					$.ajax({
						type: "POST",
						url: "edit_item.php",
						data: "iid="+caller_index+"&isp="+new_saleprice	,
						success: function(data){
							if ( data.indexOf("UPDATED:") == 0 ) {
								$("#editbox_"+caller_index).hide();
								$("#editable_"+caller_index).text(new_saleprice);
								$("#editable_"+caller_index).show();
								alert('Price Updated');
							}
							else {
								alert("ERROR: Failed to update!");
								alert (data);
							}
						}
					});
				}
			}
		});


		//inline editor for item name
		$(document).on( 'dblclick', '[name="iid_editable[]"]', function() {
			var index=$('[name="iid_editable[]"]').index(this);
			$('[name="inm_disp[]"]').eq(index).hide();
			$('[name="inm_editable[]"]').eq(index).attr('size', $('[name="inm_disp[]"]').eq(index).text().length);
			$('[name="inm_editable[]"]').eq(index).show();
			$('[name="inm_editable[]"]').eq(index).focus();
			$('[name="i_link[]"]').eq(index).removeAttr('href');
		});
		$(document).on( 'blur', '[name="inm_editable[]"]', function() {
			var index=$('[name="inm_editable[]"]').index(this);
			$('[name="inm_disp[]"]').eq(index).show();
			$('[name="inm_editable[]"]').eq(index).hide();
			$('[name="inm_editable[]"]').eq(index).val($('[name="inm_disp[]"]').eq(index).text());
			$('[name="i_link[]"]').eq(index).attr('href', $('[name="link_space[]"]').eq(index).val());
		});
		$(document).on( 'keyup', '[name="inm_editable[]"]', function(event) {
			if ( (event.type == 'keyup' && event.keyCode == 13) ) {
				var index=$('[name="inm_editable[]"]').index(this);
				var new_name=$('[name="inm_editable[]"]').eq(index).val();
				var old_name=$('[name="inm_disp[]"]').eq(index).text();
				var itid=$('[name="iid_editable[]"]').eq(index).text();

				if (new_name != old_name)
					//alert('Chaging'+old_name+' to '+new_name);
					$.ajax({
						type: "POST",
						url: "edit_item.php",
						data: "iid="+itid+"&inm="+new_name,
						success: function(data){
							if ( data.indexOf("UPDATED:") == 0 ) {
								$('[name="inm_disp[]"]').eq(index).text(new_name);
								alert(data);
							}
							else {
								alert("ERROR: Failed to update!");
								alert (data);
							}
						}
					});

			}
		});


		//Handling Success and Failure Messages
		$('document').ready(function() {
			$('[id^=vadd_msg_]').fadeIn(1000);
			$('[id^=vadd_msg_]').delay(5000).fadeOut(1000);
		});

		//Add Group popup
		$(function() {
			$( "#div_add_g" ).dialog( {
				autoOpen: false,
				height: 370,
				width: 500,
				modal: true,
				position: ['middle',20],
				dialogClass: "c_popup",
				close: function() {
				}
			});
		});
		$(document).on('click', '#b_add_g', function() {
			$('#div_add_g').dialog( "open" );
		});

		//Add Item popup
		$(function() {
			$( "#div_add_i" ).dialog( {
				autoOpen: false,
				height: 320,
				width: 500,
				modal: true,
				position: ['middle',20],
				dialogClass: "c_popup",
				close: function() {
				}
			});
		});
		$(document).on('click', '#b_add_i', function() {
			$('#div_add_i').dialog( "open" );
		});

		//disable form submit on enter key press
		$(window).keydown(function(event) {
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});


	</script>

</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
	<!--
	<button style="margin: 10px; font-weight:bold; background-color:white;" id="b_add_g">Add Group</button>
	!-->
	<button style="margin: 10px; font-weight:bold; background-color:white;" id="b_add_i">Add Item</button>

<script>

		//Temp Add item table
		$(document).on('click', '#add_button', function() {
			$('#quick_i_add').submit();
		});
</script>

<center>

	<table border="1" class="tclass_std" >
		<thead>
			<tr>
				<th align="center" colspan="5">
					<input id="ipid_search" placeholder="Quick Filter" type=text size=30 />
				</th>
			</tr>
			<tr>
				<th>ID</th>
				<th>Item Name</th>
				<th>Code</th>
				<th>Stock</th>
				<th>Sale Price</th>
			</tr>
		</thead>

		<tbody id="tbodyid">
			<?php
				$mysql_q = "SELECT item_id, item_name, item_detail, item_saleprice
							FROM items
							ORDER BY item_id DESC";
				$query_res = $dbhi->query($mysql_q) or die ($dbhi->error);
				while($row = $query_res->fetch_assoc() ) {
					$i_id = $row['item_id'];
					$i_name = $row['item_name'];
					$i_detail = $row['item_detail'];
					$i_saleprice = clean_num($row['item_saleprice']);
						$stock_info = get_stock_info($i_id, $dbhi);
					$i_costprice = get_costprice($i_id, $dbhi);
					$i_costcode = price2code($i_costprice);
					$i_stocknumber = $stock_info['total_purchase'] - $stock_info['total_sale'];
					
					echo
					"
					<tr>
						<td>
							#<div style='display: inline' name='iid_editable[]'>$i_id</div>
						</td>
						<td>
							<b><a name='i_link[]' href='transactions.php?iid=$i_id'>
								<div name='inm_disp[]'>$i_name</div>
								<input style='display: none' name='inm_editable[]' value='$i_name'/>
								<input type='hidden' name='link_space[]' value='transactions.php?iid=$i_id'/>
							</a></b>
							<div style='font-size:0.9em'>$i_detail</div>
						</td>
						<td title='$i_costprice'>
							$i_costcode
						</td>
						<td title='".$stock_info['total_purchase']."|".$stock_info['total_sale']."|".$stock_info['total_credit']."'>
							$i_stocknumber
						</td>
						<td>
							$Currency
							<div style='display: inline' id='editable_$i_id'>$i_saleprice</div>
							<input style='display: none' id='editbox_$i_id' size='4' value='$i_saleprice' type='text' />
						</td>
					</tr>
					";
				}
			?>
		</tbody>
	</table>


</center>

<!--
<div id="div_add_g" title="Add Group">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>
					<label for="gname">Group Name:</label>
				</td>
				<td>
					<input type=text name="gname" id="gname" size=30>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="add_group" value="Add Group" />
				</td>
			</tr>
		</table>
	</form>
</div>
!-->

<div id="div_add_i" title="Add Item">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>
					<label for="iname">Item Name:</label>
				</td>
				<td>
					<input type=text name="iname" id="iname" size=35 required>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<textarea name="idetails" id="idetails" placeholder="Items Details"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label for="isprice">Sale Price:</label>
				</td>
				<td>
					<input name="isprice" id="isprice" value="0" type="number" step="any" class="input-num-4"> <?php echo $Currency ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="add_item" value="Add Item" />
				</td>
			</tr>
		</table>

	</form>
</div>