<?php

function purchase_records_order_shortcode($orderID){
	//$order=(isset($orderID))?(pr_getOrderByOrderID($orderID)):(pr_getOrderByPostID(get_the_ID()));
	//$html = "<div><h3 class='purchase_records_shortcode_order'>Order</h3>";
}
function purchase_records_item_shortcode($orderID, $items){

}


add_shortcode('pr-order', 'purchase_records_order_shortcode');
add_shortcode('pr-item', 'purchase_records_item_shortcode');
