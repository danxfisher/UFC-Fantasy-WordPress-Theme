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
				<h2>Events</h2>
				<div class="row event-list">

				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php $event_id = get_the_title(); ?>
								<div class="col-md-4 events__item">
									<a href="<?php echo get_the_permalink(); ?>">
										<?php foreach($events as $obj){ ?>
								      <?php if ($obj->id == $event_id) { ?>
												<div class="col-sm-12 events__thumbnail-image" style="background-image:url('<?php echo $obj->feature_image ?>'); background-color: rgba(0,0,0,0.5);">
													<div class="test__event-title-wrap">
														<div class="test__event-title">
															<?php the_field('event_title'); ?>
														</div>
													</div>
												</div>
											<?php } } ?>
										<div class="col-sm-12 events__date-wrap">
											<div class="events__date">
												<?php echo date('F j, Y', strtotime(get_field('event_start_date'))); ?>
											</div>
										</div>
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
				<?php
					$news = UfcAPI::getNewsArticles();
					// most recent 5 news articles
					$articles = array_slice($news, 0, 6);
				?>
					<div class="row">

					<?php
						$article_count = 0;

						foreach ($articles as $article) {
							$article_count++;
							if ($article_count % 2 == 1) {
					?>
							<div class="row">
					<?php	} ?>
								<div class="col-md-6 news-item">
									<div class="col-sm-12 news__thumbnail-image" style="background-image:url('<?php echo $article->thumbnail ?>');">
									</div>
									<div class="col-sm-12 news__news-title">
										<a href="http://ufc.com/news/<?php echo $article->url_name ?>"><?php echo $article->title ?></a>
									</div>
								</div>
					<?php if ($article_count % 2 == 0) { ?>
							</div>
					<?php } }	?>
					</div>
			</div>

		</div>
	</div>
</section>



<?php get_footer(); ?>
