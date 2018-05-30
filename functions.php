<?php

// theme cleanup
require_once(get_template_directory().'/includes/functions/cleanup.php');

// register scripts, stylesheets, and fonts
require_once(get_template_directory().'/includes/functions/enqueue-scripts.php');
require_once(get_template_directory().'/includes/functions/enqueue-google-fonts.php');

// register custom plugins
// recaptcha-options
require_once(get_template_directory().'/includes/functions/recaptcha.php');

// register custom ufc betting plugin
require_once(get_template_directory().'/includes/functions/ufc-bets/controller.php');

// register custom form hooks
require_once(get_template_directory().'/includes/functions/forms.php');

// register custom captcha form hooks
$captcha_enabled = get_option('captcha_enabled');
if ($captcha_enabled == 'yes') {
  require_once(get_template_directory().'/includes/functions/captcha-forms.php');
}

// adds multiple language support
require_once(get_template_directory().'/includes/functions/advanced-custom-fields.php');

?>
