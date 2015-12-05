<?php

	//General Configuration Variables
	$Currency = "AED";
	$Company_Name = "Khurshid Traders";
	$Company_Title = "Khurshid Electric & Sanitary Trading LLC";
	$Company_Phone = "06 5621918";
	$Company_Fax = "06 5623894";
	$Company_Email = "info@kest.me";
	$Company_Web = "www.kest.me";
	$Company_SalePolicy="Items once sold will not be returned";
	$Price_Code = "KHURSIDTAE";
	//$Transaction_Types = array('Sale', 'Purchase', 'Credit', 'Hold');
	$Transaction_Types = array('Sale', 'Purchase');
	$Payment_Types = array('Cash', 'Cheque');


	//Database Configuration Variables
	$mysql_host = 'localhost';
	$mysql_user = 'ktdb2user';
	$mysql_pass = 'KTDB2p@55';
	$mysql_database = 'ktdb_stock';

	date_default_timezone_set('Asia/Dubai');

	// Decimal number cleaner i.e, removing trailing zeros
	// This function should go to a function library if one is built
	function clean_num( $num ){
		if ( $num == "" )
			return 0;
		$pos = strpos($num, '.');
		if($pos === false) { // it is integer number
			return $num;
		}
		else { // it is decimal number
			return rtrim(rtrim($num, '0'), '.');
		}
	}

	//Convert price to code
	function price2code ( $price) {
		global $Price_Code;
		$code_arr = str_split($Price_Code);
		$price_arr = str_split($price);
		$nums_arr = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0' );
		return str_replace( $nums_arr, $code_arr, $price);
	}

	//Get Item Info
	function get_item_info ( $itemid, $dbhi ) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}

		$item_info=array();
		$mysql_q="SELECT item_name, item_detail, item_saleprice
					FROM items
					WHERE item_id='$itemid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		//$item_info['group_id'] = $row['group_id'];
		$item_info['name'] = $row['item_name'];
		$item_info['detail'] = $row['item_detail'];
		$item_info['sale_price'] = $row['item_saleprice'];

		//$mysql_q="SELECT group_name
		//			FROM item_groups
		//			WHERE group_id='".$item_info['group_id']."'";
		//$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		//$row = $query_res->fetch_assoc();
		//$item_info['group_name'] = $row['group_name'];

		return $item_info;
	}

	//Get Group Info
	function get_group_info ( $itemid, $dbhi) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		$group_info=array();
		$mysql_q="SELECT group_id
					FROM items
					WHERE item_id='$itemid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		$group_info['id'] = $row['group_id'];

		$mysql_q="SELECT group_name
					FROM item_groups
					WHERE group_id='".$group_info['id']."'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		$group_info['name'] = $row['group_name'];

		return $group_info;
	}
	//Get Stock Info
	function get_stock_info ( $itemid, $dbhi ) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		$stock_info=array();

		$mysql_q="SELECT sum(qty) AS sum FROM purchase_transactions WHERE item_id='$itemid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		$stock_info['total_purchase'] = clean_num($row['sum']);

		$mysql_q="SELECT sum(qty) AS sum FROM sale_transactions WHERE item_id='$itemid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		$stock_info['total_sale'] = clean_num($row['sum']);

		//$mysql_q="SELECT sum(qty) AS sum FROM credit_transactions WHERE item_id='$itemid'";
		//$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		//$row = $query_res->fetch_assoc();
		//$stock_info['total_credit'] = clean_num($row['sum']);

		return $stock_info;
	}


	//Get Item Cost
	function get_costprice ( $itemid, $dbhi ) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		$mysql_q="SELECT uprice 
					FROM purchase_transactions
					WHERE item_id='$itemid'
					ORDER BY p_trans_id DESC";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows > 0 ) {
			$row = $query_res->fetch_assoc();
			return clean_num($row['uprice']);
		}
		else
			return 0;
	}

	//Get Vendor Info
	function get_vendor_info ( $vendorid, $dbhi ) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		$mysql_q="SELECT	vendor_name AS name,
							vendor_phone AS phone,
							vendor_fax AS fax,
							vendor_email AS email,
							vendor_details AS details
					FROM vendors
					WHERE vendor_id='$vendorid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows == 1 ) {
			$row = $query_res->fetch_assoc();
			return $row;
		}
		else
			return "Error: Got Multiple Vendors against one vendor id=$vendorid";


	}

	//Get Vendor Name
	function get_vendorname ( $vendorid, $dbhi ) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		if ( !isset($vendorid) )
			return 'N/A';

		$mysql_q="SELECT vendor_name
					FROM vendors
					WHERE vendor_id='$vendorid'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows == 1 ) {
			$row = $query_res->fetch_assoc();
			return $row['vendor_name'];
		}
		elseif ( $query_res->num_rows == 0)
			return "N/A";
		else
			return "Error: Got Multiple Vendors against one vendor id=$vendorid";
	}

	// Get Vendor Invoice no.
	function get_vendor_invoice ($inv_no, $dbhi) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}
		if (!isset($inv_no))
			return "N/A";
		$mysql_q="SELECT vendor_invoice_no
					FROM purchase_invoices
					WHERE p_invoice_id='$inv_no'";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		if ( $query_res->num_rows == 1 ) {
			$row = $query_res->fetch_assoc();
			return $row['vendor_invoice_no'];
		}
		elseif ( $query_res->num_rows  > 1 )
			return "Error: Multiple invoices found for id=$inv_no";
		else
			return "Error: No invoice found for id=$inv_no";
	}

	function get_vendor_pending($vend_id, $dbhi) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}

		//Logic to calculate the pending amount of a vendor
		// Calculate from invoices
		$mysql_q="	SELECT SUM(invoice_total) as inv_total_sum
					FROM purchase_invoices
					WHERE vendor_id='$vend_id' AND payment_id = ''";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		return clean_num($row['inv_total_sum']);

	}

	//Get total amount of a Purchase Invoice from transactions
	function get_purchase_invoice_total($p_inv_id, $dbhi) {
		if (!isset($dbhi)) {
			global $mysql_host, $mysql_user, $mysql_pass, $mysql_database;
			$dbhi = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_database);
		}

		$mysql_q="	SELECT SUM(uprice*qty) AS p_inv_total
					FROM purchase_transactions
					WHERE invoice_id = $p_inv_id
		";
		$query_res = $dbhi->query($mysql_q) or die($dbhi->error);
		$row = $query_res->fetch_assoc();
		return clean_num($row['p_inv_total']);
	}
?>
