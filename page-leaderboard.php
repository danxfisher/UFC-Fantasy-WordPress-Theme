<?php
/*
 * Template Name: Overall Leaderboard Page
 */
get_header();

global $wpdb;

/*

  get var all ufc_event_id from events
  loop through events and call ufc api
  loop through bets and fights from page-eventleaderboard
  build up-to-date leaderboard on page

  look for "START HERE" in page-eventleaderboard



*/
// *****************************************************************************
// start leaderboard update

// NEED TO GET EVENTS table and loop through them ******************************
// ONLY NEED TO GET BETS WHERE is_in_totals_leader = 0 SINCE IF ITS 1
// IT WILL HAVE ALREADY BEEN ADDED

// get fights
$events_table = $wpdb->prefix . 'ufcBet_events';
$events = $wpdb->get_results("SELECT event_id FROM $events_table");

foreach ($events as $event) {
  $ufc_event_id = $event->event_id;

  $fights_table = $wpdb->prefix . 'ufcBet_fights';
  $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $ufc_event_id));

  // get bets
  $bets_table = $wpdb->prefix . 'ufcBet_bets';
  $all_bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s AND is_in_totals_leader = 0", $ufc_event_id));

  $ufc_event_url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $ufc_event_id . '/fights';
  $ufc_event = file_get_contents($ufc_event_url);
  $ufc_event = json_decode($ufc_event);


  // *****************************************************************************
  // this needs to be elsewhere so it isn't only run on eventleaderboard page load
  // *****************************************************************************
  foreach($ufc_event as $ufc_fight){
    foreach ($fights as $fight) {
      if ($ufc_fight->id == $fight->ufc_fight_id){

        $winner = '';

        if (isset($ufc_fight->fighter1_is_winner) && isset($ufc_fight->fighter2_is_winner)){
          if ($ufc_fight->fighter1_is_winner){

            $winner = $fight->fighter1;
            $wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

            // add winner to fights table
            // add is_correct to bets table
            foreach ($all_bets as $bet){
              if ($bet->ufc_fight_id == $ufc_fight->id) {
                if($winner == $bet->fighter_selected){
                  $wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                }
                else {
                  $wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                }
              }
            }
          }
          elseif ($ufc_fight->fighter2_is_winner){

            $winner = $fight->fighter2;
            $wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

            // add winner to fights table
            // add is_correct to bets table
            foreach ($all_bets as $bet){
              if ($bet->ufc_fight_id == $ufc_fight->id) {
                if($winner == $bet->fighter_selected){
                  $wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                }
                else {
                  $wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id, 'id' => $bet->id ), array('%d', '%d'), array( '%d', '%d' ));
                }
              }
            }
          }
          elseif (!empty($ufc_fight->result->Method)) {
            foreach ($all_bets as $bet){
              if ($bet->ufc_fight_id == $ufc_fight->id) {
                // update fight table with draw
                $wpdb->update($fights_table, array('winner' => 'DRAW'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

                // update bets as incorrect (suck it, you can't choose draw)
                $wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
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

// *****************************************************************************
// end leaderboard update

// get bets
$bets_table = $wpdb->prefix . 'ufcBet_bets';
$bets = $wpdb->get_results("SELECT * FROM $bets_table WHERE is_in_totals_leader = 0 AND is_complete = 1");


// *****************************************************************************
// 1) iterate all events from ufcBet_events table
// 2) set the event leaderboard stuff for each event in
//    case no one has clicked on it yet
// *****************************************************************************


// leaderboard table
$leaderboard_table = $wpdb->prefix . 'ufcBet_leaderboard_totals';

foreach ($bets as $bet) {
  // see if user exists
  // change this table to use overall not event_bet ****************************
  $user_exists = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $leaderboard_table WHERE username = %s", $bet->username) );

  // if user exists
  if ($user_exists) {
    //get current leaderboard table
    $current_leaderboard = $wpdb->get_row($wpdb->prepare("SELECT * FROM $leaderboard_table WHERE username = %s", $bet->username));

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
      $wpdb->update($leaderboard_table, $data, array( 'username' => $bet->username ), array('%s', '%s', '%s'), array( '%s' ));

      // update bet table with a 1 in is_in_totals_leader
      $wpdb->update($bets_table, array('is_in_totals_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
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
    $wpdb->insert($leaderboard_table, $data);

    // update bet table with a 1 in is_in_totals_leader
    $wpdb->update($bets_table, array('is_in_totals_leader' => '1'), array( 'id' => $bet->id ), array('%d'), array( '%d' ));
  }
}
?>

  <div class="ufc-event">
    <div class="row">
      <div class="col-md-12">
        <div id="event-title">
          Leaderboard
        </div>
        <div id="event-tagline">
          <!-- nothing -->
        </div>
      </div>
    </div>
  </div>
  <div id="ufc-leaderboard">
    <div class="container">
      <h2>Overall Leaderboard</h2>
      <table class="table-leaderboard" width="100%">
        <tr>
          <th width="40%">
            User
          </th>
          <th width="20%">
            Picks
          </th>
          <th width="20%">
            Correct
          </th>
          <th width="20%">
            Win %
          </th>
        </tr>

      <!-- change this table to use overall not event_bet **************************** -->
      <?php $leaderboard_totals = $wpdb->get_results("SELECT * FROM $leaderboard_table ORDER BY win_percentage DESC LIMIT 10");
        foreach ($leaderboard_totals as $total) { ?>
          <tr>
            <td>
              <?php echo $total->username ?>
            </td>
            <td>
              <?php echo $total->total_bets ?>
            </td>
            <td>
              <?php echo $total->total_correct ?>
            </td>
            <td>
              <?php echo ($total->win_percentage * 100) ?>%
            </td>
          </tr>
      <?php } ?>
      </table>
    </div>
  </div>

<?php get_footer(); ?>
