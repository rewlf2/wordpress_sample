<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/public
 * @author     multidots <inquiry@multidots.in>
 */
class Get_Post_Custom_Taxonomy_Term_Shortcode_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->loaddependancy_public();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Get_Post_Custom_Taxonomy_Term_Shortcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Get_Post_Custom_Taxonomy_Term_Shortcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/get-post-custom-taxonomy-term-shortcode-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Get_Post_Custom_Taxonomy_Term_Shortcode_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Get_Post_Custom_Taxonomy_Term_Shortcode_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/get-post-custom-taxonomy-term-shortcode-public.js', array( 'jquery' ), $this->version, false );

	}
	public function loaddependancy_public() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/get-post-custom-taxonomy-term-shortcode-public-display.php';
	}
	
	/**
	 * BN code added
	 */
	
	function paypal_bn_code_filter_get_post_custom_taxonomy_term_shortcode ($paypal_args) {
		$paypal_args['bn'] = 'Multidots_SP';
		return $paypal_args;
	}

}
