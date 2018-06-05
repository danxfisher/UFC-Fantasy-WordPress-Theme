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

    public function calculateEventLeaderboard(){}

    public function calculateOverallLeaderboard(){}
  }

?>
