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

<section id="news-events">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>News</h2>
				<?php
					// $news = file_get_contents('http://ufc-data-api.ufc.com/api/v3/news');
					// $news = json_decode($news);

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
									<div class="col-sm-12 thumbnail-image" style="background-image:url('<?php echo $article->thumbnail ?>');">
									</div>
									<div class="col-sm-12 news-title">
										<a href="http://ufc.com/news/<?php echo $article->url_name ?>"><?php echo $article->title ?></a>
									</div>
								</div>
					<?php if ($article_count % 2 == 0) { ?>
							</div>
					<?php } }	?>
					</div>
			</div>
			<div class="col-md-6">
				<h2>Events</h2>
				<div class="row event-list">

				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>

							<div class="col-md-12 event-item">
								<a href="<?php echo get_the_permalink(); ?>">
									<span class="fp-event-title"><?php the_field('event_title'); ?></span> - <?php echo date('F j, Y', strtotime(get_field('event_start_date'))); ?>
								</a>
							</div>

					<?php endwhile; ?>
				<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</section>



<?php get_footer(); ?>
