<?php
/*
 * single post page
 */

	include('includes/php/ufc-api.php');

	get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php //the_title(); ?>
	<?php the_content(); ?>
	<?php
	// get list of events
	// $events = file_get_contents('http://ufc-data-api.ufc.com/api/v3/events');
	// $events = json_decode($events);

	$events = UfcAPI::getAllEvents();

	$feature_image = '';
	$fight_url = '';

	$event_id = 0;

	//$post_start_event = get_field("event_start_date", get_the_id());
	$post_start_event = get_field("event_start_date");
	$post_event_name = get_field("event_title");

	foreach($events as $obj){
		$event_date_time = $obj->event_date;
		$format_date = new DateTime($event_date_time);
		$simple_event_date = $format_date->format('Y-m-d');

		if (($obj->base_title == $post_event_name) && ($simple_event_date == $post_start_event)) {
			$feature_image = $obj->feature_image;
			$event_date = date('F j, Y', strtotime($obj->event_date));
			$fight_url = $obj->url_name;
			$event_id = $obj->id;
			?>
		<div class="ufc-event">
			<div class="row">
				<div class="col-md-12">
					<div id="event-title">
						<?php echo $obj->base_title; ?>
					</div>
					<div id="event-tagline">
						<?php echo $obj->title_tag_line; ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
				</div>
				<div class="col-md-3">
					<div class="event-when">
						<div id="event-date">
							<?php echo $event_date; ?>
						</div>
						<div id="event-time">
							<?php echo $obj->event_time_text; ?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="event-where">
						<div id="event-arena">
							<?php echo $obj->arena; ?>
						</div>
						<div id="event-location">
							<?php echo $obj->location; ?>
						</div>
					</div>
					<div class="col-md-3">
					</div>
				</div>
			</div>
			<?php if (is_user_logged_in()) { ?>
			<div class="row">
				<div class="col-md-3">

				</div>
				<div class="col-md-3 make-your-selections">
					<a href="<?php echo esc_url( add_query_arg( array('ufc_event_id' => $event_id, 'title' => $post_event_name,), site_url( '/betting/' ) ) )?>">
					<!-- <a href="betting?ufc_event_id=<?php echo $event_id ?>&amp;title=<?php echo get_the_title(); ?>"> -->
						<div class="selections-button">
							Make your selections
						</div>
					</a>
				</div>
				<div class="col-md-3 event-ldr">
					<a href="event-leaderboard?ufc_event_id=<?php echo $event_id ?>&amp;title=<?php echo $post_event_name; ?>">
						<div class="ldr-button">
							View the event leaderboard
						</div>
					</a>
				</div>
				<div class="col-md-3">

				</div>
			</div>
			<?php } ?>
		</div>
		<?php if ($obj->trailer_url != null) { ?>
		<div id="event-trailer">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<video controls>
							<source src="<?php echo $obj->trailer_url ?>" type="video/mp4">
						</video>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	<?php
		}
	}

			// get fights for event
			// $ufc_event_url = 'http://ufc-data-api.ufc.com/api/v3/events/' . $event_id . '/fights';
			// $ufc_event = file_get_contents($ufc_event_url);
			// $ufc_event = json_decode($ufc_event);
			$ufc_event = UfcAPI::getEventByID($event_id);

			// get fights
			// ***************************************************
			// use this instead of array_slice
			// ***************************************************
			$fights_table = $wpdb->prefix . 'ufcBet_fights';
			$fights = $wpdb->get_results($wpdb->prepare("SELECT * FROM $fights_table WHERE ufc_event_id = %s AND is_betting_enabled = 1", $event_id));

			// ***************************************************
			// use fights table instead of this
			// ***************************************************
			//$main_card = array_slice($ufc_event, 0, 6);
			//$main_card_order = array_reverse($main_card);
			$main_card_order = array_reverse($ufc_event);

			// loop through each fight
			foreach ($main_card_order as $obj) {

				$is_betting_enabled = $wpdb->get_var($wpdb->prepare("SELECT is_betting_enabled FROM $fights_table WHERE ufc_event_id = %s AND ufc_fight_id = %s", $event_id, $obj->id));
				if ($is_betting_enabled > 0) {
			?>
					<div class="fighter-background">
						<div class="row">
							<div class="col-md-5 fighter-title">
								<div class="fighter-title-text">
							<?php if ($obj->is_title_fight) { ?>
									<div class="championship-fight">
										<?php echo $obj->fight_description; ?>
									</div>
							<?php }
							echo $obj->fighter1_first_name . ' ' . $obj->fighter1_last_name . ' ';
							if (isset($obj->fighter1_rank)) { ?>
								<sup<?php if ($obj->fighter1_rank == 'C') {	echo ' class="champ"';} ?>>
									<?php echo $obj->fighter1_rank; ?>
								</sup>
							<?php
							}
							?>
							<br />
							vs
							<br />
							<?php echo $obj->fighter2_first_name . ' ' . $obj->fighter2_last_name . ' ';
							if (isset($obj->fighter2_rank)) { ?>
								<sup<?php if ($obj->fighter2_rank == 'C') {	echo ' class="champ"';} ?>>
									<?php echo $obj->fighter2_rank; ?>
								</sup>
							<?php
							}
							?>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
						<div class="col-md-3" style="text-align: right;">
							<img src="<?php echo $obj->fighter1_full_body_image; ?>" class="hidden-sm hidden-xs" />
						</div>
						<div class="col-md-6">
							<table class="fighter-names">
								<tr>
									<td width="50%">
										<?php echo $obj->fighter1_first_name . ' ' . $obj->fighter1_last_name; ?>
									</td>
									<td width="50%">
										<?php echo $obj->fighter2_first_name . ' ' . $obj->fighter2_last_name; ?>
									</td>
								</tr>
								<tr>
									<td class="nickname">
									<?php
										if ($obj->fighter1_nickname != ""){
											echo '"' . $obj->fighter1_nickname . '"';
										}
									?>
									</td>
									<td class="nickname">
									<?php
										if ($obj->fighter2_nickname != ""){
											echo '"' . $obj->fighter2_nickname . '"';
										}
									?>
									</td>
								</tr>
							</table>
							<table class="fighter-tale">
								<tr>
									<td class="left-column" width="20%">
										<?php echo $obj->fighter1record; ?>
									</td>
									<td class="center-column" width="60%">
										Record
									</td>
									<td class="right-column" width="20%">
										<?php echo $obj->fighter2record; ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php
										$feet = $obj->fighter1height / 12;
										$f1_feet = (int)$feet;
										$f1_inches = round(($feet - $f1_feet)*12);
										echo $f1_feet . "'" . $f1_inches . '"';
										?>
									</td>
									<td class="center-column">
										Height
									</td>
									<td class="right-column">
										<?php
										$feet = $obj->fighter2height / 12;
										$f2_feet = (int)$feet;
										$f2_inches = round(($feet - $f2_feet)*12);
										echo $f2_feet . "'" . $f2_inches . '"';
										?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php echo $obj->fighter1weight . ' lbs'; ?>
									</td>
									<td class="center-column">
										Weight
									</td>
									<td class="right-column">
										<?php echo $obj->fighter2weight . ' lbs'; ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1reach)){ echo $obj->fighter1reach . '"'; } ?>
									</td>
									<td class="center-column">
										Reach
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2reach)){ echo $obj->fighter2reach . '"'; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_averagefighttime)){ echo $obj->fighter1_averagefighttime; } ?>
									</td>
									<td class="center-column">
										Average Fight Time
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_averagefighttime)){ echo $obj->fighter2_averagefighttime; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_kdaverage)){ echo $obj->fighter1_kdaverage; } ?>
									</td>
									<td class="center-column">
										Knockdown Average
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_kdaverage)){ echo $obj->fighter2_kdaverage; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_slpm)){ echo $obj->fighter1_slpm; } ?>
									</td>
									<td class="center-column">
										Strikes Landed P/M
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_slpm)){ echo $obj->fighter2_slpm; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_strikingaccuracy)){ echo $obj->fighter1_strikingaccuracy . '%'; } ?>
									</td>
									<td class="center-column">
										Striking Accuracy
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_strikingaccuracy)){ echo $obj->fighter2_strikingaccuracy . '%'; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_sapm)){ echo $obj->fighter1_sapm; } ?>
									</td>
									<td class="center-column">
										Strikes Absorbed P/M
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_sapm)){ echo $obj->fighter2_sapm; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_strikingdefense)){ echo $obj->fighter1_strikingdefense . '%'; } ?>
									</td>
									<td class="center-column">
										Striking Defense
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_strikingdefense)){ echo $obj->fighter2_strikingdefense . '%'; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_takedownaverage)){ echo $obj->fighter1_takedownaverage; } ?>
									</td>
									<td class="center-column">
										Takedown Average
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_takedownaverage)){ echo $obj->fighter2_takedownaverage; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_takedownaccuracy)){ echo $obj->fighter1_takedownaccuracy . '%'; } ?>
									</td>
									<td class="center-column">
										Takedown Accuracy
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_takedownaccuracy)){ echo $obj->fighter2_takedownaccuracy . '%'; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_takedowndefense)){ echo $obj->fighter1_takedowndefense . '%'; } ?>
									</td>
									<td class="center-column">
										Takedown Defense
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_takedowndefense)){ echo $obj->fighter2_takedowndefense . '%'; } ?>
									</td>
								</tr>
								<tr>
									<td class="left-column">
										<?php if(isset($obj->fighter1_submissionsaverage)){ echo $obj->fighter1_submissionsaverage; } ?>
									</td>
									<td class="center-column">
										Submission Average
									</td>
									<td class="right-column">
										<?php if(isset($obj->fighter2_submissionsaverage)){ echo $obj->fighter2_submissionsaverage; } ?>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<img src="<?php echo $obj->fighter2_full_body_image; ?>" class="hidden-sm hidden-xs" />
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
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
