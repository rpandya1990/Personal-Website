<?php
/**
 * Plugin Name: Insert Code in Header and Footer
 * Plugin URI: http://www.smallbusinesswebdesigns.co.nz/insert-code-in-header-and-footer-plugin.html
 * Description: This plugin allows you to insert custom javascript or css code in header and footer sections of the theme.
 * Version: 1.0.0
 * Author: Small Business Web Design Auckland
 * Author URI: http://www.smallbusinesswebdesigns.co.nz
 */

function ichf_header_scripts(){
 echo get_option('wpb-inhead-scr');	
}
add_action('wp_head', 'ichf_header_scripts');

function ichf_footer_scripts(){
 echo get_option('wpb-infoot-scr');
}
add_action('wp_footer', 'ichf_footer_scripts');


function ichf_ins_scr() {
if(isset($_POST['sub'])){
	add_option('wpb-inhead-scr', stripslashes($_POST['wpb-header']), '', 'yes' );
	add_option('wpb-infoot-scr', stripslashes($_POST['wpb-footer']), '', 'yes' );
	
	$hd_scr = get_option('wpb-inhead-scr');
	$ft_scr = get_option('wpb-infoot-scr');
	if($ft_scr!="" || $ft_scr==NULL){
	 update_option('wpb-infoot-scr', stripslashes($_POST['wpb-footer']));
	}
	if($hd_scr!="" || $hd_scr==NULL){
	 update_option('wpb-inhead-scr', stripslashes($_POST['wpb-header']));
	}
}
?>
<form method="post" action="">
<fieldset style="border:1px solid #000; width:90%; margin-top:20px; padding:10px;">
<legend style="font-size:16px; font-weight:700;">Insert Code in Header and Footer sections of your WordPress site </legend>
<table cellpadding="10" cellspacing="10">
<tr><td width="25%" valign="top"><h3>Header Part</h3><p>( Add javascript or css code that you want to insert in header section of your website )</p></td><td><textarea name="wpb-header" cols="120" rows="10"><?php echo get_option('wpb-inhead-scr'); ?></textarea></td></tr>
<tr><td width:"25%" valign="top"><h3>Footer Part</h3><p>( Add javascript or css code that you want to insert in footer section of your website )</p></td><td><textarea name="wpb-footer" cols="120" rows="10"><?php echo get_option('wpb-infoot-scr'); ?></textarea></td></tr>
<tr><td colspan="2"><input type="submit" id="sub" name="sub" value="Save"/></td></tr>
</table>
</fieldset>
</form>
<?php
}

function ichf_insert_scripts() {

 add_options_page('Insert Code', 'Insert Code', 'manage_options', 'ins-scr', 'ichf_ins_scr');

}
add_action('admin_menu', 'ichf_insert_scripts');
