<?php

// HTML for submenu
function purchase_records_settings_container_html(){
	if(!current_user_can( 'manage_options' ) ) {
		return;
	}?>
	<div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'purchase_records_options' );
            do_settings_sections( 'purchase_records_section' );
            // output save settings button
            submit_button( __( 'Save Settings', 'textdomain' ) );
            ?>
        </form>
    </div>
	<?php
}
function purchase_records_options_page(){
	add_options_page(
		'Purchase Records Options',
		'Purchase Records Options',
		'manage_options',
		'purchase_records',
		'purchase_records_settings_container_html'
	);
}
add_action('admin_menu', 'purchase_records_options_page');
