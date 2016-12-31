<?php
/*
 * Custom UFC betting plugin
 *
 * 2) install admin menu and page classes
 */

/*
 * ufc bet admin menu
 *===========================================*/

function ufcBets_menu() {
 	add_menu_page("UFC Bet Management", "UFC Bet Management", "manage_options", "ufcBet", "ufcBet_main_page", "dashicons-tickets-alt", 90);
  add_submenu_page("ufcBet", "Settings", "Settings", "manage_options", "ufcBet");
  add_submenu_page("ufcBet", "Events", "Events", "manage_options", "ufcBet-events", "ufcBet_events");
  add_submenu_page(null, "Add Event", "Add Event", "manage_options", "ufcBet-add-event", "ufcBet_add_event");
  add_submenu_page(null, "Fights", "Fights", "manage_options", "ufcBet-fights", "ufcBet_fights");
  add_submenu_page(null, "Add Fight", "Add Fight", "manage_options", "ufcBet-add-fight", "ufcBet_add_fight");
  add_submenu_page("ufcBet", "Manage Bets", "Manage Bets", "manage_options", "ufcBet-bets", "ufcBet_bets");
}

add_action("admin_menu", "ufcBets_menu");


/*
 * ufc bet - wp list table class
 *===========================================*/
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


?>
