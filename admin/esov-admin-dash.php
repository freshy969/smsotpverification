<?php
		add_action( 'admin_menu', 'wpesov_customer_validation_menu' );
		

	function wpesov_customer_validation_menu() {
		$menu_slug = 'wpesov_settings';
		/*Main Admin Menu*/
		add_menu_page (	'Easy SMS OTP Verification' , 'Easy SMS OTP Verification' , 'activate_plugins', $menu_slug , 'wpesov_main_settings_page', 'dashicons-email-alt' );
		/*Main Settings page*/
		add_submenu_page( $menu_slug	,'Main Settings','Settings', 'administrator', $menu_slug , 'wpesov_main_settings_page');
		/*Account Settings Page*/
		add_submenu_page( $menu_slug	,'Account'	,'Account', 'administrator', 'wpesov_account' , 'wpesov_account_settings_page');

		add_action( 'admin_init','wpesov_custom_settings' );
		
	}


	function wpesov_custom_settings() {
	//Registering settings
	register_setting( 'wpesov_plugin_settings_group', 'wpesov_api_key' );
	register_setting( 'wpesov_plugin_settings_group', 'wpesov_api_senderid_ind' );
	register_setting( 'wpesov_plugin_settings_group', 'wpesov_api_senderid_int' );
	register_setting( 'wpesov_plugin_settings_group', 'wpesov_api_sender_msg' );
	register_setting( 'wpesov_plugin_settings_group', 'wpesov_otp_page' );

	//API Section
	add_settings_section( 'wpesov_sidebar_options', 'SMS API Settings', false, 'wpesov_settings' );
	add_settings_section( 'wpesov_sidebar_account', 'User Profile', false, 'wpesov_account' );
	
	//API Fields
	add_settings_field( 'sidebar_wpesov_api_key', 'API Key', 'wpesov_settings_api_key', 'wpesov_settings', 'wpesov_sidebar_options');
	add_settings_field( 'sidebar_wpesov_api_senderid_ind', 'Sender ID (India)', 'wpesov_settings_api_senderid_ind', 'wpesov_settings', 'wpesov_sidebar_options');
	add_settings_field( 'sidebar_wpesov_api_senderid_int', 'Sender ID (International)', 'wpesov_settings_api_senderid_int', 'wpesov_settings', 'wpesov_sidebar_options');
	add_settings_field( 'sidebar_wpesov_api_msg', 'Message', 'wpesov_settings_api_msg', 'wpesov_settings', 'wpesov_sidebar_options');
	add_settings_field( 'sidebar_wpesov_otp_page', 'OTP Page', 'wpesov_settings_otp_page', 'wpesov_settings', 'wpesov_sidebar_options');
	}

	function wpesov_settings_api_key($args){
		$api_key = esc_attr( get_option('wpesov_api_key') );
		echo '<input type="text" name="wpesov_api_key" value="'.$api_key.'" placeholder="API Key"/>';

	}

	function wpesov_settings_api_senderid_ind($args){
		$sender_id_ind = esc_attr( get_option('wpesov_api_senderid_ind') );
		echo '<input type="text" name="wpesov_api_senderid_ind" value="'.$sender_id_ind.'" placeholder="Sender ID (India)"/>';
	}

	function wpesov_settings_api_senderid_int($args){
		$sender_id_int = esc_attr( get_option('wpesov_api_senderid_int') );
		echo '<input type="text" name="wpesov_api_senderid_int" value="'.$sender_id_int.'" placeholder="Sender ID (International)"/>';
	}

	function wpesov_settings_api_msg($args){
		$api_msg = esc_attr( get_option('wpesov_api_sender_msg') );
		echo '<input type="text" name="wpesov_api_sender_msg" value="'.$api_msg.'" placeholder="Sender Messsage"/><p class="description">*Use <b>wpesov_otp</b> in place of your dynamic string</p>';
	}

	function wpesov_settings_otp_page($args){
		$otp_page = esc_attr( get_option('wpesov_otp_page') );
		echo '<input type="text" name="wpesov_otp_page" value="'.$otp_page.'" placeholder="OTP Page Slug"/>';
	}

	function wpesov_main_settings_page() {
		settings_errors(); ?>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'wpesov_plugin_settings_group' );
    			do_settings_sections( 'wpesov_settings' );
				submit_button();
			?>
		</form>
	<?php
	}

	/*Account Section*/
	function wpesov_account_settings_page() {
		settings_errors(); ?>
		<form method="post" action="options.php">
			<?php
				settings_fields( 'wpesov_plugin_settings_group' );
    			do_settings_sections( 'wpesov_account' );
				//submit_button();
			?>
		</form>
	<?php
	}

	?>