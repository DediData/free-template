<?php
/**
 * Template part for displaying gallery posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'panel box'); ?>>
	<?php get_template_part( 'template-parts/part/entry-header' ); ?>

	<div class="entry-content panel-body">
	<?php get_template_part( 'template-parts/part/entry-featured' ); ?>
		<?php
		if ( ! is_single() ) {

			// If not a single post, highlight the gallery.
			if ( get_post_gallery() ) {
				echo '<div class="entry-gallery">';
					echo get_post_gallery(); // xss ok
				echo '</div>';
			};

		};

		/* translators: %s: Name of current post */
		the_content( '<span class="fa fa-eye btn btn-default"></span> ' . esc_html__( 'Continue reading', 'free-template' ) );
	
		get_template_part( 'template-parts/part/entry-pagination' ); ?>
	
	</div><!-- .entry-content -->

	<div>
		<?php get_template_part( 'template-parts/part/entry-footer' ); ?>
	</div>
</article><!-- #post-## --><?php

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
