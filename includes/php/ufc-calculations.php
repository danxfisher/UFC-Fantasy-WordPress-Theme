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

    public function calculateEventResults($ufc_event, $fights, $bets){
      $fights_table = $this->wpdb->prefix . 'ufcBet_fights';
      $bets_table = $this->wpdb->prefix . 'ufcBet_bets';

      foreach($ufc_event as $ufc_fight){
        foreach ($fights as $fight) {
          if ($ufc_fight->id == $fight->ufc_fight_id){

            $winner = '';

            // if a fight has finish / if result is available
            if (isset($ufc_fight->fighter1_is_winner) && isset($ufc_fight->fighter2_is_winner)){
              // if fighter 1 is the winner
              if ($ufc_fight->fighter1_is_winner){

                $winner = $fight->fighter1;
                $this->wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

                // add winner to fights table
                // add is_correct to bets table
                foreach ($bets as $bet){
                  if ($bet->ufc_fight_id == $ufc_fight->id) {
                    if($winner == $bet->fighter_selected){
                      $this->wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                    }
                    else {
                      $this->wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                    }
                  }
                }
              }
              // if fighter 2 is the winner
              elseif ($ufc_fight->fighter2_is_winner){

                $winner = $fight->fighter2;
                $this->wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

                // add winner to fights table
                // add is_correct to bets table
                foreach ($bets as $bet){
                  if ($bet->ufc_fight_id == $ufc_fight->id) {
                    if($winner == $bet->fighter_selected){
                      $this->wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                    }
                    else {
                      $this->wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                    }
                  }
                }
              }
              // if there is a draw
              elseif (!empty($ufc_fight->result->Method)) {
                // update fight table with draw
                $this->wpdb->update($fights_table, array('winner' => 'DRAW'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));
                foreach ($bets as $bet){
                  if ($bet->ufc_fight_id == $ufc_fight->id) {
                    // update bets as incorrect (suck it, you can't choose draw)
                    $this->wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
                  }
                }
              }
              else {
                // do nothing.
              }
            }
          }
        }
      }
    }

    public function calculateEventLeaderboard($event_id){
      $bets_table = $this->wpdb->prefix . 'ufcBet_bets';
      $event_bets = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s AND is_complete = 1 AND is_in_event_leader = 0 ", $event_id));

      // get bets
      $event_leaderboard_table = $this->wpdb->prefix . 'ufcBet_event_bet_totals';

      foreach ($event_bets as $bet) {
        // see if user exists
        $user_exists = $this->wpdb->get_var( $this->wpdb->prepare("SELECT COUNT(*) FROM $event_leaderboard_table WHERE username = %s AND ufc_event_id = %s", $bet->username, $bet->ufc_event_id) );

        // if user exists
        if ($user_exists) {
          //get current event leaderboard table
          $current_leaderboard = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $event_leaderboard_table WHERE username = %s AND ufc_event_id = %s", $bet->username, $bet->ufc_event_id));

          $total_bets     = $current_leaderboard->total_bets + 1;
          $total_correct  = $current_leaderboard->total_correct + $bet->is_correct;
          // calculate win percentage
          $win_percentage = $total_correct / $total_bets;

          $data = array(
            'total_bets'      => $total_bets,
            'total_correct'   => $total_correct,
            'win_percentage'  => $win_percentage
          );

          // update event leaderboard table with new stats
          $this->wpdb->update($event_leaderboard_table, $data, array( 'username' => $bet->username , 'ufc_event_id' => $event_id), array('%d', '%d', '%s'), array( '%s', '%s' ));

          // update bet table with a 1 in is_in_totals_leader
          $this->wpdb->update($bets_table, array('is_in_event_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
        }
        else {

          // first time through
          $total_bets = 1;
          $total_correct = 0 + $bet->is_correct;
          // calculate win percentage
          $win_percentage = $total_correct / $total_bets;

          $data = array(
            'ufc_event_id'    => $event_id,
            'username'        => $bet->username,
            'total_bets'      => $total_bets,
            'total_correct'   => $total_correct,
            'win_percentage'  => $win_percentage
          );

          // update event leaderboard table with new stats
          $this->wpdb->insert($event_leaderboard_table, $data);

          // update bet table with a 1 in is_in_totals_leader
          $this->wpdb->update($bets_table, array('is_in_event_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
        }
      }
    }

    public function calculateOverallLeaderboard($event_id){
      // get bets
      $bets_table = $this->wpdb->prefix . 'ufcBet_bets';
      $bets = $this->wpdb->get_results("SELECT * FROM $bets_table WHERE is_in_totals_leader = 0 AND is_complete = 1");

      // leaderboard table
      $leaderboard_table = $this->wpdb->prefix . 'ufcBet_leaderboard_totals';

      foreach ($bets as $bet) {
        // see if user exists
        // change this table to use overall not event_bet ****************************
        $user_exists = $this->wpdb->get_var( $this->wpdb->prepare("SELECT COUNT(*) FROM $leaderboard_table WHERE username = %s", $bet->username) );

        // if user exists
        if ($user_exists) {
          //get current leaderboard table
          $current_leaderboard = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $leaderboard_table WHERE username = %s", $bet->username));

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

            // change this table to use overall not event_bet ****************************
            $this->wpdb->update($leaderboard_table, $data, array( 'username' => $bet->username ), array('%s', '%s', '%s'), array( '%s' ));

            // update bet table with a 1 in is_in_totals_leader
            $this->wpdb->update($bets_table, array('is_in_totals_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
          }
        }
        else {

          // first time through
          $total_bets = 1;
          $total_correct = 0 + $bet->is_correct;

          $win_percentage = $total_correct / $total_bets;
          //$win_percentage = $win * 100;

          $data = array(
            'username'        => $bet->username,
            'total_bets'      => $total_bets,
            'total_correct'   => $total_correct,
            'win_percentage'  => $win_percentage
          );

          // change this table to use overall not event_bet ****************************
          $this->wpdb->insert($leaderboard_table, $data);

          // update bet table with a 1 in is_in_totals_leader
          $this->wpdb->update($bets_table, array('is_in_totals_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
        }
      }
    }
  }

?>
