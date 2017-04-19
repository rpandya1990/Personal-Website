<?php

/*---------------------------------------------------

add settings page to menu

----------------------------------------------------*/

function register_fuse_social_sidebar_submenu_page()

		{

				add_submenu_page('options-general.php', 'Fuse Social Sidebar', 'Fuse Social Sidebar', 'manage_options', 'fuse-social', 'fuse_social_callback');

		}

add_action('admin_menu', 'register_fuse_social_sidebar_submenu_page');

//Getting square icons image for Admin Settings 

$awesome_sq_icon = "Square Icons  <img src='" . plugin_dir_url(__FILE__) . "img/square_logo.png' /> &nbsp;  &nbsp;  &nbsp; ";

//Getting round icons image for Admin Settings 

$awesome_ro_icon = "Round Icons  <img src='" . plugin_dir_url(__FILE__) . "img/round_logo.png' />";

$style_options   = array(

				'square' => array(

								'value' => 'square',

								'label' => __($awesome_sq_icon, 'fuse_social')

				),

				'round' => array(

								'value' => 'round',

								'label' => __($awesome_ro_icon, 'fuse_social')

				)

);

$size_options = array(

	'48' => array(

		'value' =>	'48',

		'label' => __( 'Large', 'fuse_social' )

	),

	'32' => array(

		'value' =>	'32',

		'label' => __( 'Medium', 'fuse_social' )

	),

	'24' => array(

		'value' => '24',

		'label' => __( 'Small', 'fuse_social' )

	)

);

// Awesome Social  call back for Admin Settings

function fuse_social_callback()

		{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-form');
      wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker-script-handle', plugins_url('wp-color-picker-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
?>
<link href="<?php echo plugin_dir_url(__FILE__); ?>css/admin_style.css" rel="stylesheet">
<link href="<?php echo plugin_dir_url(__FILE__); ?>css/font-awesome.min.css" rel="stylesheet">
<?php
		settings_fields('fuse_social');
		$options = get_option('fuse_social_options');

				_e('<div class="wrap"><div id="icon-tools" class="icon32"></div>',"fuse_social");

				_e('<h2>Fuse Social Sidebar</h2>',"fuse_social");

		?>

		<script>

		// Activating tabs for Admin Settings
(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.color-field').wpColorPicker();
    });
     
})( jQuery );
            (function( $ ) {
    $(function() {
         
        // Add Color Picker to all inputs that have 'color-field' class
        $( '.cpa-color-picker' ).wpColorPicker();
         
    });
})( jQuery );
            
		$j=jQuery.noConflict();

		$j(document).ready(function(){

		$j("#fuse_social_options_anim_sec").hide();

		$j("#fuse_social_options_anim_s").hide();

		$j('#fuse_social_animate').change(function() {

		if($j(this).is(":checked")) {

		$j("#fuse_social_options_anim_sec").fadeIn();

		$j("#fuse_social_options_anim_s").fadeIn();

		}

		else{

		$j("#fuse_social_options_anim_s").hide();

		$j("#fuse_social_options_anim_sec").fadeOut();

		}

		});

		$j("#awesome_settings").hide();

		$j("#tab_awsome_settings").click(function(){

		$j("#awesome_links").fadeOut();

		$j("#awesome_settings").fadeIn();

		});

		$j("#tab_awsome_links").click(function(){

		$j("#awesome_links").fadeIn();

		$j("#awesome_settings").fadeOut();

		});

		<?php

		// If animation checked, then show corresponding text box for seconds

		if ($options['animations'] == 1)

		{

		?>

			   $j("#fuse_social_options_anim_sec").show();

			   $j("#fuse_social_options_anim_s").show(100);

		<?php

		}

		?>

		

		});

		</script>

		<div class="awesome_spacer"></div>

		<div class="fuse_social_tabs">

		<a href="javascript:void(0);" id="tab_awsome_links"><span class="awesome_tabs">Links</span></a>

		<a href="javascript:void(0);"  id="tab_awsome_settings"><span class="awesome_tabs">Settings</span></a>

		</div>

		<div class="awesome_spacer"></div>

		<form method="post" action="options.php" id="fuse_social_form">

			<?php

				settings_fields('fuse_social');

				$options = get_option('fuse_social_options');

			?>

			<div id="awesome_settings">

			<?php

			// Getting social icons style type i.e Round or Sqaure for Admin Settings

				global $style_options,$size_options;

				$fuse_social_style = "";

				if (!isset($checked))

								$checked = '';

				foreach ($style_options as $option)

						{

								$radio_setting = $options['style_input'];

								if ('' != $radio_setting)

										{

												if ($options['style_input'] == $option['value'])

														{

																$fuse_social_style = $options['style_input'];

																$checked              = "checked=\"checked\"";

														}

												else

														{

																$checked = '';

														}

										}

			?>

											<label class="awesome_description"><input type="radio" name="fuse_social_options[style_input]" value="<?php

											esc_attr_e($option['value']);

			?>" <?php

											echo $checked;

			?> /> <?php

											echo $option['label'];

			?></label>

											<?php

									}

			?>

									<br />
<div id="other_settings">
				<h3><?php _e("Other Settings","fuse_social"); ?></h3>

					<hr />

					<table>

					<tr style="height:45px;">

					<td style="width: 300px;">

		<?php // Open Links in new tab setting ?>			

						<input id="fuse_social_options[linksnewtab]" name="fuse_social_options[linksnewtab]" type="checkbox" value="1" <?php

							checked('1', $options['linksnewtab']);

			?>  />

					

						<label class="awesome_description" for="fuse_social_options[linksnewtab]"><strong><?php _e("Open links new tab.","fuse_social"); ?></strong></label>

						</td>

			<?php // Animation Settings ?>			

									<td>

									<input id="fuse_social_animate" name="fuse_social_options[animations]" type="checkbox" value="1" <?php

							checked('1', $options['animations']);

			?>  />

					

						<label class="awesome_description" for="fuse_social_animate"><strong><?php _e("Animation on hover","fuse_social"); ?> </strong></label>

			<?php // Animation time settings ?>		

						<input  id="fuse_social_options_anim_sec" value="<?php

							esc_attr_e($options['animate_sec']);

			?>" name="fuse_social_options[animate_sec]" type="text" placeholder="Delay in Seconds" style="width:250px;" /> 

			<span id="fuse_social_options_anim_s"><em><?php _e("Animation delay in seconds. For-example 0.5","fuse_social") ?></em></span>



						</td>

						

						</tr>

			<tr style="height:45px;">

			<td>

			<hr />

			<?php // Font Shadow settings ?>

						<input id="fuse_social_options[shadow]" name="fuse_social_options[shadow]" type="checkbox" value="1" <?php

							checked('1', $options['shadow']);

			?>  />

									

							

						<label class="awesome_description" for="fuse_social_options[shadow]"><strong><?php _e("Don't use shadow in icons.","fuse_social"); ?></strong></label>

				</td>
	<td>

			<hr />

			<?php // Mobile settings ?>

						<input id="fuse_social_options[mobile]" name="fuse_social_options[mobile]" type="checkbox" value="1" <?php

							checked('1', $options['mobile']);

			?>  />

									

							

						<label class="awesome_description" for="fuse_social_options[mobile]"><strong><?php _e("Don't show in Mobile Version","fuse_social"); ?></strong></label>

				</td>
				<td style="padding-top:8px;">

				<hr />

				<select name="fuse_social_options[size]">

										<?php

										// Setting size of Social icons. 

								$selected = $options['size'];

								$p = '';

								$r = '';



								foreach ( $size_options as $option ) {

									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list

										$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";

									else

										$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";

								}

								echo $p . $r;

							?>

						</select>

						<label class="description" for="fuse_social_options[size]"><?php _e( '<strong>Size</strong>', 'fuse_social' ); ?></label>

								</td>
                </tr>
                        <tr>
                		<td>

			<hr />
<?php
    add_action( 'admin_enqueue_scripts', 'awesome_color_picker' );
function awesome_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}

    ?>
			

						<label class="samecolor" for="fuse_social_options[colorpicker]"><strong><?php _e("Use custom background color for all icons.","fuse_social"); ?></strong></label>
<input class="cpa-color-picker"  id="fuse_social_options[colorpicker]" value="<?php

							esc_attr_e($options['colorpicker']);

			?>" name="fuse_social_options[colorpicker]" type="text"  />
				</td>
                            			<td>


			<?php // on hover color settings ?>

						<input id="fuse_social_options[hover]" name="fuse_social_options[hover]" type="checkbox" value="1" <?php

							checked('1', $options['hover']);

			?>  />

									

							

						<label class="awesome_description" for="fuse_social_options[hover]"><strong><?php _e("Change icon color on hover - For Custom Background","fuse_social"); ?></strong></label>

				</td>

                        </tr>

				

					

							</table>


                </div>
									</div>

			<div id="awesome_links">

<?php // Setting links for social icons from Admin Settings ?>

				<strong><?php _e("Enter the URL(s) for your various social profiles below. If you leave a profile URL field blank, it will not be used.","fuse_social") ?> </strong>
                

				<div class="awesome_spacer"></div>

							<table id="icons">

				<tr>

					<td>

					<i class="fa fa-facebook"></i><label for="fuse_social_options[facebook]" > Facebook</label><br /><input  id="fuse_social_options[facebook]" value="<?php

							esc_attr_e($options['facebook']);

			?>" name="fuse_social_options[facebook]" type="text" placeholder="Facebook URL" />

					</td>

					<td>

					<i class="fa fa-twitter"></i><label for="fuse_social_options[twitter]" > Twitter</label><br /><input  id="fuse_social_options[twitter]" value="<?php

							esc_attr_e($options['twitter']);

			?>" name="fuse_social_options[twitter]" type="text" placeholder="Twitter URL" />

					



					</td>

					<td>

					<i class="fa fa-rss"></i><label for="fuse_social_options[rss]" > Rss</label><br /><input  id="fuse_social_options[rss]" value="<?php

							esc_attr_e($options['rss']);

			?>" name="fuse_social_options[rss]" type="text" placeholder="Rss URL" />

				



					</td>

				</tr>

				<tr>

				

					<td>

						<hr />

					<i class="fa fa-linkedin"></i><label for="fuse_social_options[linkedin]" > Linkedin</label><br /><input  id="fuse_social_options[linkedin]" value="<?php

							esc_attr_e($options['linkedin']);

			?>" name="fuse_social_options[linkedin]" type="text" placeholder="Linkedin URL" />

									

						

					</td>

					<td>

						<hr />

					<i class="fa fa-youtube"></i><label for="fuse_social_options[youtube]" > Youtube</label><br /><input  id="fuse_social_options[youtube]" value="<?php

							esc_attr_e($options['youtube']);

			?>" name="fuse_social_options[youtube]" type="text" placeholder="Youtube URL" />

					</td>

					<td>

						<hr />

						<i class="fa fa-flickr"></i><label for="fuse_social_options[flickr]" > Flickr</label><br /><input  id="fuse_social_options[flickr]" value="<?php

							esc_attr_e($options['flickr']);

			?>" name="fuse_social_options[flickr]" type="text" placeholder="Flickr URL" />



						

					</td>

				</tr>



					<tr>

					<td>

						<hr />

											<i class="fa fa-pinterest"></i><label for="fuse_social_options[pinterest]" > Pinterest</label><br /><input  id="fuse_social_options[pinterest]" value="<?php

							esc_attr_e($options['pinterest']);

			?>" name="fuse_social_options[pinterest]" type="text" placeholder="Pinterest URL" />

						

						</td>

					<td>

						<hr />

												<i class="fa fa-stumbleupon"></i><label for="fuse_social_options[stumbleupon]" > Stumbleupon</label><br /><input  id="fuse_social_options[stumbleupon]" value="<?php

							esc_attr_e($options['stumbleupon']);

			?>" name="fuse_social_options[stumbleupon]" type="text" placeholder="Stumbleupon URL" />

						

					

				</td>

					<td>

						<hr />

						

															<i class="fa fa-google-plus"></i><label for="fuse_social_options[google-plus]" > Google Plus</label><br /><input  id="fuse_social_options[google-plus]" value="<?php

							esc_attr_e($options['google-plus']);

			?>" name="fuse_social_options[google-plus]" type="text" placeholder="Google Plus URL" />

						

					

					</td>

				</tr>

				

						<tr>

					<td>

						<hr />

								

					<i class="fa fa-instagram"></i><label for="fuse_social_options[instagram]" > Instagram</label><br /><input  id="fuse_social_options[instagram]" value="<?php

							esc_attr_e($options['instagram']);

			?>" name="fuse_social_options[instagram]" type="text" placeholder="Instagram URL" />

						

				

					</td>

					<td>

						<hr />

							<i class="fa fa-tumblr"></i><label for="fuse_social_options[tumblr]" > Tumblr</label><br /><input  id="fuse_social_options[tumblr]" value="<?php

							esc_attr_e($options['tumblr']);

			?>" name="fuse_social_options[tumblr]" type="text" placeholder="Tumblr URL" />

						

				

					</td>

					<td>

						<hr />

						

									<i class="fa fa-vine"></i><label for="fuse_social_options[vine]" > Vine</label><br /><input  id="fuse_social_options[vine]" value="<?php

							esc_attr_e($options['vine']);

			?>" name="fuse_social_options[vine]" type="text" placeholder="Vine URL" />

						

				</td>

				</tr>
                                
                                
						<tr>

					<td>

						<hr />

								

					<i class="fa fa-vk"></i><label for="fuse_social_options[vk]" > VK</label><br /><input  id="fuse_social_options[vk]" value="<?php

							esc_attr_e($options['vk']);

			?>" name="fuse_social_options[vk]" type="text" placeholder="VK URL" />

						

				

					</td>

					<td>

						<hr />

							<i class="fa fa-soundcloud"></i><label for="fuse_social_options[soundcloud]" > Sound Cloud</label><br /><input  id="fuse_social_options[soundcloud]" value="<?php

							esc_attr_e($options['soundcloud']);

			?>" name="fuse_social_options[soundcloud]" type="text" placeholder="Sound Cloud URL" />

						

				

					</td>

					<td>

						<hr />

						

									<i class="fa fa-reddit"></i><label for="fuse_social_options[reddit]" > Reddit</label><br /><input  id="fuse_social_options[reddit]" value="<?php

							esc_attr_e($options['reddit']);

			?>" name="fuse_social_options[reddit]" type="text" placeholder="Reddit URL" />

						

				</td>

				</tr>

                                
                                                 
						<tr>

					<td>

						<hr />

								

					<i class="fa fa-stack-overflow"></i><label for="fuse_social_options[stack]" > Stack OverFLow</label><br /><input  id="fuse_social_options[stack]" value="<?php

							esc_attr_e($options['stack']);

			?>" name="fuse_social_options[stack]" type="text" placeholder="Stack OverFlow URL" />

						

				

					</td>

					<td>

						<hr />

							<i class="fa fa-behance"></i><label for="fuse_social_options[behance]" > Behance</label><br /><input  id="fuse_social_options[behance]" value="<?php

							esc_attr_e($options['behance']);

			?>" name="fuse_social_options[behance]" type="text" placeholder="Behance URL" />

						

				

					</td>

					<td>

						<hr />

						

									<i class="fa fa-github"></i><label for="fuse_social_options[github]" > Github</label><br /><input  id="fuse_social_options[github]" value="<?php

							esc_attr_e($options['github']);

			?>" name="fuse_social_options[github]" type="text" placeholder="Github URL" />

						

				</td>

				</tr>
                 <tr>

					<td>

						<hr />

						

									<i class="fa fa-envelope-o"></i><label for="fuse_social_options[envelope]" > Email</label><br /><input  id="fuse_social_options[envelope]" value="<?php

							esc_attr_e($options['envelope']);

			?>" name="fuse_social_options[envelope]" type="text" placeholder="mailto:someone@example.com" />

						

				</td>
                 </tr>     
                                
			</table>		

			</div>

					<p class="submit">

							<input type="submit" class="button-primary" value="<?php

							_e('Save Options', 'awesomesocial');

			?>" />

						<?php

							echo "<img src='" . plugin_dir_url(__FILE__) . "img/loading.gif' id='fuse_social_load'/>";

			?>

						</p>

							<div class="awesome_spacer"></div>

								<div class="awesome_spacer"></div>

									<div class="awesome_spacer"></div>

									<p>Made With Love - By  (<a href="https://www.facebook.com/mewoooooo" target="_blank" >Daniyal Ahmed</a>).</p><br />

					</form>

				<div id="saveResult"></div>

			<script type="text/javascript">

					jQuery(document).ready(function() {

					jQuery("#fuse_social_load").hide();

					jQuery('#fuse_social_form').submit(function() { 

					jQuery("#fuse_social_load").show();

					jQuery(this).ajaxSubmit({
                       

					success: function(){

					jQuery("#fuse_social_load").hide();

					jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

					jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();

							 }, 

							 timeout:5000,
                error: function(data){
                 		jQuery("#fuse_social_load").hide();
                        
					jQuery('#saveResult').html("<div id='saveMessage' class='successModal'></div>");

					jQuery('#saveMessage').append("<p><?php

					echo htmlentities(__('Settings Saved Successfully', 'wp'), ENT_QUOTES);

					?></p>").show();
                    }
						  }); 

						  setTimeout("jQuery('#saveMessage').hide('slow');", 5000);

						  return false; 

					   });

					});

			</script>

			<?php

							echo '</div>';

		

		}

add_action('admin_init', 'fuse_social_options_init');

/**

 * Init plugin options to white list our options

 */

function fuse_social_options_init()

		{

				register_setting('fuse_social', 'fuse_social_options', 'fuse_social_icon_validate');

		}

function fuse_social_icon_validate($input)

		{

				global $size_options, $style_options;

				// Link new tab checkbox value is either 0 or 1

				if (!isset($input['linksnewtab']))

				$input['linksnewtab'] = null;

				$input['linksnewtab'] = ($input['linksnewtab'] == 1 ? 1 : 0);

				if (!isset($input['animations']))

				// Animation checkbox value is either 0 or 1

				$input['animations'] = null;

				$input['animations'] = ($input['animations'] == 1 ? 1 : 0);

				// Shadow checkbox value is either 0 or 1

				if (!isset($input['shadow']))

				$input['shadow'] = null;

				$input['shadow'] = ($input['shadow'] == 1 ? 1 : 0);

				// Our style option must actually be in our array of style options

				if (!isset($input['style_input']))

				$input['style_input'] = null;

				if (!array_key_exists($input['style_input'], $style_options))

				$input['style_input'] = null;

				// Social profiles links must be safe links with no HTML tags

				$input['facebook']     = wp_filter_nohtml_kses($input['facebook']);

				$input['twitter']     = wp_filter_nohtml_kses($input['twitter']);

				$input['rss']     = wp_filter_nohtml_kses($input['rss']);

				$input['linkedin']     = wp_filter_nohtml_kses($input['linkedin']);

				$input['youtube']     = wp_filter_nohtml_kses($input['youtube']);

				$input['flickr']     = wp_filter_nohtml_kses($input['flickr']);

				$input['pinterest']     = wp_filter_nohtml_kses($input['pinterest']);

				$input['stumbleupon']     = wp_filter_nohtml_kses($input['stumbleupon']);

				$input['google-plus']     = wp_filter_nohtml_kses($input['google-plus']);

				$input['instagram']     = wp_filter_nohtml_kses($input['instagram']);

				$input['tumblr']     = wp_filter_nohtml_kses($input['tumblr']);

				$input['vine']     = wp_filter_nohtml_kses($input['vine']);

				// Our size option must actually be in our array of size options

				if ( ! array_key_exists( $input['size'], $size_options ) )

				$input['size'] = null;

				return $input;

		}

?>