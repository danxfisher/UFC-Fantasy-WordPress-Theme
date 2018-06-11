<?php

  // class for all ufc table calls
  class UfcCalculations
  {
    private $wpdb;

    // set up our global $wpdb var
    public function __construct() {
      global $wpdb;

      $this->wpdb = $wpdb;

    }

    public function calculateEventFights(){}

    public function calculateEventLeaderboard(){
      $leader_bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s AND is_complete = 1 AND is_in_event_leader = 0 ", $ufc_event_id));

      // get bets
      $event_leaderboard_table = $wpdb->prefix . 'ufcBet_event_bet_totals';

      foreach ($leader_bets as $bet) {
        // see if user exists
        $user_exists = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $event_leaderboard_table WHERE username = %s AND ufc_event_id = %s", $bet->username, $bet->ufc_event_id) );

        // if user exists
        if ($user_exists) {
          //get current event leaderboard table
          $current_leaderboard = $wpdb->get_row($wpdb->prepare("SELECT * FROM $event_leaderboard_table WHERE username = %s AND ufc_event_id = %s", $bet->username, $bet->ufc_event_id));

          $total_bets     = $current_leaderboard->total_bets + 1;
          $total_correct  = $current_leaderboard->total_correct + $bet->is_correct;

          // calculate win percentage
          $win_percentage = $total_correct / $total_bets;

          // check to see if there were any changes
          $current_total_bets = $current_leaderboard->total_bets;

          // if the values are different, update
          if ($current_total_bets != $total_bets) {
            $data = array(
              'total_bets'      => $total_bets,
              'total_correct'   => $total_correct,
              'win_percentage'  => $win_percentage
            );

            $wpdb->update($event_leaderboard_table, $data, array( 'username' => $bet->username , 'ufc_event_id' => $ufc_event_id), array('%d', '%d', '%s'), array( '%s', '%s' ));

            // update bet table with a 1 in is_in_totals_leader
            $wpdb->update($bets_table, array('is_in_event_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
          }
        }
        else {

          // first time through
          $total_bets = 1;
          $total_correct = 0 + $bet->is_correct;

          $win_percentage = $total_correct / $total_bets;
          //$win_percentage = $win * 100;

          $data = array(
            'ufc_event_id'    => $ufc_event_id,
            'username'        => $bet->username,
            'total_bets'      => $total_bets,
            'total_correct'   => $total_correct,
            'win_percentage'  => $win_percentage
          );

          $wpdb->insert($event_leaderboard_table, $data);

          // update bet table with a 1 in is_in_totals_leader
          $wpdb->update($bets_table, array('is_in_event_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
        }
      }
    }

    public function calculateOverallLeaderboard(){}
  }

?>
