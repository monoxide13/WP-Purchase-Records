<?php
/**
 * Plugin Name: Purchase Records
 * Plugin URI: http://www.monoxide13.com/purchase-records
 * Description: Plugin used to save metadata to database to be displayed later.
 * Version: 0.1
 * Author: Monoxide13
 * Author URI: http://www.monoxide13.com
 */

// DEFINES
define ('PR_VERSION', '0.1');
define ('PR_PLUGIN_DIR' , plugin_dir_path(__FILE__));
define ('PR_PLUGIN_LOCATION' , __FILE__);


// Include other files
require_once(PR_PLUGIN_DIR . 'php/includes.php');
require_once(PR_PLUGIN_DIR . 'activation.php');
require_once(PR_PLUGIN_DIR . 'post-type.php');

// Add options
add_option('purchase_records_post_typs', 'post');


// Add shortcodes
function purchase_records_shortcode_purchase_order($atts){
	// Attributes
	$atts = shortcode_atts(
		array(
			'order' => '',
			'category' => '',
		),
		$atts,
		'pr_totalsum'
	);
	// return the price value;
	return '20';
}
add_shortcode('pr_totalsum', 'purchase_records_shortcode_purchase_order');

function purchase_records_post_meta_box(){
	$screens=['post'];
	foreach ($screens as $screen){
		add_meta_box(
			'wporg_box_id',
			'Purchase Record',
			'purchase_records_post_html',
			$screen
		);
	}
}
add_action('add_meta_boxes', 'purchase_records_post_meta_box');

function purchase_records_post_html_field($array){
	$html = "<fieldset>
		<legend>#".$array['id'].":</legend>
		<label for=&quot;partno&quot;>Part No:</label>
		<input type=&quot;text&quot; id=&quot;partno&quot; name=&quot;partno&quot;/>
		</fieldset>";
	return $html;
}

function purchase_records_post_html($post){
	wp_nonce_field( plugin_basename(__FILE__), 'purchase_records_nonce_field');
	$html = '<label id=&quot;Purchase-Records&quot; for=&quot;Purchase-Records&quot;>Purchase Records</label>';
	$html .= '<form id=&quot;purchase_records_form&quot;>';
	$tempField = [ 'id' => 1, 'partno' => '24-sj-224' ];
	$html .= purchase_records_post_html_field($tempField);
	$html .= '</form>';
	echo $html;
}

