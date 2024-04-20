<?php
/**
 * Template part for displaying audio posts
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
		$audio = false;

		// Only get audio from the content if a playlist isn't present.
		if ( false === strpos( $content, 'wp-playlist-script' ) ) {
			$audio = get_media_embedded_in_content( $content, array( 'audio' ) );
		}
		if ( ! is_single() ) {

			// If not a single post, highlight the audio file.
			if ( ! empty( $audio ) ) {
				foreach ( $audio as $audio_html ) {
					echo '<div class="entry-audio">';
						echo $audio_html; // xss ok
					echo '</div><!-- .entry-audio -->';
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
