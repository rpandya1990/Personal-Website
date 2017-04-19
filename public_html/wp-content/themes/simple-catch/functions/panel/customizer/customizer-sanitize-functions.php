<?php
/**
 * @package Catch Themes
 * @subpackage Simple Catch
 * @since Simple Catch 3.0
 */


/**
 * Sanitizes Checkboxes
 * @param  $input entered value
 * @return sanitized output
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_checkbox( $input ) {
	if ( "1" == $input ) {
		return "1";
	} 
	else {
		return "0";
   	}
}


/**
 * Sanitizes Custom CSS 
 * @param  $input entered value
 * @return sanitized output
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_custom_css( $input ) {
	if ( $input != '' ) { 
        $input = str_replace( '<=', '&lt;=', $input ); 
        
        $input = wp_kses_split( $input, array(), array() ); 
        
        $input = str_replace( '&gt;', '>', $input ); 
        
        $input = strip_tags( $input ); 

        return $input;
 	}
    else {
    	return '';
    }
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
function simplecatch_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
	// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Sanitizes post_id in slider
 * @param  $input entered value
 * @return sanitized output
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_post_id( $input ) {
    // Ensure $input is an absolute integer.
	$post_id = absint( $input );
	// If $page_id is an ID of a published page, return it; otherwise, return false
	return ( 'publish' == get_post_status( $post_id ) ? $post_id : false );
}


/**
 * Sanitizes category list in slider
 * @param  $input entered value
 * @return sanitized output
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_category_list( $input ) {
	if ( $input != '' ) { 
		$args = array(
						'type'			=> 'post',
						'child_of'      => 0,
						'parent'        => '',
						'orderby'       => 'name',
						'order'         => 'ASC',
						'hide_empty'    => 0,
						'hierarchical'  => 0,
						'taxonomy'      => 'category',
					); 
		
		$categories = ( get_categories( $args ) );

		$category_list 	=	array();
		
		foreach ( $categories as $category )
			$category_list 	=	array_merge( $category_list, array( $category->term_id ) );

		if ( count( array_intersect( $input, $category_list ) ) == count( $input ) ) {
	    	return $input;
	    } 
	    else {
    		return '';
   		}
    }
    else {
    	return '';
    }
}


/**
 * Number Range sanitization callback example.
 *
 * - Sanitization: number_range
 * - Control: number, tel
 * 
 * Sanitization callback for 'number' or 'tel' type text inputs. This callback sanitizes
 * `$number` as an absolute integer within a defined min-max range.
 * 
 * @see absint() https://developer.wordpress.org/reference/functions/absint/
 *
 * @param int                  $number  Number to check within the numeric range defined by the setting.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return int|string The number, if it is zero or greater and falls within the defined range; otherwise,
 *                    the setting default.
 */
function simplecatch_sanitize_number_range( $number, $setting ) {

	// Ensure input is an absolute integer.
	$number = absint( $number );
	
	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;	
	
	// Get minimum number in the range.
	$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );
	
	// Get maximum number in the range.
	$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );
	
	// Get step.
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );
	
	// If the number is within the valid range, return it; otherwise, return the default
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}


/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 * 
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 * 
 * @see sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see $wp_customize->get_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function simplecatch_sanitize_select( $input, $setting ) {
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}


/**
 * Reset all settings to default
 * @param  $input entered value
 * @return sanitized output
 *
 * @since Simple Catch 3.0
 */
function simplecatch_reset_all_settings( $input ) {
	if ( $input == 1 ) {
        // Delete all theme options
        delete_option('simplecatch_options' );
       
        // Flush out all transients	on reset
        simplecatch_themeoption_invalidate_caches();
    } 
    else {
        return "0";
    }
}


/**
 * Dummy Sanitizaition function as it contains no value to be sanitized
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_important_link() {
	return false;
}

/**
 * Reset colors
 * @param  $input entered value
 * @return nothing
 *
 * @since Simple Catch 3.0
 */
function simplecatch_sanitize_reset_color( $input ) {
	//Reset Header Featured Image Options
	if( $input == 1 ) {
		global $simplecatch_options_settings, $simplecatch_options_defaults;
    	
    	$options = $simplecatch_options_settings;	
	
		$defaults = $simplecatch_options_defaults;

		//Reset Header Text Color
		set_theme_mod( 'header_textcolor', '#444' );

		//Reset Background Color
		set_theme_mod( 'background_color', '' );

		$options[ 'color_scheme' ] 			= $defaults[ 'color_scheme' ];
		$options[ 'heading_color' ] 		= $defaults[ 'heading_color' ];
		$options[ 'meta_color' ] 			= $defaults[ 'meta_color' ];
		$options[ 'text_color' ] 			= $defaults[ 'text_color' ];
		$options[ 'link_color' ] 			= $defaults[ 'link_color' ];
		$options[ 'widget_heading_color' ] 	= $defaults[ 'widget_heading_color' ]; 
		$options[ 'widget_text_color' ] 	= $defaults[ 'widget_text_color' ]; 

		update_option( 'simplecatch_options', $options );
	}
}