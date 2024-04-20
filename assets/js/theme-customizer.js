/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	/*
	wp.customize( 'YOUR_SETTING_ID', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
		} );
	} );
	*/

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.site-title a' ).html( newval );
		} );
	} );
	
	//Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).html( newval );
		} );
	} );

	//Update site title color in real time...
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( newval ) {
			$('#HeaderCarousel .carousel-caption h1').css('color', newval );
			$('#HeaderCarousel .carousel-caption h3').css('color', newval );
			$('#HeaderCarousel .carousel-caption h3 a').css('color', newval );
			$('#HeaderCarousel .carousel-caption h4').css('color', newval );
			$('#HeaderCarousel .carousel-caption h4 a').css('color', newval );
			$('#HeaderCarousel .carousel-caption p').css('color', newval );
			$('#top-menu.in-top #menu-mainmenu>li>a').css('color', newval );
			$('#top-menu.in-top #top-menu-side>li>a').css('color', newval );
			$('#top-menu.in-top .navbar-header a').css('color', newval );
			$('#top-menu.in-top .icon-bar').css('background-color', newval );
		} );
	} );

	//Update site background color...
	wp.customize( 'background_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background-color', newval );
		} );
	} );
	
} )( jQuery );
