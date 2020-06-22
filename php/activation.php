<?php
//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once 'includes.php';

function purchase_records_activation(){
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
		PRIMARY KEY (order_id),
 		UNIQUE KEY order_id_UNIQUE (order_id)
		) $charset_collate ENGINE=InnoDB;";
	hit_log($sql);
	$wpdb->query($sql);
	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pr_items (
		item_id int(10) unsigned NOT NULL AUTO_INCREMENT,
		order_id int(10) unsigned NOT NULL,
		istool bit(1) DEFAULT b'0',
		item varchar(64) NOT NULL DEFAULT '',
		cost double(10,3) NOT NULL DEFAULT 0.000,
		quantity int(10) unsigned NOT NULL DEFAULT 1,
		weblink varchar(128) DEFAULT '',
		PRIMARY KEY (item_id),
		UNIQUE KEY item_id_UNIQUE (item_id),
		KEY fk_order_id (order_id),
		CONSTRAINT order_id FOREIGN KEY (order_id) REFERENCES {$wpdb->prefix}pr_orders (order_id)
	) $charset_collate ENGINE=InnoDB;
	";
	hit_log($sql);
	$wpdb->query($sql);
	$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}pr_getCost;";
	$sql = "CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW {$wpdb->prefix}pr_getCost3 AS
	SELECT o.order_id AS order_id, o.shipping_cost as shipping, o.tax as tax, COALESCE(ity.items, 0) as tools, COALESCE(itn.items, 0) AS parts
	FROM (({$wpdb->prefix}pr_orders o
	LEFT JOIN (
	SELECT {$wpdb->prefix}pr_items.order_id as order_id, SUM(ROUND({$wpdb->prefix}pr_items.cost * {$wpdb->prefix}pr_items.quantity, 2)) AS items
	FROM {$wpdb->prefix}pr_items WHERE {$wpdb->prefix}pr_items.istool = 1
	GROUP BY {$wpdb->prefix}pr_items.order_id) ity ON(o.order_id = ity.order_id))
	LEFT JOIN (
	SELECT {$wpdb->prefix}pr_items.order_id as order_id, SUM(ROUND({$wpdb->prefix}pr_items.cost * {$wpdb->prefix}pr_items.quantity, 2)) AS items
	FROM {$wpdb->prefix}pr_items WHERE {$wpdb->prefix}pr_items.istool = 0
	GROUP BY {$wpdb->prefix}pr_items.order_id) itn ON(o.order_id = itn.order_id));";
	hit_log($sql);
	$wpdb->query($sql);
	flush_rewrite_rules(false);
}
register_activation_hook(PR_PLUGIN_LOCATION, 'purchase_records_activation' );

function purchase_records_deactivation(){
	hit_log('Deleting DB');
	global $wpdb;

	//$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pr_orders;");
	//$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pr_items;");
}
register_deactivation_hook(PR_PLUGIN_LOCATION, 'purchase_records_deactivation');

