<?php
/*
 * main page
 */

	include('includes/php/ufc-api.php');

	get_header();

?>

<section id="splash">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="header">
					UFC
				</div>
			</div>
		</div>
	</div>
</section>

<?php $events = UfcAPI::getAllEvents() ?>

<?php date_default_timezone_set('America/Los_Angeles'); ?>

<?php $thedate = date('Y-m-d'); ?>

<section id="s__events">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Upcoming Events</h2>
				<div class="row event-list">

				<?php $today = date('Y-m-d'); ?>
				<?php $args = array(
								'post_type'		=> 'post',
								'meta_query'	=>  array (
																		array(
															        'key'		=> 'event_start_date',
															        'compare'	=> '>=',
															        'value'		=> $today,
																    )
																	),
							);
				?>

				<?php $the_query = new WP_Query( $args ); ?>

				<?php if ( $the_query->have_posts() ) : ?>
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

						<?php $event_id = get_the_title(); ?>

						<?php foreach($events as $obj): ?>
							<?php if ($obj->id == $event_id): ?>

								<?php // calculations to see if event is 'live' or aka the current time is less than or equal to 3 hours from start ?>
								<?php $ue_event_time = $obj->event_time_text ?>
								<?php $ue_event_time = explode('/', $ue_event_time); ?>
								<?php $ue_event_start_time = date('H', strtotime($ue_event_time[1])); ?>

								<?php if (($thedate == get_field('event_start_date') && date('H') < $ue_event_start_time) || $thedate != get_field('event_start_date')) : ?>

									<div class="col-md-4 events__item">
										<a href="<?php echo get_the_permalink(); ?>">

											<div class="col-sm-12 events__thumbnail-image" style="background-image:url('<?php echo $obj->feature_image ?>'); background-color: rgba(0,0,0,0.5);">
												<div class="events__title-wrap">
													<div class="events__title">
														<?php the_field('event_title'); ?>
													</div>
												</div>
											</div>

											<div class="col-sm-12 events__date-wrap">
												<div class="events__tagline">
													<?php echo $obj->title_tag_line ?>
												</div>
												<div class="events__date">
													<?php echo date('F j, Y', strtotime(get_field('event_start_date'))); ?>
												</div>
											</div>

										</a>
									</div>

								<?php endif; ?>
							<?php endif; ?>
						<?php endforeach; ?>

					<?php endwhile; ?>
				<?php else : ?>
					<div class="col-md-12 events__no-event">
						No upcoming events.
					</div>
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>

<section id="s__results">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Results</h2>
				<div class="row event-list">

				<?php wp_reset_query(); ?>
				<?php wp_reset_postdata(); ?>

				<?php $args_results = array(
								'post_type'		=> 'post',
								'posts_per_page'	=>	'3',
								'meta_query'	=>  array (
																		array(
															        'key'		=> 'event_start_date',
															        'compare'	=> '<=',
															        'value'		=> $thedate,
																    )
																	),
							);
				?>

				<?php $the_results_query = new WP_Query( $args_results ); ?>

				<?php if ( $the_results_query->have_posts() ) : ?>
					<?php while ( $the_results_query->have_posts() ) : $the_results_query->the_post(); ?>

						<?php $event_id = get_the_title(); ?>

						<?php foreach($events as $obj): ?>
							<?php if ($obj->id == $event_id): ?>
								<div class="col-md-4 events__item">
									<a href="<?php echo get_the_permalink(); ?>">

										<div class="col-sm-12 events__thumbnail-image" style="background-image:url('<?php echo $obj->feature_image ?>'); background-color: rgba(0,0,0,0.5);">
											<div class="events__title-wrap">
												<div class="events__title">
													<?php the_field('event_title'); ?>
												</div>
											</div>
										</div>

										<?php // calculations to see if event is 'live' or aka the current time is less than or equal to 3 hours from start ?>
										<?php $r_event_time = $obj->event_time_text ?>
										<?php $r_event_time = explode('/', $r_event_time); ?>
										<?php $r_event_start_time = date('H', strtotime($r_event_time[1])); ?>
										<?php $r_event_end_time = date('H', strtotime('+2 hours', strtotime($r_event_time[1]))); ?>

										<?php if (($thedate == get_field('event_start_date')) && date('H') >= $r_event_start_time && date('H') <= $r_event_end_time) : ?>
											<div class="col-sm-12 results__live">
												Live
											</div>
										<?php endif; ?>

										<div class="col-sm-12 events__date-wrap">
											<div class="events__tagline">
												<?php echo $obj->title_tag_line ?>
											</div>
											<div class="events__date">
												<?php echo date('F j, Y', strtotime(get_field('event_start_date'))); ?>
											</div>
										</div>

									</a>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>

					<?php endwhile; ?>
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>

<section id="news-events">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>News</h2>
				<?php	$news = UfcAPI::getNewsArticles(); ?>
				<?php // most recent 5 news articles ?>
				<?php $articles = array_slice($news, 0, 6);	?>
					<div class="row">
					<?php	$article_count = 0; ?>
					<?php foreach ($articles as $article): ?>
						<?php $article_count++; ?>
						<?php if ($article_count % 2 == 1): ?>
							<div class="row">
						<?php	endif; ?>
								<div class="col-md-6 news__item">
									<?php $url = $article->thumbnail; ?>
									<?php if ($article->thumbnail != '') : ?>
										<?php $url = preg_replace('/\?.*/', '', $url); ?>
									<?php else: ?>
										<?php $url = get_template_directory_uri().'/includes/img/cage-dark.jpg'; ?>
									<?php endif; ?>
									<div class="col-sm-12 news__thumbnail-image" style="background-image:url('<?php echo $url ?>');">
									</div>
									<div class="col-sm-12 news__news-title">
										<a href="http://ufc.com/news/<?php echo $article->url_name ?>"><?php echo $article->title ?></a>
									</div>
								</div>
						<?php if ($article_count % 2 == 0) : ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					</div>
			</div>

		</div>
	</div>
</section>



<?php get_footer(); ?>
