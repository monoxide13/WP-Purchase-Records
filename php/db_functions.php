<?php

require_once('includes.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function pr_getOrderByPostID($postID){
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pr_orders WHERE post_id=$postID", ARRAY_A);
	if($results==null){
		// Possibly fill values with nothing?
		return ['order_id'=>'0', 'post_id'=>$postID, 'date_ordered'=>'', 'date_received'=>'', 'supplier'=>'', 'shipping_cost'=>0, 'tax'=>0];
	}
	return $results[0];
}

function pr_getItemsByOrderID($orderID){
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pr_items WHERE order_id=$orderID", ARRAY_A);
	if($results==null){
		return [['item_id'=>'0', 'order_id'=>$orderID, 'build_category'=>0, 'istool'=>false, 'item'=>'', 'cost'=>0, 'quantity'=>0, 'weblink'=>0]];
	}
	return $results;
}

function pr_saveOrderByID($order){
	global $wpdb;
	if($order['order_id']==0){
		unset($order['order_id']);
		$result=$wpdb->insert("{$wpdb->prefix}pr_orders", $order);
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
}
function pr_removeOrderByID($orderID){
	global $wpdb;
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
	if($item['item_id']==0){
		unset($item['item_id']);
		$result=$wpdb->insert("{$wpdb->prefix}pr_items", $item);
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
}
function pr_removeItemByID($itemID){
	global $wpdb;
	$result=$wpdb->delete("{$wpdb->prefix}pr_items", ['item_id'=>$itemID]);
	if($result===false){
		error_log("DB Error: ".__file__.' '.__line__);
	}
}
