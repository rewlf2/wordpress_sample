<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.multidots.com/
 * @since             1.0.0
 * @package           Get_Post_Custom_Taxonomy_Term_Shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Post Listing Shortcode
 * Plugin URI:        http://www.multidots.com/
 * Description:       Advanced Post Listing Shortcode plugins allows you to generate shortcode to display posts using the filter of post type, taxonomy or with specific search term.
 * Version:           1.0.3
 * Author:            multidots
 * Author URI:        http://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       get-post-custom-taxonomy-term-shortcode
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-get-post-custom-taxonomy-term-shortcode-activator.php
 */
function activate_get_post_custom_taxonomy_term_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-post-custom-taxonomy-term-shortcode-activator.php';
	Get_Post_Custom_Taxonomy_Term_Shortcode_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-get-post-custom-taxonomy-term-shortcode-deactivator.php
 */
function deactivate_get_post_custom_taxonomy_term_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-get-post-custom-taxonomy-term-shortcode-deactivator.php';
	Get_Post_Custom_Taxonomy_Term_Shortcode_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_get_post_custom_taxonomy_term_shortcode' );
register_deactivation_hook( __FILE__, 'deactivate_get_post_custom_taxonomy_term_shortcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-get-post-custom-taxonomy-term-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_get_post_custom_taxonomy_term_shortcode() {

	$plugin = new Get_Post_Custom_Taxonomy_Term_Shortcode();
	$plugin->run();

}
run_get_post_custom_taxonomy_term_shortcode();
