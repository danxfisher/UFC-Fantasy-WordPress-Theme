<?php
/*
 * Template Name: Event Leaderboard Page
 */

include('includes/php/ufc-api.php');
include('includes/php/ufc-event-leaderboard.php');
include('includes/php/ufc-calculations.php');

get_header();

global $wpdb;

$ufc_event_id = $_GET['ufc_event_id'];
$event_title = $_GET['title'];

$ufcEventLdrInstance = new UfcEventLeaderboard();
$ufcCalcInstance = new UfcCalculations();

// get fights
$fights = $ufcEventLdrInstance->getFightsByEventId($ufc_event_id);
// get all bets for event
$bets = $ufcEventLdrInstance->getBetsByEventId($ufc_event_id);

$ufc_event = UfcAPI::getFightsForEvent($ufc_event_id);


// *****************************************************************************
// this needs to be elsewhere so it isn't only run on eventleaderboard page load
// *****************************************************************************
$ufcCalcInstance->calculateEventResults($ufc_event, $fights, $bets);

// do the event leaderboard ... show top 10
// leave the overall leaderboard for a different page
$ufcCalcInstance->calculateEventLeaderboard($ufc_event_id);

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

      <?php $event_leaderboard_table = $wpdb->prefix . 'ufcBet_event_bet_totals'; ?>
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
            <?php $bets_table = $wpdb->prefix . 'ufcBet_bets'; ?>
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
                <div class="col-xs-5 event-ldr-users<?php if($fight->fighter1 === $fight->winner) { ?> event-ldr-winner-bet<?php } ?>">
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
                <div class="col-xs-5 event-ldr-users<?php if($fight->fighter2 === $fight->winner) { ?> event-ldr-winner-bet<?php } ?>">
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
