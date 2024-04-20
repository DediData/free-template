<?php
if( has_nav_menu( 'bottom' ) or is_customize_preview() ){ ?>
<!--nav class="megamenu navbar navbar-default navbar-toggleable-md navbar-inverse bg-inverse  navbar-toggleable-md navbar-light bg-faded"-->
<nav id="bottom-menu" class="megamenu navbar navbar-default navbar-inverse bg-inverse">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#bottom-navbar-collapse" aria-expanded="false" aria-controls="bottom-menu">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		</div>
		<div id="bottom-navbar-collapse" class="collapse navbar-collapse"><?php
			wp_nav_menu( array(
				'theme_location'		=> 'bottom',
				'depth'					=> 1,
				'menu_class'			=> 'nav navbar-nav megamenu',
				'menu_id'				=> '',
				'container'				=> '',
				'container_class'	=> '',
				'container_id'			=> '',
				'fallback_cb'			=> 'WP_Bootstrap_Bottom_Navwalker::fallback',
				'walker'					=> new WP_Bootstrap_Bottom_Navwalker(),
				)
			); ?>
		</div>
</nav>
<?php }