<?php
/**
 * Part for displaying Header Carousel
 *
 * @package Free_Template
 */

declare(strict_types=1);

$attachments = get_uploaded_header_images();
if ( ( is_home() || is_front_page() ) && $attachments ) {
	?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<ol class="carousel-indicators">
			<?php
			$attachments_count = count( $attachments );
			for ( $counter = 0; $counter < $attachments_count; $counter++ ) {
				?>
			<li data-target="#HeaderCarousel" data-slide-to="<?php echo esc_attr( $counter ); ?>"<?php echo 0 === $counter ? ' class="active"' : ''; ?>></li>
				<?php
			}
			?>
		</ol>
		<div class="carousel-inner" role="listbox">
			<?php
			$counter = 1;
			foreach ( $attachments as $attachment ) {

			$img_src = wp_get_attachment_image_url( $attachment['attachment_id'], 'full' );
			$img_srcset = wp_get_attachment_image_srcset( $attachment['attachment_id'], 'full' );
			/*
				http://localhost/!!wordpress/wp-content/uploads/2018/09/wp1-300x120.jpg 300w,
				http://localhost/!!wordpress/wp-content/uploads/2018/09/wp1-600x240.jpg 600w,
				http://localhost/!!wordpress/wp-content/uploads/2018/09/wp1-768x307.jpg 768w,
				http://localhost/!!wordpress/wp-content/uploads/2018/09/wp1.jpg 1000w"
			*/
			$sizes = wp_get_attachment_image_sizes( $attachment['attachment_id'], 'full' );
			$attachment = wp_prepare_attachment_for_js($attachment['attachment_id']); ?>

			<div class="item<?php echo 1 === $counter ? ' active' : ''; ?>">
				<?php
				if ( $attachment['title'] ) {
					?>
				<a href="<?php echo esc_url( $attachment['alt'] ); ?>" title="<?php echo esc_attr( $attachment['title'] ); ?>">
					<?php
				}
				?>
					<div class="overlay"></div>
					<img class="item-image slide-<?php echo esc_attr( $counter ); ?>" src="<?php echo esc_url( $img_src ); ?>" srcset="<?php echo esc_attr( $img_srcset ); ?>" sizes="<?php echo esc_attr( $sizes );?>" title="<?php echo esc_attr( $attachment['title'] ); ?>" alt="<?php echo esc_attr( $attachment['title'] ); ?>" width="1000" height="400" />
					<?php
					if ( $attachment['title'] ) {
						?>
				</a>
						<?php
					}
					if ( display_header_text() ) {
						?>
				<div class="carousel-caption">
						<?php
						if ( $attachment['title'] ) {
							?>
						<a href="<?php echo esc_url( $attachment['alt'] ); ?>"  title="<?php echo esc_attr( $attachment['title'] ); ?>">
							<h3><?php echo esc_html( $attachment['title'] ); ?></h3>
						</a>
							<?php
						}
						if ( $attachment['caption'] ) {
							?>
							<h4><?php echo esc_html( $attachment['caption'] ); ?></h4>
							<?php
						}
						if ( $attachment['description'] ) {
							?>
							<p><?php echo esc_html( $attachment['description'] ); ?></p>
							<?php
						}
						?>
				</div>
						<?php
					}//end if
					?>
			</div>
				<?php
				// $attachment['title']
				// $attachment['url']
				// $attachment['alt']
				// $attachment['description']
				// $attachment['caption']
				// $attachment['sizes']['thumbnail']['url']
				// $attachment['sizes']['thumbnail']['width']
				// $attachment['sizes']['thumbnail']['height']
				$counter++;
			}//end foreach
			?>
		</div>
		<div class="control-box">
			<a class="left carousel-control" href="#HeaderCarousel" role="button" data-slide="prev">
				<span class="control-icon glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			</a>
			<a class="right carousel-control" href="#HeaderCarousel" role="button" data-slide="next">
				<span class="control-icon glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			</a>
		</div>

	</div><!-- /.carousel -->
</section>
	<?php
} elseif ( ( is_home() || is_front_page() ) && get_header_image() ) {
	?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<div class="overlay"></div>
				<img class="item-image slide-1" src="<?php echo esc_url( get_header_image() ); ?>" title="<?php echo esc_attr( get_bloginfo() ); ?>" alt="<?php echo esc_attr( get_bloginfo() ); ?>" width="100%" height="auto" />
				<?php
				if ( display_header_text() ) {
					?>
				<div class="carousel-caption">
					<h3><?php echo esc_html( get_bloginfo() ); ?></h3>
					<h4><?php echo esc_html( get_bloginfo( 'description' ) ); ?></h4>
				</div>
				<?php } ?>
			</div>
		</div>
	</div><!-- /.carousel -->
</section>
	<?php
} else {
	?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<div class="overlay"></div>
				<?php
				if (
					'yes' === get_theme_mod( 'display_featured_in_header', 'no' ) &&
					has_post_thumbnail() &&
					( ! function_exists( 'is_woocommerce' ) || ! is_woocommerce() )
				) {
						// check if woocommerce is not loaded
						$featured_image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					?>
				<img class="item-image slide-1" src="<?php echo esc_url( $featured_image_array[0] ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" />
						<?php
				} else {
					?>
				<img class="item-image slide-1" src="<?php echo esc_url( get_theme_mod( 'default_header_background', get_stylesheet_directory_uri() . '/assets/images/header-bg.jpg' ) ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" />
					<?php 
				}
				?>
				<div class="carousel-caption"><?php FREE_TEMPLATE()::print_title(); ?></div>
			</div>
		</div>
	</div>
</section>
	<?php
}//end if
