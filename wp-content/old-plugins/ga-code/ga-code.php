<?php
/*
Plugin Name: GA Code
Description: GA Code is a simple method to use Google Analytics in your website
Version: 1.0.1
Author: Pedro Laxe
Author URI: http://www.phpsec.com.br/
License: GPLv2
*/
/*
 *      Copyright 2016 Pedro Laxe <pedro@phpsec.com.br>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

add_action( 'init', 'GACode' );

function GACode(){

/**
 * Function Activate GA-Code
 *
 * @since 1.0
 *
 */

register_activation_hook( __FILE__, 'GACode_install' );

/**
 * Function Compare Version of WP else Desactive Plugin
 *
 * @since 1.0
 *
 */

function GACode_install() {

  if ( version_compare( PHP_VERSION, '5.2.1', '<' )

    or version_compare( get_bloginfo( 'version' ), '3.3', '<' ) ) {

      deactivate_plugins( basename( __FILE__ ) );

  }

  add_option( 'GACode', 'GACode_defeito' );

}
}

/**

 * Admin Page Functions

 *

 * @since 1.0

 *

 */

if ( is_admin() ){

	add_action('admin_menu', 'GACode_opcoes');

}

/**
 * Function add Page Options in WP Menu
 *
 * @since 1.0
 *
*/

function GACode_opcoes() {

  add_menu_page( 'GA-Code', 'GA-Code', 'manage_options', 'GA-Code-options', 'GACode_content' );

}

/**
*
* Scripts on Footer
*
* @since 1.0
*/
function Gacode_footer() {
    echo "
    <!-- GA-Code -->
    <script>
    	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    	ga('create', '".get_option('gacode')."', 'auto');
    	ga('send', 'pageview');

    </script>

	";
}
add_action('wp_footer', 'Gacode_footer');
/**

 * Admin Page Options

 *

 * @since 1.0

 *

 */



function GACode_content() {

?>
<div class="wrap">
    <div id="poststuff">
        <div id="postbox-container" class="postbox-container">
            <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                <div class="postbox " id="test1">
                    <div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>GA-Code</span></h3>
                    <div class="inside">
                    <form method="post" action="options.php">
                    <?php wp_nonce_field('update-options') ?>
                    <p>
                        <label for="gacode">Google Analytics Tracking Code</label>
                        <input type="text" name="gacode" placeholder="UA-XXXXXXXX-X" value="<?php echo get_option('gacode'); ?>" /><br>
                        <br>
                       	<input type="submit" class="button button-primary" name="botenvia" value="Save Modifications">
                       	<input type="hidden" name="action" value="update">
                       	<input type="hidden" name="page_options" value="gacode">
                   </p>
                   </form>
                    </div>
                </div>
        </div>
    </div>
</div>

<?php
}
