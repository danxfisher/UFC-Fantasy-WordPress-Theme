<?php
/*
 * single post page
 */

get_header(); ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php //the_title(); ?>
			<?php the_content(); ?>
			<?php
			// get list of events
			$events = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
			$events = json_decode($events);

			$feature_image = '';
			$fight_url = '';

			$event_id = 0;
			?>

			<div class="back-button">
				<a href="<?php echo home_url(); ?>">Home</a>
			</div>

			<?php
			// find event id for event = post name
			// get fight info and make top splash
			foreach($events as $obj){
				if ($obj->base_title == get_the_title()) {
					$feature_image = $obj->feature_image;
					$event_date = date('F j, Y', strtotime($obj->event_date));
					$fight_url = $obj->url_name;
					$event_id = $obj->id;
					echo '<div class="ufc-event">';
					echo '<div class="row">';
					echo '<div class="col-md-12">';

					echo '<div id="event-title">';
					echo $obj->base_title;
					echo '</div>';
					echo '<div id="event-tagline">';
					echo $obj->title_tag_line;
					echo '</div>';

					echo '</div>';
					echo '</div>';

					echo '<div class="row">';
					echo '<div class="col-md-3">';
					echo '</div>';
					echo '<div class="col-md-3">';

					echo '<div class="event-when">';
					echo '<div id="event-date">';
					echo $event_date;
					echo '</div>';
					echo '<div id="event-time">';
					echo $obj->event_time_text;
					echo '</div>';
					echo '</div>';

					echo '</div>';

					echo '<div class="col-md-3">';

					echo '<div class="event-where">';
					echo '<div id="event-arena">';
					echo $obj->arena;
					echo '</div>';
					echo '<div id="event-location">';
					echo $obj->location;
					echo '</div>';
					echo '</div>';

					echo '<div class="col-md-3">';
					echo '</div>';

					echo '</div>';
					echo '</div>';

					echo '</div>';

					echo '<div id="event-trailer">';
					echo '<div class="container">';
					echo '<div class="row">';
					echo '<div class="col-md-12">';
					echo '<video controls>';
					echo '<source src="' . $obj->trailer_url . '" type="video/mp4">';
					echo '</video>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
			}

			// get fights for event
			$ufc_event_url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights';
			$ufc_event = file_get_contents($ufc_event_url);
			$ufc_event = json_decode($ufc_event);

			$main_card = array_slice($ufc_event, 0, 6);
			$main_card_order = array_reverse($main_card);

			$fight_count = 1;
			// loop through each fight
			foreach ($main_card_order as $obj) {
				if (!($obj->is_prelim)) {
				echo '<div id="fight-' . $fight_count . '">';
				echo '<div class="row">';
				echo '<div class="col-md-5 fight-title">';
				echo '<div class="fight-title-text">';
				if ($obj->is_title_fight) {
					echo '<div class="championship-fight">';
					echo $obj->fight_description . ' fight';
					echo '</div>';
				}
				echo $obj->fighter1_first_name . ' ' . $obj->fighter1_last_name . ' ';
				if (isset($obj->fighter1_rank)) {
					echo '<sup';
					if ($obj->fighter1_rank == 'C') {
						echo ' class="champ"';
					}
					echo '>';
					echo $obj->fighter1_rank;
					echo '</sup>';
				}
				echo '<br />';
				echo 'vs';
				echo '<br />';
				echo $obj->fighter2_first_name . ' ' . $obj->fighter2_last_name . ' ';
				if (isset($obj->fighter2_rank)) {
					echo '<sup';
					if ($obj->fighter2_rank == 'C') {
						echo ' class="champ"';
					}
					echo '>';
					echo $obj->fighter2_rank;
					echo '</sup>';
				}
				echo '</div>';
				echo '</div>';
				echo '<div class="col-md-7">';
				echo '<div class="row">';
				echo '<div class="col-md-3" style="text-align: right;">';
				echo '<img src="' . $obj->fighter1_full_body_image . '" class="hidden-xs" />';
				echo '</div>';
				echo '<div class="col-md-6">';
				echo '<table class="fighter-names">';
				echo '<tr>';
				echo '<td width="50%">';
				echo $obj->fighter1_first_name . ' ' . $obj->fighter1_last_name;
				echo '</td>';
				echo '<td width="50%">';
				echo $obj->fighter2_first_name . ' ' . $obj->fighter2_last_name;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="nickname">';
				if ($obj->fighter1_nickname != ""){
					echo '"' . $obj->fighter1_nickname . '"';
				}
				echo '</td>';
				echo '<td class="nickname">';
				if ($obj->fighter2_nickname != ""){
					echo '"' . $obj->fighter2_nickname . '"';
				}
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '<table class="fight-tale">';
				echo '<tr>';
				echo '<td class="left-column" width="20%">';
				echo $obj->fighter1record;
				echo '</td>';
				echo '<td class="center-column" width="60%">';
				echo 'Record';
				echo '</td>';
				echo '<td class="right-column" width="20%">';
				echo $obj->fighter2record;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				$feet = $obj->fighter1height / 12;
				$f1_feet = (int)$feet;
				$f1_inches = round(($feet - $f1_feet)*12);
				echo $f1_feet . "'" . $f1_inches . '"';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Height';
				echo '</td>';
				echo '<td class="right-column">';
				$feet = $obj->fighter2height / 12;
				$f2_feet = (int)$feet;
				$f2_inches = round(($feet - $f2_feet)*12);
				echo $f2_feet . "'" . $f2_inches . '"';
				//echo $obj->fighter2height . '"';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1weight . ' lbs';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Weight';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2weight . ' lbs';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1reach . '"';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Reach';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2reach . '"';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_averagefighttime;
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Average Fight Time';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_averagefighttime;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_kdaverage;
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Knockdown Average';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_kdaverage;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_slpm;
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Strikes Landed P/M';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_slpm;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_strikingaccuracy . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Striking Accuracy';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_strikingaccuracy . '%';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_sapm;
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Strikes Absorbed P/M';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_sapm;
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_strikingdefense . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Striking Defense';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_strikingdefense . '%';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_takedownaverage . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Takedown Average';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_takedownaverage . '%';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_takedownaccuracy . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Takedown Accuracy';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_takedownaccuracy . '%';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_takedowndefense . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Takedown Defense';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_takedowndefense . '%';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="left-column">';
				echo $obj->fighter1_submissionsaverage . '%';
				echo '</td>';
				echo '<td class="center-column">';
				echo 'Submission Average';
				echo '</td>';
				echo '<td class="right-column">';
				echo $obj->fighter2_submissionsaverage . '%';
				echo '</td>';
				echo '</tr>';
				echo '</table>';
				echo '</div>';
				echo '<div class="col-md-3">';
				echo '<img src="' . $obj->fighter2_full_body_image . '" class="hidden-xs" />';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

				$fight_count++;
				}
			}
			?>
			<div id="ufc-link">
				<div class="row">
					<div class="col-md-12">
						For additional event information and fighter statistics, visit the <a href="http://ufc.com/event/<?php echo $fight_url ?>" target="_blank">UFC event website</a>.
					</div>
				</div>
			</div>

		<?php endwhile; ?>
<?php get_footer(); ?>
