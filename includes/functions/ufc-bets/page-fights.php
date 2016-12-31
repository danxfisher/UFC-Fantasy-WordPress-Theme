<?php
/*
 * Custom UFC betting plugin
 *
 * 5) add fights pages
 */

// fights pages ==============================
function ufcBet_fights() { ?>
 	<div class="wrap">
    <?php if( isset( $_GET['action'] ) ) : ?>
      <?php if ($_GET['action'] === 'edit') { ?>
        <h1>Edit Fight <a href="admin.php?page=ufcBet-add-fight" class="page-title-action">Add Fight</a></h1>
        <p>
          {edit-fight-form}
        </p>
      <?php } elseif ($_GET['action'] === 'delete') { ?>
        <h1>UFC Bet Management - Fights <a href="admin.php?page=ufcBet-add-fight" class="page-title-action">Add Fight</a></h1>
        <p>
           Delete item, show delete notification, then show table with updates
        </p>
      <?php } ?>

    <?php else : ?>
      <h1>UFC Bet Management - Fights <a href="admin.php?page=ufcBet-add-fight" class="page-title-action">Add Fight</a></h1>
      <p>
        {fights-table}
      </p>
      <p>
        <a href="admin.php?page=ufcBet-fights&amp;action=edit" class="page-title-action">Edit Fight</a>
      </p>
      <p>
        <a href="admin.php?page=ufcBet-fights&amp;action=delete" class="page-title-action">Delete Fight</a>
      </p>
    <?php endif; ?>

 	</div>
<?php }

function ufcBet_add_fight() { ?>
 	<div class="wrap">
 		<h1>UFC Bet Management - Add Fight</h1>
      <p>
        {add-fight-form}
      </p>
 	</div>
<?php }

?>
