<?php

	if ( !$_POST['iid'] > 0 )
		die('ERROR: Bad call. Shouldnt be called directly');

	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}


	if( $_POST['iid'] > 0 && $_POST['isp'] > 0 ) {

		$new_saleprice = clean_num($_POST['isp']);
		$i_id = $_POST['iid'];

		$mysql_q = "UPDATE items
						SET item_saleprice = '$new_saleprice'
						WHERE item_id = '$i_id'";
		$dbhi->query($mysql_q) or die($dbhi->error);
		echo 'UPDATED: Item SalePrice';
	}
	elseif( $_POST['iid'] > 0 && !empty($_POST['inm']) ) {

		$new_name = clean_num($_POST['inm']);
		$i_id = $_POST['iid'];

		$mysql_q = "UPDATE items
							SET item_name = '$new_name'
							WHERE item_id = '$i_id'";
		$dbhi->query($mysql_q) or die($dbhi->error);
		echo 'UPDATED: Item Name';
	}


?>
