<?php
require_once('includes.php');
require_once(PR_PLUGIN_DIR . 'php/db_functions.php');

// Create custom post
function purchase_records_post(){
	register_post_type('pr_purchase_record',
		array(
			'labels'=>array(
				'name' => __('Purchase Records', 'textdomain'),
				'singular_name' => __('Purchase Record', 'textdomain'),
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array( 'slug' => 'purchases' ),
		)
	);
};
add_action ('init', 'purchase_records_post');

// Create html for meta-data display in meta-boxs
function purchase_records_metabox_createBox($label, $inputID, $type, $value, $options=[]){
	$output="
		<div class='pr_o_metabox'>
		<label for='$inputID'>$label</label>
		<input id='$inputID' name='$inputID' type='$type' value='$value'";
	foreach($options as $key=> $setting){
		$output.= " $key='$setting'";
	}
	$output.=">
		</div>
		";
	echo $output;
}
// Order metabox html
function purchase_records_metabox_order_html($post){
	wp_nonce_field(plugin_basename(__FILE__), 'purchase_records_s_nonce_field');
	$order=getOrderByPostID($post->ID);
	?>
	<label for='purchase_records_order_field'>Supplier of goods</label>
	<fieldset id='purchase_records_order_field'>
	<input id='pr_o_id' type='number' value='<?php echo "$post->ID";?>'>
	<?php

	purchase_records_metabox_createBox('Supplier: ', 'pr_o_supplier', 'text', $order['supplier']);
	purchase_records_metabox_createBox('Tax: ', 'pr_o_tax', 'number', $order['tax'], ['step'=>.01]);
	purchase_records_metabox_createBox('Shipping: ', 'pr_o_shipping', 'number', $order['shipping_cost'], ['step'=>.01]);
	purchase_records_metabox_createBox('Date Ordered: ', 'pr_o_ordered', 'date', $order['date_ordered']);
	purchase_records_metabox_createBox('Date Received: ', 'pr_o_received', 'date', $order['date_received']);
	
	?>
	</fieldset>
	<?php
}
// Item metabox html
function purchase_records_metabox_items_line($item){
	
}

function purchase_records_metabox_items_html($post){
	wp_nonce_field(plugin_basename(__FILE__), 'purchase_records_i_nonce_field');
	?>
    <label for='purchase_records_items_field'>Items ordered</label>
	<fieldset id='prchase_records_items_field'>
	<?php
	$tempField=null;
    purchase_records_metabox_items_line($tempField);
	?>
	</fieldset>
	<textarea><?php print_r($_POST)?></textarea>
	<?php
}

// Create meta-boxs for post
function purchase_records_metabox(){
	add_meta_box(
		'purchase_records_metabox_order',
		'Order Information',
		'purchase_records_metabox_order_html',
		'pr_purchase_record'
	);
	add_meta_box(
		'purchase_records_metabox_items',
		'Items',
		'purchase_records_metabox_items_html',
		'pr_purchase_record'
	);
}
add_action('add_meta_boxes', 'purchase_records_metabox');

function purchase_records_metabox_save(){
	hit_log('post saved: '.print_r($_POST));
}
add_action('save_post', 'purchase_records_metabox_save');
