<?php
/**
 *  Captcha forms customization
 */


/***************************************
 * login scripts
 ***************************************/
function enqueue_custom_captcha_login_scripts( $page ) {
  /* js */
  wp_enqueue_script( 'captcha', 'https://www.google.com/recaptcha/api.js', null, null, true );
}
add_action( 'login_enqueue_scripts', 'enqueue_custom_captcha_login_scripts' );


/***************************************
 * recaptcha for login form
 ***************************************/
function display_login_captcha() { ?>
    <div class="g-recaptcha google-captcha" data-sitekey="<?php echo get_option('captcha_site_key'); ?>"></div>
<?php }
add_action( 'login_form', 'display_login_captcha' );

function verify_login_captcha($user, $password) {
	if (isset($_POST['g-recaptcha-response'])) {
		$recaptcha_secret = get_option('captcha_secret_key');
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
		$response = json_decode($response["body"], true);
		if (true == $response["success"]) {
			return $user;
		} else {
			return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot"));
		}
	} else {
		return new WP_Error("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot. If not then enable JavaScript"));
	}
}
add_filter("wp_authenticate_user", "verify_login_captcha", 10, 2);


/***************************************
 * recaptcha for sign up form
 ***************************************/
function display_register_captcha() { ?>
	<div class="g-recaptcha" data-sitekey="<?php echo get_option('captcha_site_key'); ?>"></div>
<?php }
add_action("register_form", "display_register_captcha");

function verify_registration_captcha($errors, $sanitized_user_login, $user_email) {
	if (isset($_POST['g-recaptcha-response'])) {
		$recaptcha_secret = get_option('captcha_secret_key');
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
		$response = json_decode($response["body"], true);
		if (true == $response["success"]) {
			return $errors;
		} else {
			$errors->add("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot"));
		}
	} else {
		$errors->add("Captcha Invalid", __("<strong>ERROR</strong>: You are a bot. If not then enable JavaScript"));
	}
	return $errors;
}
add_filter("registration_errors", "verify_registration_captcha", 10, 3);


/***************************************
 * recaptcha for lost password form
 ***************************************/
function verify_lostpassword_captcha() {
	if (isset($_POST['g-recaptcha-response'])) {
		$recaptcha_secret = get_option('captcha_secret_key');
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $_POST['g-recaptcha-response']);
		$response = json_decode($response["body"], true);
		if (true == $response["success"]) {
			return;
		} else {
			wp_die(__("<strong>ERROR</strong>: You are a bot"));
		}
	} else {
		wp_die(__("<strong>ERROR</strong>: You are a bot. If not then enable JavaScript"));
	}
	return $errors;
}
add_action("lostpassword_form", "display_login_captcha");
add_action("lostpassword_post", "verify_lostpassword_captcha");

?>
