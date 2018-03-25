<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/admin/partials
 */

class Shoertcode_Creator_Main {


	public static function init() {
		add_action('admin_menu', array(__CLASS__, 'add_settings_menu'));
	}

	public static function add_settings_menu() {
		add_menu_page('Short Code Creator', 'Get Post Option', 'manage_options', 'get-post-custom-taxonomy-term-shortcode', array(__CLASS__, 'shortcode_page_menu'));
	}

	public static function shortcode_page_menu() {
		global $wpdb;
		$current_user = wp_get_current_user();
			
		if (!get_option('gpctts_plugin_notice_shown')) {
			 echo '<div id="gpctts_dialog" title="Basic dialog"> <p> Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_gpctts" class="regular-text" name="txt_user_sub_gpctts" value="'.$current_user->user_email.'"></p></div>';
		}
		
		echo '<div class="shortcode_main_div">
				<div class="wrap">
					<h2>Get Post Custom Taxonomy Term Shortcode</h2>
			
					<table class="form-table">
						<tbody class="select-option">
							<tr valign="top">
								<th scope="row">
									<label for="default_role">Select Post Type</label>
								</th>
								<td>
									<select id="default_role" name="default_role" class="shortcode_main">';
										$post_types=get_post_types();
										$exclude=array( 'attachment', 'revision', 'nav_menu_item', 'page' );
										foreach ( $post_types as $key=> $value ) {
											if ( in_array( $key, $exclude ) ) {
												unset( $post_types[$key] );
											}
										}
										echo '<option value="select-post-type">Select Post Type</option>';
										foreach ( $post_types as $key => $value ):
										echo '<option value="' . $key . '">' . $value . '</option>';
										endforeach;
										echo '</select>
								</td>
							</tr>
					</table>
					<br/><br/>
					<table class="form-table display-view">
						<tbody>
							<tr>
							<th scope="row">Select Dispaly View</th>
								<td>	
										
									<div class="color-option">
									<label>
										<input type="radio" name="post_view" value="first-view">
										<img src="'.plugin_dir_url( __FILE__ ).'images/first.jpg" alt="" align="center"/></input></label>
									</div>
									
									<div class="color-option">
									<label>
										<input type="radio" name="post_view" value="second-view" checked="checked">
										<img src="'.plugin_dir_url( __FILE__ ).'images/second.jpg" alt="" align="center"/>
										</label>
									</div>
									<div class="color-option">
									<label>
										<input type="radio" name="post_view" value="third-view">
										<img src="'.plugin_dir_url( __FILE__ ).'images/third.jpg" alt="" align="center"/>
										</label>
									</div>								
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<p class="submin">
					<input id="submit" class="get_shortcode button button-primary" type="submit" value="Get Shortcode">
				</p>
			</div>
			</div>';
								?>
								<div class='popup'>
									<div class='content'>
									<img src='<?php echo plugin_dir_url( __FILE__ ) ?>images/fancy_close.png' alt='quit' class='x' id='x' />
									<!--<p><textarea rows="3" id="txtarea" onClick="SelectAll('txtarea');" style="width:200px" >This text you can select all by clicking here </textarea>-->
									<b>Copy Shortcode & Paste Wherever You Want Display Posts</b>
									<label></label>
								</div>
						</div>
								<?php
	}
}
Shoertcode_Creator_Main::init();