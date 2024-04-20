<?php
$attachments = get_uploaded_header_images();
if( (is_home() or is_front_page()) and $attachments ) { ?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<ol class="carousel-indicators"><?php
		for($counter=0; $counter < count($attachments) ; $counter++){ ?>
			<li data-target="#HeaderCarousel" data-slide-to="<?php echo esc_attr($counter); ?>"<?php echo ($counter == 0) ? ' class="active"' : ''; ?>></li><?php
		} ?>
		</ol>
		<div class="carousel-inner" role="listbox"><?php
		$counter = 1;
		foreach($attachments as $attachment){
			$attachment = wp_prepare_attachment_for_js($attachment['attachment_id']); ?>
			<div class="item<?php echo(($counter == 1) ? " active" : ""); ?>"><?php
				if($attachment['title']) { ?>
				<a href="<?php echo(esc_url($attachment['alt'])); ?>" title="<?php echo(esc_attr($attachment['title'])); ?>"><?php
				} ?>
					<div class="overlay"></div>
					<img class="item-image slide-<?php echo $counter; // xss ok ?>" src="<?php echo(esc_url($attachment['url'])); ?>" title="<?php echo(esc_attr($attachment['title'])); ?>" alt="<?php echo(esc_attr($attachment['title'])); ?>" /><?php
				if($attachment['title']) { ?>
				</a><?php
				}
				if(display_header_text()){ ?>
				<div class="carousel-caption"><?php
						if($attachment['title']) { ?>
						<a href="<?php echo(esc_url($attachment['alt'])); ?>"  title="<?php echo(esc_attr($attachment['title'])); ?>">
							<h3><?php echo(esc_html($attachment['title'])); ?></h3>
						</a><?php
						}
						if($attachment['caption']) { ?>
							<h4><?php echo(esc_html($attachment['caption'])); ?></h4><?php
						}
						if($attachment['description']) { ?>
							<p><?php echo(esc_html($attachment['description'])); ?></p><?php
						} ?>
				</div>
				<?php } ?>
			</div><?php
			//$attachment['title']
			//$attachment['url']
			//$attachment['alt']
			//$attachment['description']
			//$attachment['caption']
			//$attachment['sizes']['thumbnail']['url']
			//$attachment['sizes']['thumbnail']['width']
			//$attachment['sizes']['thumbnail']['height']
			$counter += 1;
		} ?>
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
</section><?php
}elseif( (is_home() or is_front_page()) and get_header_image()) { ?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<div class="overlay"></div>
				<img class="item-image slide-1" src="<?php echo esc_url(get_header_image()); ?>" title="<?php echo esc_attr(get_bloginfo()); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>" /><?php
				if(display_header_text()){ ?>
				<div class="carousel-caption">
					<h3><?php echo esc_html( get_bloginfo() ); ?></h3>
					<h4><?php echo esc_html( get_bloginfo( 'description' ) ); ?></h4>
				</div>
				<?php } ?>
			</div>
		</div>
	</div><!-- /.carousel -->
</section><?php
}else{ ?>
<section class="main-slider">
	<div id="HeaderCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="8000">
		<div class="carousel-inner" role="listbox">
			<div class="item active">
				<div class="overlay"></div><?php
					if(get_theme_mod('display_featured_in_header', 'no') == 'yes' and has_post_thumbnail() and
						( ! function_exists('is_woocommerce') or !is_woocommerce() ) ){ // check if woocommerce is not loaded
						$featured_image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
						<img class="item-image slide-1" src="<?php echo esc_url($featured_image_array[0]); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" /><?php
					}else{ ?>
					<img class="item-image slide-1" src="<?php echo esc_url(get_theme_mod('default_header_background', get_stylesheet_directory_uri() . '/assets/images/header-bg.jpg')); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" /><?php 
					} ?>
				<div class="carousel-caption"><?php Free_Template::print_title(); ?></div>
			</div>
		</div>
	</div><!-- /.carousel -->
</section><?php
}
