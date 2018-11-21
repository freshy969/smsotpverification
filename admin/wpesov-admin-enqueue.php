<?php 



function wpesov_custom_wp_admin_styles($hook) {

	if ( 'toplevel_page_wpesov_settings' != $hook ) {
		return;
	}
    //echo $hook;

	wp_register_style( 'custom_wpesov_admin_css',plugins_url('css/wpesov-admin.css', __FILE__)  );
	wp_enqueue_style( 'custom_wpesov_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'wpesov_custom_wp_admin_styles' );



 ?>