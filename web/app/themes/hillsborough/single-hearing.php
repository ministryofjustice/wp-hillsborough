<?php
/**
 * The Template for displaying all hearing sessions.
 *
 * @package hillsborough
 */

get_header(); ?>
<?php get_sidebar(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'hearing' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>