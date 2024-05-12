<?php
/**
 * Contains methods for customizing the theme customization screen.
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

namespace FreeTemplate;

class Customizer {
	
	static $login_form_systems = array(
		'WordPress'		=> 'WordPress',
		'WooCommerce'	=> 'WooCommerce',
		'WHMCS'			=> 'WHMCS',
	);

   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    * 
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *  
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    */
	public static function register( $wp_customize ) {

		$wp_customize->add_section( 'free-template' . '-options', array(
				'title'       		=> esc_html__( 'Theme Options', 'free-template' ),										//Visible title of section
				'priority'    		=> 20,																										//Determines what order this appears in
				'capability'  	=> 'edit_theme_options',																			//Capability needed to tweak
				'description'	=> esc_html__('Allows you to customize settings for Theme.', 'free-template'),	//Descriptive tooltip
			)
		);
 		$wp_customize->add_setting( 'bootstrap_theme_name',								//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'default'    		=> 'default',																//Default setting/value to save
				'type'      		=> 'theme_mod', 														//Is this an 'option' or a 'theme_mod'?
				'capability'		=> 'edit_theme_options', 											//Optional. Special permissions for accessing this setting.
				'sanitize_callback'		=> '\FreeTemplate\Customizer::sanitize_text',
				//'transport'	=> 'postMessage', 														//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
			)
		);
		/* Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
		 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly. */
		$wp_customize->add_control( new \WP_Customize_Control(
			$wp_customize, 																					//Pass the $wp_customize object (required)
			'bootstrap_theme_name', 																	//Set a unique ID for the control
			array(
				'label'      		=> esc_html__( 'Select Theme Name', 'free-template' ),	//Admin-visible name of the control
				'description'	=> esc_html__( 'Using this option you can change the theme colors', 'free-template' ),
				'settings'		=> 'bootstrap_theme_name', 										//Which setting to load and manipulate (serialized is okay)
				'priority'			=> 10, 																		//Determines the order this control appears in for the specified section
				'section'			=> 'free-template' . '-options', 										//ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'type'			=> 'select',
				'choices'		=> array(
					'default' 	=> esc_html__( 'Default', 'free-template' ),
					'cerulean' 	=> 'Cerulean',
					'cosmo'		=> 'Cosmo',
					'cyborg' 	=> 'Cyborg',
					'darkly' 		=> 'Darkly',
					'flatly' 		=> 'Flatly',
					'journal'		=> 'Journal',
					'lumen'		=> 'Lumen',
					'paper'		=> 'Paper',
					'readable'	=> 'Readable',
					'sandstone'=> 'Sandstone',
					'simplex'		=> 'Simplex',
					'slate'		=> 'Slate',
					'spacelab'	=> 'Spacelab',
					'superhero'	=> 'Superhero',
					'united'		=> 'United',
					'yeti'			=> 'Yeti',
				)
			)
		) );

		
		if ( function_exists( 'wp_statistics_pages' )){
			$wp_customize->add_setting( 'display_visits',		//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
				array(
					'default'			=> true,							//Default setting/value to save
					'type'			=> 'theme_mod', 				//Is this an 'option' or a 'theme_mod'?
					'capability'		=> 'edit_theme_options', 	//Optional. Special permissions for accessing this setting.
					'theme_supports'	=> array(), 				//Theme features required to support the panel. Default is none.
					'transport'		=> 'refresh',						//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
					'validate_callback'	=> '',
					'sanitize_callback'		=> '\FreeTemplate\Customizer::sanitize_checkbox',
					'dirty'					=> '',
				)
			);
			/* Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
			 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly. */
			$wp_customize->add_control( new \WP_Customize_Control(
				$wp_customize, 																				//Pass the $wp_customize object (required)
				'display_visits', 																				//Set a unique ID for the control
				array(
					'settings'			=> 'display_visits', 												//Which setting to load and manipulate (serialized is okay)
					'capability'			=> 'edit_theme_options', 									//Optional. Special permissions for accessing this setting.
					'priority'				=> 13, 																//Determines the order this control appears in for the specified section , Default: 10
					'section'				=> 'free-template' . '-options', 								//ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'label'				=> esc_html__( 'Display visits?', 'free-template' ),	//Admin-visible name of the control
					'description'		=> esc_html__( 'Display number of visits in pages and posts', 'free-template' ),
					'input_attrs'		=> array(),														// List of custom input attributes for control output, where attribute names are the keys and values are the values.
																													// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
					'allow_addition'	=> false,															// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
					'active_callback'	=> array(),
					'type'				=> 'checkbox',													// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
																													// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
					/*
					'choices'			=> [																	// List of choices for 'radio' or 'select' type controls
						'yes'	=> esc_html__( 'Yes', 'free-template' ),
						'no'	=> esc_html__( 'No', 'free-template' ),
					],
					*/
				)
			) );
		}
		
		$wp_customize->add_section( 'free-template' . '-login-form-options', 
			array(
				'title'				=> esc_html__( 'Popup Login Form', 'free-template' ),												//Visible title of section
				'priority'			=> 22,																												//Determines what order this appears in
				'capability'		=> 'edit_theme_options',																					//Capability needed to tweak
				'description'	=> esc_html__('Allows you to customize login link and login form.', 'free-template'),	//Descriptive tooltip
			)
		);
		$wp_customize->add_setting( 'display_login_link',		//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'type'					=> 'theme_mod',				//Is this an 'option' or a 'theme_mod'?
				'capability'				=> 'edit_theme_options',	//Optional. Special permissions for accessing this setting.
				'theme_supports'	=> array(),						//Theme features required to support the panel. Default is none.
				'default'					=> false,							//Default value for the setting. Default is empty string.
				'transport'				=> 'refresh',						//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'validate_callback'	=> '',
				'sanitize_callback'		=> '\FreeTemplate\Customizer::sanitize_checkbox',
				'dirty'					=> '',
			)
		);
		$wp_customize->add_control( new \WP_Customize_Control(
			$wp_customize,																					//Pass the $wp_customize object (required)
			'display_login_link',																				//Set a unique ID for the control
			array(
				'settings'			=> 'display_login_link',												//Which setting to load and manipulate (serialized is okay)
				'capability'			=> 'edit_theme_options',										//Optional. Special permissions for accessing this setting.
				'priority'				=> 11,																	//Determines the order this control appears in for the specified section , Default: 10
				'section'				=> 'free-template' . '-login-form-options',						//ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'label'				=> esc_html__( 'Display login link?', 'free-template' ),	//Admin-visible name of the control
				'description'		=> esc_html__( 'Display a link on topmenu for login user', 'free-template' ),
				'input_attrs'		=> array(),															// List of custom input attributes for control output, where attribute names are the keys and values are the values.
																													// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
				'allow_addition'	=> false,																// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
				'active_callback'	=> array(),
				'type'				=> 'checkbox',														// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
																													// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
				/*
				'choices'			=> [																		// List of choices for 'radio' or 'select' type controls
					'yes'	=> esc_html__( 'Yes', 'free-template' ),
					'no'	=> esc_html__( 'No', 'free-template' ),
				],
				*/
			)
		) );
		$wp_customize->add_setting( 'login_link_text',				//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'type'						=> 'theme_mod',				//Is this an 'option' or a 'theme_mod'?
				'capability'					=> 'edit_theme_options',	//Optional. Special permissions for accessing this setting.
				'theme_supports'		=> array(),						//Theme features required to support the panel. Default is none.
				'default'						=> 'Login',							//Default value for the setting. Default is empty string.
				'transport'					=> 'refresh',						//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'validate_callback'		=> '',
				'sanitize_callback'			=> '\FreeTemplate\Customizer::sanitize_login_link_texts',
				'dirty'						=> '',
			)
		);
		$wp_customize->add_control( new \WP_Customize_Control(
			$wp_customize,																				//Pass the $wp_customize object (required)
			'login_link_text',																				//Set a unique ID for the control
			array(
				'settings'			=> 'login_link_text',											//Which setting to load and manipulate (serialized is okay)
				'capability'			=> 'edit_theme_options',									//Optional. Special permissions for accessing this setting.
				'priority'				=> 12,																//Determines the order this control appears in for the specified section , Default: 10
				'section'				=> 'free-template' . '-login-form-options',					//ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'label'				=> esc_html__( 'Login link text', 'free-template' ),	//Admin-visible name of the control
				'description'		=> esc_html__( 'Please select the login link text', 'free-template' ),
				'input_attrs'		=> array(),														// List of custom input attributes for control output, where attribute names are the keys and values are the values.
																												// Not used for 'checkbox', 'radio', 'select', 'textarea', or 'dropdown-pages' control types. Default empty array.
				'allow_addition'	=> false,															// (bool) Show UI for adding new content, currently only used for the dropdown-pages control. Default false.
				'active_callback'	=> array(),
				'type'				=> 'select',														// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
																												// Additional input types such as 'email', 'url', 'number', 'hidden', and 'date' are supported implicitly. Default 'text'.
				'choices'			=>  FREE_TEMPLATE()::login_link_texts(),									// List of choices for 'radio' or 'select' type controls
			)
		) );

		$wp_customize->add_setting( 'header_text_change_content',				//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'type'						=> 'theme_mod',				//Is this an 'option' or a 'theme_mod'?
				'capability'					=> 'edit_theme_options',	//Optional. Special permissions for accessing this setting.
				'theme_supports'		=> array(),						//Theme features required to support the panel. Default is none.
				'default'						=> '',							//Default value for the setting. Default is empty string.
				'transport'					=> 'refresh',						//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'validate_callback'		=> '',
				'sanitize_callback'			=> '\FreeTemplate\Customizer::sanitize_text',
				'dirty'						=> '',
			)
		);
		$wp_customize->add_control(
			new \FreeTemplate\Customizer_Library_Content(
				$wp_customize,
				'header_text_change_content',
				array(
					'settings'			=> 'header_text_change_content',											//Which setting to load and manipulate (serialized is okay)
					'section'				=> 'header_image',					//ID of the section this control should render in (can be one of yours, or a WordPress default section)
					'capability'			=> 'edit_theme_options',									//Optional. Special permissions for accessing this setting.
					'priority'				=> 10,																//Determines the order this control appears in for the specified section , Default: 10
					'label'				=> sprintf('<span style="color: red">%s</span>', esc_html__( 'How to modify frontpage header texts?', 'free-template' )),	//Admin-visible name of the control
					'description'		=> '<p style="text-align: justify">' . esc_html__('You can modify texts of header images when you are uploading them by modifing Title, Description and Alt fields. ', 'free-template' ) . 
												/* translators: %1$s: Relaces with link tag, %2$s: Relaces with link tag */
												sprintf(esc_html__('You can also modify them via %1$sMedia Manager%2$s. ', 'free-template'),
													sprintf('<a href="%s">', esc_url(get_admin_url( '', 'upload.php?mode=grid' ) ) ) ,
													'</a>'
												) .
												/* translators: %1$s: Relaces with link tag, %2$s: Relaces with link tag */
												sprintf(esc_html__('If you cropped your image while uploading the file, So you need to use %1$sMedia Manager/List Mode%2$s to find those cropped images.', 'free-template'),
													sprintf('<a href="%s">', esc_url(get_admin_url( '', 'upload.php?mode=list' ) ) ),
													'</a>'
												) . '</p>',
					'type'				=> 'content',														// Control type. Core controls include 'text', 'checkbox', 'textarea', 'radio', 'select', and 'dropdown-pages'.
				)
			)
		);
		
		$wp_customize->add_setting( 'display_featured_in_header',		//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'default'				=> 'no',							//Default setting/value to save
				'type'				=> 'theme_mod',				//Is this an 'option' or a 'theme_mod'?
				'capability'			=> 'edit_theme_options',	//Optional. Special permissions for accessing this setting.
				//'transport'		=> 'postMessage', 				//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'sanitize_callback'	=> '\FreeTemplate\Customizer::sanitize_text',
			)
		);
		/* Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
		 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly. */
		$wp_customize->add_control( new \WP_Customize_Control(
			$wp_customize, 																									//Pass the $wp_customize object (required)
			'display_featured_in_header', 																								//Set a unique ID for the control
			array(
				'label'			=> esc_html__( 'Display featured image in header', 'free-template' ),	//Admin-visible name of the control
				'description'	=> esc_html__( 'To display the featured image of the post/page in header area, Select this option to Yes! So your selected image will be display in header area as background for that post/page', 'free-template' ),
				'settings'		=> 'display_featured_in_header', 																	//Which setting to load and manipulate (serialized is okay)
				'priority'			=> 11, 																						//Determines the order this control appears in for the specified section
				'section'			=> 'header_image', 														//ID of the section this control should render in (can be one of yours, or a WordPress default section)
				'type'			=> 'select',
				'choices'		=> array(
					'no' 	=> esc_html__( 'No', 'free-template' ),
					'yes' 	=> esc_html__('Yes', 'free-template'),
				)
			)
		) );

		$wp_customize->add_setting( 'default_header_background',		//No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
			array(
				'default'				=> get_stylesheet_directory_uri() . '/assets/images/header-bg.jpg',	//Default setting/value to save
				'type'				=> 'theme_mod',				//Is this an 'option' or a 'theme_mod'?
				'capability'			=> 'edit_theme_options',	//Optional. Special permissions for accessing this setting.
				//'transport'		=> 'postMessage', 				//What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'sanitize_callback'	=> '\FreeTemplate\Customizer::sanitize_image',
			)
		);
		$wp_customize->add_control(
			new \WP_Customize_Upload_Control(
			$wp_customize, 																									//Pass the $wp_customize object (required)
			'default_header_background', 																								//Set a unique ID for the control
			array(
				'label'			=> esc_html__( 'Default header background image', 'free-template' ),	//Admin-visible name of the control
				'description'	=> '<p style="text-align: justify">' . esc_html__( 'if you like to change default header background image for all pages, you can select an image here.', 'free-template' ) . '</p>',
				'settings'		=> 'default_header_background', 																	//Which setting to load and manipulate (serialized is okay)
				'priority'			=> 12, 																						//Determines the order this control appears in for the specified section
				'section'			=> 'header_image', 														//ID of the section this control should render in (can be one of yours, or a WordPress default section)
			) ) 
		);

		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
		$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->default = 'fff';
		$wp_customize->get_setting( 'background_color' )->default = 'inherit';
	  
	}


	static function sanitize_checkbox($input){
		return ( isset( $input ) && true === (bool) $input ? true : false );
	}
	
	static function sanitize_login_link_texts( $input ) {
		return (array_key_exists($input, FREE_TEMPLATE()::login_link_texts())) ? $input :  'Login';
	}

	static function sanitize_text($input) {
		return (sanitize_text_field($input));
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
	 *
	 * @param string               $image   Image filename.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	static function sanitize_image( $image, $setting ) {
		/*
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		 */
		$mimes = array(
			'jpg|jpeg|jpe'	=> 'image/jpeg',
			'gif'				=> 'image/gif',
			'png'				=> 'image/png',
			'tif|tiff'			=> 'image/tiff',
		);
		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $image, $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
		return ( $file['ext'] ? $image : $setting->default );
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
	*/
	public static function live_preview() {
		wp_enqueue_script(
			'free-template' . '-theme-customizer', // Give the script a unique ID
			get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
			array('jquery', 'customize-preview'), // Define dependencies
			wp_get_theme()->get( 'Version' ), // Define a version (optional) 
			true // Specify whether to put in footer (leave this true)
		);
	}

}
