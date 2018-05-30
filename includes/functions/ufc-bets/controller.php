<?php
/**
 * Custom UFC betting plugin
 */

// install db tables
require_once(get_template_directory().'/includes/functions/ufc-bets/database-tables.php');

// install admin menu
require_once(get_template_directory().'/includes/functions/ufc-bets/dashboard/menu.php');

// if WP_List_Table exists, use wp list table class for admin tables
if(!class_exists('WP_List_Table')){
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// install admin tables for pages
require_once(get_template_directory().'/includes/functions/ufc-bets/dashboard/generate-list-tables.php');

// install settings pages
require_once(get_template_directory().'/includes/functions/ufc-bets/dashboard/page-settings.php');

// install events pages
require_once(get_template_directory().'/includes/functions/ufc-bets/dashboard/page-events.php');

// install bets pages
require_once(get_template_directory().'/includes/functions/ufc-bets/dashboard/page-bets.php');

// add pages with the correct templates to the front end
require_once(get_template_directory().'/includes/functions/ufc-bets/generate-client-pages.php');
?>
