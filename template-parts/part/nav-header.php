<?php
if (has_nav_menu( 'header' ) or has_nav_menu( 'header-right' ) or is_customize_preview()){ ?>
<nav id="header-menu" class="megamenu navbar navbar-default container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#header-navbar-collapse" aria-expanded="false" aria-controls="header-menu">
		<span class="sr-only"><?php esc_html_e('Toggle navigation', 'free-template'); ?></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	</div>
	<div id="header-navbar-collapse" class="collapse navbar-collapse"><?php

		wp_nav_menu( array(
			'theme_location'		=> 'header',
			'depth'					=> 3,
			'menu_class'			=> 'nav navbar-nav megamenu',
			'menu_id'				=> '',
			'container'				=> '',
			'container_class'	=> '',
			'container_id'			=> '',
			'fallback_cb'			=> 'WP_Bootstrap_Navwalker::fallback',
			'walker'					=> new WP_Bootstrap_Navwalker(),
			)
		);
		
		wp_nav_menu( array(
			'theme_location'		=> 'header-right',
			'depth'					=> 3,
			'menu_class'			=> 'nav navbar-nav navbar-right megamenu',
			'menu_id'				=> '',
			'container'				=> '',
			'container_class'	=> '',
			'container_id'			=> '',
			'fallback_cb'			=> 'WP_Bootstrap_Navwalker::fallback',
			'walker'					=> new WP_Bootstrap_Navwalker(),
			)
		); ?>
	</div>
</nav>
<?php }