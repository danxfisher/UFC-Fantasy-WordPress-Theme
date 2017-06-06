<?php
/*
 * Template Name: Event Leaderboard Page
 */

include('includes/php/ufc-api.php');

get_header();

global $wpdb;

$ufc_event_id = $_GET['ufc_event_id'];
$event_title = $_GET['title'];

// START HERE FOR OVERALL LEADERBOARD ******************************************
// ALSO FIGHT BY FIGHT NEEDS TO BE IN REVERSE ORDER

// get fights
$fights_table = $wpdb->prefix . 'ufcBet_fights';
$fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $ufc_event_id));

echo count($fights);

// get bets
$bets_table = $wpdb->prefix . 'ufcBet_bets';
$bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s", $ufc_event_id));

echo count($bets);

// $ufc_event_url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $ufc_event_id . '/fights';
// $ufc_event = file_get_contents($ufc_event_url);
// $ufc_event = json_decode($ufc_event);

$ufc_event = UfcAPI::getFightsForEvent($ufc_event_id);


// *****************************************************************************
// this needs to be elsewhere so it isn't only run on eventleaderboard page load
// *****************************************************************************
foreach($ufc_event as $ufc_fight){
  foreach ($fights as $fight) {
    if ($ufc_fight->id == $fight->ufc_fight_id){

      $winner = '';
      if (isset($ufc_fight->fighter1_is_winner) && isset($ufc_fight->fighter2_is_winner)){

        if ($ufc_fight->fighter1_is_winner){
          // add winner to fights table
          // add is_correct to bets table
          foreach ($bets as $bet){
            if ($bet->ufc_fight_id == $ufc_fight->id) {

              $winner = $fight->fighter1;
              $wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

              if($winner == $bet->fighter_selected){
                $wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
              }
              else {
                $wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
              }
            }
          }
        }
        elseif ($ufc_fight->fighter2_is_winner){
          // add winner to fights table
          // add is_correct to bets table
          foreach ($bets as $bet){
            if ($bet->ufc_fight_id == $ufc_fight->id) {

              $winner = $fight->fighter2;
              $wpdb->update($fights_table, array('winner' => $winner), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%s'), array( '%d' ));

              if($winner == $bet->fighter_selected){
                $wpdb->update($bets_table, array('is_correct' => '1', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
              }
              else {
                $wpdb->update($bets_table, array('is_correct' => '0', 'is_complete' => '1'), array( 'ufc_fight_id' => $fight->ufc_fight_id ), array('%d', '%d'), array( '%d' ));
              }
            }
          }
        }
        elseif (!empty($ufc_fight->result->Method)) {
          foreach ($bets as $bet){
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

// do the event leaderboard ... show top 10
// leave the overall leaderboard for a different page
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

      $wpdb->update($event_leaderboard_table, $data, array( 'username' => $bet->username ), array('%s', '%s', '%s'), array( '%s' ));

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



?>

  <div class="ufc-event">
    <div class="row">
      <div class="col-md-12">
        <div id="event-title">
          <?php echo $event_title; ?>
        </div>
        <div id="event-tagline">
          Event Leaderboards
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 button-wrap">
        <?php $event_url = str_replace(" ", "-", $event_title); ?>
        <a href="<?php echo site_url() . '/' . $ufc_event_id . '-' . $event_url ?>">
          <div class="dark-button">
            Back to <?php echo $event_title ?> Event Page
          </div>
        </a>
      </div>
    </div>
  </div>
  <div id="ufc-event-leaderboard">
    <div class="container">
      <h2>Event Leaderboard</h2>
      <table class="table-event-leaderboard" width="100%">
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

      <?php $event_bet_totals = $wpdb->get_results($wpdb->prepare("SELECT * FROM $event_leaderboard_table WHERE ufc_event_id = %s ORDER BY win_percentage DESC LIMIT 10", $ufc_event_id));
        foreach ($event_bet_totals as $total) { ?>
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
  <div id="ufc-event-ldr-fights">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Fight by Fight</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php $fights = array_reverse($fights); ?>
          <?php foreach ($fights as $fight) { ?>
            <?php $bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s AND ufc_fight_id = %s", $ufc_event_id, $fight->ufc_fight_id)); ?>
            <div class="event-ldr-wrap">
              <div class="row ldr-img-row">
                <div class="col-xs-5 <?php if($fight->fighter1 === $fight->winner) { ?> event-ldr-winner<?php } ?>">
                  <?php foreach ($ufc_event as $event_fight) {
                    if ($fight->ufc_fight_id == $event_fight->id) {
                  ?>
                      <img src="<?php echo $event_fight->fighter1_profile_image; ?>" class="img-responsive center-stuff" />
                    <?php }
                  } ?>
                </div>
                <div class="col-xs-2">

                </div>
                <div class="col-xs-5 <?php if($fight->fighter2 === $fight->winner) { ?> event-ldr-winner<?php } ?>">
                  <?php foreach ($ufc_event as $event_fight) {
                    if ($fight->ufc_fight_id == $event_fight->id) {
                  ?>
                      <img src="<?php echo $event_fight->fighter2_profile_image; ?>" class="img-responsive center-stuff" />
                    <?php }
                  } ?>
                </div>
              </div>
              <div class="row event-ldr-row">
                <div class="col-xs-5 event-ldr-fighter">
                  <?php echo $fight->fighter1 ?>
                </div>
                <div class="col-xs-2 event-ldr-vs">
                  vs
                </div>
                <div class="col-xs-5 event-ldr-fighter">
                  <?php echo $fight->fighter2 ?>
                </div>
              </div>

              <div class="row ldr-bets">
                <div class="col-xs-5 event-ldr-users<?php if($fight->fighter1 === $fight->winner) { ?> event-ldr-winner<?php } ?>">
                  <!-- usea -->
                  <?php
                  foreach ($bets as $bet) {
                    if ($bet->fighter_selected === $fight->fighter1) {
                      echo $bet->username . '<br />';
                    }
                  }
                  ?>
                </div>
                <div class="col-xs-2">
                  <!-- nothing -->
                </div>
                <div class="col-xs-5 event-ldr-users<?php if($fight->fighter2 === $fight->winner) { ?> event-ldr-winner<?php } ?>">
                  <?php foreach ($bets as $bet) {
                    if ($bet->fighter_selected === $fight->fighter2) {
                        echo $bet->username . '<br />';
                    }
                  } ?>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

<?php get_footer(); ?>
