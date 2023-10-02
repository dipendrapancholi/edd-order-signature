<?php
/**
 * Plugin Name: Easy Digital Downloads - Order Signature
 * Plugin URI: https://dharmisoft.com/
 * Description: This plugin allowes you to add signature field in the easy digital downloads checkout page where customer can signature on checkout page and also admin can see the signature image in backend order detail page. Also you can add signature image in various emails.
 * Version: 1.0.0
 * Author: Serveonetech
 * Author URI: https://profiles.wordpress.org/dipendrapancholi/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions 
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
if( !defined( 'EDD_OS_VERSION' ) ) {
	define( 'EDD_OS_VERSION', '1.0.0' );// Plugin Version
}
if( !defined( 'EDD_OS_DIR' ) ) {
	define( 'EDD_OS_DIR', dirname( __FILE__ ) );// Plugin dir
}
if( !defined( 'EDD_OS_URL' ) ) {
	define( 'EDD_OS_URL', plugin_dir_url( __FILE__ ) );// Plugin url
}
if( !defined( 'EDD_OS_INC_DIR' ) ) {
	define( 'EDD_OS_INC_DIR', EDD_OS_DIR . '/includes' );// Plugin include dir
}
if( !defined( 'EDD_OS_INC_URL' ) ) {
	define( 'EDD_OS_INC_URL', EDD_OS_URL . 'includes' );// Plugin include url
}
if( !defined( 'EDD_OS_ADMIN_DIR' ) ) {
	define( 'EDD_OS_ADMIN_DIR', EDD_OS_INC_DIR . '/admin' );// Plugin admin dir
}
if( !defined( 'EDD_OS_BASENAME' ) ) {
	define( 'EDD_OS_BASENAME', basename( EDD_OS_DIR ) ); // base name
}
if( !defined( 'EDD_OS_META_PREFIX' ) ) {
	define( 'EDD_OS_META_PREFIX', '_edd_os_' );// Plugin Prefix
}
if ( ! defined( 'EDD_OS_SIGNATURE_DIR' ) ) {
	define( 'EDD_OS_SIGNATURE_DIR', ABSPATH . 'eddos-images/' );
}
if ( ! defined( 'EDD_OS_SIGNATURE_URL' ) ) {
	define( 'EDD_OS_SIGNATURE_URL', trailingslashit( site_url() ) . 'eddos-images/' );
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_load_textdomain() {
	
	// Set filter for plugin's languages directory
	$edd_os_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$edd_os_lang_dir	= apply_filters( 'edd_os_languages_directory', $edd_os_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'eddos' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'eddos', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $edd_os_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . EDD_OS_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/edd-order-signature folder
		load_textdomain( 'eddos', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/edd-order-signature/languages/ folder
		load_textdomain( 'eddos', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'eddos', false, $edd_os_lang_dir );
	}
}

/**
 * Add plugin action links
 * 
 * Adds a Settings, Support and Docs link to the plugin list.
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_add_plugin_links( $links ) {
	
	$plugin_links = array(
		'<a href="edit.php?post_type=download&page=edd-settings&tab=extensions">' . __( 'Settings', 'eddos' ) . '</a>',
		//'<a target="_blank" href="http://support.serveonetech.com/">' . __( 'Support', 'eddos' ) . '</a>',
		'<a target="_blank" href="http://docs.serveonetech.com/edd-order-signature/">' . __( 'Docs', 'eddos' ) . '</a>'
	);
	
	return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_os_add_plugin_links' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'edd_os_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_install() {
	global $wpdb;
}

//add action to load plugin
add_action( 'plugins_loaded', 'edd_os_plugin_loaded' );

/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_plugin_loaded() {
	
	if( class_exists( 'Easy_Digital_Downloads' ) ) { //check Easy Digital Downloads is activated or not
		
		//Gets the plugin ready for translation
		edd_os_load_textdomain();
		
		/**
		 * Deactivation Hook
		 *
		 * Register plugin deactivation hook.
		 *
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		register_deactivation_hook( __FILE__, 'edd_os_uninstall' );
		
		/**
		 * Plugin Setup (On Deactivation)
		 * 
		 * Delete  plugin options.
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		function edd_os_uninstall() {
		  	global $wpdb;
		}
		
		// Global variables
		global $edd_os_scripts, $edd_os_public, $edd_os_admin;
		
		// Include Misc Functions File
		include_once( EDD_OS_INC_DIR.'/edd-os-misc-functions.php' );
		
		// Script class handles most of script functionalities of plugin
		include_once( EDD_OS_INC_DIR.'/class-edd-os-scripts.php' );
		$edd_os_scripts = new Edd_Os_Scripts();
		$edd_os_scripts->add_hooks();
		
		// Admin class handles most of admin panel functionalities of plugin
		include_once( EDD_OS_INC_DIR.'/class-edd-os-public.php' );
		$edd_os_public = new Edd_Os_Public();
		$edd_os_public->add_hooks();
		
		// Public class handles most of public functionalities of plugin
		include_once( EDD_OS_ADMIN_DIR.'/class-edd-os-admin.php' );
		$edd_os_admin = new Edd_Os_Admin();
		$edd_os_admin->add_hooks();
	}
}