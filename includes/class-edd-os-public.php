<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Public Collection Pages Class
 * 
 * Handles all the different features and functions
 * for the front end pages.
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */

if( !class_exists( 'Edd_Os_Public' ) ) {
	
	class Edd_Os_Public {
		
		public function __construct() {
			
		}
		
		/**
		 * Add Signature Field
		 * 
		 * Handle to add signature field on checkout page
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_checkout_fields() { 
			
			$prefix	= EDD_OS_META_PREFIX; 
			
			// enable signature functionality
			$enable_signature	= edd_os_enable_signature();
			
			// signature validation is enable or not
			$required_signature	= edd_get_option( 'eddos_enable_required' );
			
			$signature_remember_msg	= edd_os_display_message( 'signature_remember_msg' );
			$clear_btn_text			= edd_os_display_message( 'clear_btn_text' );
			$save_btn_text			= edd_os_display_message( 'save_btn_text' );
			
			if( $enable_signature == 1 ) { ?>
	 			
				<div id="signature-pad" class="m-signature-pad">
					<p class="m-signature-title"><?php echo edd_os_signature_title(); if( $required_signature == 1 ) { ?><span class="edd-required-indicator">*</span><?php }?></p>
					<div class="m-signature-pad--body">
						<canvas></canvas>
					</div>
					<div class="m-signature-pad--footer">
						<input type="hidden" value="" name="<?php echo $prefix;?>hidden_signature" id="eddos_hidden_signature" />
						<button type="button" class="button clear eddos-button" data-action="clear"><?php echo $clear_btn_text;?></button>
						<button type="button" class="button save eddos-button" data-action="save"><?php echo $save_btn_text;?></button>
					</div>
					<i class="description"><?php echo $signature_remember_msg;?></i>
				</div><?php
			}
		}
		
		/**
		 * Validate Signature Field
		 * 
		 * Handle to validate signature field on checkout page
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_checkout_signature_error() {
			
			$prefix	= EDD_OS_META_PREFIX;
			
			// enable signature functionality
			$enable_signature	= edd_os_enable_signature();
			
			// signature validation is enable or not
			$required_signature	= edd_get_option( 'eddos_enable_required' );
			
			if( $enable_signature == 1 && $required_signature == 1 && empty( $_POST[$prefix.'hidden_signature'] ) ) {
				
				$error_message = __( 'Please enter your signature and save this.', 'eddos' );
				
				//set error to show to user
				edd_set_error( 'edd_signature', $error_message );
			}
		}
		
		/**
		 * Save Signature Field
		 * 
		 * Handle to save signature field on checkout page
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_checkout_field_update_order_meta( $payment_meta ) {
			
			$prefix	= EDD_OS_META_PREFIX;
			
			if ( !empty( $_POST[$prefix.'hidden_signature'] ) ) {
				$payment_meta[$prefix.'hidden_signature']	= $_POST[$prefix.'hidden_signature'];
			}
			
			return $payment_meta;
		}
		
		/**
		 * Signature Image Display On Order Detail page
		 * 
		 * Handle to display signature on  order detail page.
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_order_details_after_order_table( $payment, $edd_receipt_args ) {
			
			// enable signature functionality
			$enable_signature	= edd_os_enable_signature();
			
			// signature is enable for this template or not
			$display_enable	= edd_get_option( 'eddos_display_payment_detail_page' );
			
			if( $enable_signature == 1 && $display_enable == 1 ) { // if signature functionality is enable
				
				$payment_id		= isset( $payment->ID ) ? $payment->ID : '';
				edd_display_signature( $payment_id, true, false, 'h3' );
			}
		}
		
		/**
		 * Signature Image Display In Email
		 * 
		 * Handle to display signature in email
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_purchase_reciept_email_signature_field( $email_body, $payment_id, $payment_data ) {
			
			// enable signature functionality
			$enable_signature	= edd_os_enable_signature();
			
			// signature is enable for this template or not
			$display_enable	= edd_get_option( 'eddos_display_purchase_reciept_email' );
			
			if( $enable_signature == 1 && $display_enable == 1 ) { // if signature functionality is enable
				
				$email_body .= edd_display_signature( $payment_id, false, true, 'h3' );
			}
			
			return $email_body;
		}
		
		/**
		 * Signature Image Display In Email
		 * 
		 * Handle to display signature in email
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function edd_os_new_sale_email_signature_field( $email_body, $payment_id, $payment_data ) {
			
			// enable signature functionality
			$enable_signature	= edd_os_enable_signature();
			
			// signature is enable for this template or not
			$display_enable	= edd_get_option( 'eddos_display_new_sale_email' );
			
			if( $enable_signature == 1 && $display_enable == 1 ) { // if signature functionality is enable
				
				$email_body .= edd_display_signature( $payment_id, false, true, 'h3' );
			}
			
			return $email_body;
		}
		
		/**
		 * Add Public Hook
		 * 
		 * Handle to add public hooks
		 * 
		 * @package Easy Digital Downloads - Order Signature
		 * @since 1.0.0
		 */
		public function add_hooks() {
			
			//add custom fields to checkout page
			add_action( 'edd_purchase_form_user_info_fields', array( $this, 'edd_os_checkout_fields' ) );
			
			//add action to add error on checkout page when user purchase download
			add_action( 'edd_checkout_error_checks', array( $this, 'edd_os_checkout_signature_error' ) );
			
			//save checkout custom meta
			add_action( 'edd_payment_meta', array( $this, 'edd_os_checkout_field_update_order_meta' ), 10, 1 );
			
			//add action to add an extra detail on order confirmation page
			add_action( 'edd_payment_receipt_after_table', array( $this, 'edd_os_order_details_after_order_table' ), 10, 2 );
			
			// display signature in purchase reciept email
			add_action( 'edd_purchase_receipt', array( $this, 'edd_os_purchase_reciept_email_signature_field' ), 10, 3 );
			
			// display signature in new sale email
			add_action( 'edd_sale_notification', array( $this, 'edd_os_new_sale_email_signature_field' ), 10, 3 );
		}
	}
}