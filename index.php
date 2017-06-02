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
							<div class="col-md-4 events__item">
								<a href="<?php echo get_the_permalink(); ?>">
									<?php foreach($events as $obj): ?>
							      <?php if ($obj->id == $event_id): ?>
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
									<?php endif; ?>
									<?php endforeach; ?>
								</a>
							</div>

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
