<?php

// theme support
//require_once(get_template_directory().'/includes/functions/theme-support.php');

// theme cleanup
require_once(get_template_directory().'/includes/functions/cleanup.php');

// register scripts, stylesheets, and fonts
require_once(get_template_directory().'/includes/functions/enqueue-scripts.php');
require_once(get_template_directory().'/includes/functions/enqueue-google-fonts.php');

// register custom plugins
// recaptcha-options
require_once(get_template_directory().'/includes/functions/recaptcha.php');

// register custom ufc betting plugin
require_once(get_template_directory().'/includes/functions/ufc-bets/ufcbets.php');

// register custom form hooks
require_once(get_template_directory().'/includes/functions/forms.php');

// register admin styles
// require_once(get_template_directory().'/includes/functions/enqueue-admin-styles.php');

// register custom menus and menu walkers
// require_once(get_template_directory().'/includes/functions/menu.php');
// require_once(get_template_directory().'/includes/bootstrap-wp-navwalker.php');

// register sidebars/widget areas
// require_once(get_template_directory().'/includes/functions/sidebar.php');

// register template tags
// require_once(get_template_directory().'/includes/functions/template-tags.php');

// adds multiple language support
require_once(get_template_directory().'/includes/functions/languages.php');

// add woocommerce theme support
// require_once(get_template_directory().'/includes/functions/woocommerce.php');

// add custom footer builder post type
// require_once(get_template_directory().'/includes/php/footer-builder/footer-builder.php');

// add customizer options
// require get_template_directory() . '/includes/customizer.php';

?>
