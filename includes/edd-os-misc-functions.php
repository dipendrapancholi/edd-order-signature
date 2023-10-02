<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Misc Functions
 * 
 * All misc functions handles to 
 * different functions 
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */

/**
 * Create Signature Directory
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_create_signature_directory() {
	
	$files = array(
		array(
			'base' 		=> EDD_OS_SIGNATURE_DIR,
			'file' 		=> 'index.html',
			'content' 	=> ''
		)
	);
	
	foreach ( $files as $file ) {
		if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
			if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
				fwrite( $file_handle, $file['content'] );
				fclose( $file_handle );
			}
		}
	}
}

/**
 * Check Signature is Enable Or Not
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_enable_signature() {
	
	$enable_signature	= edd_get_option( 'eddos_enable_signature' );
	return apply_filters( 'edd_os_enable_signature', $enable_signature );
}

/**
 * Display Signature
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_display_signature( $payment_id = '', $echo = true, $convert_image = false, $title_tag = 'h2' ) {
	
	$prefix	= EDD_OS_META_PREFIX;
	
	// initilize signature field
	$signature_html	= '';
	
	if( !empty( $payment_id ) ) { // If order id is not empty
		
		// get image string
		$payment_meta	= edd_get_payment_meta( $payment_id );
		$image_str		= isset( $payment_meta[$prefix.'hidden_signature'] ) ? $payment_meta[$prefix.'hidden_signature'] : '';
		
		if( !empty( $image_str ) ) { // if image string is not empty
			
			if( $convert_image ) { // If need to create signature image
				
				if( !is_dir( EDD_OS_SIGNATURE_DIR ) ) { // if directory not exit
					edd_os_create_signature_directory();
				}
				
				$image_file_path	= EDD_OS_SIGNATURE_DIR . 'edd-os-' . $payment_id . '.png';
				$image_file_source	= EDD_OS_SIGNATURE_URL . 'edd-os-' . $payment_id . '.png';
				
				if( !file_exists( $image_file_path ) ) { // if file not exist
					
					$data	= base64_decode( preg_replace( '#^data:image/\w+;base64,#i', '', $image_str ) );
					file_put_contents( $image_file_path, $data );
				}
				
			} else {
				
				$image_file_source	= $image_str;
			}
			
			$signature_html .= '<'. $title_tag . '>' . edd_os_signature_title() . '</' . $title_tag . '>';
			$signature_html .= '<img src="' . $image_file_source . '" id="sign-img">';
		}
	}
	
	// Modify signature html
	$signature_html	= apply_filters( 'edd_display_signature', $signature_html, $payment_id, $echo, $title_tag );
	
	if( $echo ) {
		echo $signature_html;
	} else {
		return $signature_html;
	}
}

/**
 * Display Signature
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_signature_title() {
	
	$signature_title	= edd_os_display_message( 'signature_title_text' );
	return apply_filters( 'edd_os_signature_title', $signature_title );
}

/**
 * Display Message
 * 
 * @package Easy Digital Downloads - Order Signature
 * @since 1.0.0
 */
function edd_os_display_message( $message_key = '' ) {
	
	$messages	= apply_filters( 'edd_os_get_all_message', array(
										'signature_error_msg'		=> __( 'Please enter your signature and save this.', 'eddos' ),
										'signature_remember_msg'	=> '<strong>'. __( 'Remember: ', 'eddos' ) .'</strong>' . __( 'Please click on save button to save signature.', 'eddos' ),
										'clear_btn_text'			=> __( 'Clear', 'eddos' ),
										'save_btn_text'				=> __( 'Save', 'eddos' ),
										'signature_title_text'		=> __( 'Signature', 'eddos' ),
									));
									
	if( !empty( $message_key ) ) {
		return isset( $messages[$message_key] ) ? $messages[$message_key] : '';
	} else {
		return $messages;
	}
}