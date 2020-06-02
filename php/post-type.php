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
		<label for='$inputID' class='purchase_records_metabox_label'>$label</label>
		<input id='$inputID' name='$inputID' type='$type' value='$value'";
	foreach($options as $key=> $setting){
		$output.= " $key='$setting'";
	}
	$output.=" class='purchase_records_metabox_input'>
		</div>
		";
	return $output;
}

function purchase_records_metabox_item_shortcut($inputID, $type, $value, $options=[]){
	if($type=='checkbox' && $value==true){
		$options['checked']='true';
	}
	$output="<input id='$inputID' name='$inputID' type='$type' value='$value' class='purchase_records_metabox_item_table_items' ";
	foreach($options as $key=> $setting){
		$output.= " $key='$setting'";
	}
	$output.=">";
	return $output;
}

// Order metabox html
function purchase_records_metabox_order_html($post){
	wp_nonce_field(plugin_basename(__FILE__), 'purchase_records_o_nonce_field');
	$order=pr_getOrderByPostID($post->ID);
	$GLOBALS['pr_order_id']=$order['order_id'];
	?>
	<label for='purchase_records_order_field'>Supplier of goods</label>
	<fieldset id='purchase_records_order_field' name='purchase_records_order_field'>
	<label for='pr_o_id'>order_id: </label>
	<input id='pr_o_id' name='pr_o_id' readonly='true' type='number' value='<?php echo $order['order_id'];?>'>
	<?php

	echo purchase_records_metabox_createBox('Supplier: ', 'pr_o_supplier', 'text', $order['supplier']);
	echo purchase_records_metabox_createBox('Tax: ', 'pr_o_tax', 'number', $order['tax'], ['step'=>.01]);
	echo purchase_records_metabox_createBox('Shipping: ', 'pr_o_shipping', 'number', $order['shipping_cost'], ['step'=>.01]);
	echo purchase_records_metabox_createBox('Date Ordered: ', 'pr_o_ordered', 'date', $order['date_ordered']);
	echo purchase_records_metabox_createBox('Date Received: ', 'pr_o_received', 'date', $order['date_received']);
	
	?>
	</fieldset>
	<?php
}
// Item metabox html
function purchase_records_metabox_items_line($item){
?>
	<td class='pr_c1'><button id='pr_i_rb[]' name='pr_i_rb[]' type='button' class='purchase_records_metabox_item_line' onclick='purchase_records_remove_meta_row(jQuery(this))'>-</button></td>
<?php
	echo "<td class='pr_c2'>".purchase_records_metabox_item_shortcut('pr_i_id[]', 'number', $item['item_id'], ['readonly'=>'true']).'</td>';
	echo "<td class='pr_c3'>".purchase_records_metabox_item_shortcut('pr_i_nm[]', 'textbox', $item['item']).'</td>';
	echo "<td class='pr_c4'>".purchase_records_metabox_item_shortcut('pr_i_is[]', 'checkbox', $item['istool'], ['style'=>'position:relative;left:25%;']).'</td>';
	echo "<td class='pr_c5'>".purchase_records_metabox_item_shortcut('pr_i_cs[]', 'number', $item['cost'], ['step'=>.01]).'</td>';
	echo "<td class='pr_c6'>".purchase_records_metabox_item_shortcut('pr_i_qt[]', 'number', $item['quantity'], ['step'=>1, 'min'=>0]).'</td>';
	echo "<td class='pr_c7'>".purchase_records_metabox_item_shortcut('pr_i_wl[]', 'textbox', $item['weblink']).'</td>';
}

function purchase_records_metabox_items_html($post){
	wp_nonce_field(plugin_basename(__FILE__), 'purchase_records_i_nonce_field');
	?>
    <label for='purchase_records_items_field'>Items ordered</label>
	<fieldset id='purchase_records_items_field' name='purchase_records_items_field'>
	<?php
	$itemlist=pr_getItemsByOrderID($GLOBALS['pr_order_id']);
	$x=0;
	echo "<table id='purchase_records_metabox_items_table' width='100%'>";
	echo "<col style='width:40px'><col style='width:50px'><col style='width:30%'>";
	echo "<col style='width:40px'><col style='width:10%'><col style='width:100px'><col style='width:40%'>";
	echo "<thead><tr><th class='pr_c1'></th><th class='pr_c2'>ID</th><th class='pr_c3'>Item</th><th class='pr_c4'>Tool?</th><th class='pr_c5'>Cost</th><th class='pr_c6'>Quantity</th><th class='pr_c7'>Weblink</th></tr></thead><tbody>";
	foreach($itemlist as $item){
		$item['line']=++$x;
		echo '<tr>';
    	purchase_records_metabox_items_line($item);
		echo '</tr>';
	}
	echo '</tbody></table>';
	?>
	</fieldset>
	<br>
	<button id='pr_i_ab' name='pr_i_ab' type='button' onclick='purchase_records_add_meta_row()'>+</button>
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

function purchase_records_metabox_save($postID, $post, $update){

	if(!isset($_POST['purchase_records_o_nonce_field'])||!isset($_POST['purchase_records_i_nonce_field'])){
		hit_log('nonce not correct');
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		hit_log('autosave, ignoring');
		return $post_id;
	}
	hit_log('saving...\n');

	// Save Order info
	pr_saveOrderByID(['order_id'=>$_POST['pr_o_id'], 'post_id'=>$_POST['ID'], 'date_ordered'=>$_POST['pr_o_ordered'], 'date_received'=>$_POST['pr_o_received'], 'supplier'=>$_POST['pr_o_supplier'], 'shipping_cost'=>$_POST['pr_o_shipping'], 'tax'=>$_POST['pr_o_tax']]);
	hit_log(print_r($_POST, true));

	// Save Item info
	$itemCount=0;//sizeof($_POST['pr_i_id[]']);
	for($x=0; $x<$itemCount; $x++){
		hit_log('PostedID:'.$_POST['pr_i_id[]'][$x].'\n');
	}
}
add_action('save_post', 'purchase_records_metabox_save', 10, 3);
