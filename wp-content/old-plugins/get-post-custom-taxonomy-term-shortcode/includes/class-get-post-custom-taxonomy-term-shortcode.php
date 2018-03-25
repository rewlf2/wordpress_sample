<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/includes
 * @author     multidots <inquiry@multidots.in>
 */
class Get_Post_Custom_Taxonomy_Term_Shortcode {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Get_Post_Custom_Taxonomy_Term_Shortcode_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'get-post-custom-taxonomy-term-shortcode';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Get_Post_Custom_Taxonomy_Term_Shortcode_Loader. Orchestrates the hooks of the plugin.
	 * - Get_Post_Custom_Taxonomy_Term_Shortcode_i18n. Defines internationalization functionality.
	 * - Get_Post_Custom_Taxonomy_Term_Shortcode_Admin. Defines all hooks for the admin area.
	 * - Get_Post_Custom_Taxonomy_Term_Shortcode_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-post-custom-taxonomy-term-shortcode-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-get-post-custom-taxonomy-term-shortcode-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-get-post-custom-taxonomy-term-shortcode-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-get-post-custom-taxonomy-term-shortcode-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shoertcode-creator-public-main.php';

		$this->loader = new Get_Post_Custom_Taxonomy_Term_Shortcode_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Get_Post_Custom_Taxonomy_Term_Shortcode_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Get_Post_Custom_Taxonomy_Term_Shortcode_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Get_Post_Custom_Taxonomy_Term_Shortcode_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_categories',$plugin_admin, 'get_textonomy' );
        $this->loader->add_action( 'wp_ajax_get_categories',$plugin_admin, 'get_textonomy' );
        $this->loader->add_action('wp_ajax_nopriv_get_term',$plugin_admin,'get_term');
        $this->loader->add_action('wp_ajax_get_term',$plugin_admin,'get_term');
        $this->loader->add_action( 'wp_ajax_add_plugin_user_gpctts', $plugin_admin, 'wp_add_plugin_userfn' );
		$this->loader->add_action( 'wp_ajax_hide_subscribe_gpctts', $plugin_admin, 'hide_subscribe_gpcttsfn' ); 
		
		
		$this->loader->add_action('admin_init', $plugin_admin, 'welcome_get_post_custom_taxonomy_term_shortcode_screen_do_activation_redirect');
		$this->loader->add_action('admin_menu', $plugin_admin, 'welcome_pages_screen_get_post_custom_taxonomy_term_shortcode');
		
		$this->loader->add_action('get_post_custom_taxonomy_term_shortcode_other_plugins', $plugin_admin, 'get_post_custom_taxonomy_term_shortcode_other_plugins');
		$this->loader->add_action('get_post_custom_taxonomy_term_shortcode_about', $plugin_admin, 'get_post_custom_taxonomy_term_shortcode_about');
		$this->loader->add_action('admin_print_footer_scripts',  $plugin_admin, 'get_post_custom_taxonomy_term_shortcode_pointers_footer');
		$this->loader->add_action('admin_menu',  $plugin_admin, 'welcome_screen_get_post_custom_taxonomy_term_shortcode_remove_menus', 999 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Get_Post_Custom_Taxonomy_Term_Shortcode_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('wp_ajax_nopriv_post_listing',$plugin_public,'post_listing');
        $this->loader->add_action('wp_ajax_post_listing',$plugin_public,'post_listing'); 
        
        if (in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')))) {
        	
			 $this->loader->add_filter( 'woocommerce_paypal_args', $plugin_public, 'paypal_bn_code_filter_get_post_custom_taxonomy_term_shortcode',99,1 );
		}
       
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Get_Post_Custom_Taxonomy_Term_Shortcode_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
