<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Get_Post_Custom_Taxonomy_Term_Shortcode
 * @subpackage Get_Post_Custom_Taxonomy_Term_Shortcode/admin
 * @author     multidots <inquiry@multidots.in>
 */
class Get_Post_Custom_Taxonomy_Term_Shortcode_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->loaddependancy();
	}

	/**
	 * Register the stylesheets for the admin area.
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
		//wp_deregister_style( 'wp-jquery-ui-dialog' );
		//wp_enqueue_style( $this->plugin_name,'wp-jquery-ui-dialog' );
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/get-post-custom-taxonomy-term-shortcode-admin.css', array('wp-jquery-ui-dialog'), $this->version, 'all' );
		wp_enqueue_style( 'wp-pointer' );
	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/get-post-custom-taxonomy-term-shortcode-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function loaddependancy() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/get-post-custom-taxonomy-term-shortcode-admin-display.php';
	}

	public function get_textonomy() {

		$taxonomy_objects = get_object_taxonomies( $_POST['select1']);
		if(!empty($taxonomy_objects)) {
			echo '<tr valign="top">
					<th scope="row">
						<label for="default_role">Select Texonomy</label>
					</th>
					<td>
						<select id="default_role" name="default_role" class="select_textonomy">
							<option>select Texonomy</option>';
							foreach ($taxonomy_objects as $key=>$value) {
								echo "<option value=".$value.">".$value."</option>";
							}
					echo '</select>
					</td>
				</tr>';
		}
		
		exit(0);

	}
	public function get_term(){
		$terms = get_terms( $_POST['select_term'] );
		if(!empty($terms)){
			echo '<tr valign="top">
					<th scope="row">
						<label for="default_role">Select Term</label>
					</th>
					<td>
						<select id="default_role" name="default_role" class="select_term">';
						foreach ( $terms as $term ) {
							echo '<option value='.$term->name.'>' . $term->name . '</option>';
						}
						echo '</select>
					</td>
				</tr>';
		}
		exit(0);
	}
	
	public function wp_add_plugin_userfn() {
    	$email_id= $_POST['email_id'];
    	$log_url = $_SERVER['HTTP_HOST'];
    	$cur_date = date('Y-m-d');
    	$url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/wp-add-plugin-users.php';
    	$response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('user'=>array('user_email'=>$email_id,'plugin_site' => $log_url,'status' => 1,'plugin_id' => '12','activation_date'=>$cur_date)),
    	'cookies' => array()));
		update_option('gpctts_plugin_notice_shown', 'true');
    }
    
    public function hide_subscribe_gpcttsfn() {
    	$email_id= $_POST['email_id'];
    	update_option('gpctts_plugin_notice_shown', 'true');
    }
    
    
     // function for welcome screen page 
    
    public function welcome_get_post_custom_taxonomy_term_shortcode_screen_do_activation_redirect () { 
    	
    	if (!get_transient('_get_post_custom_taxonomy_term_shortcode_welcome_screen')) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient('_get_post_custom_taxonomy_term_shortcode_welcome_screen');

		// if activating from network, or bulk
		if (is_network_admin() || isset($_GET['activate-multi'])) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect(add_query_arg(array('page' => 'advanced-post-listing-shortcode&tab=about'), admin_url('index.php')));
		
    } 
    
    public function welcome_pages_screen_get_post_custom_taxonomy_term_shortcode ( ){ 
    	add_dashboard_page(
		'Advanced Post Listing Shortcode Dashboard', 'Advanced Post Listing Shortcode Dashboard', 'read', 'advanced-post-listing-shortcode',  array( $this,'welcome_screen_content_advanced_post_listing_shortcode' ) );
    } 
    
    public function  welcome_screen_get_post_custom_taxonomy_term_shortcode_remove_menus ( ){ 
    	remove_submenu_page( 'index.php', 'advanced-post-listing-shortcode' );
    } 
    
    public function welcome_screen_content_advanced_post_listing_shortcode () { 
    		global $wpdb;
			$current_user = wp_get_current_user();
				
			if (!get_option('gpctts_plugin_notice_shown')) {
				 echo '<div id="gpctts_dialog" title="Basic dialog"> <p> Subscribe for latest plugin update and get notified when we update our plugin and launch new products for free! </p> <p><input type="text" id="txt_user_sub_gpctts" class="regular-text" name="txt_user_sub_gpctts" value="'.$current_user->user_email.'"></p></div>';
			}
    	 ?>
    	 <style type="text/css">span.ui-button-icon-primary.ui-icon.ui-icon-closethick {display: none;}.ui-dialog-titlebar-close:before {line-height: 15px; !important}.ui-widget-overlay.ui-front {
    display: none;
}</style>
    	 <div class="wrap about-wrap">
            <h1 style="font-size: 2.1em;"><?php printf(__('Welcome to Advanced Post Listing Shortcode', 'get-post-custom-taxonomy-term-shortcode')); ?></h1>

            <div class="about-text woocommerce-about-text">
        <?php
        $message = '';
        printf(__('%s Advanced Post Listing Shortcode plugins allows you to generate shortcode to display posts using the filter of post type, taxonomy or with specific search term. Later you can use the shortcode to display the list of posts anywhere in your website as content.', 'get-post-custom-taxonomy-term-shortcode'), $message);
        ?>
                <img class="version_logo_img" src="<?php echo plugin_dir_url(__FILE__) . 'images/get-post-custom-taxonomy-term-shortcode.png'; ?>">
            </div>

        <?php
        $setting_tabs_wc = apply_filters('advanced_post_listing_shortcode_setting_tab', array("about" => "Overview", "other_plugins" => "Checkout our other plugins" ));
        $current_tab_wc = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        $aboutpage = isset($_GET['page'])
        ?>
            <h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs_wc as $name => $label)
            echo '<a  href="' . home_url('wp-admin/index.php?page=advanced-post-listing-shortcode&tab=' . $name) . '" class="nav-tab ' . ( $current_tab_wc == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
            </h2>
                <?php
                foreach ($setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue) {
                	switch ($setting_tabkey_wc) {
                		case $current_tab_wc:
                			do_action('get_post_custom_taxonomy_term_shortcode_' . $current_tab_wc);
                			break;
                	}
                }
                ?>
            <hr />
            <div class="return-to-dashboard">
                <a href="<?php echo home_url('/wp-admin/admin.php?page=banner-setting'); ?>"><?php _e('Go to Advanced Post Listing Shortcode Settings', 'get-post-custom-taxonomy-term-shortcode'); ?></a>
            </div>
        </div>	
    
    <?php }
    
    // function for welcome page about us tag content 
    
    public  function get_post_custom_taxonomy_term_shortcode_about ( ){ ?>
    	 <div class="changelog">
            </br>
           	<style type="text/css">
				p.get_post_custom_taxonomy_term_shortcode_overview {max-width: 100% !important;margin-left: auto;margin-right: auto;font-size: 15px;line-height: 1.5;}.get_post_custom_taxonomy_term_shortcode_content_ul ul li {margin-left: 3%;list-style: initial;line-height: 23px;}
			</style>  
            <div class="changelog about-integrations">
                <div class="wc-feature feature-section col three-col">
                    <div>
                        <p class="get_post_custom_taxonomy_term_shortcode_overview"><?php _e('It will allow a user to generate shortcode according to what user need to display posts. The user will select Custom Post Type, Taxonomy & Terms and will get a Shortcode which will display posts which user have selected the filters. No Need to write queries to display posts.', 'get-post-custom-taxonomy-term-shortcode'); ?></p>
                        <p class="get_post_custom_taxonomy_term_shortcode_overview"><?php _e('Just use the shortcode in any post or page or also in theme custom templates to display posts.', 'get-post-custom-taxonomy-term-shortcode'); ?></p>
                    </div>
                </div>
            </div>
        </div>	
    		
   <?php  } 
   
   public function get_post_custom_taxonomy_term_shortcode_other_plugins ( ) { 
   	global $wpdb;
         $url = 'http://www.multidots.com/store/wp-content/themes/business-hub-child/API/checkout_other_plugin.php';
    	 $response = wp_remote_post( $url, array('method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => array('plugin' => 'advance-flat-rate-shipping-method-for-woocommerce'),
    	'cookies' => array()));
    	
    	$response_new = array();
    	$response_new = json_decode($response['body']);
		$get_other_plugin = maybe_unserialize($response_new);
		
		$paid_arr = array();
		?>

        <div class="plug-containter">
        	<div class="paid_plugin">
        	<h3>Paid Plugins</h3>
	        	<?php foreach ($get_other_plugin as $key=>$val) { 
	        		if ($val['plugindesc'] =='paid') {?>
	        			
	        			
	        		   <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>	
	        			
	        			
	        		<?php }else {
	        			
	        			$paid_arry[$key]['plugindesc']= $val['plugindesc'];
	        			$paid_arry[$key]['pluginimage']= $val['pluginimage'];
	        			$paid_arry[$key]['pluginurl']= $val['pluginurl'];
	        			$paid_arry[$key]['pluginname']= $val['pluginname'];
	        		
	        	?>
	        	
	         
	            <?php } }?>
           </div>
           <?php if (isset($paid_arry) && !empty($paid_arry)) {?>
           <div class="free_plugin">
           	<h3>Free Plugins</h3>
                <?php foreach ($paid_arry as $key=>$val) { ?>  	
	            <div class="contain-section">
	                <div class="contain-img"><img src="<?php echo $val['pluginimage']; ?>"></div>
	                <div class="contain-title"><a target="_blank" href="<?php echo $val['pluginurl'];?>"><?php echo $key;?></a></div>
	            </div>
	            <?php } }?>
           </div>
          
        </div>

    <?php
   
   } 
   
   public  function  get_post_custom_taxonomy_term_shortcode_pointers_footer  ( ){  
   	
 	 $admin_pointers = get_post_custom_taxonomy_term_shortcode_admin_pointers();
	    ?>
	    <script type="text/javascript">
	        /* <![CDATA[ */
	        ( function($) {
	            <?php
	            foreach ( $admin_pointers as $pointer => $array ) {
	               if ( $array['active'] ) {
	                  ?>
	            $( '<?php echo $array['anchor_id']; ?>' ).pointer( {
	                content: '<?php echo $array['content']; ?>',
	                position: {
	                    edge: '<?php echo $array['edge']; ?>',
	                    align: '<?php echo $array['align']; ?>'
	                },
	                close: function() {
	                    $.post( ajaxurl, {
	                        pointer: '<?php echo $pointer; ?>',
	                        action: 'dismiss-wp-pointer'
	                    } );
	                }
	            } ).pointer( 'open' );
	            <?php
	         }
	      }
	      ?>
	        } )(jQuery);
	        /* ]]> */
	    </script>
	<?php	
 }
    

} 

function get_post_custom_taxonomy_term_shortcode_admin_pointers ( ) { 
		
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	    $version = '1_0'; // replace all periods in 1.0 with an underscore
	    $prefix = 'get_post_custom_taxonomy_term_shortcode_admin_pointers' . $version . '_';
	
	    $new_pointer_content = '<h3>' . __( 'Welcome to Advanced Post Listing Shortcode' ) . '</h3>';
	    $new_pointer_content .= '<p>' . __( 'Advanced Post Listing Shortcode plugins allows you to generate shortcode to display posts using the filter of post type, taxonomy or with specific search term.' ) . '</p>';
	
	    return array(
	        $prefix . 'get_post_custom_taxonomy_term_shortcode_admin_pointers' => array(
	            'content' => $new_pointer_content,
	            'anchor_id' => '#toplevel_page_get-post-custom-taxonomy-term-shortcode',
	            'edge' => 'left',
	            'align' => 'left',
	            'active' => ( ! in_array( $prefix . 'get_post_custom_taxonomy_term_shortcode_admin_pointers', $dismissed ) )
	        )
	    );
}
