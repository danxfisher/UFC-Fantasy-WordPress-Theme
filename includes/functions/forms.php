<?php
/**
 *  Forms customization
 */

/***************************************
 * login header
 ***************************************/
function ufc_login_message( $message ) {
 if ( empty($message) ){
   return '<div class="login-title">UFC</div>';
 }
 elseif(strpos($message,"Register For This Site") == true) {
   $message = '<div class="register-title">UFC</div><p class="message">Register for this site</p>';
   return $message;
 }
 elseif(strpos($message,"Please enter your username or email address. You will receive a link to create a new password via email.") == true) {
   $message = '<div class="register-title">UFC</div><p class="message">Please enter your username or email address. You will receive a link to create a new password via email.</p>';
   return $message;
 }
 else {
   return $message;
 }
}

add_filter( 'login_message', 'ufc_login_message' );

function enqueue_custom_login_scripts( $page ) {
  /* css */
  wp_enqueue_style( 'custom', get_template_directory_uri() . '/includes/css/login.css' );
}
add_action( 'login_enqueue_scripts', 'enqueue_custom_login_scripts' );




?>
