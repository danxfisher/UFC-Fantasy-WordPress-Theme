<?php
/*
 * Custom UFC betting plugin
 *
 * 1) install database tables
 */

/*
 * ufc bet database tables
 *===========================================*/
 /*
global $ufcEvents_db_version;
$ufcEvents_db_version = '1.0';

global $ufcFights_db_version;
$ufcFights_db_version = '1.0';

global $ufcBets_db_version;
$ufcBets_db_version = '1.0';

global $ufcUserTotals_db_version;
$ufcUserTotals_db_version = '1.0';

global $ufcUserLeaderboard_db_version;
$ufcUserLeaderboard_db_version = '1.0';
*/
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function ufcBets_db_tables() {
  global $wpdb;

  $ufc_events = $wpdb->prefix . 'ufcBet_events';
  $ufc_fights = $wpdb->prefix . 'ufcBet_fights';
  $ufc_bets = $wpdb->prefix . 'ufcBet_bets';
  $ufc_event_bet_totals = $wpdb->prefix . 'ufcBet_event_bet_totals';
  $ufc_leaderboard_totals = $wpdb->prefix . 'ufcBet_leaderboard_totals';

  // create events table
  $wpdb->query(
    "CREATE TABLE IF NOT EXISTS $ufc_events (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        event_id INT UNSIGNED NOT NULL,
        name tinytext NOT NULL,
        lock_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        is_complete BIT(1) DEFAULT 0 NOT NULL
    ) ENGINE = INNODB
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci"
  );
  dbDelta();

  //add_option( 'ufcEvents_db_version', $ufcEvents_db_version );

  // create fights table
  $wpdb->query(
    "CREATE TABLE IF NOT EXISTS $ufc_fights (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        event_id INT UNSIGNED NOT NULL,
        ufc_event_id INT UNSIGNED NOT NULL,
        ufc_fight_id INT UNSIGNED NOT NULL,
        fighter1 TEXT NOT NULL,
        fighter2 TEXT NOT NULL,
        winner TEXT NOT NULL,
        weight_class TEXT NOT NULL,
        is_title_fight BIT(1) DEFAULT 0 NOT NULL,
        is_betting_enabled BIT(1) DEFAULT 0 NOT NULL,
        FOREIGN KEY  (event_id) REFERENCES $ufc_events(id)
    ) ENGINE = INNODB
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci"
  );
  dbDelta();

  //add_option( 'ufcFights_db_version', $ufcFights_db_version );

  // create bets table
  $wpdb->query(
    "CREATE TABLE IF NOT EXISTS $ufc_bets (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        fight_id INT UNSIGNED NOT NULL,
        ufc_event_id INT UNSIGNED NOT NULL,
        ufc_fight_id INT UNSIGNED NOT NULL,
        username TEXT NOT NULL,
        fighter_selected TEXT NOT NULL,
        is_correct BIT(1) DEFAULT 0 NOT NULL,
        is_complete BIT(1) DEFAULT 0 NOT NULL,
        is_in_event_leader BIT(1) DEFAULT 0 NOT NULL,
        is_in_totals_leader BIT(1) DEFAULT 0 NOT NULL,
        FOREIGN KEY  (fight_id) REFERENCES $ufc_fights(id)
    ) ENGINE = INNODB
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci"
  );
  dbDelta();

  //add_option( 'ufcBets_db_version', $ufcBets_db_version );

  // create user event totals table
  $wpdb->query(
    "CREATE TABLE IF NOT EXISTS $ufc_event_bet_totals (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        ufc_event_id INT UNSIGNED NOT NULL,
        username TEXT NOT NULL,
        total_bets INT UNSIGNED,
        total_correct INT UNSIGNED,
        win_percentage DECIMAL(5,4)
    ) ENGINE = INNODB
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci"
  );
  dbDelta();

  //add_option( 'ufcUserTotals_db_version', $ufcUserTotals_db_version );

  // create user leaderboard totals table
  $wpdb->query(
    "CREATE TABLE IF NOT EXISTS $ufc_leaderboard_totals (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username TEXT NOT NULL,
        total_bets INT UNSIGNED,
        total_correct INT UNSIGNED,
        win_percentage DECIMAL(5,4)
    ) ENGINE = INNODB
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci"
  );
  dbDelta();

  //add_option( 'ufcUserLeaderboard_db_version', $ufcUserLeaderboard_db_version );



}

ufcBets_db_tables();

?>
