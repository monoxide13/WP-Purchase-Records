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
	$html .= "<div class='purchase_records_so_item' style='width:80px'><div class='purchae_records_so_text'>Order ID:</div><input type='number' readonly='true' value='${order['order_id']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:170px'><div class='purchae_records_so_text'>Date Ordered:</div><input type='date' readonly='true' value='${order['date_ordered']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:170px'><div class='purchae_records_so_text'>Date Received:</div><input type='date' readonly='true' value='${order['date_received']}'></div>";
	$html .= "<div class='purchase_records_so_item'><div class='purchae_records_so_text'>Supplier:</div><input type='text' readonly='true' value='${order['supplier']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:120px'><div class='purchae_records_so_text'>Shipping Cost:</div><input type='number' readonly='true' value='${order['shipping_cost']}'></div>";
	$html .= "<div class='purchase_records_so_item' style='width:100px'><div class='purchae_records_so_text'>Tax:</div><input type='number' readonly='true' value='${order['tax']}'></div>";
	$html .= "</div></div>";

	return $html;
}
function purchase_records_item_shortcode($atts=[]){

}


add_shortcode('pr-order', 'purchase_records_order_shortcode');
add_shortcode('pr-item', 'purchase_records_item_shortcode');
