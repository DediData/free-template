<?php
if(get_theme_mod('display_featured_in_header', 'no') == 'no'){
	if ( has_post_thumbnail() ) { ?>
	<div class="featured-image"><?php
		$featured_image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$alt_tag_value = trim( strip_tags( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) ) );
		if ( ! is_single() ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php
		} ?>
			<img class="entry-header-image" src="<?php echo esc_url($featured_image_array[0]); ?>" title="<?php the_title_attribute(); ?>" alt="<?php echo esc_attr($alt_tag_value); ?>" /><?php
		if ( ! is_single() ) { ?>
		</a><?php
		} ?>
	</div><?php
	}
}
