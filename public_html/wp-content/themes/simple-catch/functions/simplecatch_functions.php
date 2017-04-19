<?php
/**
 * Register jquery scripts
 *
 * @register jquery cycle and custom-script
 * hooks action wp_enqueue_scripts
 */
function simplecatch_scripts_method() {
	global $post, $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	//Register 978 grid to add it in main stylesheet as dependency
	wp_register_style( 'simplecatch_grid', get_template_directory_uri() . '/css/978.css' );

	//Register Google Font Style
	wp_register_style( 'simplecatch_web_fonts', simplecatch_load_google_fonts(), array(), null );

	// Enqueue catchevolution Sytlesheet
	wp_enqueue_style( 'simplecatch_style', get_stylesheet_uri(), array( 'simplecatch_grid', 'simplecatch_web_fonts' ) );

	/**
	 * Loads up Color Scheme
	 */
	$color_scheme = $options['color_scheme'];
	if ( 'dark' == $color_scheme ) {
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/css/dark.css', array(), null );
	}
	elseif ( 'brown' == $color_scheme ) {
		wp_enqueue_style( 'brown', get_template_directory_uri() . '/css/brown.css', array(), null );
	}

	//Register JQuery circle all and JQuery set up as dependent on Jquery-cycle
	wp_register_script( 'jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', array( 'jquery' ), '20140315', true );

	//Enqueue Slider Script only in Front Page
	if ( is_front_page() || is_home() ) {
		wp_enqueue_script( 'simplecatch_slider', get_template_directory_uri() . '/js/simplecatch_slider.js', array( 'jquery-cycle' ), '20140315', true );
	}

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//Enqueue Search Script
	wp_enqueue_script ( 'simplecatch_search', get_template_directory_uri() . '/js/simplecatch_search.js', array( 'jquery' ), '1.0', true );

	//Browser Specific Enqueue Script i.e. for IE 1-6
	$simplecatch_ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/(?i)msie [1-6]/',$simplecatch_ua)) {
		wp_enqueue_script( 'pngfix', get_template_directory_uri() . '/js/pngfix.min.js' );
	}
	 if(preg_match('/(?i)msie [1-8]/',$simplecatch_ua)) {
	 	wp_enqueue_style( 'iebelow8', get_template_directory_uri() . '/css/ie.css', true );
	}

} // simplecatch_scripts_method
add_action( 'wp_enqueue_scripts', 'simplecatch_scripts_method' );


/**
 * Register Google Font Style
 *
 * @uses wp_register_style and wp_enqueue_style
 * @action wp_enqueue_scripts
 */
function simplecatch_load_google_fonts() {
    $fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Lobster, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$lora = _x( 'on', 'Lobster font: on or off', 'simple-catch' );

	if ( 'off' !== $lora ) {
		$font_families[] = 'Lobster';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
	}
	return esc_url_raw( $fonts_url );
}


/**
 * Register script for admin section
 *
 * No scripts should be enqueued within this function.
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 * @uses wp_register_script
 * @action admin_enqueue_scripts
 */
function simplecatch_register_js() {
	//jQuery Cookie
	wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'simplecatch_register_js' );


/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 * @uses filter excerpt_length
 */
function simplecatch_excerpt_length( $length ) {
	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	return $options[ 'excerpt_length' ];
}
add_filter( 'excerpt_length', 'simplecatch_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
function simplecatch_continue_reading() {
	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$more_tag_text = $options[ 'more_tag_text' ];
	return ' <a class="readmore" href="'. esc_url( get_permalink() ) . '">' . sprintf( __( '%s', 'simple-catch' ), esc_attr( $more_tag_text ) ) . '</a>';
}


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with simplecatch_continue_reading().
 *
 */
function simplecatch_excerpt_more( $more ) {
	return ' &hellip;' . simplecatch_continue_reading();
}
add_filter( 'excerpt_more', 'simplecatch_excerpt_more' );


/**
 * Adds Continue Reading link to post excerpts.
 *
 * function tied to the get_the_excerpt filter hook.
 */
function simplecatch_custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= simplecatch_continue_reading();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'simplecatch_custom_excerpt' );


if ( ! function_exists( 'simplecatch_headerdetails' ) ) :
/**
 * Get the header logo Image from theme options
 *
 * @uses header logo
 * @get the data value of image from theme options
 * @display Header Image logo
 *
 * @uses default logo if logo field on theme options is empty
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_headerdetails() {
	//delete_transient( 'simplecatch_headerdetails' );

	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	if ( ( !$simplecatch_headerdetails = get_transient( 'simplecatch_headerdetails' ) ) && ( !empty( $options[ 'featured_logo_header' ] ) || empty( $options[ 'remove_site_title' ] ) || empty( $options[ 'remove_site_description' ] ) ) ) {
		echo '<!-- refreshing cache -->';
		$simplecatch_headerdetails = '<div class="logo-wrap">';
		if( empty ($options[ 'remove_header_logo' ] ) ) {
			$simplecatch_headerdetails .= '<div id="site-logo"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'">';

				// if not empty featured_logo_footer on theme options
				if ( !empty( $options[ 'featured_logo_header' ] ) ) :
					$simplecatch_headerdetails .= '<img src="'.esc_url( $options[ 'featured_logo_header' ] ).'" alt="'.get_bloginfo( 'name' ).'" />';
				else:
					// if empty featured_logo_footer on theme options, display default Header Logo
					$simplecatch_headerdetails .='<img src="'. get_template_directory_uri().'/images/logo-head.png" alt="logo" />';
				endif;

			$simplecatch_headerdetails .= '</a></div>';
		}

		if( empty( $options[ 'remove_site_title' ] ) || empty( $options[ 'remove_site_description' ] ) ) {
			$simplecatch_headerdetails .= '<div id="site-details">';

			if ( empty( $options[ 'remove_site_title' ] ) ) {
				$simplecatch_headerdetails .= '<h1 id="site-title"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'">'.esc_attr( get_bloginfo( 'name', 'display' ) ).'</a></h1>';
			}

			if ( empty( $options[ 'remove_site_description' ] ) ) {
				$simplecatch_headerdetails .= '<h2 id="site-description">'.esc_attr( get_bloginfo( 'description' ) ).'</h2>';
			}

			$simplecatch_headerdetails .= '</div><!-- .site-details -->';
		}
        $simplecatch_headerdetails .= '</div><!-- .logo-wrap -->';

	set_transient( 'simplecatch_headerdetails', $simplecatch_headerdetails, 86940 );
	}
	echo $simplecatch_headerdetails;
}
endif; // simplecatch_headerdetails


if ( ! function_exists( 'simplecatch_footerlogo' ) ) :
/**
 * Get the footer logo Image from theme options
 *
 * @uses footer logo
 * @get the data value of image from theme options
 * @display footer Image logo
 *
 * @uses default logo if logo field on theme options is empty
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_footerlogo() {
	//delete_transient('simplecatch_footerlogo');

	if ( !$simplecatch_footerlogo = get_transient( 'simplecatch_footerlogo' ) ) {
		global $simplecatch_options_settings;
        $options = $simplecatch_options_settings;

		echo '<!-- refreshing cache -->';
		if ( empty( $options[ 'remove_footer_logo' ] ) ) :

			// if not empty featured_logo_footer on theme options
			if ( !empty( $options[ 'featured_logo_footer' ] ) ) :
				$simplecatch_footerlogo =
					'<img src="'.esc_url( $options[ 'featured_logo_footer' ] ).'" alt="'.get_bloginfo( 'name' ).'" />';
			else:
				// if empty featured_logo_footer on theme options, display default Footer Logo
				$simplecatch_footerlogo ='
					<img src="'. get_template_directory_uri().'/images/logo-foot.png" alt="footerlogo" />';
			endif;
		endif;


	set_transient( 'simplecatch_footerlogo', $simplecatch_footerlogo, 86940 );
	}
	echo $simplecatch_footerlogo;
}
endif; // simplecatch_footerlogo


/**
 * Get the favicon Image from theme options
 *
 * @uses favicon
 * @get the data value of image from theme options
 * @display favicon
 *
 * @uses default favicon if favicon field on theme options is empty
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_favicon() {
	//delete_transient( 'simplecatch_favicon' );

	if( ( !$simplecatch_favicon = get_transient( 'simplecatch_favicon' ) ) ) {
		global $simplecatch_options_settings;
        $options = $simplecatch_options_settings;

		echo '<!-- refreshing cache -->';

		// if not empty fav_icon on theme options
		if ( empty( $options[ 'remove_fav_icon' ] ) ) :

			// if not empty fav_icon on theme options
			if ( !empty( $options[ 'fav_icon' ] ) ) :
				$simplecatch_favicon = '<link rel="shortcut icon" href="'.esc_url( $options[ 'fav_icon' ] ).'" type="image/x-icon" />';
			else:
				// if empty featured_logo_footer on theme options, display default fav icon
				$simplecatch_favicon ='<link rel="shortcut icon" href="'. get_template_directory_uri().'/images/favicon.ico" type="image/x-icon" />';
			endif;

		endif;

		set_transient( 'simplecatch_favicon', $simplecatch_favicon, 86940 );
	}
	echo $simplecatch_favicon ;
} // simplecatch_favicon

//Load Favicon in Header Section
add_action('wp_head', 'simplecatch_favicon');

//Load Favicon in Admin Section
add_action( 'admin_head', 'simplecatch_favicon' );


if ( ! function_exists( 'simplecatch_sliders' ) ) :
/**
 * This function to display featured posts on homepage header
 *
 * @get the data value from theme options
 * @displays on the homepage header
 *
 * @useage Featured Image, Title and Content of Post
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_sliders() {
	global $post;
	//delete_transient( 'simplecatch_sliders' );

	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$postperpage = $options[ 'slider_qty' ];

	if( ( !$simplecatch_sliders = get_transient( 'simplecatch_sliders' ) ) && !empty( $options[ 'featured_slider' ] ) ) {
		echo '<!-- refreshing cache -->';

		$simplecatch_sliders = '
		<div class="featured-slider">';
			$get_featured_posts = new WP_Query( array(
				'posts_per_page' => $postperpage,
				'post__in'		 => $options[ 'featured_slider' ],
				'orderby' 		 => 'post__in',
				'ignore_sticky_posts' => 1 // ignore sticky posts
			));
			$i=0; while ( $get_featured_posts->have_posts()) : $get_featured_posts->the_post(); $i++;
				$title_attribute = apply_filters( 'the_title', get_the_title( $post->ID ) );
				$excerpt = get_the_excerpt();
				if ( $i == 1 ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }
				$simplecatch_sliders .= '
				<div class="'.$classes.'">
					<div class="featured">
						<div class="slide-image">';
							if( has_post_thumbnail() ) {

								$simplecatch_sliders .= '<a href="' . get_permalink() . '" title="Permalink to '.the_title('','',false).'">';

								if( $options[ 'remove_noise_effect' ] == "0" ) {
									$simplecatch_sliders .= '<span class="img-effect pngfix"></span>';
								}

								$simplecatch_sliders .= get_the_post_thumbnail( $post->ID, 'slider', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ), 'class'	=> 'pngfix' ) ).'</a>';
							}
							else {
								$simplecatch_sliders .= '<span class="img-effect pngfix"></span>';
							}
							$simplecatch_sliders .= '
						</div> <!-- .slide-image -->
					</div> <!-- .featured -->
					<div class="featured-text">';
						if( $excerpt !='') {
							$simplecatch_sliders .= the_title( '<span class="slider-title">','</span>', false ).'<span class="slider-sep">: </span><span class="slider-content">'.$excerpt.'</span>';
						}
						$simplecatch_sliders .= '
					</div><!-- .featured-text -->
				</div> <!-- .slides -->';
			endwhile; wp_reset_query();
		$simplecatch_sliders .= '
		</div> <!-- .featured-slider -->
			<div id="controllers">
			</div><!-- #controllers -->';

	set_transient( 'simplecatch_sliders', $simplecatch_sliders, 86940 );
	}
	echo $simplecatch_sliders;
}
endif; // simplecatch_sliders


if ( ! function_exists( 'simplecatch_sliderbreadcrumb' ) ) :
/**
 * Display slider or breadcrumb on header
 *
 * If the page is home or front page, slider is displayed.
 * In other pages, breadcrumb will display if exist bread
 */
function simplecatch_sliderbreadcrumb() {
	global $post, $wp_query;

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// If the page is home or front page
	if ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) :
		// This function passes the value of slider effect to js file
		if( function_exists( 'simplecatch_pass_slider_value' ) ) {
			simplecatch_pass_slider_value();
		}
		// display featured slider
		if ( function_exists( 'simplecatch_sliders' ) ):
			simplecatch_sliders();
		endif;
	else :
		// if breadcrumb is not empty, display breadcrumb
		if ( function_exists( 'bcn_display_list' ) ):
			echo '<div class="breadcrumb">
					<ul>';
						bcn_display_list();
			 	echo '</ul>
					<div class="row-end"></div>
				</div> <!-- .breadcrumb -->';
		endif;

  	endif;
}
endif; // simplecatch_sliderbreadcrumb


if ( ! function_exists( 'simplecatch_headersocialnetworks' ) ) :
/**
 * This function for social links display on header
 *
 * @fetch links through Theme Options
 * @use in widget
 * @social links, Facebook, Twitter and RSS
  */
function simplecatch_headersocialnetworks() {
	//delete_transient( 'simplecatch_headersocialnetworks' );

	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$elements = array();

	$elements = array( 	$options[ 'social_facebook' ],
						$options[ 'social_twitter' ],
						$options[ 'social_googleplus' ],
						$options[ 'social_linkedin' ],
						$options[ 'social_pinterest' ],
						$options[ 'social_youtube' ],
						$options[ 'social_vimeo' ],
						$options[ 'social_slideshare' ],
						$options[ 'social_foursquare' ],
						$options[ 'social_flickr' ],
						$options[ 'social_tumblr' ],
						$options[ 'social_deviantart' ],
						$options[ 'social_dribbble' ],
						$options[ 'social_myspace' ],
						$options[ 'social_wordpress' ],
						$options[ 'social_rss' ],
						$options[ 'social_delicious' ],
						$options[ 'social_lastfm' ],
						$options[ 'social_instagram' ],
						$options[ 'social_github' ],
						$options[ 'social_vkontakte' ],
						$options[ 'social_myworld' ],
						$options[ 'social_odnoklassniki' ],
						$options[ 'social_goodreads' ],
						$options[ 'social_skype' ],
						$options[ 'social_soundcloud' ],
						$options[ 'social_email' ]
					);
	$flag = 0;
	if( !empty( $elements ) ) {
		foreach( $elements as $option) {
			if( !empty( $option ) ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if( $flag == 1 ) {
				break;
			}
		}
	}

	if ( ( !$simplecatch_headersocialnetworks = get_transient( 'simplecatch_headersocialnetworks' ) ) && ( $flag == 1 ) )  {
		echo '<!-- refreshing cache -->';

		$simplecatch_headersocialnetworks .='
			<ul class="social-profile">';

				//facebook
				if ( !empty( $options[ 'social_facebook' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="facebook"><a href="'.esc_url( $options[ 'social_facebook' ] ).'" title="'.sprintf( esc_attr__( '%s on Facebook', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Facebook </a></li>';
				}
				//Twitter
				if ( !empty( $options[ 'social_twitter' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="twitter"><a href="'.esc_url( $options[ 'social_twitter' ] ).'" title="'.sprintf( esc_attr__( '%s on Twitter', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}
				//Google+
				if ( !empty( $options[ 'social_googleplus' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="google-plus"><a href="'.esc_url( $options[ 'social_googleplus' ] ).'" title="'.sprintf( esc_attr__( '%s on Google+', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Google+ </a></li>';
				}
				//Linkedin
				if ( !empty( $options[ 'social_linkedin' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="linkedin"><a href="'.esc_url( $options[ 'social_linkedin' ] ).'" title="'.sprintf( esc_attr__( '%s on Linkedin', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Linkedin </a></li>';
				}
				//Pinterest
				if ( !empty( $options[ 'social_pinterest' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="pinterest"><a href="'.esc_url( $options[ 'social_pinterest' ] ).'" title="'.sprintf( esc_attr__( '%s on Pinterest', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Twitter </a></li>';
				}
				//Youtube
				if ( !empty( $options[ 'social_youtube' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="you-tube"><a href="'.esc_url( $options[ 'social_youtube' ] ).'" title="'.sprintf( esc_attr__( '%s on YouTube', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' YouTube </a></li>';
				}
				//Vimeo
				if ( !empty( $options[ 'social_vimeo' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="viemo"><a href="'.esc_url( $options[ 'social_vimeo' ] ).'" title="'.sprintf( esc_attr__( '%s on Vimeo', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vimeo </a></li>';
				}
				//Slideshare
				if ( !empty( $options[ 'social_slideshare' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="slideshare"><a href="'.esc_url( $options[ 'social_slideshare' ] ).'" title="'.sprintf( esc_attr__( '%s on Slideshare', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Slideshare </a></li>';
				}
				//Foursquare
				if ( !empty( $options[ 'social_foursquare' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="foursquare"><a href="'.esc_url( $options[ 'social_foursquare' ] ).'" title="'.sprintf( esc_attr__( '%s on Foursquare', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' foursquare </a></li>';
				}
				//Flickr
				if ( !empty( $options[ 'social_flickr' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="flickr"><a href="'.esc_url( $options[ 'social_flickr' ] ).'" title="'.sprintf( esc_attr__( '%s on Flickr', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Flickr </a></li>';
				}
				//Tumblr
				if ( !empty( $options[ 'social_tumblr' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="tumblr"><a href="'.esc_url( $options[ 'social_tumblr' ] ).'" title="'.sprintf( esc_attr__( '%s on Tumblr', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Tumblr </a></li>';
				}
				//deviantART
				if ( !empty( $options[ 'social_deviantart' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="deviantart"><a href="'.esc_url( $options[ 'social_deviantart' ] ).'" title="'.sprintf( esc_attr__( '%s on deviantART', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' deviantART </a></li>';
				}
				//Dribbble
				if ( !empty( $options[ 'social_dribbble' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="dribbble"><a href="'.esc_url( $options[ 'social_dribbble' ] ).'" title="'.sprintf( esc_attr__( '%s on Dribbble', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Dribbble </a></li>';
				}
				//MySpace
				if ( !empty( $options[ 'social_myspace' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="myspace"><a href="'.esc_url( $options[ 'social_myspace' ] ).'" title="'.sprintf( esc_attr__( '%s on MySpace', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' MySpace </a></li>';
				}
				//WordPress
				if ( !empty( $options[ 'social_wordpress' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="wordpress"><a href="'.esc_url( $options[ 'social_wordpress' ] ).'" title="'.sprintf( esc_attr__( '%s on WordPress', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' WordPress </a></li>';
				}
				//RSS
				if ( !empty( $options[ 'social_rss' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="rss"><a href="'.esc_url( $options[ 'social_rss' ] ).'" title="'.sprintf( esc_attr__( '%s on RSS', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' RSS </a></li>';
				}
				//Delicious
				if ( !empty( $options[ 'social_delicious' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="delicious"><a href="'.esc_url( $options[ 'social_delicious' ] ).'" title="'.sprintf( esc_attr__( '%s on Delicious', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Delicious </a></li>';
				}
				//Last.fm
				if ( !empty( $options[ 'social_lastfm' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="lastfm"><a href="'.esc_url( $options[ 'social_lastfm' ] ).'" title="'.sprintf( esc_attr__( '%s on Last.fm', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Last.fm </a></li>';
				}
				//Instagram
				if ( !empty( $options[ 'social_instagram' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="instagram"><a href="'.esc_url( $options[ 'social_instagram' ] ).'" title="'.sprintf( esc_attr__( '%s on Instagram', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' Instagram </a></li>';
				}
				//GitHub
				if ( !empty( $options[ 'social_github' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="github"><a href="'.esc_url( $options[ 'social_github' ] ).'" title="'.sprintf( esc_attr__( '%s on GitHub', 'simple-catch' ),get_bloginfo('name') ).'" target="_blank">'.get_bloginfo( 'name' ).' GitHub </a></li>';
				}
				//Vkontakte
				if ( !empty( $options[ 'social_vkontakte' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="vkontakte"><a href="'.esc_url( $options[ 'social_vkontakte' ] ).'" title="'.sprintf( esc_attr__( '%s on Vkontakte', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Vkontakte </a></li>';
				}
				//My World
				if ( !empty( $options[ 'social_myworld' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="myworld"><a href="'.esc_url( $options[ 'social_myworld' ] ).'" title="'.sprintf( esc_attr__( '%s on My World', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' My World </a></li>';
				}
				//Odnoklassniki
				if ( !empty( $options[ 'social_odnoklassniki' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="odnoklassniki"><a href="'.esc_url( $options[ 'social_odnoklassniki' ] ).'" title="'.sprintf( esc_attr__( '%s on Odnoklassniki', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Odnoklassniki </a></li>';
				}
				//Goodreads
				if ( !empty( $options[ 'social_goodreads' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="goodreads"><a href="'.esc_url( $options[ 'social_goodreads' ] ).'" title="'.sprintf( esc_attr__( '%s on Goodreads', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Goodreads </a></li>';
				}
				//Skype
				if ( !empty( $options[ 'social_skype' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="skype"><a href="'.esc_attr( $options[ 'social_skype' ] ).'" title="'.sprintf( esc_attr__( '%s on Skype', 'simple-catch' ),get_bloginfo( 'name' ) ).'">'.get_bloginfo( 'name' ).' Skype </a></li>';
				}
				//Soundcloud
				if ( !empty( $options[ 'social_soundcloud' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="soundcloud"><a href="'.esc_url( $options[ 'social_soundcloud' ] ).'" title="'.sprintf( esc_attr__( '%s on Soundcloud', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Soundcloud </a></li>';
				}
				//Email
				if ( !empty( $options[ 'social_email' ] ) && is_email($options[ 'social_email' ] ) ) {
					$simplecatch_headersocialnetworks .=
						'<li class="email"><a href="mailto:'.sanitize_email( $options[ 'social_email' ] ).'" title="'.sprintf( esc_attr__( '%s on Email', 'simple-catch' ),get_bloginfo( 'name' ) ).'" target="_blank">'.get_bloginfo( 'name' ).' Email </a></li>';
				}
				$simplecatch_headersocialnetworks .='
			</ul>
			<div class="row-end"></div>';

		set_transient( 'simplecatch_headersocialnetworks', $simplecatch_headersocialnetworks, 86940 );
	}
	echo $simplecatch_headersocialnetworks;
}
endif; // simplecatch_headersocialnetworks


/**
 * Site Verification  and Webmaster Tools
 *
 * If user sets the code we're going to display meta verification
 * @get the data value from theme options
 * @uses wp_head action to add the code in the header
 * @uses set_transient and delete_transient API for cache
 */
function simplecatch_site_verification() {
	//delete_transient( 'simplecatch_site_verification' );

	if ( ( !$simplecatch_site_verification = get_transient( 'simplecatch_site_verification' ) ) )  {

		global $simplecatch_options_settings;
        $options = $simplecatch_options_settings;
		echo '<!-- refreshing cache -->';

		$simplecatch_site_verification = '';
		//google
		if ( !empty( $options['google_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="google-site-verification" content="' .  $options['google_verification'] . '" />' . "\n";
		}
		//bing
		if ( !empty( $options['bing_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="msvalidate.01" content="' .  $options['bing_verification']  . '" />' . "\n";
		}
		//yahoo
		 if ( !empty( $options['yahoo_verification'] ) ) {
			$simplecatch_site_verification .= '<meta name="y_key" content="' .  $options['yahoo_verification']  . '" />' . "\n";
		}

		//site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
			$simplecatch_site_verification .=  $options[ 'analytic_header' ] ;
		}
		set_transient( 'simplecatch_site_verification', $simplecatch_site_verification, 86940 );
	}
	echo $simplecatch_site_verification;
}
add_action('wp_head', 'simplecatch_site_verification');


/**
 * This function loads the Footer Code such as Add this code from the Theme Option
 *
 * @get the data value from theme options
 * @load on the footer ONLY
 * @uses wp_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function simplecatch_footercode() {
	//delete_transient( 'simplecatch_footercode' );


	if ( ( !$simplecatch_footercode = get_transient( 'simplecatch_footercode' ) ) ) {

		global $simplecatch_options_settings;
        $options = $simplecatch_options_settings;
		echo '<!-- refreshing cache -->';

		//site stats, analytics header code
		if ( !empty( $options['analytic_footer'] ) ) {
			$simplecatch_footercode =  $options[ 'analytic_footer' ] ;
		}

	set_transient( 'simplecatch_footercode', $simplecatch_footercode, 86940 );
	}
	echo $simplecatch_footercode;
}
add_action('wp_footer', 'simplecatch_footercode');


/**
 * Hooks the Custom Inline CSS to head section
 *
 * @since Simple Catch 1.2.3
 */
function simplecatch_inline_css() {
	//delete_transient( 'simplecatch_inline_css' );

	if ( ( !$simplecatch_inline_css = get_transient( 'simplecatch_inline_css' ) ) ) {
		global $simplecatch_options_settings, $simplecatch_options_defaults;
        $options = $simplecatch_options_settings;
		$defaults = $simplecatch_options_defaults;

		if( $options[ 'reset_color' ] == "0" || !empty( $options[ 'custom_css' ] ) ) {

			$simplecatch_inline_css	= '<!-- '.get_bloginfo('name').' Custom CSS Styles -->' . "\n";
	        $simplecatch_inline_css .= '<style type="text/css" media="screen">' . "\n";

			if( $defaults[ 'text_color' ] != $options[ 'text_color' ] ) {
				$simplecatch_inline_css	.=  "#main { color: ".  $options[ 'text_color' ] ."; }". "\n";
			}
			if( $defaults[ 'link_color' ] != $options[ 'link_color' ] ) {
				$simplecatch_inline_css	.=  "#main a { color: ".  $options[ 'link_color' ] ."; }". "\n";
			}
			if( $defaults[ 'heading_color' ] != $options[ 'heading_color' ] ) {
				$simplecatch_inline_css	.=  "#main h1 a, #main h2 a, #main h3 a, #main h4 a, #main h5 a, #main h6 a { color: ".  $options[ 'heading_color' ] ."; }". "\n";
			}
			if( $defaults[ 'meta_color' ] != $options[ 'meta_color' ] ) {
				$simplecatch_inline_css	.=  "#main #content ul.post-by li, #main #content ul.post-by li a { color: ".  $options[ 'meta_color' ] ."; }". "\n";
			}
			if( $defaults[ 'widget_heading_color' ] != $options[ 'widget_heading_color' ] ) {
				$simplecatch_inline_css	.=  "#sidebar h3, #sidebar h4, #sidebar h5 { color: ".  $options[ 'widget_heading_color' ] ."; }". "\n";
			}
			if( $defaults[ 'widget_text_color' ] != $options[ 'widget_text_color' ] ) {
				$simplecatch_inline_css	.=  "#sidebar, #sidebar p, #sidebar a, #sidebar ul li a, #sidebar ol li a { color: ".  $options[ 'widget_text_color' ] ."; }". "\n";
			}

			//Custom CSS Option
			if( !empty( $options[ 'custom_css' ] ) ) {
				$simplecatch_inline_css .=  $options['custom_css'] . "\n";
			}

			$simplecatch_inline_css .= '</style>' . "\n";

		}

	set_transient( 'simplecatch_inline_css', $simplecatch_inline_css, 86940 );
	}
	echo $simplecatch_inline_css;
}
add_action('wp_head', 'simplecatch_inline_css');


/*
 * Function for showing custom tag cloud
 */
function simplecatch_custom_tag_cloud() {
?>
	<div class="custom-tagcloud"><?php wp_tag_cloud('smallest=12&largest=12px&unit=px'); ?></div>
<?php
}


/**
 * shows footer credits
 */
function simplecatch_footer() {
?>
	<div class="col5 powered-by">
        <?php _e( 'Powered By:', 'simple-catch');?> <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'simple-catch' ) ); ?>" target="_blank" title="<?php esc_attr_e( 'Powered By WordPress', 'simple-catch' ); ?>"><?php _e( 'WordPress', 'simple-catch' ); ?></a> | <?php _e( 'Theme:', 'simple-catch');?> <a href="<?php echo esc_url( __( 'http://catchthemes.com/', 'simple-catch' ) ); ?>" target="_blank" title="<?php esc_attr_e( 'Simple Catch', 'simple-catch' ); ?>"><?php _e( 'Simple Catch', 'simple-catch' ); ?></a>
  	</div><!--.col5 powered-by-->

<?php
}
add_filter( 'simplecatch_credits', 'simplecatch_footer' );


/**
 * Function to pass the slider value
 */
function simplecatch_pass_slider_value() {
	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$transition_effect = $options[ 'transition_effect' ];
	$transition_delay = $options[ 'transition_delay' ] * 1000;
	$transition_duration = $options[ 'transition_duration' ] * 1000;
	wp_localize_script(
		'simplecatch_slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)
	);
}// simplecatch_pass_slider_value


/**
 * Alter the query for the main loop in home page
 * @uses pre_get_posts hook
 */
function simple_catch_alter_home( $query ){
	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;
	$cats = $options[ 'front_page_category' ];

    if ( $options[ 'exclude_slider_post'] != "0" && !empty( $options[ 'featured_slider' ] ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['post__not_in'] = $options[ 'featured_slider' ];
		}
	}
	if ( !in_array( '0', (array) $cats ) ) {
		if( $query->is_main_query() && $query->is_home() ) {
			$query->query_vars['category__in'] = $options[ 'front_page_category' ];
		}
	}
}
add_action( 'pre_get_posts','simple_catch_alter_home' );


/**
 * Add specific CSS class by filter
 * @uses body_class filter hook
 * @since Simple Catch 1.3.2
 */
function simplecatch_class_names($classes) {
	global $post;

	if ( is_page_template( 'page-blog.php') ) {
		$classes[] = 'page-blog';
	}

	if( $post ) {
		if ( is_attachment() ) {
			$parent = $post->post_parent;
			$layout = get_post_meta( $parent,'simplecatch-sidebarlayout', true );
		} else {
			$layout = get_post_meta( $post->ID,'simplecatch-sidebarlayout', true );
		}
	}

	if( empty( $layout ) || ( !is_page() && !is_single() ) ) {
		$layout='default';
	}

	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$themeoption_layout = $options['sidebar_layout'];

	if( ( $layout == 'no-sidebar' || ( $layout=='default' && $themeoption_layout == 'no-sidebar') ) ){
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class','simplecatch_class_names');


if ( ! function_exists( 'simplecatch_content' ) ) :
/**
 * Display the page/post content
 * @since Simple Catch 1.3.2
 */
function simplecatch_content() {
	global $post;
	$layout = get_post_meta( $post->ID,'simplecatch-sidebarlayout', true );
	if( empty( $layout ) )
		$layout='default';

	get_header();

	if( $layout=='default') {
		global $simplecatch_options_settings;
		$options = $simplecatch_options_settings;

		$themeoption_layout = $options['sidebar_layout'];

		if( $themeoption_layout == 'left-sidebar' ) {
			get_template_part( 'content-sidebar','left' );
		}
		elseif( $themeoption_layout == 'right-sidebar' ) {
			get_template_part( 'content-sidebar','right' );
		}
		elseif( $themeoption_layout == 'no-sidebar-full-width' ) {
			get_template_part( 'content-sidebar','full' );
		}
		else {
			get_template_part( 'content-sidebar','no' );
		}
	}
	elseif( $layout=='left-sidebar' ) {
		get_template_part( 'content-sidebar','left' );
	}
	elseif( $layout=='right-sidebar' ) {
		get_template_part( 'content-sidebar','right' );
	}
	elseif( $layout == 'no-sidebar-full-width' ) {
		get_template_part( 'content-sidebar','full' );
	}
	else{
		get_template_part( 'content-sidebar','no' );
	}

	get_footer();
}
endif; // simplecatch_content


if ( ! function_exists( 'simplecatch_loop' ) ) :
/**
 * Display the page/post loop part
 * @since Simple Catch 1.3.2
 */
function simplecatch_loop() {

	if( is_page() ): ?>

		<div <?php post_class(); ?> >
			<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
       		<?php the_content();
			// copy this <!--nextpage--> and paste at the post content where you want to break the page
			 wp_link_pages(array(
					'before'			=> '<div class="pagination">Pages: ',
					'after' 			=> '</div>',
					'link_before' 		=> '<span>',
					'link_after'       	=> '</span>',
					'pagelink' 			=> '%',
					'echo' 				=> 1
				) ); ?>
		</div><!-- .post -->

    <?php elseif( is_single() ): ?>

		<div <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
            <ul class="post-by">
                <li class="no-padding-left"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"
                    title="<?php echo esc_attr( get_the_author_meta( 'display_name' ) ); ?>"><?php _e( 'By', 'simple-catch' ); ?>&nbsp;<?php the_author_meta( 'display_name' );?></a></li>
                <li><?php $simplecatch_date_format = get_option( 'date_format' ); the_time( $simplecatch_date_format ); ?></li>
                <li><?php comments_popup_link( __( 'No Comments', 'simple-catch' ), __( '1 Comment', 'simple-catch' ), __( '% Comments', 'simple-catch' ) ); ?></li>
            </ul>
            <?php the_content();
            // copy this <!--nextpage--> and paste at the post content where you want to break the page
			 wp_link_pages(array(
					'before'			=> '<div class="pagination">Pages: ',
					'after' 			=> '</div>',
					'link_before' 		=> '<span>',
					'link_after'       	=> '</span>',
					'pagelink' 			=> '%',
					'echo' 				=> 1
				) );
			$tag = get_the_tags();
			if (! $tag ) { ?>
				<div class='tags'><?php _e( 'Categories: ', 'simple-catch' ); ?> <?php the_category(', '); ?> </div>
			<?php } else {
					 the_tags( '<div class="tags"> Tags: ', ', ', '</div>');
			} ?>
		</div> <!-- .post -->
	<?php endif;
}
endif; // simplecatch_loop


if ( ! function_exists( 'simplecatch_display_div' ) ) :
/**
 * Display the header div
 * @since Simple Catch 1.3.2
 */
function simplecatch_display_div() {
	echo '<div id="main" class="layout-978">';

	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

	$themeoption_layout = $options['sidebar_layout'];

	if( $themeoption_layout == 'left-sidebar' ) {
		get_sidebar();
		echo '<div id="content" class="col8">';
	}
	elseif( $themeoption_layout == 'right-sidebar' ) {
		echo '<div id="content" class="col8 no-margin-left">';
	}
	elseif( $themeoption_layout == 'no-sidebar-full-width' ) {
		echo '<div id="content" class="col12">';
	}
	else {
		echo '<div id="content" class="col8">';
	}
	return $themeoption_layout;
}
endif;  // simplecatch_display_div


/**
 * Redirect WordPress Feeds To FeedBurner
 */
function simplecatch_rss_redirect() {
	global $simplecatch_options_settings;
    $options = $simplecatch_options_settings;

    if ($options['feed_url']) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT']))
		{
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}
add_action('template_redirect', 'simplecatch_rss_redirect');


if ( ! function_exists( 'simplecatch_comment_form_fields' ) ) :
/**
 * Altering Comment Form Fields
 * @uses comment_form_default_fields filter
 */
function simplecatch_comment_form_fields( $fields ) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] = '<label for="author">' . __('Name','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Name', 'simple-catch' ) .'&nbsp;'. ( $req ? esc_attr__( '( required )', 'simple-catch' ) : '' ) .'" name="author"'. $aria_req .' />';
	$fields['email'] = '<label for="email">' . __('Email','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Email', 'simple-catch' ) .'&nbsp;'. ( $req ? esc_attr__( '( required )', 'simple-catch' ) : '' ) .'" name="email"'. $aria_req .' />';
	$fields['url'] = '<label for="url">' . __('Website','simple-catch') . '</label><input type="text" class="text" placeholder="'.esc_attr__( 'Website', 'simple-catch' ) .'" name="url"'. $aria_req .' />';

    return $fields;
}
endif;  // simplecatch_comment_form_fields

add_filter( 'comment_form_default_fields', 'simplecatch_comment_form_fields' );


if ( ! function_exists( 'simplecatch_comment_form_defaults' ) ) :
/**
 * Altering Comment Form Defaults
 *
 * @uses comment_form_defaults filter
 */
function simplecatch_comment_form_defaults( $defaults ) {

	$defaults['comment_notes_before'] = '';
	$defaults['comment_notes_after'] = '';

	return $defaults;
}
endif;  // simplecatch_comment_form_defaults

add_filter( 'comment_form_defaults', 'simplecatch_comment_form_defaults' );


/**
 * Adds in post ID when viewing lists of posts
 * This will help the admin to add the post ID in featured slider
 *
 * @param mixed $post_columns
 * @return post columns
 */
function simplecatch_post_id_column( $post_columns ) {
	$beginning = array_slice( $post_columns, 0 ,1 );
	$beginning[ 'postid' ] = __( 'ID', 'simple-catch'  );
	$ending = array_slice( $post_columns, 1 );
	$post_columns = array_merge( $beginning, $ending );
	return $post_columns;
}
add_filter( 'manage_posts_columns', 'simplecatch_post_id_column' );

function simplecatch_posts_id_column( $col, $val ) {
	if( $col == 'postid' ) echo $val;
}
add_action( 'manage_posts_custom_column', 'simplecatch_posts_id_column', 10, 2 );

function simplecatch_posts_id_column_css() {
	echo '<style type="text/css">#postid { width: 40px; }</style>';
}
add_action( 'admin_head-edit.php', 'simplecatch_posts_id_column_css' );


/**
 * Get the Web Clip Icon from theme options
 *
 * @uses web clip
 * @get the data value of image from theme options
 * @display web clip
 *
 * @uses set_transient and delete_transient
 */
function simplecatch_webclip() {
	//delete_transient( 'simplecatch_webclip' );

	if( ( !$simplecatch_webclip = get_transient( 'simplecatch_webclip' ) ) ) {
		global $simplecatch_options_settings;
        $options = $simplecatch_options_settings;

		echo '<!-- refreshing cache -->';

		// if not empty fav_icon on theme options
		if ( !empty( $options[ 'web_clip' ] ) ) :
			$simplecatch_webclip = '<link rel="apple-touch-icon-precomposed" href="'.esc_url( $options[ 'web_clip' ] ).'" />';
		endif;

		set_transient( 'simplecatch_webclip', $simplecatch_webclip, 86940 );
	}
	echo $simplecatch_webclip ;
} // simplecatch_webclip

//Load Web Clip Icon in Header Section
add_action('wp_head', 'simplecatch_webclip');


if ( !function_exists( 'simplecatch_infinite_scroll_render' ) ):
/**
 * Set the code to be rendered on for calling posts,
 * hooked to template parts when possible.
 *
 * Note: must define a loop.
 */
function simplecatch_infinite_scroll_render() {
   get_template_part( 'content' );
}
endif; // simplecatch_infinite_scroll_render


if ( ! function_exists( 'simplecatch_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Simple Catch 1.0
 */
function simplecatch_content_nav( $nav_id ) {
	global $wp_query, $post;

	/**
	 * Check Jetpack Infinite Scroll
	 * if it's active then disable pagination
	 */
	if ( class_exists( 'Jetpack', false ) ) {
		$jetpack_active_modules = get_option('jetpack_active_modules');
		if ( $jetpack_active_modules && in_array( 'infinite-scroll', $jetpack_active_modules ) ) {
			return false;
		}
	}

	// Checking WP Page Numbers plugin exist
	if ( function_exists('wp_pagenavi' ) ) :
		wp_pagenavi();

	// Checking WP-PageNaviplugin exist
	elseif ( function_exists('wp_page_numbers' ) ) :
		wp_page_numbers();

	else:
		if ( $wp_query->max_num_pages > 1 ) :
	?>
			<ul class="default-wp-page">
				<li class="previous"><?php next_posts_link( __( 'Previous', 'simple-catch' ) ); ?></li>
				<li class="next"><?php previous_posts_link( __( 'Next', 'simple-catch' ) ); ?></li>
			</ul>
		<?php endif;
	endif;

}
endif; // simplecatch_content_nav
