<?php

function purchase_records_activation(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	hit_log('Creating DB');
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$db_version = '0.1';
	$wpdb->show_errors();

	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pr_orders (
		order_id int(10) unsigned NOT NULL AUTO_INCREMENT,
		post_id int(10) unsigned NOT NULL,
		date_ordered date DEFAULT NULL,
		date_received date DEFAULT NULL,
		supplier varchar(64) NOT NULL,
		shipping_cost double unsigned DEFAULT NULL,
		tax double DEFAULT NULL,
		PRIMARY KEY  (order_id),
 		UNIQUE KEY order_id_UNIQUE (order_id)
		) $charset_collate ENGINE=InnoDB;";
	$wpdb->query($sql);
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pr_items (
		item_id int(10) unsigned NOT NULL AUTO_INCREMENT,
		order_id int(10) unsigned NOT NULL,
		build_category_id int(10) unsigned NOT NULL,
		istool bit(1) DEFAULT b'0',
		item varchar(64) NOT NULL,
		cost double(10,2) NOT NULL DEFAULT 0.00,
		quantity int(10) unsigned NOT NULL DEFAULT 1,
		weblink varchar(128) DEFAULT '',
		PRIMARY KEY  (purchase_id),
		UNIQUE KEY purchase_id_UNIQUE (purchase_id),
		KEY fk_order_id_idx (order_id),
		KEY fk_build_category_idx (build_category_id),
		CONSTRAINT fk_order_id FOREIGN KEY (order_id) REFERENCES {$wpdb->prefix}pr_orders (order_id)
	) $charset_collate ENGINE=InnoDB;
	";
	$wpdb->query($sql);
	//add_option( 'purchase_orders_db_version', $db_version);
	flush_rewrite_rules(false);
}
register_activation_hook(PR_PLUGIN_LOCATION, 'purchase_records_activation' );

function purchase_records_deactivation(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	hit_log('Deleting DB');
	global $wpdb;

	//$wpdb->query( "DROP TABLE IF EXISTS pr_orders;");
	//$wpdb->query( "DROP TABLE IF EXISTS pr_items;");
}
register_deactivation_hook(PR_PLUGIN_LOCATION, 'purchase_records_deactivation');

