<?php
/*
 * Template Name: Betting Page
 */

 include('includes/php/ufc-api.php');

get_header(); ?>

<?php
global $wpdb;
global $current_user;

$ufc_event_id = '';
$event_title = '';

if (isset($_GET['ufc_event_id'])) {
  $ufc_event_id = $_GET['ufc_event_id'];
}
if (isset($_GET['title'])) {
  $event_title = $_GET['title'];
}
$bets_update_success = false;

if ($ufc_event_id && $event_title) {

  $events_table = $wpdb->prefix . 'ufcBet_events';
  $event = $wpdb->get_results($wpdb->prepare("SELECT * FROM $events_table WHERE event_id = %s", $ufc_event_id));

  if (!$event){ ?>
    <div class="ufc-event">
      <div class="row">
        <div class="col-md-12">
          <div id="event-title">
            Error
          </div>
          <div id="event-tagline">
            There has been an error.
          </div>
        </div>
      </div>
    </div>
    <div id="ufc-bets">
      <div class="row">
        <div class="col-md-12">
          <div class="bet-locked">
            This event doesn't exist.
          </div>
        </div>
      </div>
    </div>

  <?php
  }
  else {
    $url = str_replace(" ", "-", $event_title);

    // $ufc_event_url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $ufc_event_id . '/fights';
    // $ufc_event = file_get_contents($ufc_event_url);
    // $ufc_event = json_decode($ufc_event);

    $ufc_event = UfcAPI::getFightsForEvent($ufc_event_id);


    // also use this if the user has previously submitted bets for this event
    if (isset( $_POST['submit'] )) {

      $fights_table = $wpdb->prefix . 'ufcBet_fights';
      $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $ufc_event_id));

      $bets_table = $wpdb->prefix . 'ufcBet_bets';

      foreach ($fights as $fight){

        $exists = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $bets_table WHERE username = %s AND fight_id = %s", $current_user->user_login, $fight->id) );

        $fighter_selected = $_POST[$fight->id];
        // use this to confirm submissions

        $fighter = '';

        if ($fighter_selected === 'fighter1') {
          $fighter = $fight->fighter1;
        }
        else if ($fighter_selected === 'fighter2') {
          $fighter = $fight->fighter2;
        }
        else {
          $fighter = 'empty';
        }

        // add bet with user name and other stuff
        if ($fighter != 'empty'){
          $bet = array(
            'fight_id'          => $fight->id,
            'ufc_event_id'      => $fight->ufc_event_id,
            'ufc_fight_id'      => $fight->ufc_fight_id,
            'username'          => $current_user->user_login,
            'fighter_selected'  => $fighter
          );

          if (!$exists){
            $wpdb->insert($bets_table, $bet);
          }
          else {
            $bet_update = array(
              'fighter_selected'  => $fighter
            );
            if (is_user_logged_in()) {
              $wpdb->update($bets_table, $bet_update, array( 'fight_id' => $fight->id, 'username' => $current_user->user_login), array('%s'), array( '%d', '%s' ));
            }
          }
        }
      }

      $bets_update_success = true;
      $bets_update_success_message = "Your bets have been submitted.";
    }

      $fights_table = $wpdb->prefix . 'ufcBet_fights';
      $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $ufc_event_id));
      $fights_order = array_reverse($fights);

      $bets_table = $wpdb->prefix . 'ufcBet_bets';
      $bets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $bets_table WHERE username = %s AND ufc_event_id = %s", $current_user->user_login, $ufc_event_id));

    ?>

      <div class="ufc-event">
        <div class="row">
          <div class="col-md-12">
            <div id="event-title">
              <?php echo $event_title; ?>
            </div>
            <div id="event-tagline">
              Select your picks
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
      <?php if ($bets_update_success) { ?>
      <div id="ufc-bets-success">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="bets-success-message">
                <div id="close-bet-success"></div>
                <?php echo $bets_update_success_message; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>

      <div id="ufc-bets">
          <div class="container">
        <?php if (is_user_logged_in()) {
          foreach ($event as $ev) {
            $event_lock_time = $ev->lock_time;
          }
          echo '<br />';
          date_default_timezone_set('America/Los_Angeles');

          $current_time = date('Y-m-d H:i:s');

          if (strtotime($event_lock_time) > strtotime($current_time)) {
          ?>
          <form action="?action=add-bets&amp;ufc_event_id=<?php echo $ufc_event_id ?>&amp;title=<?php echo $event_title ?>" method="POST" id="add-bets">
            <div class="row">
              <!-- <div class="col-md-4"> -->
                <!-- nothing -->
              <!-- </div> -->

              <div class="col-md-12 bet-fights">
                <?php foreach ($fights_order as $fight) { ?>
                  <div class="row">
                    <div class="col-md-12 bet-fight-title bet__header">
                      <?php echo $fight->weight_class ?>
                    </div>
                  </div>
                  <input type="hidden" name="<?php echo $fight->id; ?>" id="<?php echo $fight->id; ?>" value="<?php $betValue = 0;foreach($bets as $bet){if ($bet->fight_id == $fight->id){if ($bet->fighter_selected == $fight->fighter1){$betValue = 'fighter1';}else{$betValue = 'fighter2';}}}echo $betValue;?>">
                  <div class="row bet__padding-bottom">
                    <div class="col-xs-5 fight-<?php echo $fight->id ?> bet-fighter fighter-1<?php if ($betValue === 'fighter1'){echo ' bet-fighter-selected';}?>" id="<?php echo $fight->id ?>-fighter1">
                      <?php foreach ($ufc_event as $event_fight): ?>
                        <?php if ($fight->ufc_fight_id == $event_fight->id): ?>
                          <img src="<?php echo $event_fight->fighter1_profile_image; ?>" class="img-responsive center-stuff" />
                        <?php endif; ?>
                      <?php endforeach; ?>
                      <p class="bet__fighter-names">
                        <?php echo $fight->fighter1; ?>
                      </p>
                    </div>
                    <div class="col-xs-2">

                    </div>
                    <div class="col-xs-5 fight-<?php echo $fight->id ?> bet-fighter fighter-2<?php if ($betValue === 'fighter2'){echo ' bet-fighter-selected';}?>" id="<?php echo $fight->id ?>-fighter2">
                      <?php foreach ($ufc_event as $event_fight): ?>
                        <?php if ($fight->ufc_fight_id == $event_fight->id): ?>
                          <img src="<?php echo $event_fight->fighter2_profile_image; ?>" class="img-responsive center-stuff" />
                        <?php endif; ?>
                      <?php endforeach; ?>
                      <p class="bet__fighter-names">
                        <?php echo $fight->fighter2; ?>
                      </p>
                    </div>
                  </div>





                <?php } ?>
              </div>
              <!-- <div class="col-md-4"> -->
                <!-- nothing -->
              <!-- </div> -->
            </div>
            <div class="row">
              <?php if ($fights_order) : ?>
                <div class="col-md-12 bet-fights-submit">
                  <input type="submit" name="submit" id="add-bets-submit" value="Confirm Bets" />
                </div>
              <?php else: ?>
                <div class="col-md-12">
                  <div class="bet-locked">
                    There are currently <span style="font-weight: bold;">no fights</span> to select.
                  </div>
                </div>
              <?php endif ?>
            </div>
          </form>
          <?php } else { ?>
            <div class="row">
              <div class="col-md-4">
                <!-- nothing -->
              </div>
              <div class="col-md-4">
                <div class="bet-locked">
                  Betting for this event has been <span style="font-weight: bold;">locked.</span>
                </div>
              </div>
              <div class="col-md-4">
                <!-- nothing -->
              </div>
            </div>
          <?php } ?>
        <?php } else { ?>
            <div class="row">
              <div class="col-md-12">
                <div class="bet-login-text">
                  Please log in to select your picks.
                </div>
              </div>
            </div>
        <?php } ?>
        </div>
      </div>

<?php
    }
  } else { ?>
    <div class="ufc-event">
      <div class="row">
        <div class="col-md-12">
          <div id="event-title">
            Error
          </div>
          <div id="event-tagline">
            There has been an error.
          </div>
        </div>
      </div>
    </div>
    <div id="ufc-bets">
      <div class="row">
        <div class="col-md-12">
          <div class="bet-locked">
            This event doesn't exist.
          </div>
        </div>
      </div>
    </div>
<?php } ?>


<?php get_footer(); ?>
