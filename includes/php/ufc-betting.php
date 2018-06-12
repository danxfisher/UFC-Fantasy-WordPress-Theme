<?php

  // class for all ufc table calls
  class UfcBetting
  {
    private $wpdb;

    // set up our global $wpdb var
    public function __construct() {
      global $wpdb;

      $this->wpdb = $wpdb;

    }

    public function getEventByEventId($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_events';
      $event = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE event_id = %s", $event_id));

      return $event;
    }

    public function getFightsByEventId($event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_fights';
      $fights = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE ufc_event_id = %s AND is_betting_enabled = 1", $event_id));

      return $fights;
    }

    public function doesBetExist($username, $fight_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_bets';
      $exists = $this->wpdb->get_var( $this->wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE username = %s AND fight_id = %s", $username, $fight_id) );

      return $exists;
    }

    public function getUserBetsForEvent($username, $event_id) {
      $table_name = $this->wpdb->prefix . 'ufcBet_bets';
      $bets = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE username = %s AND ufc_event_id = %s", $username, $event_id));

      return $bets;
    }

    public function addNewBet($bet) {
      $bets_table = $this->wpdb->prefix . 'ufcBet_bets';
      return $wpdb->insert($bets_table, $bet);
    }

    public function updateBet($bet_update, $fight_id, $username) {
      $bets_table = $this->wpdb->prefix . 'ufcBet_bets';

      return $this->wpdb->update($bets_table, $bet_update, array(
        'fight_id' => $fight_id,
        'username' => $username),
        array('%s'),
        array( '%d', '%s' )
      );
    }
  }
