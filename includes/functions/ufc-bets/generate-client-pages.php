<?php
/*
 * Custom UFC fantasy
 *
 * auto generate front pages for the user if they don't already exist
 */

if (isset($_GET['activated']) && is_admin()){
  createNewPage('Betting', 'page-betting.php');
  createNewPage('Event Leaderboard', 'page-eventleaderboard.php');
  createNewPage('Leaderboard', 'page-leaderboard.php');
}

function createNewPage($new_page_title, $new_page_template) {
  $page_check = get_page_by_title($new_page_title);
  $new_page = array(
          'post_type'     => 'page',
          'post_title'    => $new_page_title,
          'post_status'   => 'publish',
          'post_author'   => 1,
  );
  if(!isset($page_check->ID)){
    $new_page_id = wp_insert_post($new_page);
    if(!empty($new_page_template)){
      update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
    }
  }
}

?>
