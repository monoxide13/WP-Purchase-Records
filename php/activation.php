<?php

function purchase_records_create_db(){
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
		istool bit(1) DEFAULT NULL,
		item varchar(64) NOT NULL,
		cost double unsigned NOT NULL,
		quantity int(10) unsigned NOT NULL,
		weblink varchar(128) DEFAULT NULL,
		PRIMARY KEY  (purchase_id),
		UNIQUE KEY purchase_id_UNIQUE (purchase_id),
		KEY fk_order_id_idx (order_id),
		KEY fk_build_category_idx (build_category_id),
		CONSTRAINT fk_order_id FOREIGN KEY (order_id) REFERENCES pr_orders (order_id)
	) $charset_collate ENGINE=InnoDB;
	";
	$wpdb->query($sql);
	//add_option( 'purchase_orders_db_version', $db_version);
}
register_activation_hook(PR_PLUGIN_LOCATION, 'purchase_records_create_db' );

function purchase_records_delete_db(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	hit_log('Deleting DB');
	global $wpdb;
	
	hit_log(ABSPATH . 'wp-admin/includes/upgrade.php');

	$wpdb->query( "DROP TABLE IF EXISTS pr_orders;");
	$wpdb->query( "DROP TABLE IF EXISTS pr_items;");
}
register_deactivation_hook(PR_PLUGIN_LOCATION, 'purchase_records_delete_db');

