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
require_once(PR_PLUGIN_DIR . 'php/activation.php');
require_once(PR_PLUGIN_DIR . 'php/settings-menu.php');
require_once(PR_PLUGIN_DIR . 'php/post-type.php');


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
