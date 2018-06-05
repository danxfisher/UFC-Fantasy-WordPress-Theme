<?php

  // class for all ufc table calls
  class UfcEventLeaderboard
  {
    private $wpdb;

    // set up our global $wpdb var
    public function __construct() {
      global $wpdb;

      $this->wpdb = $wpdb;

    }

    public function getFightsByEventId($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_fights';
      $fights = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s AND is_betting_enabled = 1", $event_id));

      return $fights;
    }

    public function getBetsByEventId($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_bets';
      $bets = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s", $event_id));

      return $bets;
    }
  }

?>
