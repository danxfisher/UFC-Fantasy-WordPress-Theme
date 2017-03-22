<?php
/*
 * main page
 */

get_header(); ?>

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
					$news = file_get_contents('http://ufc-data-api.ufc.com/api/v3/news');
					$news = json_decode($news);

					// most recent 5 news articles
					$articles = array_slice($news, 0, 6);

				?>
					<div class="row">

					<?php
					$article_count = 0;
					foreach ($articles as $article) {
						$article_count++;
						if ($article_count % 2 == 1) {
							echo '<div class="row">';
						}
						echo '<div class="col-md-6 news-item">';
						echo '<div class="col-sm-12 thumbnail-image" style="background-image:url(' . $article->thumbnail . ');">';
						echo '</div>';
						echo '<div class="col-sm-12 news-title">';
						echo '<a href="http://ufc.com/news/' . $article->url_name . '">' . $article->title . '</a>';
						echo '</div>';
						echo '</div>';
						if ($article_count % 2 == 0) {
							echo '</div>';
						}
					}
				?>
					</div>
			</div>
			<div class="col-md-6">
				<h2>Events</h2>
				<div class="row event-list">

				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post();

							echo '<div class="col-md-12 event-item">';
							echo '<a href="' . get_the_permalink();
							echo '">';
							echo get_the_title();
							echo '</a>';
							echo '</div>';
					?>

					<?php endwhile; ?>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>



<?php get_footer(); ?>
