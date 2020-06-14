<?php

require_once('includes.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function pr_getOrderByPostID($postID){
	global $wpdb;
	if(!(isset($postID) && $postID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pr_orders WHERE post_id=$postID", ARRAY_A);
	if(!isset($postID)){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	if($results==null){
		// Possibly fill values with nothing?
		return ['order_id'=>'0', 'post_id'=>$postID, 'date_ordered'=>'', 'date_received'=>'', 'supplier'=>'', 'shipping_cost'=>0, 'tax'=>0];
	}
	return $results;
}
function pr_getOrderByOrderID($orderID){
	global $wpdb;
	if(!(isset($orderID) && $orderID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pr_orders WHERE order_id=$orderID", ARRAY_A);
	return $results;
}

function pr_getItemsByOrderID($orderID){
	global $wpdb;
	if(!(isset($orderID) && $orderID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pr_items WHERE order_id=$orderID", ARRAY_A);
	if($results==null){
		return [];
	}
	return $results;
}

function pr_getItemByItemID($itemID){
	global $wpdb;
	if(!(isset($itemID) && $itemID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pr_items WHERE item_id=$itemID", ARRAY_A);
	if($results==null){
		return null;
	}
	return $results;
}

function pr_saveOrderByID($order){
	global $wpdb;
	$order_id = 0;
	if(!(isset($order) && $order['order_id']!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	if($order['order_id']==0){
		unset($order['order_id']);
		$result=$wpdb->insert("{$wpdb->prefix}pr_orders", $order);
		$order_id = $wpdb->insert_id;
		if($result===false){
			error_log("DB Error: ".__file__.__line__);
		}
	}else{
		$order_id=$order['order_id'];
		unset($order['order_id']);
		$result=$wpdb->update("{$wpdb->prefix}pr_orders", $order, ['order_id'=>$order_id]);
		if($result===false){
			error_log("DB Error: ".__file__.' '.__line__);
		}
	}
	return $order_id;
}
function pr_removeOrderByID($orderID){
	global $wpdb;
	if(!(isset($orderID) && $orderID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$result=$wpdb->delete("{$wpdb->prefix}pr_items", ['order_id'=>$orderID]);
	if($result===false){
		error_log("DB Error: ".__file__.__line__);
	}
	$result=$wpdb->delete("{$wpdb->prefix}pr_orders", ['order_id'=>$orderID]);
	if($result===false){
		error_log("DB Error: ".__file__.__line__);
	}
}
function pr_saveItemByID($item){
	global $wpdb;
	$item_id=0;
	if(!(isset($item) && $item['item_id']!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	if($item['item_id']==0){
		unset($item['item_id']);
		$result=$wpdb->insert("{$wpdb->prefix}pr_items", $item);
		$item_id = $wpdb->insert_id;
		if($result===false){
			error_log("DB Error: ".__file__.__line__);
		}
	}else{
		$item_id=$item['item_id'];
		unset($item['item_id']);
		$result=$wpdb->update("{$wpdb->prefix}pr_items", $item, ['item_id'=>$item_id]);
		if($result===false){
			error_log("DB Error: ".__file__.' '.__line__);
		}
	}
	return $item_id;
}
function pr_removeItemByID($itemID){
	global $wpdb;
	if(!(isset($itemID) && $itemID!="")){
		hit_log("Unexpected empty db call: ".__file__.__line__);
		return null;
	}
	$result=$wpdb->delete("{$wpdb->prefix}pr_items", ['item_id'=>$itemID]);
	if($result===false){
		error_log("DB Error: ".__file__.' '.__line__);
	}
}

function pr_getCostByOrderID($orderID){
	global $wpdb;
	$result = $wpdb->get_results("SELECT 0 AS order_id, SUM(shipping) AS shipping, SUM(tax) AS tax, SUM(tools) AS tools, SUM(parts) AS parts FROM wp_pr_getCost UNION ALL SELECT * FROM wp_pr_getCost", ARRAY_A);
	if($result===false){
		error_log("DB Error: ".__file__.' '.__line__);
	}
	hit_log(print_r($result, true));
	return $result[$orderID];
}
