<?php

require_once('includes.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function getOrderByPostID($postID){
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pr_orders WHERE post_id=$postID", ARRAY_A);
	if($results==null){
		// Possibly fill values with nothing?
		return ['order_id'=>'0', 'post_id'=>$postID, 'date_ordered'=>'', 'date_received'=>'', 'supplier'=>'', 'shipping_cost'=>0, 'tax'=>0];
	}
	return $results[0];
}

function getItemsByOrderID($orderID){
	global $wpdb;
	$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pr_items WHERE order_id=$orderID", ARRAY_A);
	if($results==null){
		return [['item_id'=>'0', 'order_id'=>$orderID, 'build_category'=>0, 'istool'=>false, 'item'=>'', 'cost'=>0, 'quantity'=>0, 'weblink'=>0]];
	}
	return $results;
}

