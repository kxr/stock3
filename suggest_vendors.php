<?php


	if ( !isset($_REQUEST['term']) )
		exit;
		#$_REQUEST['term']='fan h/d';

	include_once('config.php');

	// MYSQL Connection and Database Selection
	$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
	if (mysqli_connect_errno()) {
		die('Could not connect to mysql: '. mysqli_connect_errno());
	}

	$data = array();
	$terms = explode( " ", $_REQUEST['term']);

	$mysql_q = "SELECT	vendor_id,
						vendor_name
				FROM vendors";

		$first=1;
		foreach ($terms as $term) {
			if ( $first == 1) 
				$mysql_q = $mysql_q." WHERE vendor_name LIKE '%$term%'";
			else
				$mysql_q = $mysql_q." AND vendor_name LIKE '%$term%'";
			$first=0;
		}
	
	$q_result = $dbhi->query($mysql_q);

	if ( $q_result->num_rows > 0 ) {

		while( $row = $q_result->fetch_assoc() ) {
			$data[] = array(
				'label'	=> $row['vendor_name'],
				'id'	=> $row['vendor_id']
			);
		}
	}

	echo json_encode($data);
	flush();

?>
