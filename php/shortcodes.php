<?php
require_once('db_functions.php');

function purchase_records_order_shortcode($atts){
	$atts = shortcode_atts(
		array(
			'postid' => get_the_ID(),
			'orderid' => 0,
		), $atts, 'pr-order');
	if($atts['orderid']==0)
		$order = pr_getOrderByPostID($atts['postid']);
	else
		$order = pr_getOrderByOrderID($atts['orderid']);
	$html = "<div class='purchase_records_so'><h3>Order</h3>";
	$html .= "<div class='purchase_records_so_container'>";
#	$html .= "<div class='purchase_records_so_text'>Order ID:</div><input type='number' readonly='true' value='${order['order_id']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:170px'><div class='purchase_records_so_text'>Date Ordered:</div><input type='date' readonly='true' value='${order['date_ordered']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:170px'><div class='purchase_records_so_text'>Date Received:</div><input type='date' readonly='true' value='${order['date_received']}'></div>";
	$html .= "<div class='purchase_records_so_item'><div class='purchase_records_so_text'>Supplier:</div><input type='text' readonly='true' value='".stripslashes($order['supplier'])."'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:120px'><div class='purchase_records_so_text'>Shipping Cost:</div><input type='number' readonly='true' value='${order['shipping_cost']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:100px'><div class='purchase_records_so_text'>Tax:</div><input type='number' readonly='true' value='${order['tax']}'></div>";
	$html .= "</div>";

	return $html;
}
add_shortcode('pr-order', 'purchase_records_order_shortcode');

function purchase_records_item_shortcode($atts=[]){
	$atts = shortcode_atts(
		array(
			'postid' => get_the_ID(),
			'orderid' => 0,
			'items' => null,
		), $atts, 'pr-order');
	$orderID = $atts['orderid'];
	if($atts['orderid']==0)
		$orderID = pr_getOrderByPostID($atts['postid'])['order_id'];
	if($atts['items']==null){
		// Get all items
		$itemList = pr_getItemsByOrderID($orderID);
	}else{
		// Get only items in list
		foreach(explode(',', $atts['items']) as $itemID){
			$itemList[] = pr_getItemByItemID($itemID);
		}
	}
	$html = "<div class='purchase_records_si'><h3>Items</h3>";
	$html .= "<table class='purchase_records_si_container'><thead><tr>";
	$html .= "<th>Item</th><th>Tool?</th><th>Cost</th><th>Qty</th>";
	$html .= "</tr></thead><tbody>";
	foreach($itemList as $item){
		$html .= "<tr><td>";
		if($item['weblink']==null OR $item['weblink']==''){
			$html .= stripslashes($item['item']);
		}else{
			$html .= "<a href='".stripslashes($item['weblink'])."'>".stripslashes($item['item'])."</a>";
		}
		$html .= "</td><td>".$item['istool']."</td><td>".$item['cost']."</td><td>".$item['quantity']."</td></tr>";
	}
	$html .= "</tbody></table></div>";
	return $html;
}
add_shortcode('pr-item', 'purchase_records_item_shortcode');

function purchase_records_cost_shortcode($atts=[]){
	global $wpdb;
	$join = false;
	$atts = shortcode_atts(
		array(
			'orderid' => 0,
			'itemid' => 0,
			'type' => 'total',
		), $atts, 'pr-cost');
	if($atts['itemid']!=0){
		$result = pr_getItemByItemID($atts['itemid']);
		if($result==null) return '';
		switch('type'){
			case 'item':
				return number_format($result['cost'], 2, '.',',');
				break;
			default:
				return number_format(($result['cost']*$result['quantity']), 2, '.',',');
				break;
		}
	}else{
		$result = pr_getCostByOrderID($atts['orderid']);
		switch($atts['type']){
			case 'tax':
				return $result['tax'];
				break;
			case 'shipping':
				return $result['shipping'];
				break;
			case 'tools':
				return $result['tools'];
				break;
			case 'parts':
				return $result['parts'];
				break;
			default:
				return $result['shipping']+$result['tax']+$result['tools']+$result['parts'];
				break;
		}
	}
}
add_shortcode('pr-cost', 'purchase_records_cost_shortcode');
