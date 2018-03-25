<?php
/*
Plugin Name: CSV Mass Importer
Description: Imports and updates post data from CSV file.
Version:     1.2
Author:      Aleapp
License:     GPL2
*/

define('CMI_INDEX', plugin_dir_url(__FILE__));
define('CMI_ROOT', dirname(__FILE__));

add_action('plugins_loaded', 'cmi_load_textomain');
function cmi_load_textomain(){
	load_plugin_textdomain('cmi', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

require CMI_ROOT . '/helpers.php';
require CMI_ROOT . '/helpers-html.php';
require CMI_ROOT . '/admin-actions.php';
require CMI_ROOT . '/admin.php';
