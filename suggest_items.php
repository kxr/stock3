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

	//If the input is #<id> or *<id>, then search for that particular id
	if (
			( substr($_REQUEST['term'], 0,1) == '#' || substr($_REQUEST['term'], 0,1) == '*' )
			&&
			is_numeric(substr($_REQUEST['term'],1))
		) {
		$iid=substr($_REQUEST['term'],1);
		$mysql_q = "SELECT	item_id,
							item_name,
							item_detail,
							item_saleprice
					FROM items
					WHERE item_id = '$iid'
					";
	}
	// Else treat it as generic text search
	else {
			$terms = explode( " ", $_REQUEST['term']);

			$mysql_q = "SELECT	item_id,
							item_name,
							item_detail,
							item_saleprice
					FROM items
					";
			$first=1;
			foreach ($terms as $term) {
				if ( $first == 1)
					$mysql_q = $mysql_q." WHERE ( item_name LIKE '%$term%'";
				else
					$mysql_q = $mysql_q." AND item_name LIKE '%$term%'";
				$first=0;
			}
			$mysql_q = $mysql_q.")";

//			$first=1;
//			foreach ($terms as $term) {
//			if ( $first == 1)
//				$mysql_q = $mysql_q." OR ( group_name LIKE '%$term%'";
//			else
//				$mysql_q = $mysql_q." AND group_name LIKE '%$term%'";
//			$first=0;
//			}
//			$mysql_q = $mysql_q.")";
	}





	
	$q_result = $dbhi->query($mysql_q);

	if ( $q_result->num_rows > 0 ) {

		while( $row = $q_result->fetch_assoc() ) {
			$costprice = get_costprice($row['item_id'], $dbhi);
			$stock_info= get_stock_info($row['item_id'], $dbhi);
			$stock_num = $stock_info['total_purchase']-$stock_info['total_sale']-$stock_info['total_credit'];
			$data[] = array(
				'label' => "<a>".
								"#".$row['item_id'].
								" ".
								$row['item_name'].
								"<span style='font-size:0.9em'>".
									" (".
									price2code($costprice).
									"|".
									clean_num($stock_num).
									")".
								"</span>".
								"<div style='font-size:0.8em'>".
									//"<span style='color:grey'>".
									//	"[".
									//	$row['group_name'].
									//	"] ".
									//"</span>".
									$row['item_detail'].
								"</div>".
							"</a>",
				//'label' => $row['item_name']." [".$row['group_name']."]"." (".price2code($costprice).")",
				'group' => $row['group_name'],
				'detail' => $row['item_detail'],
				'value' => $row['item_name'],
				'id'		=> $row['item_id'],
				'saleprice'	=> clean_num($row['item_saleprice']),
				'costprice' => $costprice
			);
		}
	}

	echo json_encode($data);
	flush();

?>
