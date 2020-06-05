<?php

function purchase_records_order_shortcode($atts){
	$atts = shortcode_atts(
		array(
			'postid' => get_the_ID(),
		), $atts, 'pr-order');

	$html = "<div><h3 class='purchase_records_shortcode_order'>Order</h3>";
	$html .= "PostID:".$atts['postid']."</div>";
	return $html;
}
function purchase_records_item_shortcode($atts=[]){

}


add_shortcode('pr-order', 'purchase_records_order_shortcode');
add_shortcode('pr-item', 'purchase_records_item_shortcode');
