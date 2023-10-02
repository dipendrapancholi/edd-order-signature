<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Script Class
 * 
 * Handles all the JS and CSS include
 * on fron and backend
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
class Edd_Os_Scripts {
	
	public function __construct() {
		
	}
	
	/**
	 * Register Public Script
	 * 
	 * @package Easy Digital Downloads - Order Signature
	 * @since 1.0.0
	 */
	public function edd_os_public_scripts() {
		
		// enable signature functionality
		$enable_signature	= edd_os_enable_signature();
		
		if( $enable_signature == 1 ) { 
			
			// Register & Enqueue jSignature
			wp_register_script( 'edd-os-app', EDD_OS_URL . 'assets/js/edd-os-app.js', array( 'jquery' ), EDD_OS_VERSION, true );
			wp_register_script( 'edd-os-signature-pad', EDD_OS_URL . 'assets/js/edd-os-signature-pad.js', array( 'jquery' ), EDD_OS_VERSION, true );
			wp_register_style( 'edd-os-public-custom-style', EDD_OS_URL . 'assets/css/edd-os-public.css', array(), EDD_OS_VERSION );
		}
	}
	
	/**
	 * Enqueue Public Script
	 * 
	 * @package Easy Digital Downloads - Order Signature
	 * @since 1.0.0
	 */
	public function edd_os_public_scripts_enqueue() {
		
		// enable signature functionality
		$enable_signature	= edd_os_enable_signature();
		
		if( $enable_signature == 1 ) { 
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'edd-os-app' );
			wp_enqueue_script( 'edd-os-signature-pad' );
			wp_enqueue_style( 'edd-os-public-custom-style' );
		}
	}
	
	/**
	 * Add Script Hook
	 * 
	 * Handle to add script hooks
	 * 
	 * @package Easy Digital Downloads - Order Signature
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'edd_os_public_scripts' ), 100 );
		add_action( 'edd_after_purchase_form', array( $this, 'edd_os_public_scripts_enqueue' ), 100 );
	}
}