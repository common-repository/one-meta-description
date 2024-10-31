<?php
/*
Plugin Name: One Meta Description
Version: 1.3.0
Plugin URI: http://wp.labnul.com/plugin/one-meta-description/
Description: Show meta description in your homepage, single posts and pages. This plugin helps you to attract visitors and boost traffic. Just plug and play or <a href="options-general.php?page=one-meta-description">Change Settings</a>.
Author: Aby Rafa
Author URI: http://wp.labnul.com/

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

add_action("wp_head","one_meta_description_head");

function one_meta_description_head() {
	$charlength = get_option("one_meta_description");
	if (is_single() || is_page()) {
		if (have_posts()) : while (have_posts()) : the_post();
			$excerpt = esc_attr(strip_tags(get_the_excerpt()));
			$charlength++;
			if ( mb_strlen( $excerpt ) > $charlength ) {
				$subex = mb_substr( $excerpt, 0, $charlength - 5 );
				$exwords = explode( ' ', $subex );
				$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
				if ( $excut < 0 ) {
					$description = mb_substr( $subex, 0, $excut );
				} else {
					$description = $subex;
				}
			} else {
				$description = $excerpt;
			}
		endwhile; endif;
		echo '<meta name="description" content="'. $description .'" />';
	} else {
		echo '<meta name="description" content="'; bloginfo('description'); echo '" />';
	}
}

// create custom plugin settings menu
add_action('admin_menu', 'one_meta_description_create_menu');

function one_meta_description_create_menu() {

	//create new top-level menu
	add_options_page("SEO Meta Description Settings","SEO Meta Description","manage_options","one-meta-description","one_meta_description_settings_page");

	//call register settings function
	add_action( 'admin_init', 'one_meta_description_settings' );
}

function one_meta_description_settings() {
	//register our settings
	register_setting( 'meta-description-abyrafa', 'one_meta_description' );
	
	if ( false === get_option('one_meta_description') ) add_option("one_meta_description", 160);
}

function one_meta_description_settings_page() { ?>
<div class="wrap">
<h1>SEO Meta Description</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'meta-description-abyrafa' ); ?>
    <?php do_settings_sections( 'meta-description-abyrafa' ); ?>
    <div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
					<div id="dashboard_primary" class="postbox ">
						<h2 class='hndle'><span>About Plugin:</span></h2>
						<div class="inside">
							<div class="rss-widget">
								<div style="float:left; margin-right:25px;">
									<p><img src="<?php echo plugins_url("images/home.jpg", __FILE__); ?>" /> <a href="http://wp.labnul.com/plugin/one-meta-description/" target="_blank">Plugin Homepage</a></p>
								</div>
								<div style="clear:left;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
					<div id="dashboard_primary" class="postbox ">
						<h2 class='hndle'><span>How many characters do you want for the meta description ?</span></h2>
						<div class="inside">
							<div class="rss-widget">
								<table class="form-table">	
									<tr valign="top">
									<th scope="row"><label for="character"><?php _e( 'Number of characters : ' ); ?></label></th>
									<td><input type="number" name="one_meta_description" step="1" min="10" value="<?php echo absint( get_option('one_meta_description') ); ?>" class="small-text" /> <?php _e( 'characters' ); ?></td>
									</tr>
								</table><p class="description">Recommended meta description length should be between 150 and 160 characters.</p>
								<?php submit_button(); ?>
								<p class="description"><a href="http://wp.labnul.com/tips-tricks/how-to-find-meta-description/" target="_blank">Show me how to find my meta description?</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>   
</form>
</div>
<?php } ?>