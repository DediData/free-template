<?php
/**
 * FreeTemplate Main Class
 * 
 * @package FreeTemplate
 */

declare(strict_types=1);

namespace FreeTemplate;

class Free_Template extends \DediData\Singleton {
	
	public function __construct(){

		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'widgets_init', array($this, 'widgets_init') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_all') );
		add_action( 'customize_register', array('Free_Template_Customizer' , 'register') );
		add_action( 'customize_preview_init', array('Free_Template_Customizer' , 'live_preview') );
		add_filter( 'excerpt_more', array($this, 'excerpt_more') );
		add_filter( 'wp_link_pages_link', array($this, 'bs_link_pages') );
		add_filter( 'wp_link_pages_args', array($this, 'wp_link_pages_args_prevnext_add') );
		add_filter( 'comment_form_default_fields', array($this, 'bootstrap3_comment_form_fields') );
		add_filter( 'comment_form_defaults', array($this, 'bootstrap3_comment_form') );
		add_filter( 'widget_nav_menu_args', array($this, 'add_div_nav_widget') );
		add_filter( 'body_class', array($this, 'body_classes') );
		add_filter( 'wp_get_attachment_image_attributes', array($this, 'image_item_add_title'), 10, 2 );
		add_filter( 'excerpt_length', array($this, 'custom_excerpt_length'), 999 );

		// Check if WooCommerce is active
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			// Order product collections by stock status, instock products first.
			add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
		}

		require( get_template_directory() . '/inc/megamenu-nav-walker.php' );
		require( get_template_directory() . '/inc/megamenu-widget-nav-walker.php' );
		require( get_template_directory() . '/inc/megamenu-bottom-nav-walker.php' );
		require( get_template_directory() . '/inc/comment-walker.php' );
		require( get_template_directory() . '/inc/customizer-library-content.php' );
		require( get_template_directory() . '/inc/customizer.php' );

	}

	
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */

	public function setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'free-template', get_template_directory() . '/languages' );
		
		// Define and register starter content to showcase the theme on new sites.
		$starter_content = array(
			'widgets' => array(
				'sidebar-1' => array(
					'categories',
					'meta',
				),
				'frontend-content-top' => array(
					'text_about',
				),
				'frontend-content-bottom' => array(
					'text_business_info',
				),
				'footer-column-1' => array(
					'calendar',
				),
				'footer-column-2' => array(
					'archives',
				),
				'footer-column-3' => array(
					'recent-posts',
				),
				'footer-column-4' => array(
					'recent-comments',
				),
			),

			// Specify the core-defined pages to create and add custom thumbnails to some of them.
			'posts' => array(
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
			'options' => array(
				'show_on_front' => 'page',
				'page_on_front' => '{{home}}',
				'page_for_posts' => '{{blog}}',
			),

			// Set up nav menus for each of the two areas registered in the theme.
			'nav_menus' => array(
				// Assign a menu to the "top" location.
				'primary' => array(
					'name' => __( 'Top Menu', 'free-template' ),
					'items' => array(
						'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
						'page_about',
						'page_blog',
						'page_news',
						'page_contact',
					),
				),

				'bottom' => array(
					'name' => __( 'Bottom of Site', 'free-template' ),
					'items' => array(
						'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
						'page_about',
						'page_blog',
						'page_news',
						'page_contact',
					),
				),

				// Assign a menu to the "header" location.
				'header' => array(
					'name' => __( 'Bottom of Header', 'free-template' ),
					'items' => array(
						'link_instagram',
						'link_facebook',
						'link_twitter',
						'link_email',
					),
				),
				
				// Assign a menu to the "header-right" location.
				'header-right' => array(
					'name' => __( 'Bottom of Header - Right', 'free-template' ),
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
			'default-image'          => '%s/assets/images/default.jpg',
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
			'video' => false,
			'video-active-callback' => 'is_front_page',
		);
		add_theme_support( 'custom-header', $header_defaults );
		
		register_default_headers( array(
			'default-header' => array(
				'url'           => '%s/assets/images/default.jpg',
				'thumbnail_url' => '%s/assets/images/default.jpg',
				'description'   => get_bloginfo(),
			),
		) );

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
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
			'status',
			'chat',
		) );

		// Add theme support for Custom Logo.
		//https://make.wordpress.org/core/2016/03/10/custom-logo/
		//https://codex.wordpress.org/Theme_Logo
		add_theme_support( 'custom-logo', array(
			'width'       => 80,
			'height'      => 80,
			'flex-width'  => true,
			'flex-height' => false,
			// Classes(s) of elements to hide.
			// It can pass an array of class names here for all elements constituting header text that could be replaced by a logo.
			//'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );
		
		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'			=> esc_html__( 'Top Menu', 'free-template' ),
			'header'			=> esc_html__( 'Bottom of Header', 'free-template' ),
			'header-right'	=> esc_html__( 'Bottom of Header - Right', 'free-template' ),
			'bottom'		=> esc_html__( 'Bottom of Site', 'free-template' ),
		) );

		add_image_size( 'free-template' . '-featured-image', 2000, 1200, true );
		add_image_size( 'free-template' . '-thumbnail-avatar', 90, 90, true );

		// Set the default content width.
		$GLOBALS['content_width'] = 525;

		// This theme styles the visual editor to resemble the theme style, specifically font, colors, and column width.
		add_editor_style( 'assets/css/editor-style.css' );
		if(is_rtl()){
			add_editor_style( 'rtl.css' );
		} else {
			add_editor_style( 'style.css' );
		}

	}
	
	/**
	 * Register widget area.
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function widgets_init() {
		register_sidebar( array(
			'name'				=> esc_html__( 'Sidebar', 'free-template' ),
			'id'					=> 'sidebar-1',
			'description'		=> esc_html__( 'Add widgets here to appear in your sidebar.', 'free-template' ),
			'before_widget'	=> '<div id="%1$s" class="widget %2$s panel box">',
			'after_widget'		=> '</div>',
			'before_title'		=> '<h4 class="widget-title">',
			'after_title'			=> '</h4>',
		) );

		register_sidebar( array(
			'name'				=> esc_html__( 'Frontend Content Top', 'free-template' ),
			'id'           			=> 'frontend-content-top',
			'description'   		=> esc_html__( 'Add widgets here to appear in your top of content in Frontpage.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s panel box">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'  		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Frontend Content Bottom', 'free-template' ),
			'id'            		=> 'frontend-content-bottom',
			'description'   		=> esc_html__( 'Add widgets here to appear in your bottom of content in Frontpage.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s panel box">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Content Top', 'free-template' ),
			'id'            		=> 'content-top',
			'description'   		=> esc_html__( 'Add widgets here to appear in your top of content.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s panel box">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Content Bottom', 'free-template' ),
			'id'            		=> 'content-bottom',
			'description'   		=> esc_html__( 'Add widgets here to appear in your bottom of content.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s panel box">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Footer Column 1', 'free-template' ),
			'id'            		=> 'footer-column-1',
			'description'   		=> esc_html__( 'Add widgets here to appear in your footer column 1.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Footer Column 2', 'free-template' ),
			'id'            		=> 'footer-column-2',
			'description'   		=> esc_html__( 'Add widgets here to appear in your footer column 2.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Footer Column 3', 'free-template' ),
			'id'            		=> 'footer-column-3',
			'description'   		=> esc_html__( 'Add widgets here to appear in your footer column 3.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );

		register_sidebar( array(
			'name'          		=> esc_html__( 'Footer Column 4', 'free-template' ),
			'id'            		=> 'footer-column-4',
			'description'   		=> esc_html__( 'Add widgets here to appear in your footer column 4.', 'free-template' ),
			'before_widget' 	=> '<div id="%1$s" class="widget %2$s">',
			'after_widget'  	=> '</div>',
			'before_title'  	=> '<h4 class="widget-title">',
			'after_title'   		=> '</h4>',
		) );


	}

	public function enqueue_all(){

		// tether js (for tooltips , should before bootstrap) load in footer
		wp_enqueue_script( 'tether', get_stylesheet_directory_uri() . '/assets/tether/js/tether.min.js', array(), '1.4.0', true);

		// bootstrap js css load in footer
		wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.7', true);

		wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css', array(), '3.3.7', 'all');

		// bootstrap theme css
		if (get_theme_mod('bootstrap_theme_name') != 'default' && get_theme_mod('bootstrap_theme_name') ) {
			wp_enqueue_style( 'bootswatch', get_stylesheet_directory_uri() . '/assets/bootswatch/' . esc_html( get_theme_mod( 'bootstrap_theme_name' ) ) . '/bootstrap.min.css', array(), '3.3.7', 'all');
		} else {
			wp_enqueue_style( 'bootstrap-theme', get_stylesheet_directory_uri() . '/assets/bootstrap/css/bootstrap-theme.min.css', array(), '3.3.7', 'all');
		}

		// rtl bootstrap
		if ( is_rtl() ) {
			wp_enqueue_style( 'partial-bootstrap-rtl', get_stylesheet_directory_uri() . '/assets/bootstrap-rtl/css/bootstrap.rtl.min.css', array(), '3.3.7.2', 'all');
		}

		// 1000hz-bootstrap-validator js load in footer
		wp_enqueue_script( 'bootstrap-validator', get_stylesheet_directory_uri() . '/assets/bootstrap-validator/validator.min.js', array(), '0.11.9', true);

		// LightBox2
		wp_enqueue_style( 'lightbox2', get_stylesheet_directory_uri() . '/assets/lightbox2/css/lightbox.min.css', array(), '2.11.3', 'all');
		// load in footer
		wp_enqueue_script( 'lightbox2', get_stylesheet_directory_uri() . '/assets/lightbox2/js/lightbox.min.js', array('jquery'), '2.11.3', true);
		
		// font awesome css
		wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', array(), '4.7.0', 'all');

		// main css
		if ( !is_rtl() ) {
			wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( ), wp_get_theme()->get( 'Version' ), 'all' );
			
		}else{
			wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array( ), wp_get_theme()->get( 'Version' ), 'all' );
			wp_style_add_data( 'theme-style', 'rtl', 'replace' );
		}

		// dedidata js load in footer
		wp_enqueue_script( 'dedidata', get_stylesheet_directory_uri() . '/assets/js/dedidata.js', array('jquery'), wp_get_theme()->get( 'Version' ), true);

		// custom js load in footer
		wp_enqueue_script( 'custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), wp_get_theme()->get( 'Version' ), true);

		// html5shiv js
		wp_enqueue_script( 'html5shiv', get_stylesheet_directory_uri() . '/assets/html5shiv/html5shiv.min.js', array(), '3.7.3', true);
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

		// printshiv js
		wp_enqueue_script( 'html5shiv-printshiv', get_stylesheet_directory_uri() . '/assets/html5shiv/html5shiv-printshiv.min.js', array(), '3.7.3', true);
		wp_script_add_data( 'html5shiv-printshiv', 'conditional', 'lt IE 9' );

		// respond
		wp_enqueue_script( 'respond', get_stylesheet_directory_uri() . '/assets/respond/respond.min.js', array(), '1.4.2', true);
		wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

		// ie 10 viewport bug js css load in footer
		wp_enqueue_script( 'ie10-viewport-bug', get_stylesheet_directory_uri() . '/assets/ie10-viewport-bug/js/ie10-viewport-bug-workaround.min.js', array(), wp_get_theme()->get( 'Version' ), true);
		wp_script_add_data( 'ie10-viewport-bug', 'conditional', 'IE 10' );
		wp_enqueue_style( 'ie10-viewport-bug', get_stylesheet_directory_uri() . '/assets/ie10-viewport-bug/css/ie10-viewport-bug-workaround.min.css', array(), wp_get_theme()->get( 'Version' ), 'all');
		wp_style_add_data( 'ie10-viewport-bug', 'conditional', 'IE 10' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		$current_locale = get_locale();
		$current_locale_2l = substr($current_locale, 1, 2);
		$locale_settings = [];
		if($current_locale == 'fa_IR' or $current_locale == 'fa_AF'){
			// Persian RTL
			// Persian (Afghanistan) RTL
			wp_enqueue_style( 'dedidata-google-fonts', get_stylesheet_directory_uri() . '/assets/fonts/Yekan.css', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Yekan';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'ar'  or $current_locale == 'azb' or $current_locale == 'ckb' or $current_locale == 'ps' or $current_locale == 'haz' or $current_locale_2l == 'ur' or $current_locale == 'ary' or $current_locale == 'skr'){
			// Arabic RTL
			// South Azerbaijani RTL
			// Kurdish (Sorani) RTL
			// Pashto RTL
			// Hazaragi RTL
			// Urdu RTL
			// Moroccan Arabic RTL
			// Saraiki RTL
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Cairo', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Cairo';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'bn_BD' or $current_locale == 'bn_IN'){
			// Bengali (Bangladesh)
			// Bengali (India)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Siliguri', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Hind Siliguri"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'bo' or $current_locale == 'dzo'){
			// Tibetan
			// Dzongkha
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Jomolhari', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Jomolhari';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'gu'){
			// Gujarati
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Vadodara', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Hind Vadodara"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'he_IL'){
			// Hebrew RTL
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Rubik', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Rubik';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'hi_IN' or $current_locale == 'mr' or $current_locale == 'ne_NP'){
			// Hindi
			// Marathi
			// Nepali
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Poppins';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'pa' or $current_locale == 'pa_IN'){
			// Panjabi India
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Mukta+Mahee', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Mukta Mahee"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'ja' or $current_locale = 'ja_JP'){
			// Japanese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Noto Sans JP"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'km'){
			// Cambodian
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hanuman', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Hanuman';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'kn'){
			// Kannada
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Tamma', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Baloo Tamma"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'ko' or $current_locale = 'ko_KR'){
			// Korean
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+KR', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Noto Sans KR"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'ml'){
			// Malayalam
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Chettan', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Baloo Chettan"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'my'){
			// Burmese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Padauk', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Padauk';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'or' or $current_locale = 'or_IN'){
			// Indo-European Oriya
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Baloo+Bhaina', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Baloo Bhaina"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'si'){
			// Sinhalese
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Abhaya+Libre', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Abhaya Libre"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'ta' or $current_locale == 'ta_IN' or $current_locale == 'ta_LK' ){
			// Tamil
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Catamaran', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Catamaran';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'te' or $current_locale = 'te_IN' or $current_locale == 'te_ST'){
			// Telugu
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Hind+Guntur', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Hind Guntur"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale_2l == 'th'){
			// Thai
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Kanit', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Kanit';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'zh-hk'){
			// Chinese (Hong Kong)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+HK', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Noto Sans HK"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'zh-Hans' or $current_locale == 'zh_CN' or $current_locale == 'zh_TW'){
			// Chinese (Simplified)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+SC', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Noto Sans SC"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}elseif($current_locale == 'zh-Hant'){
			// Chinese (Traditional)
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+TC', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='"Noto Sans TC"';
			$locale_settings['locale'] = $current_locale;
			$locale_settings['HTML_lang'] = get_bloginfo( 'language' );
		}else{
			// English & Others
			wp_enqueue_style( 'dedidata-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300', array(), wp_get_theme()->get( 'Version' ), 'all');
			$locale_settings['font']='Roboto';
			$locale_settings['locale'] = $current_locale;
		}
		
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
				font-family: '. $locale_settings['font'] .', Tahoma, Arial, Helvetica, sans-serif;
				letter-spacing: normal !important;
				line-height: normal;
				font-size: 15px;
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
			.site .skip-link,
			.logged-in .site .skip-link,
			.site-description,
			.widget_calendar caption,
			.widget_rss .rss-date,
			.widget_rss cite,
			.author-heading,
			.entry-footer,
			.page-links,
			.entry-caption,
			.comment-metadata,
			.pingback .edit-link,
			.comment-list .reply a,
			.comment-form label,
			.comment-notes,
			.comment-awaiting-moderation,
			.logged-in-as,
			.form-allowed-tags,
			.no-comments,
			.wp-caption-text,
			.gallery-caption,
			.widecolumn label,
			.widecolumn .mu_register label,
			.search-field,
			.tooltip,
			.popover,
			.carousel-control .icon-prev,
			.carousel-control .icon-next,
			.navbar,
			.mejs-container *{
				font-family: '. $locale_settings['font'] .', Tahoma, Arial, Helvetica, sans-serif !important;
				letter-spacing: normal !important;
				line-height: normal;
				font-size: 15px;
			}

			.entry-title,
			.widget .widget-title,
			.site-footer .site-title,
			.site-footer .site-title:after,
			.post-navigation .post-title,
			.site-title,
			.widget-title,
			.page-title,
			.comments-title,
			.comment-reply-title,
			.jumbotron h1,
			.jumbotron h2,
			.jumbotron h3,
			.jumbotron h4,
			.jumbotron h5,
			.jumbotron h6,
			h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h5, .h5, h6, .h6{
				font-family: '. $locale_settings['font'] .', Arial, sans-serif !important;
				font-weight: bold;
				line-height: normal;
				letter-spacing: normal !important;
			}
		';
		if(isset($locale_settings['extra_style'])){
			$custom_style .= $locale_settings['extra_style'];
		}
		wp_add_inline_style( 'theme-style', $custom_style );
	}

	/**
	 * Prints title
	 */
	static function print_title(){
		if(is_archive()){
			$archive_title = get_the_archive_title();
			$archive_title =  strip_tags(str_replace(substr($archive_title, 0, strpos($archive_title, ':') + 1 ),'',$archive_title)); ?>
			<h1 class="page-title"><?php echo esc_html($archive_title); ?></h1>
		<?php
		}elseif(is_tag()){ ?>
			<h1 class="page-title"><?php single_tag_title(); ?></h1><?php
		}else{
			$icons = FREE_TEMPLATE()::get_post_icon(); ?>
			<h1 class="page-title"><?php echo(trim($icons . esc_html(strip_tags(get_the_title())))); ?></h1><?php
		}
	}
	
	/**
	 * Get the post icon
	 */
	static function get_post_icon(){
		$sticky = is_sticky() ? '<i class="sticky-icon fa fa-thumb-tack fa-lg"></i>' : '';
		$posttypeicon = '';
		if (get_post_format() == 'image'){
			$posttypeicon = '<i class="fa fa-file-image-o fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'gallery'){
			$posttypeicon = '<i class="fa fa-picture-o fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'video'){
			$posttypeicon = '<i class="fa fa-file-video-o fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'audio'){
			$posttypeicon = '<i class="fa fa-file-audio-o fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'chat'){
			$posttypeicon = '<i class="fa fa-comments fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'status'){
			$posttypeicon = '<i class="fa fa-info-circle fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'status'){
			$posttypeicon = '<i class="fa fa-link fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'quote'){
			$posttypeicon = '<i class="fa fa-quote-right fa-fw" aria-hidden="true"></i> ';
		}elseif (get_post_format() == 'aside'){
			$posttypeicon = '<i class="fa fa-sticky-note-o fa-fw" aria-hidden="true"></i> ';
		}
		$icons = $sticky . $posttypeicon;
		
		return $icons;
	}
	
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ... and a 'Continue reading' link.
	 * @param string $link Link to single post/page.
	 * @return string 'Continue reading' link prepended with an ellipsis.
	 */
	public function excerpt_more( $link ) {
		if ( is_admin() ) {
			return $link;
		}

		$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link btn btn-default" title="%2$s" data-toggle="tooltip" data-placement="bottom" aria-hidden="true"><span class="fa fa-eye"></span> ' . esc_html__( 'Continue reading', 'free-template' ) . '</a></p>',
			esc_url( get_permalink( get_the_ID() ) ),
			esc_attr(get_the_title())
		);
		return ' &hellip; ' . $link;
	}

	/**
	 * Filter wp_link_pages to wrap current page
	 *
	 * @param $link
	 * @return string
	 */
	public function bs_link_pages( $link ) {
		if ( ctype_digit( $link ) ) {
			return '<li class="active"><span aria-hidden="true">' . $link . '</span></li>';
		}
		return '<li>' . $link . '</li>';
	}

	// Add prev and next links to a numbered page link list
	public function wp_link_pages_args_prevnext_add($args){
		global $page, $numpages, $more, $pagenow;

		if (!$args['next_or_number'] == 'next_and_number')
			return $args; # exit early

		$args['next_or_number'] = 'number'; # keep numbering for the main part
		if (!$more)
			return $args; # exit early

		//<li class="disabled"><a href="#"><span aria-hidden="true">&laquo;</span></a></li>

		if($page-1) {# there is a previous page
			$args['before'] .= '<li>' . _wp_link_page($page-1) . $args['link_before']. '<span aria-hidden="true">' . $args['previouspagelink'] . '</span>' . $args['link_after'] . '</a></li>';
		}else{
			$args['before'] .= '<li class="disabled">' . $args['link_before'] . '<span aria-hidden="true">' . $args['previouspagelink'] . '</span>' . $args['link_after'] . '</li>';
		}

		if ($page<$numpages){ # there is a next page
			$args['after'] = '<li>' . _wp_link_page($page+1) . $args['link_before'] . '<span aria-hidden="true">' . $args['nextpagelink'] . '</span>' . $args['link_after'] . '</a>' . $args['after'];
		}else{
			$args['after'] = '<li class="disabled">' . $args['link_before'] . '<span aria-hidden="true">' . $args['nextpagelink'] . '</span>' . $args['link_after'] . $args['after'];
		}

		return $args;
	}

	public function bootstrap3_comment_form_fields( $fields ) {
		$commenter = wp_get_current_commenter();

		$req      = intval(get_option( 'require_name_email' ));
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$html5    = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;

		$fields   =  array(
			'author' => '<div class="form-group has-feedback comment-form-author">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
									<input placeholder="' . esc_attr__( 'Name', 'free-template' ) .( $req ? ' *' : '' ) . '" class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" required="required" data-error="' . esc_html__('Please enter your name!', 'free-template') . '"' . $aria_req . ' />
								</div>
								<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>',
			'email'  => '<div class="form-group has-feedback comment-form-email">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-at fa-lg"></i></span>
									<input placeholder="' . esc_attr__( 'Email', 'free-template' ) . ( $req ? ' *' : '' ) . '" style="direction: ltr;" class="form-control" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" required="required" data-error="' . esc_html__('Please enter your email address!', 'free-template') . '"' . $aria_req . ' />
								</div>
								<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>',
			'url'    => '<div class="form-group has-feedback comment-form-url">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-globe fa-lg"></i></span>
									<input placeholder="' . esc_attr__( 'Website', 'free-template' ) . '" style="direction: ltr;" class="form-control" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" data-error="' . esc_html__('Please enter a valid website starting with http:// on nothing!', 'free-template') . '" />
								</div>
								<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
								<div class="help-block with-errors"></div>
							</div>',
		);

		return $fields;
	}

	public function bootstrap3_comment_form( $args ) {
		$args['comment_field'] = '
											<div class="form-group has-feedback comment-form-comment">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-comments fa-lg"></i></span>
													<textarea placeholder="' . esc_attr__( 'Comment', 'free-template' ) . '" class="form-control" id="comment" name="comment" cols="45" rows="8" required="required" data-error="' . esc_html__('Please enter your comment!', 'free-template') . '"></textarea>
												</div>
												<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
												<div class="help-block with-errors"></div>
											</div>';
		$args['class_submit'] = 'btn btn-default'; // since WP 4.1

		return $args;
	}

	public function add_div_nav_widget( $args ) {
		$args['menu_class'] = 'nav nav-stacked';
		//$args['fallback_cb'] = 'WP_Bootstrap_Navwalker::fallback';
		$args['walker'] = new WP_Bootstrap_Widget_Navwalker();
		return $args;
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {
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
			$classes[] = 'free-template' . '-customizer';
		}
		
		$classes[] = esc_html( get_theme_mod( 'bootstrap_theme_name' ) ) . '-theme';

		if( ! (has_nav_menu( 'primary' ) or get_theme_mod('display_login_link') ) ) {
			$classes[] = 'non-top-menu';
		}
		
		return $classes;
	}

	/**
	 * Filter attributes for the current gallery image tag.
	 *
	 * @param array   $atts       Gallery image tag attributes.
	 * @param WP_Post $attachment WP_Post object for the attachment.
	 * @return array (maybe) filtered gallery image tag attributes.
	 */
	public function image_item_add_title( $atts, $attachment ) {
		if($atts['alt'] !== ''){
			$atts['title'] = $atts['alt'];
		}
		return $atts;
	}

	/**
	 * Filter the except length to 45 words.
	 *
	 * @param int $length Excerpt length.
	 * @return int (Maybe) modified excerpt length.
	 */
	public function custom_excerpt_length( $length ) {
		if ( ! is_admin() ) {
			return 75;
		}
	}

	public function order_by_stock_status($posts_clauses){
		global $wpdb;
		// only change query on WooCommerce loops
		if (get_queried_object() && !is_admin() && is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
			$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
			$posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
			$posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
		}
		return $posts_clauses;
	}

	/* change custom background */
	public function change_custom_background_cb() {
		$background = get_background_image();
		$color = get_background_color();
		$head_txt_color = get_header_textcolor();
		if($head_txt_color == 'blank'){
			$head_txt_color = 'fff';
		}
		if ( ! $background && ! $color )
			return;

		$style = $color ? "background-color: #$color !important;" : '';

		if ( $background ) {
			$image = " background-image: url('$background') !important;";

			$repeat = get_theme_mod( 'background_repeat', 'repeat' );

			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$repeat = 'repeat';

			$repeat = " background-repeat: $repeat !important;";

			$position = get_theme_mod( 'background_position_x', 'left' );

			if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
				$position = 'left';

			$position = " background-position: top $position !important;";

			$attachment = get_theme_mod( 'background_attachment', 'scroll' );

			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
				$attachment = 'scroll';

			$attachment = " background-attachment: $attachment !important;";

			$style .= $image . $repeat . $position . $attachment;
		}
		?>
		<style type="text/css" id="custom-background-css">
			body.custom-background { <?php echo trim( $style ); // xss ok ?>
				-webkit-background-size: cover !important;
				-moz-background-size: cover !important;
				-o-background-size: cover !important;
				background-size: cover !important;
			}
			#HeaderCarousel .carousel-caption h1,
			#HeaderCarousel .carousel-caption h3,
			#HeaderCarousel .carousel-caption h3 a,
			#HeaderCarousel .carousel-caption h4,
			#HeaderCarousel .carousel-caption h4 a,
			#HeaderCarousel .carousel-caption p,
			#top-menu.in-top ul.megamenu>li>a,
			#top-menu.in-top #top-menu-side>li>a,
			#top-menu.in-top .navbar-header a{
				color: #<?php echo esc_html($head_txt_color); // xss ok ?>;
			}
			#top-menu.in-top .icon-bar{
				background-color: #<?php echo esc_html($head_txt_color); // xss ok ?>;
			}
		</style>
		<?php
	}
	
	static function login_link_texts(){
		return array(
			'Login'				=> esc_html__('Login', 'free-template'),
			'Customer Panel'	=> esc_html__('Customer Panel', 'free-template'),
			'Customer Login'	=> esc_html__('Customer Login', 'free-template'),
			'Management'		=> esc_html__('Management', 'free-template'),
			'Administration'	=> esc_html__('Administration', 'free-template'),
		);
	}

	/****** CONTENT FUNCTIONS ******/

	public static function validate_comment_form(){
		ob_start();
		comment_form();
		echo str_replace('novalidate','data-toggle="validator" ',ob_get_clean()); // xss ok
	}

	public static function comments_pagination( $args = array() ) {
		$navigation = '';
		$args['echo'] = false;
		$links = paginate_comments_links( $args );
		if ( $links ) {
			$navigation = _navigation_markup( $links, 'comments-pagination', '' );
		}
		$navigation = str_replace("ul class='" , "ul class='pagination ", $navigation);
		$navigation = str_replace("<li><span class='page-numbers current'" , "<li class='active'><span class='page-numbers current'", $navigation);
		echo $navigation; // xss ok
	}

	public static function posts_pagination( $args = array() ) {
		$navigation = '';

		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
			$args = wp_parse_args( $args, array(
				'mid_size'           => 1,
				'prev_text'          => esc_html__( 'Previous', 'free-template' ),
				'next_text'          => esc_html__( 'Next', 'free-template' ),
			) );

			// Make sure we get a string back. Plain is the next best thing.
			if ( isset( $args['type'] ) && 'array' == $args['type'] ) {
				$args['type'] = 'plain';
			}

			// Set up paginated links.
			$links = paginate_links( $args );

			if ( $links ) {
				$navigation = _navigation_markup( $links, 'posts-pagination', '' );
			}
		}

		$navigation = str_replace("ul class='" , "ul class='pagination ", $navigation);
		$navigation = str_replace("<li><span class='page-numbers current'" , "<li class='active'><span class='page-numbers current'", $navigation);
		echo $navigation; // xss ok
	}

	/**
	 * Returns an accessibility-friendly link to edit a post or page.
	 *
	 * This also gives us a little context about what exactly we're editing
	 * (post or page?) so that users understand a bit more where they are in terms
	 * of the template hierarchy and their content. Helpful when/if the single-page
	 * layout with multiple posts/pages shown gets confusing.
	 */
	public static function edit_link() {
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				'<i class="fa fa-pencil-square-o fa-lg" data-toggle="tooltip" data-placement="top" title="%s" aria-hidden="true"></i>',
				esc_attr( __('Edit ', 'free-template') . get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
		return false;
	}
	
	// Prints HTML with meta information for the current post-date/time and author.
	public static function posted_on() {
		global $wpp_settings;
		$time_string = '<time class="entry-date published" title="'. esc_html__('Posted on', 'free-template') .'" data-toggle="tooltip" data-placement="bottom" datetime="%1$s">%2$s</time>';

		if( in_array( 'wp-parsidate/wp-parsidate.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && isset($wpp_settings) && $wpp_settings['persian_date'] == 'enable' ) {
			$time_string = sprintf( $time_string,
				gregdate(DATE_W3C, eng_number(get_the_date( DATE_W3C ))),
				get_the_date()
			);
		}else{
			$time_string = sprintf( $time_string,
				get_the_date( DATE_W3C ),
				get_the_date()
			);
		}

		// Finally, let's write all of this to the page.
		?>
		<span class="posted-on">
			<i class="fa fa-calendar" aria-hidden="true" title="<?php esc_attr_e('Posted on', 'free-template'); ?>" data-toggle="tooltip" data-placement="bottom"></i> 
			<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php echo $time_string; // xss ok ?></a>
		</span>
		<?php
	}

	// Prints HTML with meta information for the current post-date/time and author.
	public static function modified_on() {
		$time_string = '<time class="entry-date updated" title="' . esc_html__('Updated on', 'free-template') .'" data-toggle="tooltip" data-placement="bottom" datetime="%1$s">%2$s</time>';
		$time_string = sprintf( $time_string,
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date()
		);

		// Finally, let's write all of this to the page.
		?>
		<span class="modified-on">
			<i class="fa fa-pencil fa-lg" aria-hidden="true" title="<?php esc_attr_e('Updated on', 'free-template'); ?>" data-toggle="tooltip" data-placement="bottom"></i>
			<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php echo $time_string; // xss ok ?></a>
		</span>
		<?php
	}
	
	public static function get_post_image($size = 'thumbnail') {
		global $post, $posts;
		$image_url = null;
		$attachments = get_attached_media( 'image');
		if($attachments){
			$image_url = wp_get_attachment_image_src(current($attachments)->ID, $size)[0];
			// $image_url = (isset($matches [1][0]))? $matches [1][0] : get_template_directory_uri() . "/assets/images/content-image.png";
			$image_url = trim($image_url);
		}elseif(has_post_thumbnail()) {
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id, $size);
			$image_url = $image_url[0];
		}
		$image_url = isset($image_url) ? $image_url : get_template_directory_uri() . "/assets/images/content-image.png";
		return esc_url($image_url);
	}

}
