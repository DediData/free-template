<?php
/**
 * FreeTemplate Main Class
 *
 * @package FreeTemplate
 */

declare(strict_types=1);

namespace FreeTemplate;

/**
 * Class Free_Template
 */
final class Free_Template extends \DediData\Singleton {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ), 10, 0 );
		add_action( 'widgets_init', array( $this, 'widgets_init' ), 10, 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_all' ), 10, 0 );
		add_action( 'customize_register', array( '\FreeTemplate\Customizer', 'register' ) );
		/** @psalm-suppress PossiblyInvalidArgument */
		add_action( 'customize_preview_init', array( '\FreeTemplate\Customizer', 'live_preview' ) );
		add_action( 'wp_head', array( $this, 'add_preconnect_links' ) );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		add_filter( 'wp_link_pages_link', array( $this, 'bs_link_pages' ) );
		add_filter( 'comment_form_default_fields', array( $this, 'bootstrap_comment_form_fields' ) );
		/** @psalm-suppress MixedArgumentTypeCoercion */
		add_filter( 'comment_form_defaults', array( $this, 'bootstrap_comment_form' ) );
		/** @psalm-suppress MixedArgumentTypeCoercion */
		add_filter( 'widget_nav_menu_args', array( $this, 'add_div_nav_widget' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		/** @psalm-suppress MixedArgumentTypeCoercion */
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'image_item_add_title' ), 10, 1 );
		add_filter( 'excerpt_length', array( $this, 'custom_excerpt_length' ), 999 );
		add_filter( 'comment_reply_link', array( $this, 'comment_reply_link' ), 10, 1 );
		add_filter( 'edit_comment_link', array( $this, 'edit_comment_link' ), 10, 1 );
		// To support JC Submenu plugin
		/** @psalm-suppress HookNotFound */
		add_filter( 'jcs/enable_public_walker', array( $this, 'jc_disable_public_walker' ) );
		// Check if WooCommerce is active
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		if ( is_array( $active_plugins ) && in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
			// Order product collections by stock status, in stock products first.
			/** @psalm-suppress MixedArgumentTypeCoercion */
			add_filter( 'posts_clauses', array( $this, 'order_by_stock_status' ), 2000 );
		}
		add_action( 'admin_notices', array( $this, 'update_new_version' ), 10, 0 );
	}

	/**
	 * Update to new version
	 *
	 * @return void return nothing
	 */
	public function update_new_version(): void {
		$template_new_name = 'FOLD';
		$install_url       = admin_url( 'theme-install.php?theme=fold' );
		echo '<div class="notice notice-info is-dismissible"><p>'
		. sprintf(
			wp_kses(
				/* translators: 1: theme name, 2: link */
				__( 'A new version of this theme has been released under the name "%1$s". Please <a href="%2$s" target="_blank">install it right now!</a>.', 'free-template' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_html( $template_new_name ),
			esc_url( $install_url )
		)
		. '</p></div>';
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @return void return nothing
	 */
	public function setup(): void {
		// Make theme available for translation.
		load_theme_textdomain( 'free-template', get_template_directory() . '/languages' );

		// Define and register starter content to showcase the theme on new sites.
		$starter_content = array(
			'widgets'   => array(
				'sidebar-1'               => array(
					'categories',
					'meta',
				),
				'frontend-content-top'    => array( 'text_about' ),
				'frontend-content-bottom' => array( 'text_business_info' ),
				'footer-column-1'         => array( 'calendar' ),
				'footer-column-2'         => array( 'archives' ),
				'footer-column-3'         => array( 'recent-posts' ),
				'footer-column-4'         => array( 'recent-comments' ),
			),

			// Specify the core-defined pages to create and add custom thumbnails to some of them.
			'posts'     => array(
				'home',
				'blog',
				'news',
				'about',
				'contact',
				'homepage-section',

				/*
				'homepage-section' => array(
					'thumbnail' => '{{image-espresso}}',
				),
				*/
			),

			// Default to a static front page and assign the front and posts pages.
			'options'   => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
			),

			// Set up nav menus for each of the two areas registered in the theme.
			'nav_menus' => array(
				// Assign a menu to the "top" location.
				'primary'      => array(
					'name'  => __( 'Top Menu', 'free-template' ),
					'items' => array(
						// Note that the core "home" page is actually a link in case a static front page is not used.
						'link_home',
						'page_about',
						'page_blog',
						'page_news',
						'page_contact',
					),
				),

				'bottom'       => array(
					'name'  => __( 'Bottom of Site', 'free-template' ),
					'items' => array(
						// Note that the core "home" page is actually a link in case a static front page is not used.
						'link_home',
						'page_about',
						'page_blog',
						'page_news',
						'page_contact',
					),
				),

				// Assign a menu to the "header" location.
				'header'       => array(
					'name'  => __( 'Bottom of Header', 'free-template' ),
					'items' => array(
						'link_instagram',
						'link_facebook',
						'link_twitter',
						'link_email',
					),
				),

				// Assign a menu to the "header-right" location.
				'header-right' => array(
					'name'  => __( 'Bottom of Header - Right', 'free-template' ),
					'items' => array(
						'link_youtube',
						'link_github',
						'link_linkedin',
						'link_pinterest',
					),
				),
			),
		);

		add_theme_support( 'starter-content', $starter_content );

		$background_defaults = array(
			'default-color'          => 'ffffff',
			'default-image'          => '',
			'default-repeat'         => '',
			'default-position-x'     => '',
			'default-attachment'     => '',
			'wp-head-callback'       => array( $this, 'change_custom_background_cb' ),
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
		add_theme_support( 'custom-background', $background_defaults );

		$header_defaults = array(
			'default-image'          => '%s/assets/images/default.webp',
			'width'                  => 1000,
			'height'                 => 400,
			'random-default'         => false,
			'flex-width'             => true,
			'flex-height'            => true,
			'default-text-color'     => 'ffffff',
			'header-text'            => true,
			'uploads'                => true,
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
			'video'                  => true,
			'video-active-callback'  => 'is_front_page',
		);
		add_theme_support( 'custom-header', $header_defaults );

		register_default_headers(
			array(
				'default-header' => array(
					'url'           => '%s/assets/images/default.webp',
					'thumbnail_url' => '%s/assets/images/default.webp',
					'description'   => get_bloginfo(),
				),
			)
		);

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title. By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'wc-product-gallery-zoom' );

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
				'status',
				'chat',
			)
		);

		// Add theme support for Custom Logo.
		// https://make.wordpress.org/core/2016/03/10/custom-logo/
		// https://codex.wordpress.org/Theme_Logo
		add_theme_support(
			'custom-logo',
			array(
				'width'       => 80,
				'height'      => 80,
				'flex-width'  => true,
				'flex-height' => false,
				// Classes(s) of elements to hide.
				// It can pass an array of class names here for all elements constituting header text that could be replaced by a logo.
				// 'header-text' => array( 'site-title', 'site-description' ),
			)
		);

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary'      => esc_html__( 'Top Menu', 'free-template' ),
				'header'       => esc_html__( 'Bottom of Header', 'free-template' ),
				'header-right' => esc_html__( 'Bottom of Header - Right', 'free-template' ),
				'bottom'       => esc_html__( 'Bottom of Site', 'free-template' ),
			)
		);

		add_image_size( 'free-template-featured-image', 2000, 1200, true );
		add_image_size( 'free-template-thumbnail-avatar', 90, 90, true );

		add_theme_support( 'editor-styles' );
		// This theme styles the visual editor to resemble the theme style, specifically font, colors, and column width.
		if ( is_rtl() ) {
			add_editor_style( 'assets/css/editor-style-rtl.css' );
		} elseif ( ! is_rtl() ) {
			add_editor_style( 'assets/css/editor-style.css' );
		}
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 * @return void Return
	 */
	public function widgets_init(): void {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'free-template' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s shadow rounded mb-3">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Frontend Content Top', 'free-template' ),
				'id'            => 'frontend-content-top',
				'description'   => esc_html__( 'Add widgets here to appear in your top of content in Frontpage.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s shadow rounded mb-3">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Frontend Content Bottom', 'free-template' ),
				'id'            => 'frontend-content-bottom',
				'description'   => esc_html__( 'Add widgets here to appear in your bottom of content in Frontpage.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s shadow rounded mb-3">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Content Top', 'free-template' ),
				'id'            => 'content-top',
				'description'   => esc_html__( 'Add widgets here to appear in your top of content.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s shadow rounded mb-3">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Content Bottom', 'free-template' ),
				'id'            => 'content-bottom',
				'description'   => esc_html__( 'Add widgets here to appear in your bottom of content.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s shadow rounded mb-3">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Column 1', 'free-template' ),
				'id'            => 'footer-column-1',
				'description'   => esc_html__( 'Add widgets here to appear in your footer column 1.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Column 2', 'free-template' ),
				'id'            => 'footer-column-2',
				'description'   => esc_html__( 'Add widgets here to appear in your footer column 2.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Column 3', 'free-template' ),
				'id'            => 'footer-column-3',
				'description'   => esc_html__( 'Add widgets here to appear in your footer column 3.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Column 4', 'free-template' ),
				'id'            => 'footer-column-4',
				'description'   => esc_html__( 'Add widgets here to appear in your footer column 4.', 'free-template' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Add Preconnect Links
	 *
	 * @return void Return
	 */
	public function add_preconnect_links(): void {
		// echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
		echo '<link rel="preconnect" href="https://secure.gravatar.com/" crossorigin>' . "\n";
	}

	/**
	 * Enqueues various scripts and styles
	 *
	 * @return void Return
	 */
	public function enqueue_all(): void {

		// bootstrap js css load in footer
		wp_enqueue_script(
			'bootstrap',
			get_stylesheet_directory_uri() . '/assets/bootstrap/js/bootstrap.bundle.min.js',
			array( 'jquery' ),
			'5.3.3',
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// bootstrap theme css
		$mod_bs_theme_name = get_theme_mod( 'bootstrap_theme_name', 'default' );
		$mod_bs_theme_name = is_string( $mod_bs_theme_name ) ? $mod_bs_theme_name : '';
		$theme_names       = array(
			'cerulean',
			'cosmo',
			'cyborg',
			'darkly',
			'flatly',
			'journal',
			'litera',
			'lumen',
			'lux',
			'materia',
			'minty',
			'morph',
			'pulse',
			'quartz',
			'sandstone',
			'simplex',
			'sketchy',
			'slate',
			'solar',
			'spacelab',
			'superhero',
			'united',
			'vapor',
			'yeti',
			'zephyr',
		);
		if ( '' === $mod_bs_theme_name || ! in_array( $mod_bs_theme_name, $theme_names, true ) ) {
			$mod_bs_theme_name = 'default';
		}
		$theme_mode = 'default' !== $mod_bs_theme_name;
		if ( $theme_mode ) {
			if ( ! is_rtl() ) {
				wp_enqueue_style( 'bootswatch', get_stylesheet_directory_uri() . '/assets/bootswatch/' . esc_html( $mod_bs_theme_name ) . '/bootstrap.min.css', array(), '5.3.3', 'all' );
			} elseif ( is_rtl() ) {
				wp_enqueue_style( 'bootswatch-rtl', get_stylesheet_directory_uri() . '/assets/bootswatch/' . esc_html( $mod_bs_theme_name ) . '/bootstrap.rtl.min.css', array(), '5.3.3', 'all' );
			}
		}
		if ( ! $theme_mode ) {
			if ( ! is_rtl() ) {
				wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), '5.3.3', 'all' );
			} elseif ( is_rtl() ) {
				wp_enqueue_style( 'bootstrap-rtl', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.rtl.min.css', array(), '5.3.3', 'all' );
			}
		}

		// LightBox2
		wp_enqueue_style( 'lightbox2', get_stylesheet_directory_uri() . '/assets/lightbox2/css/lightbox.min.css', array(), '2.11.3', 'all' );
		// load in footer
		wp_enqueue_script(
			'lightbox2',
			get_stylesheet_directory_uri() . '/assets/lightbox2/js/lightbox.min.js',
			array( 'jquery' ),
			'2.11.3',
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// Font Awesome CSS
		wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/assets/font-awesome/css/all.min.css', array(), '7.0.0', 'all' );

		$theme_version = wp_get_theme()->get( 'Version' );
		/** @psalm-suppress RedundantConditionGivenDocblockType, DocblockTypeContradiction */
		$theme_version = ! is_array( $theme_version ) ? $theme_version : '';
		// main css
		if ( ! is_rtl() ) {
			wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), $theme_version, 'all' );
		} elseif ( is_rtl() ) {
			wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), $theme_version, 'all' );
			wp_style_add_data( 'theme-style', 'rtl', 'replace' );
		}

		// dedidata js load in footer
		wp_enqueue_script(
			'dedidata',
			get_stylesheet_directory_uri() . '/assets/js/dedidata.js',
			array( 'jquery' ),
			$theme_version,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		$this->enqueue_fonts();
	}

	/**
	 * Enqueues fonts and styles
	 *
	 * @return void Return
	 */
	public function enqueue_fonts(): void {
		$current_locale    = get_locale();
		$current_locale_2l = substr( $current_locale, 1, 2 );
		$locale_settings   = array();
		$theme_version     = wp_get_theme()->get( 'Version' );
		/** @psalm-suppress RedundantConditionGivenDocblockType, DocblockTypeContradiction */
		$theme_version = ! is_array( $theme_version ) ? $theme_version : '';

		if ( 'fa_IR' === $current_locale || 'fa_AF' === $current_locale ) {
			// Persian RTL
			// Persian (Afghanistan) RTL
			wp_enqueue_style( 'dedidata-persian-fonts', get_stylesheet_directory_uri() . '/assets/fonts/persian-fonts.css', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'shabnam';
			$locale_settings['font']      = 'sahel';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'ar' === $current_locale_2l || 'azb' === $current_locale || 'ckb' === $current_locale || 'ps' === $current_locale || 'haz' === $current_locale || 'ur' === $current_locale_2l || 'ary' === $current_locale || 'skr' === $current_locale ) {
			// Arabic RTL
			// South Azerbaijani RTL
			// Kurdish (Sorani) RTL
			// Pashto RTL
			// Hazaragi RTL
			// Urdu RTL
			// Moroccan Arabic RTL
			// Saraiki RTL
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Cairo', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Cairo';
			$locale_settings['font']      = 'Cairo';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'bn_BD' === $current_locale || 'bn_IN' === $current_locale ) {
			// Bengali (Bangladesh)
			// Bengali (India)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Siliguri', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Hind Siliguri"';
			$locale_settings['font']      = '"Hind Siliguri"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'bo' === $current_locale || 'dzo' === $current_locale ) {
			// Tibetan
			// Dzongkha
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Jomolhari', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Jomolhari';
			$locale_settings['font']      = 'Jomolhari';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'gu' === $current_locale ) {
			// Gujarati
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Vadodara', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Hind Vadodara"';
			$locale_settings['font']      = '"Hind Vadodara"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'he_IL' === $current_locale ) {
			// Hebrew RTL
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Rubik', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Rubik';
			$locale_settings['font']      = 'Rubik';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'hi_IN' === $current_locale || 'mr' === $current_locale || 'ne_NP' === $current_locale ) {
			// Hindi
			// Marathi
			// Nepali
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Poppins';
			$locale_settings['font']      = 'Poppins';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'pa' === $current_locale || 'pa_IN' === $current_locale ) {
			// Panjabi India
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Mukta+Mahee', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Mukta Mahee"';
			$locale_settings['font']      = '"Mukta Mahee"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'ja' === $current_locale || 'ja_JP' === $current_locale ) {
			// Japanese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Noto Sans JP"';
			$locale_settings['font']      = '"Noto Sans JP"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'km' === $current_locale_2l ) {
			// Cambodian
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hanuman', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Hanuman';
			$locale_settings['font']      = 'Hanuman';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'kn' === $current_locale_2l ) {
			// Kannada
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Tamma', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Baloo Tamma"';
			$locale_settings['font']      = '"Baloo Tamma"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'ko' === $current_locale || 'ko_KR' === $current_locale ) {
			// Korean
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+KR', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Noto Sans KR"';
			$locale_settings['font']      = '"Noto Sans KR"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'ml' === $current_locale_2l ) {
			// Malayalam
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Chettan', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Baloo Chettan"';
			$locale_settings['font']      = '"Baloo Chettan"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'my' === $current_locale_2l ) {
			// Burmese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Padauk', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Padauk';
			$locale_settings['font']      = 'Padauk';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'or' === $current_locale || 'or_IN' === $current_locale ) {
			// Indo-European Oriya
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Bhaina', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Baloo Bhaina"';
			$locale_settings['font']      = '"Baloo Bhaina"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'si' === $current_locale_2l ) {
			// Sinhalese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Abhaya+Libre', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Abhaya Libre"';
			$locale_settings['font']      = '"Abhaya Libre"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'ta' === $current_locale || 'ta_IN' === $current_locale || 'ta_LK' === $current_locale ) {
			// Tamil
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Catamaran', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Catamaran';
			$locale_settings['font']      = 'Catamaran';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'te' === $current_locale || 'te_IN' === $current_locale || 'te_ST' === $current_locale ) {
			// Telugu
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Guntur', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Hind Guntur"';
			$locale_settings['font']      = '"Hind Guntur"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'th' === $current_locale_2l ) {
			// Thai
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Kanit', array(), $theme_version, 'all' );
			$locale_settings['title']     = 'Kanit';
			$locale_settings['font']      = 'Kanit';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'zh-hk' === $current_locale ) {
			// Chinese (Hong Kong)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+HK', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Noto Sans HK"';
			$locale_settings['font']      = '"Noto Sans HK"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'zh-Hans' === $current_locale || 'zh_CN' === $current_locale || 'zh_TW' === $current_locale ) {
			// Chinese (Simplified)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+SC', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Noto Sans SC"';
			$locale_settings['font']      = '"Noto Sans SC"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} elseif ( 'zh-Hant' === $current_locale ) {
			// Chinese (Traditional)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+TC', array(), $theme_version, 'all' );
			$locale_settings['title']     = '"Noto Sans TC"';
			$locale_settings['font']      = '"Noto Sans TC"';
			$locale_settings['locale']    = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		} else {
			// Default English & Others
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300', array(), $theme_version, 'all' );
			$locale_settings['title']  = 'Roboto';
			$locale_settings['font']   = 'Roboto';
			$locale_settings['locale'] = $current_locale;
		}//end if

		$custom_style = '
			html,
			body,
			button,
			input,
			select,
			textarea,
			input[type="button"],
			input[type="reset"],
			input[type="submit"],
			button[disabled]:hover,
			button[disabled]:focus,
			input[type="button"][disabled]:hover,
			input[type="button"][disabled]:focus,
			input[type="reset"][disabled]:hover,
			input[type="reset"][disabled]:focus,
			input[type="submit"][disabled]:hover,
			input[type="submit"][disabled]:focus,
			keygen,
			blockquote cite,
			blockquote small{
				font-family: ' . $locale_settings['font'] . ', Tahoma, Arial, Helvetica, sans-serif;
				letter-spacing: normal !important;
				line-height: normal;
				font-size: medium;
				font-variation-settings: "wght" 500;
			}

			.main-navigation,
			.post-navigation,
			.widget_recent_entries .post-date,
			.tagcloud a,
			.sticky-post,
			.comment-reply-link,
			.required,
			.post-password-form label,
			.main-navigation .menu-item-description,
			.post-navigation .meta-nav,
			.pagination,
			.image-navigation,
			.comment-navigation,
			.site-description,
			.widget_calendar caption,
			.widget_rss .rss-date,
			.widget_rss cite,
			.author-heading,
			.entry-footer,
			.page-links,
			.entry-caption,
			.pingback .edit-link,
			#comment-list .reply a,
			.comment-form label,
			.comment-notes,
			.logged-in-as,
			.form-allowed-tags,
			.wp-caption-text,
			.gallery-caption,
			.widecolumn label,
			.widecolumn .mu_register label,
			.search-field,
			.popover,
			.navbar,
			.mejs-container *{
				font-family: ' . $locale_settings['font'] . ', Tahoma, Arial, Helvetica, sans-serif !important;
				letter-spacing: normal !important;
				line-height: normal;
				font-size: medium;
				font-variation-settings: "wght" 500;
			}

			.entry-title,
			.widget .widget-title,
			.site-footer .site-title,
			.site-footer .site-title:after,
			.post-navigation .post-title,
			.site-title,
			.widget-title,
			.page-title,
			.comment-reply-title,
			.carousel-caption p,
			.jumbotron h1,
			.jumbotron h2,
			.jumbotron h3,
			.jumbotron h4,
			.jumbotron h5,
			.jumbotron h6,
			h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h5, .h5, h6, .h6{
				font-family: ' . $locale_settings['title'] . ', Arial, sans-serif !important;
				line-height: normal;
				letter-spacing: normal !important;
				font-variation-settings: "wght" 600;
			}
		';
		/** @psalm-suppress InvalidArrayOffset */
		if ( isset( $locale_settings['extra_style'] ) ) {
			/** @psalm-suppress MixedOperand */
			$custom_style .= $locale_settings['extra_style'];
		}
		wp_add_inline_style( 'theme-style', $custom_style );
	}

	/**
	 * Retrieve the current page URL.
	 *
	 * @return string Return current page url
	 */
	public function get_current_page_url(): string {
		$server_http_host   = filter_input( \INPUT_SERVER, 'HTTP_HOST', \FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$server_request_uri = filter_input( \INPUT_SERVER, 'REQUEST_URI', \FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$current_page_url   = '';
		if ( isset( $server_http_host ) && isset( $server_request_uri ) ) {
			$current_page_url  = is_ssl() ? 'https://' : 'http://';
			$current_page_url .= wp_unslash( $server_http_host );
			$current_page_url .= wp_unslash( $server_request_uri );
			$url_query         = wp_parse_url( $current_page_url, \PHP_URL_QUERY );
			if ( isset( $url_query ) ) {
				$current_page_url = str_replace( $url_query, '', $current_page_url );
			}
			$current_page_url = trim( $current_page_url, '?' );
		}
		return $current_page_url;
	}

	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
	 *
	 * @param string $link Link to single post/page.
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	public function excerpt_more( string $link ): string {
		if ( is_admin() ) {
			return $link;
		}

		$the_id       = get_the_ID();
		$the_id       = false !== $the_id ? $the_id : 1;
		$permalink_id = get_permalink( $the_id );
		$permalink_id = is_string( $permalink_id ) ? $permalink_id : '';
		$link         = sprintf(
			'<p class="link-more"><a href="%1$s" class="more-link btn btn-outline-primary" title="%2$s" aria-hidden="true"><span class="fas fa-eye"></span> ' . esc_html__( 'Continue reading', 'free-template' ) . '</a></p>',
			esc_url( $permalink_id ),
			esc_attr( get_the_title() )
		);
		return ' &hellip; ' . $link;
	}

	/**
	 * Filter wp_link_pages to wrap current page
	 *
	 * @param string $link The link.
	 * @return string Return
	 */
	public function bs_link_pages( string $link ): string {
		if ( ctype_digit( $link ) ) {
			return '<li class="active"><span aria-hidden="true">' . $link . '</span></li>';
		}
		$link = str_replace( 'post-page-numbers', 'post-page-numbers page-link', $link );
		$link = str_replace( 'current', 'active', $link );

		return '<li class="page-item">' . $link . '</li>';
	}

	/**
	 * Generates HTML form fields for the comment form in a WordPress theme using Bootstrap 3 styling.
	 *
	 * @param array<string> $fields The function is used to customize the comment form fields.
	 * @return array<string> of form fields for a comment form in a Bootstrap 3 styled format.
	 */
	public function bootstrap_comment_form_fields( array $fields ): array {
		$commenter = wp_get_current_commenter();

		$req      = intval( get_option( 'require_name_email' ) );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields   = array(
			'author' => '<div class="form-group has-feedback comment-form-author">
							<div class="input-group">
								<span class="input-group-addon"><i class="fas fa-user fa-lg"></i></span>
								<input placeholder="' . esc_attr__( 'Name', 'free-template' ) . ( $req ? ' *' : '' ) . '" class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" required="required" data-error="' . esc_attr__( 'Please enter your name!', 'free-template' ) . '"' . $aria_req . ' />
							</div>
							<span class="fas form-control-feedback" aria-hidden="true"></span>
							<div class="help-block with-errors"></div>
						</div>',
			'email'  => '<div class="form-group has-feedback comment-form-email">
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-at fa-lg"></i></span>
									<input placeholder="' . esc_attr__( 'Email', 'free-template' ) . ( $req ? ' *' : '' ) . '" style="direction: ltr;" class="form-control" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" required="required" data-error="' . esc_attr__( 'Please enter your email address!', 'free-template' ) . '"' . $aria_req . ' />
								</div>
								<span class="fas form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>',
			'url'    => '<div class="form-group has-feedback comment-form-url">
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-globe fa-lg"></i></span>
									<input placeholder="' . esc_attr__( 'Website', 'free-template' ) . '" style="direction: ltr;" class="form-control" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" data-error="' . esc_attr__( 'Please enter a valid website starting with http:// on nothing!', 'free-template' ) . '" />
								</div>
								<span class="fas form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>',
		);

		return $fields;
	}

	/**
	 * Customizes the comment form in WordPress using Bootstrap 3 styling.
	 *
	 * @param array<string> $args Contains various settings and configurations for the comment form.
	 * @return array<string> Return
	 */
	public function bootstrap_comment_form( array $args ): array {
		$args['comment_field'] = '
			<div class="form-group has-feedback comment-form-comment">
				<div class="input-group">
					<span class="input-group-addon"><i class="fas fa-comments fa-lg"></i></span>
					<textarea placeholder="' . esc_attr__( 'Comment', 'free-template' ) . '" class="form-control" id="comment" name="comment" cols="45" rows="8" required="required" data-error="' . esc_attr__( 'Please enter your comment!', 'free-template' ) . '"></textarea>
				</div>
				<span class="fas form-control-feedback" aria-hidden="true"></span>
				<div class="help-block with-errors"></div>
			</div>';
		// since WP 4.1
		$args['class_submit'] = 'btn btn-outline-primary';

		return $args;
	}

	/**
	 * Modifies the arguments for a navigation menu widget in WordPress.
	 *
	 * @param array<mixed> $args Contains various settings and configurations for a navigation menu widget.
	 * @return array<mixed>
	 */
	public function add_div_nav_widget( array $args ): array {
		$args['menu_class'] = 'nav nav-stacked';
		// $args['fallback_cb'] = 'WP_Bootstrap_Nav_Walker::fallback';
		$args['walker'] = new Walker_Bootstrap_Nav_Widget();
		return $args;
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array<string> $classes Classes for the body element.
	 * @return array<string> Return
	 */
	public function body_classes( array $classes ): array {
		// Add class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		// Add class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Add class if we're viewing the Customizer for easier styling of theme options.
		if ( is_customize_preview() ) {
			$classes[] = 'free-template-customizer';
		}
		$mod_bs_theme_name = get_theme_mod( 'bootstrap_theme_name', 'default' );
		$mod_bs_theme_name = is_string( $mod_bs_theme_name ) ? $mod_bs_theme_name : '';
		$classes[]         = esc_html( $mod_bs_theme_name ) . '-theme';

		if ( ! ( has_nav_menu( 'primary' ) || get_theme_mod( 'display_login_link', false ) ) ) {
			$classes[] = 'non-top-menu';
		}

		return $classes;
	}

	/**
	 * Filter attributes for the current gallery image tag.
	 *
	 * @param array<mixed> $attr Gallery image tag attributes.
	 * @return array<mixed> filtered gallery image tag attributes.
	 */
	public function image_item_add_title( array $attr ): array {
		if ( isset( $attr['alt'] ) && '' !== $attr['alt'] ) {
			$attr['title'] = $attr['alt'];
		}
		return $attr;
	}

	/**
	 * Filter the except length to 45 words.
	 *
	 * @param integer $length Excerpt length.
	 * @return integer (Maybe) modified excerpt length.
	 */
	public function custom_excerpt_length( int $length ): int {
		if ( is_admin() ) {
			return $length;
		}
		return 75;
	}

	/**
	 * The function modifies the SQL query clauses to order WooCommerce products by stock status.
	 *
	 * @param array<mixed> $posts_clauses An array that contains various parts of the SQL query.
	 * @return array<mixed> Modifies the SQL query clauses for ordering posts.
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public function order_by_stock_status( array $posts_clauses ): array {
		// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
		if ( ! isset( $GLOBALS['wpdb'] ) ) {
			return array();
		}
		// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
		$wpdb = $GLOBALS['wpdb'];
		// only change query on WooCommerce loops
		if ( get_queried_object() && ! is_admin() && is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			if ( isset( $posts_clauses['join'] ) && is_string( $posts_clauses['join'] ) && is_object( $wpdb ) ) {
				$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
			}
			if ( isset( $posts_clauses['orderby'] ) && is_string( $posts_clauses['orderby'] ) ) {
				$posts_clauses['orderby'] = ' istockstatus.meta_value ASC, ' . $posts_clauses['orderby'];
			}
			if ( isset( $posts_clauses['where'] ) && is_string( $posts_clauses['where'] ) ) {
				$posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
			}
		}
		return $posts_clauses;
	}

	/**
	 * Generates custom background styles and header text colors.
	 *
	 * @return mixed a custom background style.
	 */
	public function change_custom_background_cb() {
		$background     = get_background_image();
		$color          = get_background_color();
		$head_txt_color = get_header_textcolor();
		if ( 'blank' === $head_txt_color ) {
			$head_txt_color = 'fff';
		}
		/** @psalm-suppress DocblockTypeContradiction */
		if ( ! isset( $background ) && ! isset( $color ) ) {
			return;
		}

		/** @psalm-suppress RedundantConditionGivenDocblockType, DocblockTypeContradiction */
		$style = isset( $color ) ? "background-color: #$color !important;" : '';

		if ( isset( $background ) ) {
			$image = " background-image: url('$background') !important;";

			$repeat = get_theme_mod( 'background_repeat', 'repeat' );

			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ), true ) ) {
				$repeat = 'repeat';
			}

			$repeat = " background-repeat: $repeat !important;";

			$position = get_theme_mod( 'background_position_x', 'left' );

			if ( ! in_array( $position, array( 'center', 'right', 'left' ), true ) ) {
				$position = 'left';
			}

			$position = " background-position: top $position !important;";

			$attachment = get_theme_mod( 'background_attachment', 'scroll' );

			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ), true ) ) {
				$attachment = 'scroll';
			}

			$attachment = " background-attachment: $attachment !important;";

			$style .= $image . $repeat . $position . $attachment;
		}//end if
		?>
		<style type="text/css" id="custom-background-css">
			body.custom-background {
				<?php echo esc_html( trim( $style ) ); ?>
				-webkit-background-size: cover !important;
				-moz-background-size: cover !important;
				-o-background-size: cover !important;
				background-size: cover !important;
			}
			#HeaderCarousel .carousel-caption h1,
			#HeaderCarousel .carousel-caption h4,
			#HeaderCarousel .carousel-caption h4 a,
			#HeaderCarousel .carousel-caption h5,
			#HeaderCarousel .carousel-caption p,
			#top-menu ul.mega-menu>li>a,
			#top-menu #top-menu-side>li>a,
			#top-menu.in-top ul.mega-menu>li>a,
			#top-menu.in-top #top-menu-side>li>a,
			#top-menu .navbar-brand,
			#top-menu .navbar-toggler {
				color: #<?php echo esc_html( $head_txt_color ); ?>;
			}
			#top-menu ul.mega-menu>.current-menu-item>a::before,
			#top-menu ul.mega-menu>.current-menu-ancestor>a::before,
			#bottom-menu ul.mega-menu>.current-menu-ancestor>a::before {
				background-color: #<?php echo esc_html( $head_txt_color ); ?>;
			}
			@media (max-width: 767px) {
				#top-menu .navbar-collapse.show li a {
					color: #<?php echo esc_html( $head_txt_color ); ?>;
				}
			}
			#top-menu.in-top .icon-bar,
			#top-menu .icon-bar,
			#top-menu .navbar-toggler {
				background-color: #<?php echo esc_html( $head_txt_color ); ?>;
			}
		</style>
		<?php
	}

	/**
	 * Modifies the class attribute of a comment reply link.
	 *
	 * @param string $comment_reply_link Represents the HTML code for a comment reply link in a web page.
	 * @return string Returning a modified version of the input
	 */
	public function comment_reply_link( string $comment_reply_link ): string {
		$comment_reply_link = str_replace( 'class="', 'class="btn btn-outline-primary ', $comment_reply_link );
		return $comment_reply_link;
	}

	/**
	 * Modifies the HTML link for editing a comment by adding a CSS class to the anchor tag.
	 *
	 * @param string $link Represents the HTML link tag generated for editing a comment. This link typically includes the URL and other attributes necessary for editing the comment.
	 * @return string The modified link is then returned.
	 */
	public function edit_comment_link( string $link ): string {
		$link = str_replace( '<a ', '<a class="btn btn-outline-primary" ', $link );
		return $link;
	}

	/**
	 * Returns false to disable the public walker.
	 *
	 * @return boolean Return
	 */
	public function jc_disable_public_walker(): bool {
		return false;
	}

	/**
	 * Prints title
	 *
	 * @return void Return
	 */
	public static function print_title(): void {
		if ( is_archive() ) {
			$archive_title = get_the_archive_title();
			$strpos_colon  = strpos( $archive_title, ':' );
			$strpos_colon  = false !== $strpos_colon ? $strpos_colon : 0;
			$archive_title = wp_strip_all_tags( str_replace( substr( $archive_title, 0, $strpos_colon + 1 ), '', $archive_title ) );
			?>
			<h1 class="page-title"><?php echo esc_html( $archive_title ); ?></h1>
			<?php
		} elseif ( is_tag() ) {
			?>
			<h1 class="page-title"><?php single_tag_title(); ?></h1>
			<?php
		} elseif ( ! is_archive() && ! is_tag() ) {
			?>
			<h1 class="page-title"><?php echo esc_html( trim( get_the_title() ) ); ?></h1>
			<?php
		}
	}

	/**
	 * Get the post icon
	 *
	 * @return string Return
	 */
	public static function get_post_icon(): string {
		$sticky         = is_sticky() ? '<i class="sticky-icon me-1 fas fa-thumb-tack"></i>' : '';
		$post_type_icon = '';
		if ( 'image' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-image fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'gallery' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-images fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'video' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-video fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'audio' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-file-audio fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'chat' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-comment fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'status' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-info-circle fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'link' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-link fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'quote' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-quote-right fa-fw me-1" aria-hidden="true"></i>';
		} elseif ( 'aside' === get_post_format() ) {
			$post_type_icon = '<i class="fas fa-sticky-note fa-fw me-1" aria-hidden="true"></i>';
		}
		return $sticky . $post_type_icon;
	}

	/**
	 * Returns an array of login link texts with corresponding translations.
	 *
	 * @return array<mixed> An array of login link texts with corresponding translations.
	 */
	public static function login_link_texts(): array {
		return array(
			'Login'          => esc_html__( 'Login', 'free-template' ),
			'Customer Panel' => esc_html__( 'Customer Panel', 'free-template' ),
			'Customer Login' => esc_html__( 'Customer Login', 'free-template' ),
			'Management'     => esc_html__( 'Management', 'free-template' ),
			'Administration' => esc_html__( 'Administration', 'free-template' ),
		);
	}

	/**
	 * Modifies the comment form output to include data validation attributes.
	 *
	 * @return void Return
	 */
	public static function validate_comment_form(): void {
		ob_start();
		comment_form();
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo str_replace( 'novalidate', 'data-toggle="validator" ', ob_get_clean() );
	}

	/**
	 * Generates paginated navigation links for comments.
	 *
	 * @param array<mixed> $args Is used to display pagination links for comments in WordPress.
	 * @return void Return
	 */
	public static function comments_pagination( $args = array() ): void {
		$navigation   = '';
		$args['echo'] = false;
		/** @psalm-suppress InvalidArgument */
		$links = paginate_comments_links( $args );
		if ( isset( $links ) && is_string( $links ) ) {
			$navigation = _navigation_markup( $links, 'comments-pagination', '' );
		}

		$navigation = str_replace( "ul class='page-numbers", "ul class='pagination justify-content-center", $navigation );
		$navigation = str_replace( '<li>', '<li class="page-item">', $navigation );
		$navigation = str_replace( 'page-numbers', 'page-link', $navigation );
		$navigation = str_replace( ' current', ' active', $navigation );
		// var_dump($navigation); die();
		echo wp_kses_post( $navigation );
	}

	/**
	 * Generates pagination links for WordPress posts with custom styling adjustments.
	 *
	 * @param array<mixed> $args Used to customize the pagination output.
	 * @return void Return
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function posts_pagination( $args = array() ): void {
		$navigation = '';

		// Don't print empty markup if there's only one page.
		// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
		if ( ! isset( $GLOBALS['wp_query'] ) || ! is_object( $GLOBALS['wp_query'] ) ) {
			return;
		}
		// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
		$wp_query = $GLOBALS['wp_query'];
		if ( $wp_query->max_num_pages > 1 ) {
			$args = wp_parse_args(
				$args,
				array(
					'mid_size'  => 1,
					'prev_text' => esc_html__( 'Previous', 'free-template' ),
					'next_text' => esc_html__( 'Next', 'free-template' ),
				)
			);

			// Make sure we get a string back. Plain is the next best thing.
			/** @psalm-suppress InvalidArrayOffset */
			if ( isset( $args['type'] ) && 'array' === $args['type'] ) {
				$args['type'] = 'plain';
			}

			// Set up paginated links.
			$links = paginate_links( $args );

			/** @psalm-suppress RedundantConditionGivenDocblockType */
			if ( is_string( $links ) ) {
				$navigation = _navigation_markup( $links, 'posts-pagination', '' );
			}
		}//end if

		$navigation = str_replace( "ul class='page-numbers", "ul class='pagination justify-content-center", $navigation );
		$navigation = str_replace( '<li>', '<li class="page-item">', $navigation );
		$navigation = str_replace( 'page-numbers', 'page-link', $navigation );
		$navigation = str_replace( ' current', ' active', $navigation );

		echo wp_kses_post( $navigation );
	}

	/**
	 * Returns an accessibility-friendly link to edit a post or page.
	 *
	 * This also gives us a little context about what exactly we're editing
	 * (post or page?) so that users understand a bit more where they are in terms
	 * of the template hierarchy and their content. Helpful when/if the single-page
	 * layout with multiple posts/pages shown gets confusing.
	 *
	 * @return boolean Return
	 */
	public static function edit_link(): bool {
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				'<i class="fas fa-pencil-square-o fa-lg" title="%s" aria-hidden="true"></i>',
				esc_attr( __( 'Edit ', 'free-template' ) . get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
		return false;
	}

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @return void Return
	 * @SuppressWarnings(PHPMD.Superglobals)
	 */
	public static function posted_on(): void {
		$time_string    = '<time class="entry-date published" title="' . esc_attr__( 'Posted on', 'free-template' ) . '" datetime="%1$s">%2$s</time>';
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$date_w3c       = get_the_date( \DATE_W3C );
		$date_w3c       = false !== $date_w3c ? $date_w3c : '';
		$the_date       = get_the_date();
		$the_date       = false !== $the_date ? $the_date : '';
		$time_string    = sprintf(
			$time_string,
			$date_w3c,
			$the_date
		);
		// phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
		$wpp_settings = $GLOBALS['wpp_settings'] ?? array();
		if (
			is_array( $active_plugins ) &&
			in_array( 'wp-parsidate/wp-parsidate.php', $active_plugins, true ) &&
			is_array( $wpp_settings ) &&
			isset( $wpp_settings['persian_date'] ) &&
			'enable' === $wpp_settings['persian_date']
		) {
			$gregdate    = gregdate( \DATE_W3C, eng_number( $date_w3c ) );
			$time_string = sprintf(
				$time_string,
				$gregdate,
				$the_date
			);
		}

		$permalink = get_permalink();
		$permalink = is_string( $permalink ) ? $permalink : '';
		// Finally, let's write all of this to the page.
		?>
		<span class="posted-on">
			<i class="fas fa-calendar" aria-hidden="true" title="<?php esc_attr_e( 'Posted on', 'free-template' ); ?>"></i>
			<a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark"><?php echo wp_kses_post( $time_string ); ?></a>
		</span>
		<?php
	}

	/**
	 * Prints HTML with meta information for the current post-date/time and author modification.
	 *
	 * @return void Return
	 */
	public static function modified_on(): void {
		$modified_date_w3c = get_the_modified_date( \DATE_W3C );
		$modified_date_w3c = false !== $modified_date_w3c ? $modified_date_w3c : '';
		$modified_date     = get_the_modified_date();
		$modified_date     = false !== $modified_date ? $modified_date : '';
		$time_string       = '<time class="entry-date updated" title="' . esc_attr__( 'Updated on', 'free-template' ) . '" datetime="%1$s">%2$s</time>';
		$time_string       = sprintf(
			$time_string,
			$modified_date_w3c,
			$modified_date
		);

		$permalink = get_permalink();
		$permalink = is_string( $permalink ) ? $permalink : '';
		// Finally, let's write all of this to the page.
		?>
		<span class="modified-on">
			<i class="fas fa-pencil fa-lg" aria-hidden="true" title="<?php esc_attr_e( 'Updated on', 'free-template' ); ?>"></i>
			<a href="<?php echo esc_url( $permalink ); ?>" rel="bookmark"><?php echo wp_kses_post( $time_string ); ?></a>
		</span>
		<?php
	}
}

