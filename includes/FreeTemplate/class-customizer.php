<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package FreeTemplate
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

declare(strict_types=1);

namespace FreeTemplate;

/**
 * Class Customizer
 */
final class Customizer {

	/**
	 * Used to declare a static variable that retains its value across function calls.
	 *
	 * @var array<string> $login_form_systems
	 */
	private static $login_form_systems = array(
		'WordPress'   => 'WordPress',
		'WooCommerce' => 'WooCommerce',
		'WHMCS'       => 'WHMCS',
	);

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * Note: To enable instant preview, we have to actually write a bit of custom
	 * javascript. See live_preview() for more.
	 *
	 * @see add_action('customize_register',$func)
	 * @param \WP_Customize_Manager $wp_customize WP Customize Manager.
	 * @return void Return nothing
	 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 */
	public static function register( \WP_Customize_Manager $wp_customize ): void {

		$wp_customize->add_section(
			'free-template-options',
			array(
				// Visible title of section
				'title'       => esc_html__( 'Theme Options', 'free-template' ),
				// Determines what order this appears in
				'priority'    => 20,
				// Capability needed to tweak
				'capability'  => 'edit_theme_options',
				// Descriptive tooltip
				'description' => esc_html__( 'Allows you to customize settings for Theme.', 'free-template' ),
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'bootstrap_theme_name',
			array(
				// Default setting/value to save
				'default'           => 'default',
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_text',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				// 'transport'      => 'postMessage',
			)
		);

		/*
		Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
		Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
		*/
		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'bootstrap_theme_name',
				array(
					// Admin-visible name of the control
					'label'       => esc_html__( 'Select Theme Name', 'free-template' ),
					'description' => esc_html__( 'Using this option you can change the theme colors', 'free-template' ),
					// Which setting to load and manipulate (serialized is okay)
					'setting'     => 'bootstrap_theme_name',
					// Determines the order this control appears in for the specified section
					'priority'    => 10,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'     => 'free-template-options',
					'type'        => 'select',
					'choices'     => array(
						'default'   => esc_html__( 'Default', 'free-template' ),
						'cerulean'  => 'Cerulean',
						'cosmo'     => 'Cosmo',
						'cyborg'    => 'Cyborg',
						'darkly'    => 'Darkly',
						'flatly'    => 'Flatly',
						'journal'   => 'Journal',
						'litera'    => 'Litera',
						'lumen'     => 'Lumen',
						'lux'       => 'Lux',
						'materia'   => 'Materia',
						'minty'     => 'Minty',
						'morph'     => 'Morph',
						'pulse'     => 'Pulse',
						'quartz'    => 'Quartz',
						'sandstone' => 'Sandstone',
						'simplex'   => 'Simplex',
						'sketchy'   => 'Sketchy',
						'slate'     => 'Slate',
						'solar'     => 'Solar',
						'spacelab'  => 'Spacelab',
						'superhero' => 'Superhero',
						'united'    => 'United',
						'vapor'     => 'Vapor',
						'yeti'      => 'Yeti',
						'zephyr'    => 'Zephyr',
					),
				)
			)
		);

		if ( function_exists( 'wp_statistics_pages' ) ) {
			$wp_customize->add_setting(
				// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
				'display_visits',
				array(
					// Default setting/value to save
					'default'           => 'true',
					// Is this an 'option' or a 'theme_mod'?
					'type'              => 'theme_mod',
					// Optional. Special permissions for accessing this setting.
					'capability'        => 'edit_theme_options',
					// Theme features required to support the panel. Default is none.
					'theme_supports'    => array(),
					// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
					'transport'         => 'refresh',
					'validate_callback' => '',
					'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_checkbox',
					'dirty'             => false,
				)
			);

			/*
			Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
			Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
			*/
			$wp_customize->add_control(
				new \WP_Customize_Control(
					// Pass the $wp_customize object (required)
					$wp_customize,
					// Set a unique ID for the control
					'display_visits',
					array(
						// Which setting to load and manipulate (serialized is okay)
						'setting'        => 'display_visits',
						// Optional. Special permissions for accessing this setting.
						'capability'     => 'edit_theme_options',
						// Determines the order this control appears in for the specified section , Default: 10
						'priority'       => 13,
						// ID of the section this control should render in (can be one of yours, or a WordPress default section)
						'section'        => 'free-template-options',
						// Admin-visible name of the control
						'label'          => esc_html__( 'Display visits?', 'free-template' ),
						'description'    => esc_html__( 'Display number of visits in pages and posts', 'free-template' ),
						// List of custom input attributes for control output, where attribute names are the keys and values are the values.
						'input_attrs'    => array(),
						// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
						// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
						'allow_addition' => false,
						// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
						'type'           => 'checkbox',
						// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.

						/*
						'choices'		 => array(
							// List of choices for 'radio' or 'select' type controls
							'yes'	=> esc_html__( 'Yes', 'free-template' ),
							'no'	=> esc_html__( 'No', 'free-template' ),
						),
						*/
					)
				)
			);
		}//end if

		$wp_customize->add_section(
			'free-template-login-form-options',
			array(
				// Visible title of section
				'title'       => esc_html__( 'Popup Login Form', 'free-template' ),
				// Determines what order this appears in
				'priority'    => 22,
				// Capability needed to tweak
				'capability'  => 'edit_theme_options',
				// Descriptive tooltip
				'description' => esc_html__( 'Allows you to customize login link and login form.', 'free-template' ),
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'display_login_link',
			array(
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// Theme features required to support the panel. Default is none.
				'theme_supports'    => array(),
				// Default value for the setting. Default is empty string.
				'default'           => 'false',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'transport'         => 'refresh',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_checkbox',
				'dirty'             => false,
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'display_login_link',
				array(
					// Which setting to load and manipulate (serialized is okay)
					'setting'        => 'display_login_link',
					// Optional. Special permissions for accessing this setting.
					'capability'     => 'edit_theme_options',
					// Determines the order this control appears in for the specified section , Default: 10
					'priority'       => 11,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'        => 'free-template-login-form-options',
					// Admin-visible name of the control
					'label'          => esc_html__( 'Display login link?', 'free-template' ),
					'description'    => esc_html__( 'Display a link on top menu for login user', 'free-template' ),
					// List of custom input attributes for control output, where attribute names are the keys and values are the values.
					'input_attrs'    => array(),
					// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
					// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
					'allow_addition' => false,
					// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
					'type'           => 'checkbox',
					// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.

					/*
					'choices'			=> array(
						// List of choices for 'radio' or 'select' type controls
						'yes'	=> esc_html__( 'Yes', 'free-template' ),
						'no'	=> esc_html__( 'No', 'free-template' ),
					),
					*/
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'login_link_text',
			array(
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// Theme features required to support the panel. Default is none.
				'theme_supports'    => array(),
				// Default value for the setting. Default is empty string.
				'default'           => 'Login',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'transport'         => 'refresh',
				'validate_callback' => '',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_login_link_texts',
				'dirty'             => false,
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'login_link_text',
				array(
					// Which setting to load and manipulate (serialized is okay)
					'setting'        => 'login_link_text',
					// Optional. Special permissions for accessing this setting.
					'capability'     => 'edit_theme_options',
					// Determines the order this control appears in for the specified section , Default: 10
					'priority'       => 12,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'        => 'free-template-login-form-options',
					// Admin-visible name of the control
					'label'          => esc_html__( 'Login link text', 'free-template' ),
					'description'    => esc_html__( 'Please select the login link text', 'free-template' ),
					// List of custom input attributes for control output, where attribute names are the keys and values are the values.
					'input_attrs'    => array(),
					// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
					// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
					'allow_addition' => false,
					// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
					'type'           => 'select',
					// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
					// List of choices for 'radio' or 'select' type controls
					'choices'        => FREE_TEMPLATE()::login_link_texts(),
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'login_form_system',
			array(
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// Theme features required to support the panel. Default is none.
				'theme_supports'    => array(),
				// Default value for the setting. Default is empty string.
				'default'           => 'WordPress',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'transport'         => 'refresh',
				'validate_callback' => '',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_login_form_systems',
				'dirty'             => false,
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'login_form_system',
				array(
					// Which setting to load and manipulate (serialized is okay)
					'setting'         => 'login_form_system',
					// Optional. Special permissions for accessing this setting.
					'capability'      => 'edit_theme_options',
					// Determines the order this control appears in for the specified section , Default: 10
					'priority'        => 13,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'         => 'free-template-login-form-options',
					// Admin-visible name of the control
					'label'           => esc_html__( 'Login form system', 'free-template' ),
					'description'     => esc_html__( 'Please select the login form system', 'free-template' ),
					// List of custom input attributes for control output, where attribute names are the keys and values are the values.
					'input_attrs'     => array(),
					// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
					// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
					'allow_addition'  => false,
					'active_callback' => '',
					// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
					'type'            => 'select',
					// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
					// List of choices for 'radio' or 'select' type controls
					'choices'         => self::$login_form_systems,
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'whmcs_url',
			array(
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// Theme features required to support the panel. Default is none.
				'theme_supports'    => array(),
				// Default value for the setting. Default is empty string.
				'default'           => 'https://panel.dedidata.com',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'transport'         => 'refresh',
				'validate_callback' => '',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_text',
				'dirty'             => false,
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'whmcs_url',
				array(
					// Which setting to load and manipulate (serialized is okay)
					'setting'         => 'whmcs_url',
					// Optional. Special permissions for accessing this setting.
					'capability'      => 'edit_theme_options',
					// Determines the order this control appears in for the specified section , Default: 10
					'priority'        => 14,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'         => 'free-template-login-form-options',
					// Admin-visible name of the control
					'label'           => esc_html__( 'WHMCS URL', 'free-template' ),
					'description'     => esc_html__( 'If you selected WHMCS, Please provide the url of your WHMCS', 'free-template' ),
					// List of custom input attributes for control output, where attribute names are the keys and values are the values.
					'input_attrs'     => array( 'style' => 'direction:ltr;' ),
					// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
					// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
					'allow_addition'  => false,
					'active_callback' => '',
					// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
					'type'            => 'text',
					// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
					// List of choices for 'radio' or 'select' type controls
					// 'choices'      =>  $login_form_systems,
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'header_text_change_content',
			array(
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// Theme features required to support the panel. Default is none.
				'theme_supports'    => array(),
				// Default value for the setting. Default is empty string.
				'default'           => '',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'transport'         => 'refresh',
				'validate_callback' => '',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_text',
				'dirty'             => false,
			)
		);

		$wp_customize->add_control(
			new \FreeTemplate\Customizer_Library_Content(
				$wp_customize,
				'header_text_change_content',
				array(
					// Which setting to load and manipulate (serialized is okay)
					'setting'     => 'header_text_change_content',
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'     => 'header_image',
					// Optional. Special permissions for accessing this setting.
					'capability'  => 'edit_theme_options',
					// Determines the order this control appears in for the specified section , Default: 10
					'priority'    => 10,
					// Admin visible name of the control
					'label'       => sprintf(
						'<span style="color: red">%s</span>',
						esc_html__( 'How to modify frontpage header texts?', 'free-template' )
					),
					'description' => '<p style="text-align: justify">'
						. esc_html__( 'You can modify texts of header images when you are uploading them by modifying Title, Description and Alt fields. ', 'free-template' )
						. sprintf(
							/* translators: %1$s: Replaces with link tag, %2$s: Replaces with link tag */
							esc_html__( 'You can also modify them via %1$sMedia Manager%2$s. ', 'free-template' ),
							sprintf(
								'<a href="%s">',
								esc_url( get_admin_url( null, 'upload.php?mode=grid' ) )
							),
							'</a>'
						)
						. sprintf(
							/* translators: %1$s: Replaces with link tag, %2$s: Replaces with link tag */
							esc_html__( 'If you cropped your image while uploading the file, So you need to use %1$sMedia Manager/List Mode%2$s to find those cropped images.', 'free-template' ),
							sprintf( '<a href="%s">', esc_url( get_admin_url( null, 'upload.php?mode=list' ) ) ),
							'</a>'
						)
						. '</p>',
					// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
					'type'        => 'content',
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'display_featured_in_header',
			array(
				// Default setting/value to save
				'default'           => 'no',
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				// 'transport'      => 'postMessage',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_text',
			)
		);

		/*
		Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
		Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
		*/
		$wp_customize->add_control(
			new \WP_Customize_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'display_featured_in_header',
				array(
					// Admin-visible name of the control
					'label'       => esc_html__( 'Display featured image in header', 'free-template' ),
					'description' => esc_html__( 'To display the featured image of the post/page in header area, Select this option to Yes! So your selected image will be display in header area as background for that post/page', 'free-template' ),
					// Which setting to load and manipulate (serialized is okay)
					'setting'     => 'display_featured_in_header',
					// Determines the order this control appears in for the specified section
					'priority'    => 11,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'     => 'header_image',
					'type'        => 'select',
					'choices'     => array(
						'no'  => esc_html__( 'No', 'free-template' ),
						'yes' => esc_html__( 'Yes', 'free-template' ),
					),
				)
			)
		);

		$wp_customize->add_setting(
			// No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			'default_header_background',
			array(
				// Default setting/value to save
				'default'           => get_stylesheet_directory_uri() . '/assets/images/header-bg.webp',
				// Is this an 'option' or a 'theme_mod'?
				'type'              => 'theme_mod',
				// Optional. Special permissions for accessing this setting.
				'capability'        => 'edit_theme_options',
				// What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				// 'transport'      => 'postMessage',
				'sanitize_callback' => '\FreeTemplate\Customizer::sanitize_image',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Upload_Control(
				// Pass the $wp_customize object (required)
				$wp_customize,
				// Set a unique ID for the control
				'default_header_background',
				array(
					// Admin-visible name of the control
					'label'       => esc_html__( 'Default header background image', 'free-template' ),
					'description' => '<p style="text-align: justify">' . esc_html__( 'if you like to change default header background image for all pages, you can select an image here.', 'free-template' ) . '</p>',
					// Which setting to load and manipulate (serialized is okay)
					'setting'     => 'default_header_background',
					// Determines the order this control appears in for the specified section
					'priority'    => 12,
					// ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'section'     => 'header_image',
				)
			)
		);

		$setting_blog_name = $wp_customize->get_setting( 'blogname' );
		if ( null !== $setting_blog_name ) {
			$setting_blog_name->transport = 'postMessage';
		}
		$setting_description = $wp_customize->get_setting( 'blogdescription' );
		if ( null !== $setting_description ) {
			$setting_description->transport = 'postMessage';
		}
		$setting_text_color = $wp_customize->get_setting( 'header_textcolor' );
		if ( null !== $setting_text_color ) {
			$setting_text_color->transport = 'postMessage';
			$setting_text_color->default   = 'fff';
		}
		$setting_back_color = $wp_customize->get_setting( 'background_color' );
		if ( null !== $setting_back_color ) {
			$setting_back_color->transport = 'postMessage';
			$setting_back_color->default   = 'inherit';
		}
	}

	/**
	 * Takes an input and checks if it is set and evaluates to true.
	 *
	 * @param boolean $input Input value.
	 * @return boolean Return
	 */
	static public function sanitize_checkbox( bool $input ): bool {
		return $input;
	}

	/**
	 * Sanitizes login link texts.
	 *
	 * @param string $input Input value.
	 * @return string Return
	 */
	static public function sanitize_login_link_texts( string $input ): string {
		return array_key_exists( $input, FREE_TEMPLATE()::login_link_texts() ) ? $input : 'Login';
	}

	/**
	 * Sanitize a text input.
	 *
	 * This function uses the `sanitize_text_field` function to clean up the text input.
	 *
	 * @param string $input The text input to be sanitized.
	 * @return string The sanitized text.
	 */
	static public function sanitize_text( string $input ): string {
		return sanitize_text_field( $input );
	}

	/**
	 * Sanitizes the input to ensure it is a valid login form system.
	 *
	 * This function checks if the provided input is within the allowed
	 * login form systems. If the input is valid, it returns the input.
	 * Otherwise, it returns 'WordPress' as the default value.
	 *
	 * @param string $input The login form system to be sanitized.
	 * @return string The sanitized login form system.
	 */
	static public function sanitize_login_form_systems( string $input ): string {
		return in_array( $input, self::$login_form_systems, true ) ? $input : 'WordPress';
	}

	/**
	 * Image sanitization callback example.
	 *
	 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
	 * send back the filename, otherwise, return the setting default.
	 *
	 * - Sanitization: image file extension
	 * - Control: text, WP_Customize_Image_Control
	 *
	 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
	 * @param string                $image   Image filename.
	 * @param \WP_Customize_Setting $setting Setting instance.
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	static public function sanitize_image( string $image, \WP_Customize_Setting $setting ): string {
		/*
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		 */
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'tif|tiff'     => 'image/tiff',
		);
		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $image, $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
		return isset( $file['ext'] ) ? $image : $setting->default;
	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @return void Return
	 */
	public static function live_preview(): void {
		$theme_version = wp_get_theme()->get( 'Version' );
		/** @psalm-suppress RedundantConditionGivenDocblockType, DocblockTypeContradiction */
		$theme_version = ! is_array( $theme_version ) ? $theme_version : '';

		wp_enqueue_script(
			// Give the script a unique ID
			'free-template-theme-customizer',
			// Define the path to the JS file
			get_template_directory_uri() . '/assets/js/theme-customizer.js',
			// Define dependencies
			array( 'jquery', 'customize-preview' ),
			// Define a version (optional)
			$theme_version,
			// Specify whether to put in footer (leave this true)
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}
}
