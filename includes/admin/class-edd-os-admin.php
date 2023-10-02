<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 * 
 * Handles all the different features and functions
 * for the admin pages.
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */

if( !class_exists( 'Edd_Os_Admin' ) ) {
	
	class Edd_Os_Admin {
		
		public function __construct() {
			
		}
		
		/**
		 * Register Settings
		 * 
		 * Handels to add settings in settings page
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_signature_settings( $settings ) {
			
			$edd_os_settings = array(
				array(
						'id'	=> 'edd_os_settings',
						'name'	=> '<strong>' . __( 'Signature Options', 'eddos' ) . '</strong>',
						'desc'	=> __( 'Configure Signature Settings.', 'eddos' ),
						'type'	=> 'header'
					),
				array(
						'id'	=> 'eddos_enable_signature',
						'name'	=> __( 'Enable Signature:', 'eddos' ),
						'desc'	=> '<p class="description">'.__( 'If you want to use the signature option on your site, then you have to enable this setting.', 'eddos' ).'</p>',
						'type'	=> 'checkbox'
					),
				array(
						'id'	=> 'eddos_enable_required',
						'name'	=> __( 'Enable Signature Required:', 'eddos' ),
						'desc'	=> '<p class="description">'.__( 'If you want to use the signature field is required when place the order then please enable this option.', 'eddos' ).'</p>',
						'type'	=> 'checkbox'
					),
				array(
						'id'	=> 'eddos_display_payment_detail_page',
						'name'	=> __( 'Display On Payment Detail Page:', 'eddos' ),
						'desc'	=> '<p class="description">'.__( 'If you want to display the signature on payment detail page then please enable this option.', 'eddos' ).'</p>',
						'type'	=> 'checkbox'
					),
				array(
						'id'	=> 'eddos_display_purchase_reciept_email',
						'name'	=> __( 'Display On Purchase Reciept Email:', 'eddos' ),
						'desc'	=> '<p class="description">'.__( 'If you want to display the signature on purchase reciept email then please enable this option.', 'eddos' ).'</p>',
						'type'	=> 'checkbox'
					),
				array(
						'id'	=> 'eddos_display_new_sale_email',
						'name'	=> __( 'Display On New Sale Email:', 'eddos' ),
						'desc'	=> '<p class="description">'.__( 'If you want to display the signature on new sale email then please enable this option.', 'eddos' ).'</p>',
						'type'	=> 'checkbox'
					),
				);
			
			// If EDD is at version 2.5 or later
		    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
		    	$edd_os_settings = array( 'eddos' => $edd_os_settings );
		    }
			
			return array_merge( $settings, $edd_os_settings );
		}
		
		/**
		 * Add plugin section in extension settings
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_signature_settings_section( $sections ) {
			
			$sections['eddos'] = __( 'Signature', 'eddos' );
		 	return $sections;
		}
		
		/**
		 * Validate Settings
		 * 
		 * Handles to validate settings
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_signature_settings_validate( $input ) {
			
			return $input;
		}
		
		/**
		 * Display Signature Image on Order Page
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_checkout_field_display_admin_order_meta( $payment_id ) {
			
			edd_display_signature( $payment_id, true, false, 'h3' );
		}
		
		/**
		 * Add Admin Hook
		 * 
		 * Handle to add admin hooks
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function add_hooks() {
			
			// display signature image
			add_action( 'edd_payment_view_details', array($this, 'edd_os_checkout_field_display_admin_order_meta' ), 10 , 1 );
			
			// Add filter to add settings in extension tab
			add_filter( 'edd_settings_extensions', array( $this, 'edd_os_signature_settings' ) );
			
			//add filter to section setting
			add_filter( 'edd_settings_sections_extensions', array( $this, 'edd_os_signature_settings_section' ) );
			
			// Add filter to validate settings in extension tab
			add_filter( 'edd_settings_extensions-eddos_sanitize', array( $this, 'edd_os_signature_settings_validate') );
		}
	}
}