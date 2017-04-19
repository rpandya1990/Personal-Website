<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Catch Themes
 * @subpackage Simple_Catch
 * @since Simple Catch 1.0
 */
get_header(); ?>
		<div id="main" class="layout-978">
        	<div id="content" class="col8">
            	<div class="post error404">
                    <h2 class="entry-title"><?php _e( 'Error 404 - Page Not Found.', 'simple-catch' ); ?></h2>
                	<p><?php _e( 'We&rsquo;re sorry! This page is not available.', 'simple-catch' ); ?></p>
               	 	<h4><?php _e( 'This might be because:', 'simple-catch' ); ?></h4>
               	 	<p><?php _e( 'You have typed the web address incorrectly, or the page you were looking for may have been moved, updated or deleted.', 'simple-catch' ); ?></p>
                	<h4><?php _e( 'Please try the following options instead:', 'simple-catch' ); ?></h4>
                	<p><?php _e( 'Check for a mis-typed URL error, then press the refresh button on your browser or Use the search box at the Header.', 'simple-catch' ); ?></p>
              	</div>
       		</div><!-- #content-->
        </div><!--#main-->
<?php get_footer(); ?>