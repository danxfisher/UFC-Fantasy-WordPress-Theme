<?php
/**
 * Custom UFC betting plugin
 */

// install db tables
require_once(get_template_directory().'/includes/functions/ufc-bets/database-tables.php');

// install admin menu
require_once(get_template_directory().'/includes/functions/ufc-bets/admin-menu.php');

// install admin tables for pages
require_once(get_template_directory().'/includes/functions/ufc-bets/admin-tables.php');

// install settings pages
require_once(get_template_directory().'/includes/functions/ufc-bets/page-settings.php');

// install events pages
require_once(get_template_directory().'/includes/functions/ufc-bets/page-events.php');

// install fights pages
require_once(get_template_directory().'/includes/functions/ufc-bets/page-fights.php');

// install bets pages
require_once(get_template_directory().'/includes/functions/ufc-bets/page-bets.php');
?>
