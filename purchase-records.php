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
define ('PR_PLUGIN_URL' , plugin_dir_url(__FILE__));
define ('PR_PLUGIN_LOCATION' , __FILE__);


// Include other files
require_once(PR_PLUGIN_DIR . 'php/includes.php');
require_once(PR_PLUGIN_DIR . 'php/activation.php');
require_once(PR_PLUGIN_DIR . 'php/settings-menu.php');
require_once(PR_PLUGIN_DIR . 'php/post-type.php');
require_once(PR_PLUGIN_DIR . 'php/shortcodes.php');

// Add styles and javascripts
function purchase_records_load_admin_csjs(){
	wp_enqueue_style('style', PR_PLUGIN_URL . 'css/admin.css');
	wp_enqueue_script('edit-post', PR_PLUGIN_URL . 'js/edit-post.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'purchase_records_load_admin_csjs');


function purchase_records_load_csjs(){
	wp_enqueue_style('style', PR_PLUGIN_URL . 'css/shortcode.css');
}
add_action('wp_enqueue_scripts', 'purchase_records_load_csjs');
