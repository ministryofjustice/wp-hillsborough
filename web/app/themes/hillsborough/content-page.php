<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package hillsborough
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<h1 class="entry-title"><?php the_title(); ?></h1>
	

        <div class="entry-content<?php if(get_the_title()=="About the Inquests") echo " faqs"; ?>">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'hillsborough' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php //edit_post_link( __( 'Edit', 'hillsborough' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
