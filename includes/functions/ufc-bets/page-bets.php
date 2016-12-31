<?php
/*
 * Custom UFC betting plugin
 *
 * 6) add bets pages
 */

// bets page =================================
function ufcBet_bets() {

  //Create an instance of our package class...
  $testListTable = new ufc_bets_display_table();
  //Fetch, prepare, sort, and filter our data...
  $testListTable->prepare_items();

?>
 	<div class="wrap">
 		<h1>UFC Bet Management - Bets</h1>
      <p>
        <?php $testListTable->display() ?>
      </p>
 	</div>
<?php }

?>
