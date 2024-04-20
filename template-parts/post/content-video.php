<?php
/**
 * Template part for displaying video posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'panel box' ); ?>>
	<?php get_template_part( 'template-parts/part/entry-header' ); ?>

	<div class="entry-content panel-body">
	<?php get_template_part( 'template-parts/part/entry-featured' ); ?>
	<?php
		$content = apply_filters( 'the_content', get_the_content() );
		$video = false;

		// Only get video from the content if a playlist isn't present.
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {
			$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
		}

		if ( ! is_single() ) {

			// If not a single post, highlight the video file.
			if ( ! empty( $video ) ) {
				foreach ( $video as $video_html ) {
					echo '<div class="entry-video">';
						echo $video_html; // xss ok
					echo '</div>';
				}
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
