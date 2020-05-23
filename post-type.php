<?php
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

// Create meta-data for post
function purchase_records_metabox(){
	add_meta_box(
		'purchase_records_metabox_supplier',
		'Purchase Record Supplier',
		'purchase_records_metabox_supplier_html',
		'pr_purchase_record'
	);
	add_meta_box(
		'purchase_records_metabox_items',
		'Purchaes Record Items',
		'purchase_records_metabox_items_html',
		'pr_purchase_record'
	);
}
add_action('add_meta_boxes', 'purchase_records_metabox');
