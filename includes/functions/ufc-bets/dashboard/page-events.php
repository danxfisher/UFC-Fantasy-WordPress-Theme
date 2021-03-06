<?php
/*
 * Custom UFC betting plugin
 *
 * 4) add events pages
 */

// manage events page ==========================================================
function ufcBet_events() {
  // declaration of message variables
  $add_fights_success = false;
  $add_fights_success_message = '';
  $delete_event_success = false;
  $delete_event_success_message = '';
  $add_event_error = false;
  $add_event_error_message = '';
  $add_event_success = false;
  $add_event_success_message = '';

  // add event (events page) ===================================================
  if ( isset( $_POST['submit'] ) && ($_GET['action'] == 'add-event') && ($_REQUEST['submit'] == "Add Event") ){
    $events = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
    $events = json_decode($events);
    $events_asc = array_reverse($events);

    global $wpdb;

    $events_table = $wpdb->prefix . 'ufcBet_events';

    $event_id = $_POST['dd_ufc-event'];
    $locktime = $_POST['dp_locktime'];
    $event_name = '';
    $event_time = '';
    $value_event_title = '';

    // iterate our events API json object
    foreach($events_asc as $obj){
      if ($obj->id == $event_id) {
        $event_time = $obj->event_date;
        $the_date = date('F j, Y', strtotime($event_time));
        $event_name = $obj->base_title . ' - ' . $the_date;

        // set values to store in ACF custom fields
        $value_event_title = $obj->base_title;
        $value_event_url = $obj->url_name;
      }
    }

    $exists = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $events_table WHERE name = %s", $event_name) );

    // add to events if it doesn't already exist
    if ( ! $exists ) {
      $data = array(
        'time'      => $event_time,
        'event_id'  => $event_id,
        'name'      => $event_name,
        'lock_time' => $locktime
      );

      $wpdb->insert($events_table, $data);

      // begin fights table stuff =========================
      $fights_table = $wpdb->prefix . 'ufcBet_fights';
      $fights = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights');
      $fights = json_decode($fights);

      $event_table_id = $wpdb->get_var( $wpdb->prepare("SELECT id FROM $events_table WHERE event_id = %s", $event_id) );

      foreach($fights as $fight) {
        $fighter1name = $fight->fighter1_first_name . ' ' . $fight->fighter1_last_name;
        $fighter2name = $fight->fighter2_first_name . ' ' . $fight->fighter2_last_name;
        $weightclass = '';
        if (!empty($fight->fighter1_weight_class)){
          $weightclass = (str_replace("_", " ", $fight->fighter1_weight_class));
        }

        $ufc_fight_id = $fight->id;

        $data = array(
          'event_id'        => $event_table_id,
          'ufc_event_id'    => $event_id,
          'ufc_fight_id'    => $ufc_fight_id,
          'fighter1'        => $fighter1name,
          'fighter2'        => $fighter2name,
          'weight_class'    => $weightclass,
        );

        if ($fight->is_title_fight){
          $data['is_title_fight'] = 1;
        }

        $wpdb->insert($fights_table, $data);
      }
      // ************************************************************
      // GO TO SCREEN TO ENABLE FIGHTS AFTER CREATING EVENT
      // end fights table stuff ===========================
      // ************************************************************

      // begin auto generated post *********************************************
      $post_name = $event_id . '-' . sanitize_title($value_event_title);

      $new_event = array (
          'post_title'  =>  $event_id,
          'post_name'   =>  $post_name,
          'post_status' =>  'publish',
          'post_type'   =>  'post'
      );

      // create new post for event
      $post_id = wp_insert_post($new_event);

      $dt = new DateTime($event_time);
      $ufc_event_date = $dt->format('Y-m-d');

      // ACF keys for custom fields to store event data
      // THIS IS DEV
      $key_event_start_date = 'field_58d22e612f794';
      $key_event_title = 'field_58d22ef658473';
      $key_event_url = 'field_58d34ad4e7227';

      // update ACF fields with UFC API data in post
			update_field( $key_event_start_date, $ufc_event_date, $post_id );
      update_field( $key_event_title, $value_event_title, $post_id );
      update_field( $key_event_url, $value_event_url, $post_id );

      // end auto generated post ***********************************************

      $add_event_success = true;
      $add_event_success_message = 'The event has been added.';
    }
    else {
      $add_event_error = true;
      $add_event_error_message = 'Error: The event was not added because it already exists.';
    }
  }
  ?>
  <?php // edit event (fights page) ================================================== ?>
  <?php if (isset($_GET['action']) && $_GET['action'] == 'edit'): ?>
    <?php global $wpdb; ?>
    <?php $fights_table = $wpdb->prefix . 'ufcBet_fights'; ?>
    <?php $event_id = $_GET['id']; ?>
    <?php $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE event_id = %s", $event_id)); ?>

    <div class="wrap">
      <h1>UFC Bet Management - Edit Fights</h1>
      <form action="admin.php?page=ufcBet-events&amp;action=add-fights&amp;event_id=<?php echo $event_id ?>" method="POST" id="add-event">
        <table class="wp-list-table widefat fixed striped events">
          <thead>
            <tr>
              <td id="active" class="manage-column column-cb" style="width: 155px; font-weight: bold;">
                Active
              </th>
              <th scope="col" id="fight" class="manage-column" style="font-weight: bold;">
                Fight
              </th>
            </tr>
            <tbody id="the-list">
              <?php foreach ($fights as $fight){ ?>
                <tr>
                  <th scope="row" class="check-column">
                    <input type="radio" name="<?php echo $fight->id; ?>" value="1" <?php if($fight->is_betting_enabled == 1){ ?>checked<?php } ?>> Yes
                    <input type="radio" name="<?php echo $fight->id; ?>" value="0" <?php if($fight->is_betting_enabled == 0){ ?>checked<?php } ?>> No
                  </td>
                  <td>
                    <?php echo $fight->fighter1 ?> vs <?php echo $fight->fighter2 ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </thead>
        </table>
        <p>
          <input type="submit" name="submit" id="add-fights-submit" value="Update Fights" />
          <input type="submit" name="cancel" id="add-fights-cancel" value="Cancel" />
        </p>
      </form>
    </div>

  <?php else: ?>
  <?php
    // deletion (events page) ==================================================
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
      global $wpdb;
      $fights_table = $wpdb->prefix . 'ufcBet_fights';
      $events_table = $wpdb->prefix . 'ufcBet_events';
      $bets_table = $wpdb->prefix . 'ufcBet_bets';
      $event_id = $_GET['id'];

      // on deletion of event, delete from events, bets, and fights table
      $ufc_event_id = $wpdb->get_var( $wpdb->prepare("SELECT event_id FROM $events_table WHERE id = %s", $event_id) );

      // delete all bets pertaining to event
      $wpdb->query("DELETE FROM $bets_table WHERE ufc_event_id IN($ufc_event_id)");
      // delete all fights pertaining to event
      $wpdb->query("DELETE FROM $fights_table WHERE event_id IN($event_id)");
      // delete all events pertaining to event
      $wpdb->query("DELETE FROM $events_table WHERE id IN($event_id)");
      // get post object by title, delete post by id
      $page = get_page_by_title($ufc_event_id, OBJECT, 'post');
      wp_delete_post($page->ID, true);

      $delete_event_success = true;
      $delete_event_success_message = "Event has been deleted.";

    }

    // handle add/remove fights submission (fights page) =======================
    if ( isset( $_POST['submit'] ) && ($_GET['action'] == 'add-fights') && $_REQUEST['submit'] == "Update Fights" ) {
      global $wpdb;
      $event_id = $_GET['event_id'];
      $fights_table = $wpdb->prefix . 'ufcBet_fights';

      $fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE event_id = %s", $event_id));

      foreach ($fights as $fight){
        if ($_POST[$fight->id] == 1) {
          // change is_betting_enabled to 1
          $wpdb->update($fights_table, array('is_betting_enabled' => '1'), array( 'id' => $fight->id ), array('%d'), array( '%d' ));
        }
        else {
          // change is_betting_enabled to 0
          $wpdb->update($fights_table, array('is_betting_enabled' => '0'), array( 'id' => $fight->id ), array('%d'), array( '%d' ));
        }
      }

      $add_fights_success = true;
      $add_fights_success_message = "The fights have been updated.";
    }

    //Create an instance of our package class...
    $testListTable = new ufc_events_display_table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    ?>
     	<div class="wrap">
     		<h1>UFC Bet Management - Events <a href="admin.php?page=ufcBet-add-event" class="page-title-action">Add Event</a></h1>
          <?php if ($add_fights_success) : ?>
            <div class="notice notice-success is-dismissible">
              <p>
                <?php echo $add_fights_success_message; ?>
              </p>
            </div>
          <?php endif; ?>
          <?php if ($delete_event_success) : ?>
            <div class="notice notice-warning is-dismissible">
              <p>
                <?php echo $delete_event_success_message; ?>
              </p>
            </div>
          <?php endif; ?>
          <?php if ($add_event_error) : ?>
            <div class="notice notice-error is-dismissible">
              <p>
                <?php echo $add_event_error_message; ?>
              </p>
            </div>
          <?php endif; ?>
          <?php if ($add_event_success) : ?>
            <div class="notice notice-success is-dismissible">
              <p>
                <?php echo $add_event_success_message; ?>
              </p>
            </div>
          <?php endif; ?>
          <p>
            <?php $testListTable->display() ?>
          </p>
     	</div>
  <?php endif ?>

<?php }
// add event page ==============================================================
function ufcBet_add_event() {
  $events = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
  $events = json_decode($events);
  $events_asc = array_reverse($events);
?>
 	<div class="wrap">
 		<h1>UFC Bet Management - Add Event</h1>
    <div class="postbox">
      <h2 class="hndle" style="cursor: auto; margin: 0; padding: 8px 15px;"><span>Add Event</span></h2>
      <div class="inside">
        <form action="admin.php?page=ufcBet-events&amp;action=add-event" method="POST" id="add-event">
          <p>
            <span style="font-weight: bold">Event:</span> <br />
            <select id="dd_ufc-event" name="dd_ufc-event" required>
              <?php

              foreach($events_asc as $obj){
                $todays_date = new DateTime();
                $todays_date->setTimezone(new DateTimeZone('America/Los_Angeles'));
                $todays_date = $todays_date->format('m-d-Y');
                $event_date = new DateTime($obj->event_date);
                $event_date = $event_date->format('m-d-Y');

                //if (date(DATE_ATOM) <= $obj->event_date || $todays_date == $event_date) {
                  $the_date = date('F j, Y', strtotime($obj->event_date));
                  echo "<option value='" . $obj->id . "'>" . $obj->base_title . ' - ' . $the_date . '</option>';
                //}
              }
              ?>
            </select>
          </p>
          <p>
            <span style="font-weight: bold">Lock Time:</span> <br />
            <input type="datetime-local" name="dp_locktime" id="dp_locktime" placeholder="2017-12-31 20:00:00" required />
            <br />
            <span style="font-style: italic;">Ex: 2017-12-31 20:00:00</span>
          </p>
          <p>
            <input type="submit" name="submit" id="add-event-submit" value="Add Event" />
            <input type="submit" name="cancel" id="add-event-cancel" value="Cancel" formnovalidate />
          </p>
        </form>
      </div>
    </div>
	</div>
<?php }

?>
