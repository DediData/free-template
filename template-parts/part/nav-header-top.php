<?php
/**
 * Part for displaying Header Top
 *
 * @package Free_Template
 */

declare(strict_types=1);

if ( has_nav_menu( 'primary' ) || get_theme_mod( 'display_login_link' ) || is_customize_preview() ) { ?>
<!--nav class="megamenu navbar navbar-default navbar-toggleable-md navbar-fixed-top navbar-inverse bg-inverse navbar-light"-->
<nav id="top-menu" class="megamenu navbar-fixed-top navbar-toggleable-md in-top container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#top-navbar-collapse" aria-expanded="false" aria-controls="top-menu">
			<span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'free-template' ); ?></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>"><i class="fa fa-lg fa-home" aria-hidden="true"></i></a>
	</div>
	<div id="top-navbar-collapse" class="collapse navbar-collapse">
		<?php
		wp_nav_menu(
			array(
				'theme_location'  => 'primary',
				'depth'           => 3,
				'menu_class'      => 'nav navbar-nav megamenu',
				'menu_id'         => '',
				'container'       => '',
				'container_class' => '',
				'container_id'    => '',
				'fallback_cb'     => '\FreeTemplate\Walker_Bootstrap_Nav::fallback',
				'walker'          => new \FreeTemplate\Walker_Bootstrap_Nav(),
			)
		);
		?>
		<ul id="top-menu-side" class="nav navbar-nav navbar-right">
			<?php
			if ( get_theme_mod( 'display_login_link' ) ) {
				$login_link_texts = FREE_TEMPLATE()::login_link_texts();
				?>
			<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" class="menu-item pull-right" id="login-menu-item">
				<a data-toggle="modal" title="<?php echo esc_attr( $login_link_texts[ get_theme_mod( 'login_link_text' ) ] ); ?>" id="login-button" data-target="#myModal" aria-haspopup="true" role="button"><i class="fa fa-lg fa-user"></i>&nbsp;<?php echo esc_html( $login_link_texts[ get_theme_mod( 'login_link_text' ) ] ); ?></a>
			</li>
				<?php
			}
			?>
		</ul>
	</div>
</nav>
	<?php
}//end if
