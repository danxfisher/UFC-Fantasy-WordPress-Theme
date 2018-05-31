<?php
  // class for all ufc table calls
  class UfcTable
  {

    private $wpdb;

    // set up our global $wpdb var
    public function __construct() {
      global $wpdb;
      $this->wpdb = $wpdb;
    }

    // get wp_ufcbet_bets table
    public static function getBetsTable($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_bets';
      $table = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s", $event_id));

      return $table;
    }

    // get wp_ufcbet_events table
    public static function getEventsTable($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_events';
      $table = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE event_id = %s", $event_id));

      return $table;
    }

    // get wp_ufcbet_event_bet_totals table
    public static function getEventBetTotalsTable() {

    }

    // get wp_ufcbet_fights table
    public static function getFightsTable($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_fights';
      $table = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s AND is_betting_enabled = 1", $event_id));

      return $table;
    }

    // get wp_ufcbet_leaderboard_totals table
    public static function getLeaderboardTotalsTable() {

    }

  }



?>
