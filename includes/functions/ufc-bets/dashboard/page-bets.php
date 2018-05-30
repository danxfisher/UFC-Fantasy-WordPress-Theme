<?php
/*
 * Custom UFC betting plugin
 *
 * 6) add bets pages
 */

// bets page =================================
function ufcBet_bets() {

  //Create an instance of our package class...
  $adminBetsTable = new ufc_bets_display_table();
  //Fetch, prepare, sort, and filter our data...
  $adminBetsTable->prepare_items();

?>
 	<div class="wrap">
 		<h1>UFC Bet Management - Bets</h1>
      <p>
        <?php $adminBetsTable->display() ?>
      </p>
 	</div>
<?php }

?>
