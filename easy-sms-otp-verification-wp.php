<?php
/*
* Plugin Name: SMS OTP Verification
* Plugin URI:  https://www.tricksladder.com/
* Description: The best SMS OTP Verification plugin for Wordpress
* Version:     1.0
* Author:      Harish Kumar
* Author URI:  https://www.tricksladder.com/
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: easy-sms-otp-verification-wp
*/

class Easy_sms_otp_main_wp {

	public static function init() {
        $class = __CLASS__;
        new $class;
    }

	function __construct() {
		$this->wpesov_inculdes();
		//add_filter( 'registration_redirect', array( $this, 'wpesov_registration_redirect' ) );
		register_activation_hook( __FILE__, array( $this, 'wpesov_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'wpesov_uninstall' ) );
        add_action('wp_enqueue_scripts', array( $this, 'wpesov_reg_scripts') );
		//add_filter( 'page_template', array( $this, 'wpesov3396_page_template' ) );

	}


	function wpesov_reg_scripts() {
		require_once( plugin_dir_path(__FILE__) . 'public/class-wpesov-public.php' );
   		Wpesov_Public::enqueue_styles();
	}


	/*
     * Actions perform on activation of plugin
     */
	public function wpesov_install() {
		require_once( plugin_dir_path(__FILE__) . 'includes/class-wpesov-activator.php' );
		Wpesov_Activator::activate();
    }

    /*
     * Actions perform on de-activation of plugin
     */
    public function wpesov_uninstall() {
    	require_once( plugin_dir_path(__FILE__) . 'includes/class-wpesov-deactivator.php' );
    	Wpesov_Deactivator::deactivate();
    }

    /*public function wpesov_otp_template() {
    	require_once( plugin_dir_path(__FILE__) . 'esov-otp-page-template.php' );

    }*/


	function wpesov_inculdes(){
		require_once( plugin_dir_path(__FILE__) . 'admin/esov-admin-dash.php' );
		//Supported registrtaion forms
		require_once( plugin_dir_path(__FILE__) . 'esov-supported-registration-forms.php' );

		//require_once( plugin_dir_path(__FILE__) . 'admin/wpesov-admin-enqueue.php' );

		
		
	}
	
}	

add_action( 'plugins_loaded', array( 'Easy_sms_otp_main_wp', 'init' ) );

//OTP template
require_once( plugin_dir_path(__FILE__) . 'esov-otp-page-template.php' );
?>