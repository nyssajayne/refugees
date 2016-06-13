<?php get_header(); ?>

<?php include('hero.php'); ?>

			<article>

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>

				<?php endwhile; endif; ?>

			</article>

			<div class="clearfix"></div>

<?php get_footer(); ?>