<?php
	require('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}

	//Add Vendor
	if ( !empty($_POST['add_client']) && !empty($_POST['cname']) ) {
		$mysql_q="INSERT INTO credit_clients
					VALUES (
								'',
								'".$_POST['cname']."',
								'".$_POST['cphone']."',
								'".$_POST['cfax']."',
								'".$_POST['cemail']."',
								'".$_POST['cdetails']."'
							)";
		$dbhi->query($mysql_q);
		if ( $dbhi->affected_rows == 1 )
			header("Location: " . $_SERVER['PHP_SELF'] . "?submit_success=yes&message=Add Client");
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
	<title><?php echo $Company_Name;?> Stock :: Credit Ledger</title>
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


		//Handling Success and Failure Messages
		$('document').ready(function() {
			$('[id^=vadd_msg_]').fadeIn(1000);
			$('[id^=vadd_msg_]').delay(5000).fadeOut(1000);
		});

		//Add Client popup
		$(function() {
			$( "#div_add_client" ).dialog( {
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
		$(document).on('click', '#b_add_client', function() {
			$('#div_add_client').dialog( "open" );
		});

	</script>

</head>

<body bgcolor="silver">
<?php include_once('panel.php'); ?>
	<button style="margin: 10px; font-weight:bold; background-color:white;" id="b_add_client">Add Credit Client</button>
<center>

	<table border="1"  class="tclass_stock" >
		<thead>
			<tr>
				<th align="center" colspan="5">
					<input id="ipid_search" placeholder="Quick Filter" type=text size=30 />
				</th>
			</tr>
			<tr>
				<th>ID</th>
				<th>Client Name</th>
				<th>Balance</th>
				<th>Last Payment</th>
				<th>Blank</th>
			</tr>
		</thead>

		<tbody id="tbodyid">
			<?php
				$mysql_q = "SELECT * FROM credit_clients";
				$query_res = $dbhi->query($mysql_q);
				while($row = $query_res->fetch_assoc() ) {
					$c_id = $row['client_id'];
					$c_name = $row['client_name'];
					$c_phone = $row['client_phone'];
					$c_fax = $row['client_fax'];
					$c_email = $row['client_email'];
					$c_details = $row['client_details'];
					
					echo
					"
					<tr>
						<td>
							$c_id
						</td>
						<td>
							<b><a href='credit_ledger.php?cid=$c_id'>
								<div id='cname_$c_id'>$c_name</div>
							</a></b>
							<div style='font-size:0.8em'>$c_details</div>
							<div style='font-size:0.7em'>Phone:$c_phone| Fax:$c_fax| Email:$c_email</div>
						</td>
						<td>
							Balance
						</td>
						<td>
							Last Payment
						</td>
						<td>
							Blank
						</td>
					</tr>
					";
				}
			?>
		</tbody>
	</table>


</center>


<div id="div_add_client" title="Add Client">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table>
			<tr>
				<td>
					<label for="cname">Client Name:</label>
				</td>
				<td>
					<input type=text name="cname"  size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vphone">Phone Nos:</label>
				</td>
				<td>
					<input type=text name="cphone" size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vfax">Fax Nos:</label>
				</td>
				<td>
					<input type=text name="cfax" size=30>
				</td>
			</tr>
			<tr>
				<td>
					<label for="vemail">Email</label>
				</td>
				<td>
					<input type=text name="cemail" size=30>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<textarea name="cdetails" placeholder="Client Details"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="add_client" value="Add Client" />
				</td>
			</tr>
		</table>
	</form>
</div>

</body>
</html>
