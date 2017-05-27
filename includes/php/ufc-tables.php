<?php
  // class for all ufc table calls
  class UfcTable
  {
    // get wp_ufcbet_bets table
    public static function getBetsTable($event_id) {
      $table_name = $wpdb->prefix . 'ufcBet_bets';
      $table = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s", $event_id));

      return $table;
    }

    // get wp_ufcbet_events table
    public static function getEventsTable($event_id) {
      $table_name = $wpdb->prefix . 'ufcBet_events';
      $table = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE event_id = %s", $event_id));

      return $table;
    }

    // get wp_ufcbet_event_bet_totals table
    public static function getEventBetTotalsTable() {

    }

    // get wp_ufcbet_fights table
    public static function getFightsTable($event_id) {
      $table_name = $wpdb->prefix . 'ufcBet_fights';
      $table = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s AND is_betting_enabled = 1", $event_id));

      return $table;
    }

    // get wp_ufcbet_leaderboard_totals table
    public static function getLeaderboardTotalsTable() {

    }

  }



?>
