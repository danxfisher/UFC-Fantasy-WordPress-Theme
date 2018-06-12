<?php
/*
 * Template Name: Overall Leaderboard Page
 */

include('includes/php/ufc-api.php');
include('includes/php/ufc-calculations.php');

get_header();

global $wpdb;


$ufcCalcInstance = new UfcCalculations();

// get fights
$events_table = $wpdb->prefix . 'ufcBet_events';
$events = $wpdb->get_results("SELECT event_id FROM $events_table");

foreach ($events as $event) {
  $ufc_event_id = $event->event_id;

  // get fights for event
  $fights_table = $wpdb->prefix . 'ufcBet_fights';
  $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $ufc_event_id));

  // get bets
  $bets_table = $wpdb->prefix . 'ufcBet_bets';
  $all_bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE ufc_event_id = %s AND is_in_totals_leader = 0", $ufc_event_id));

  $ufc_event = UfcAPI::getFightsForEvent($ufc_event_id);

  // as we're doing this, calculate event leaderboard results that we may have not calculated yet
  $ufcCalcInstance->calculateEventResults($ufc_event, $fights, $all_bets);
}

$ufcCalcInstance->calculateOverallLeaderboard($ufc_event_id);

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

      <?php
        // leaderboard table
        $leaderboard_table = $wpdb->prefix . 'ufcBet_leaderboard_totals';
      ?>
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
