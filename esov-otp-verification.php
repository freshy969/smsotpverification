<?php

/* Template name: OTP Verification */
session_start();

    /*** Added By Harish to verify OTP ***/
    $user_info = get_userdata($_SESSION["num"]);
    //echo $user_info;
?>


<form action="" method="post">

    <label for="otp">
        <?php _e( 'Enter OTP', 'mydomain' ) ?><br />
        <input type="tel" name="otp" id="otp" class="input" value="<?php echo esc_attr( wp_unslash( $otp ) ); ?>"
            required /></label>

    <br><br>

    <input type="submit" value="submit">

</form>

<?php

    if(isset($_POST["otp"])){

        $motp = $_POST["otp"];

        $votp = $user_info->phone_num_otp;
        //ECHO "hI".$votp;

        if( $motp == $votp){

            $u = new WP_User( $user_info );

                            // Add role

            $u->add_role( 'subscriber' );

            echo "OTP Verified Successfully";
            echo "<br><a herf=".get_home_url().">Return to Home</a>";

            session_destroy();

        } 
        else{
                $wp_otp_page = esc_attr( get_option('wpesov_otp_page') );
            echo "Incorrect OTP <a name='resend1' href='$wp_otp_page?resend1=true'>Resend OTP?</a>";
        }

    }

    if(empty($_POST["otp"])){

     if(isset($_GET["resend1"])){

        $str= "";

        $mobile_number = $user_info->Phone_num;

        /*** Added By Harish to generate 7 digit OTP ***/

        for($i=7;$i>0;$i--){

            $str = $str.chr(rand(97,122)); 

        }

        /*** End By Harish to generate 7 digit OTP ***/


        /*** Added By Harish to send OTP ***/

        $first_mobile_digits = substr($mobile_number, 0, 2);

                //if($first_mobile_digits == "91" || $first_mobile_digits == "+91"){

        if(preg_match("/^(0)?(91)?[789]\d{9}$/",$mobile_number)){

            $api_key = esc_attr( get_option('wpsov_api_key') );
                $sender_id_ind = esc_attr( get_option('wpsov_api_senderid_ind') );
                $sender_id_int = esc_attr( get_option('wpsov_api_senderid_int') );
                $sender_message = esc_attr( get_option('wpsov_api_sender_msg') );
                $sender_msg_decoded = str_replace("wpesov_otp", $str , $sender_message );
                //Your OTP for TeluguConnect is ".$otp_str."

                $api_url = "http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=".$api_key."&MobileNo=".$mobile_number."&SenderID=".$sender_id_ind."&Message=".$sender_msg_decoded."&ServiceName=TEMPLATE_BASED";

            }else{

                $api_url = "http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=".$api_key."&MobileNo=".$mobile_number."&SenderID=".$sender_id_ind."&Message=".$sender_msg_decoded."&ServiceName=INTERNATIONAL";

            }

                //$url3 = "http://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY=1hHjfKUVuFL&MobileNo=".$mobile_number."&SenderID=SMSMsg&Message=Your OTP for TeluguConnect is ".$str."&ServiceName=INTERNATIONAL";



        wp_remote_get( $api_url );

        /*** End By Harish to send OTP ***/

        update_user_meta( $_SESSION["num"], 'phone_num_otp', $str );

        echo "OTP Resent";

    }

    }

/*** End By Harish to verify OTP ***/