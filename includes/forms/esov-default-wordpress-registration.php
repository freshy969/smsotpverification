<?php

//Exit if accessed directly
if( !defined( 'ABSPATH' ) ){
	exit;
}

add_action(	'init', 'wpesov_custom_default_registration', 1 );

function wpesov_custom_default_registration()
{
		/*Adds Phone Number Field to Registrtaion form*/
		add_action( 'register_form', 'wpesov_custom_register_form' );

		add_action( 'user_register', 'wpesov_user_register' );
		add_action( 'show_user_profile', 'wpesov_show_extra_profile_fields' );
		add_action( 'edit_user_profile', 'wpesov_show_extra_profile_fields' );
		add_action( 'personal_options_update', 'wpesov_extra_profile_fields' );
		add_action( 'edit_user_profile_update', 'wpesov_extra_profile_fields' );
		/*Remove user role after registration*/
		add_action( 'user_register', 'wpesov_registration_remove_user_role', 10, 1 );
		/*Registrtaion errors*/
		add_filter( 'registration_errors', 'wpesov_registration_errors', 10, 3 );
		/*Registrtaion Redirect*/
		add_filter( 'registration_redirect', 'wpesov_registration_redirect' );
		
}

	function wpesov3396_page_template( $page_template )
	{
		if ( is_page( 'otp-verification' ) ) {
			$page_template = plugin_dir_path( __FILE__ ) . '/esov-otp-verification.php';
		}
		return $page_template;
	}
	
	function wpesov_custom_register_form() {
		 $Phone_num = ( ! empty( $_POST['Phone_num'] ) ) ? trim( $_POST['Phone_num'] ) : '';
	?>
		<p>
			<label for="Phone_num"><?php _e( 'Phone Number', 'wpesov' ) ?><br /></label>
			<input type="tel" name="Phone_num" id="Phone_num" class="input" placeholder="E.g 919849xxxxxx" value="<?php echo esc_attr( wp_unslash( $Phone_num ) ); ?>"  />
		</p>
	<?php
	}


	//2. Add validation. In this case, we make sure first_name is required.
	
	function wpesov_registration_errors( $errors, $sanitized_user_login, $user_email ) {

		if ( empty( $_POST['Phone_num'] ) || ! empty( $_POST['Phone_num'] ) && trim( $_POST['Phone_num'] ) == '' ) {
			$errors->add( 'Phone_num_error', __( '<strong>ERROR</strong>: Please enter your mobile number.', 'mydomain' ) );
		}

		return $errors;
	}

	//3. Finally, save our extra registration user meta.

	function wpesov_user_register( $user_id ) {

		if ( ! empty( $_POST['Phone_num'] ) ) {
			$mobile_number = $_POST['Phone_num'];

			$first_mobile_digits = substr($mobile_number, 0, 2);

			$otp_str = "";

			/*** Added By Harish to generate 7 digit OTP ***/
			for($i=7;$i>0;$i--){

				$otp_str = $otp_str.chr(rand(97,122)); 

			}
			/*** End By Harish to generate 7 digit OTP ***/

       	//if($first_mobile_digits=="91" || $first_mobile_digits =="+91"){

			if(preg_match("/^(0)?(91)?[789]\d{9}$/",$mobile_number)){
				$api_key = esc_attr( get_option('wpsov_api_key') );
				$sender_id_ind = esc_attr( get_option('wpesov_api_senderid_ind') );
				$sender_id_int = esc_attr( get_option('wpesov_api_senderid_int') );
				$sender_message = esc_attr( get_option('wpesov_api_sender_msg') );
				$sender_msg_decoded = str_replace("wpesov_otp", $otp_str , $sender_message );
				//Your OTP for TeluguConnect is ".$otp_str."

				$api_url = "http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=".$api_key."&MobileNo=".$mobile_number."&SenderID=".$sender_id_ind."&Message=".$sender_msg_decoded."&ServiceName=TEMPLATE_BASED";

			}else{

				$api_url = "http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=".$api_key."&MobileNo=".$mobile_number."&SenderID=".$sender_id_ind."&Message=".$sender_msg_decoded."&ServiceName=INTERNATIONAL";

			}

			/*** Added By Harish to send OTP ***/

			wp_remote_get( $api_url );

			/*** End By Harish to send OTP ***/

			update_user_meta( $user_id, 'phone_num_otp', $otp_str );

			update_user_meta( $user_id, 'Phone_num', trim( $_POST['Phone_num'] ) );

		}

	}

	function wpesov_show_extra_profile_fields( $user ) { ?>

		<h3>Extra profile information</h3>

		<table class="form-table">
			<tr><?php
		 //$cell=the_author_meta( 'phone_number_mo', $user_id );?>
		 <th><label for="twitter">Phone Number</label></th>
		 <td>
		 	<input type="tel" name="Phone_num" id="Phone_num" value="<?php echo esc_attr( get_the_author_meta( 'Phone_num', $user->ID ) ); ?>" class="regular-text"/><br />
		 </td>
		</tr>
		<tr><?php
		 //$cell=the_author_meta( 'phone_number_mo', $user_id );?>
		 <th><label for="phone">Phone Number OTP</label></th>
		 <td>
		 	<input type="tel" name="phone_num_otp" id="phone_num_otp" value="<?php echo esc_attr( get_the_author_meta( 'phone_num_otp', $user->ID ) ); ?>" class="regular-text"	 /><br />

		 	<!--<span class="description">Please enter your Twitter username.</span>-->
		 </td>
		</tr>
	</table>

	<?php 
	}


	function wpesov_extra_profile_fields( $user_id ) {

		/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
		if ( !empty( $_POST['Phone_num'] ) ){

			update_usermeta( $user_id, 'Phone_num', $_POST['Phone_num'] );
			}

	}

	function wpesov_registration_redirect($redirect) {

		

		$wp_otp_page = esc_attr( get_option('wpesov_otp_page') );

		return home_url( '/'. $wp_otp_page );

	}

	


	//$url1 = 'http://smsapi.24x7sms.com/api_2.0/BalanceCheck.aspx?APIKEY=1hHjfKUVuFL&ServiceName=TEMPLATE_BASED';
	//$response = wp_remote_get( $url1  );

	function wpesov_registration_remove_user_role( $user_id ) {

		session_start();
		$_SESSION["num"] = $user_id;
		$u = new WP_User( $user_id );

    // Remove role
    $u->remove_role( 'subscriber' ); //or whatever your site's default role is

	}
	
	

?>