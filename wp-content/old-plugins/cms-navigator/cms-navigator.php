<?php
/*
Plugin Name: CMS Navigator
Plugin URI:  http://www.cms-navigator.com
Description: Tree view for all menus and list of pages not contained in the tree. Status: Menu switched on or off, draft, published, deleted pages. Additional functions for the admin menu "All pages". Ordering pages automatically.
Version:     1.0
Author:      Josef Leifeld
Author URI:  http://www.cms-navigator.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages/
Text Domain: cms-navigator
*/

# Recht "manage_options" fuer Redakteure setzen
function wa_cms_navigator_add_theme_caps() {

    $role = get_role( 'editor' );
	$role -> add_cap('manage_options');		
	}
add_action( 'admin_init', 'wa_cms_navigator_add_theme_caps');



#Neue Adminseite: CMS Navigator
function wa_cms_navigator_cms_navigator_control_menu() {
   //Achtung für den Redakteuer (editor) muss das Recht 'manage_options' gesetzt sein: $role -> add_cap('manage_options');	
   add_submenu_page('edit.php?post_type=page', 'cms-navigator-control', 'CMS Navigator', 'manage_options', 'cms-navigator-control-menu', 'wa_cms_navigator_cms_navigator_control_options');
}
add_action('admin_menu', 'wa_cms_navigator_cms_navigator_control_menu');

#Neue Adminseite aufbauen
function wa_cms_navigator_cms_navigator_control_options() 
{
	global $wpdb;   //, $post, $pages;
	
	
	$debug = "false";
	$server_url = admin_url().'edit.php?post_type=page&page=cms-navigator-control-menu';
	
	//Plugin Ordner
	$plugin_url = plugins_url( '/', __FILE__ );

	//checkboxen auswerten
	$checkmenu = '';
	$checkid = '';
	$menuorder = '';
	
	if (isset($_POST["send"]))
	{
		if (isset($_POST["checkmenu"]))
		{
			if ($_POST["checkmenu"] == 'checked')
			{
				$option[] = array	('checkmenu' => 'true');
			}
		} else {
			$option[] = array	('checkmenu' => 'false');
		}
		
		if (isset($_POST["checkid"]))
		{
			if ($_POST["checkid"] == 'checked')
			{
				$option[] = array	('checkid' => 'true');
			}
		} else {
			$option[] = array	('checkid' => 'false');
		}

		if (isset($_POST["menuorder"]))
		{
			if ($_POST["menuorder"] == 'checked')
			{
				$option[] = array	('menuorder' => 'true');
			}
		} else {
			$option[] = array	('menuorder' => 'false');
		}
		
		//optionen schreiben
		update_option('cms_navigator', $option );
	}
	
	//optionen einlesen
	$page_option = get_option('cms_navigator');

	if ($page_option[0]['checkmenu'] == 'true')		$checkmenu = 'checked="checked"';
	if ($page_option[0]['checkmenu'] == 'false') 	$checkmenu = '';

	if ($page_option[1]['checkid'] == 'true')		$checkid = 'checked="checked"';
	if ($page_option[1]['checkid'] == 'false') 		$checkid = '';
	
	if ($page_option[2]['menuorder'] == 'true')		$menuorder = 'checked="checked"';
	if ($page_option[2]['menuorder'] == 'false') 	$menuorder = '';	
	//@optionen	

	#checkboxen
	echo 	'<form  id="form1" name="form1" method="post" action="'.$server_url.'">
			  <input name="send" type="hidden" id="send" value="yes" />
			  <div style="float:right;margin-top:0px;margin-right:20px;">
			  <p><strong>'.__('Extensions Admin: Design/Menu', 'cms-navigator').'</strong><br /> 
			  <label><input type="checkbox" name="menuorder" value="checked" '.$menuorder.' onClick="this.form.submit()" />'.__('Sort pages automatically', 'cms-navigator').'</label></p></div>	
			  <div style="float:right;margin-top:0px;margin-right:20px;">			  
			  <p><strong>'.__('Extensions Admin: Pages/All Pages', 'cms-navigator').'</strong><br />
			  <label><input type="checkbox" name="checkmenu" value="checked" '.$checkmenu.' onClick="this.form.submit()" />'.__('List Menu', 'cms-navigator').'</label>
			  <label><input type="checkbox" name="checkid" value="checked" '.$checkid.' onClick="this.form.submit()" />'.__('List ID', 'cms-navigator').'</label></p></div>
			 </form>';
	
 	echo '<h1 style="margin-bottom:20px;">CMS Navigator</h1>';		
	echo '<p><sup>*</sup>'.__('Buy the CMS Navigator pro with advanced features:', 'cms-navigator').' ';
	echo '<a href="http://www.cms-navigator.com" target="_blank">www.cms-navigator.com</a><br />';
	echo __('The pro version permits actions like enabling and disabling menus on the website.', 'cms-navigator').' ';
	echo __('The symbols draft, published, copy, delete are active.', 'cms-navigator').'</p>';	
	

	# ALLE Kategorien ermitteln
	$categories = get_terms( 'nav_menu', array( 'orderby' => 'term_id', 'order' => 'ASC', 'hide_empty' => true ) ); 

	# Kategorien ausgeben
	$ii=1;
	foreach($categories as $categorie){
		echo '<h2>'.$ii++.'. '.$categorie->name.'</h2>';

		# Seiten aus den einzelnen Kategorien ermitteln 
		$menu = wp_get_nav_menu_object ($categorie->term_id);
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		
		# Level ermitteln; class "walker" zu Hilfe nehmen
		$menu = wp_nav_menu(array(  'echo' => false,'container' => false, 'menu'   => $categorie->term_id, 'walker' => new wa_cms_navigator_Excerpt_Walker()));
		$menu = preg_replace ('/[^0123456789:;]/','',$menu); 
		$menu=substr($menu,0,-1);

		$depths = explode(';', $menu);

		foreach($depths as $dep){
			$ebenen[] = array	(	'postid' => substr($dep, 0, strpos($dep, ':')), 
									'level' => substr($dep, strpos($dep, ':')+1, strlen($dep))
								);
		}
 		 
		# Menus ausgeben
		$txt = '<div class="wa-tree">';
		$txt .= '<div class="tree"></div>';
		$txt .= '<table style="width:100%">';	

		$txt .= '<tr>';
		$txt .= '<th align="left" style="width:15%;"><p>'.__('Title', 'cms-navigator').'</p></th>';
		$txt .= '<th align="left" style="width:5%;"><p>'.__('Menus', 'cms-navigator').'<sup>*</sup></p></th>';
		$txt .= '<th align="left" style="width:6%;"><p>'.__('Pages', 'cms-navigator').'<sup>*</sup></p></th>';		
		$txt .= '<th align="center" style="width:5%;"><p>'.__('ID', 'cms-navigator').'</p></th>';
		$txt .= '<th align="left" style="width:9%;"><p>'.__('Status', 'cms-navigator').'</p></th>';
		$txt .= '<th align="left" style="width:10%;"><p>'.__('Author', 'cms-navigator').'</p></th>';	
		$txt .= '<th align="left" style="width:10%;"><p>'.__('Date', 'cms-navigator').'</p></th>';	
		$txt .= '<th align="left" style="width:40%;"><p>'.__('Permalink', 'cms-navigator').'</p></th>';			
		$txt .= '</tr>';	
			
		foreach ($menu_items as $item) 
		{			
			//Array mit Navigations ID zum abgleich mit weiteren Seiten erzeugen
			$mnu[] = $item->object_id;	
								
			$status = get_post($item->object_id)->post_status;
			$password = "";
			if (get_post($item->object_id)->post_password != "" and $status != 'trash') $password = "password";
			
			if ($item->object != "page" and get_post($item->object_id)->post_content == "") $status = "forwarding";

			if ($item->object != "page") {
				$link = apply_filters( 'the_title', $item->title, $item->ID );				
			} else {
				$url = admin_url().'post.php?post='.$item->object_id.'&action=edit';
				$link = '<a class="'.$status.' menu" href="'.$url.'" alt="'.__('edit page', 'cms-navigator').'" title="'.__('edit page', 'cms-navigator').'">'.apply_filters( 'the_title', $item->title, $item->ID ).'</a>';
			}
			
			//css
			$pageid = ' pageid'.$item->object_id;	

			$level = '0';
			foreach($ebenen as $ebene)
			{
				if ($ebene['postid'] == $item->ID)
				{	
					$level = $ebene['level'];
				} 
			}

			$txt .= '<tr>';
				//Titel
				$txt .= '<td><p class="level'.$level.$pageid.' '.$status.'"><span class="linie"></span>'.$link.'</p></td>';
				//Menu
				$txt .= '<td>';	
					$txt .= '<img class="icon" 
					alt="'.__('set enabled/disabled', 'cms-navigator').'" title="'.__('set enabled/disabled', 'cms-navigator').'" 
					src="'.$plugin_url.'images/online.png">';
					
					if ($status == 'forwarding') 
					$txt .= '<img class="icon" 
						alt="'.__('forwarding', 'cms-navigator').'" title="'.__('forwarding', 'cms-navigator').'" 
						src="'.$plugin_url.'images/page_weiter.png">';	
				$txt .= '</td>';	
				
				//Pages
				$txt .= '<td>';					  
					if ($status == 'publish') 
					$txt .= '<img class="icon" 
						alt="'.__('set draft/published', 'cms-navigator').'" title="'.__('set draft/published', 'cms-navigator').'" 
						src="'.$plugin_url.'images/page_publish.png">';
					if ($status == 'draft') 
					$txt .= '<img class="icon" 
						alt="'.__('set draft/published', 'cms-navigator').'" title="'.__('set draft/published', 'cms-navigator').'" 
						src="'.$plugin_url.'images/page_draft.png">';	

					if ($status == 'private') 
					$txt .= '<img class="icon" alt="" title="" src="'.$plugin_url.'images/clear.png">';	

					//Page copy	
					if ($status != 'forwarding' and $status != 'trash')						
					$txt .= '<img class="icon" 
						alt="'.__('copy page', 'cms-navigator').'" title="'.__('copy page', 'cms-navigator').'" 
						src="'.$plugin_url.'images/copy.png">';	
						
					if ($status != 'forwarding' and $status != 'trash')
					$txt .= '<img class="icon" 
						alt="'.__('trash', 'cms-navigator').'" title="'.__('trash', 'cms-navigator').'" 
						src="'.$plugin_url.'images/delete.png">';

					if ($status == 'trash')	
					$txt .= '<img class="icon" 
						alt="'.__('trash', 'cms-navigator').'" title="'.__('trash', 'cms-navigator').'" 
						src="'.$plugin_url.'images/trash.png">';
						
					if ($password == 'password') 
					$txt .= '<img class="icon" 
						alt="'.__('password', 'cms-navigator').'" title="'.__('password', 'cms-navigator').'" 
						src="'.$plugin_url.'images/page_look.png">';
						
					if ($status == 'private') 
					$txt .= '<img class="icon" 
						alt="'.__('privately published', 'cms-navigator').'" title="'.__('privately published', 'cms-navigator').'" 
						src="'.$plugin_url.'images/page_private.png">';
						
				$txt .= '</td>';
				
				//ID
				$txt .= '<td align="center"><p>'.$item->object_id.'</p></td>';
				//Status
				$txt .= '<td><p>'.__($status, 'cms-navigator').'</p></td>';	
				
				//Autor
				$txt .= '<td>'.get_the_author_meta( 'display_name', get_post( $item->object_id ) ->post_author ).'</td>';
				//Datum
				$txt .= '<td>'.get_the_date( 'm.d.Y  H:i', $item->object_id ).'</td>';
				//Permalink
				if ($status == 'publish') {
					$txt .= '<td><a alt="'.__('go to website', 'cms-navigator').'" title="'.__('go to website', 'cms-navigator').'"
					href="'.get_permalink( $item->object_id ).'" target="_blank">'.get_permalink( $item->object_id ).'</a></td>';
				} else {
					$txt .= '<td></td>';
				}
				
				$txt .= '</tr>';
			}
			$txt .= '</table>';
			$txt .= '</div>';	
			echo $txt;
			
			if ($debug == "true") 
			{
				echo '<pre>';
					print_r($menu_items);
				echo '</pre>';
			}
	}


	echo '<h2>'.__('Other pages', 'cms-navigator').'</h2>';

	$page_list = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'asc', 'post_status' => 'publish,private,draft,trash' ) );
	foreach( $page_list as $page_item ) {
		$page_ids[] = $page_item->ID;
	} 	
	
			$txt = '<div class="wa-tree wa-all">';
			$txt .= '<table style="width:100%">';	

			$txt .= '<tr>';
			$txt .= '<th align="left" style="width:15%;"><p>'.__('Title', 'cms-navigator').'</p></th>';
			$txt .= '<th align="left" style="width:5%;"><p> </p></th>';
			$txt .= '<th align="left" style="width:6%;"><p>'.__('Pages', 'cms-navigator').'<sup>*</sup></p></th>';		
			$txt .= '<th align="center" style="width:5%;"><p>'.__('ID', 'cms-navigator').'</p></th>';
			$txt .= '<th align="left" style="width:9%;"><p>'.__('Status', 'cms-navigator').'</p></th>';
			$txt .= '<th align="left" style="width:10%;"><p>'.__('Author', 'cms-navigator').'</p></th>';	
			$txt .= '<th align="left" style="width:10%;"><p>'.__('Date', 'cms-navigator').'</p></th>';	
			$txt .= '<th align="left" style="width:40%;"><p>'.__('Permalink', 'cms-navigator').'</p></th>';				
			$txt .= '</tr>';		
			
	foreach($page_ids as $page)
	{
		if (!in_array($page, $mnu) and get_the_title($page) != "Automatisch gespeicherter Entwurf") 
		{
		
			$status = get_post_status($page);
			$password = "";
			if (get_post($page)->post_password != "" and $status != 'trash') $password = "password";
						
			$url = admin_url().'post.php?post='.$page.'&action=edit';
			$link = '<a class="'.$status.'" href="'.$url.'" alt="'.__('edit page', 'cms-navigator').'" title="'.__('edit page', 'cms-navigator').'">'.get_the_title($page).'</a>';

			$pageid = ' pageid'.$page;
			$txt .= '<tr>';
			$txt .= '<td><p class="level'.get_the_title($page).'">'.$link.'</p></td>';
			
			$txt .= '<td> </td>';
					
			$txt .= '<td>';			
				if ($status == 'publish') 
				$txt .= '<img class="icon" 
					alt="'.__('set draft/published', 'cms-navigator').'" title="'.__('set draft/published', 'cms-navigator').'" 
					src="'.$plugin_url.'images/page_publish.png">';
					
				if ($status == 'draft') 
				$txt .= '<img class="icon" 
					alt="'.__('set draft/published', 'cms-navigator').'" title="'.__('set draft/published', 'cms-navigator').'" 
					src="'.$plugin_url.'images/page_draft.png">';	
					
				if ($status == 'private') 
				$txt .= '<img class="icon" alt="" title="" src="'.$plugin_url.'images/clear.png">';						
	
				//Page copy	
				if ($status != 'forwarding' and $status != 'trash')						
				$txt .= '<img class="icon" 
					alt="'.__('copy page', 'cms-navigator').'" title="'.__('copy page', 'cms-navigator').'" 
					src="'.$plugin_url.'images/copy.png">';	
						
				if ($status != 'trash')	
				$txt .= '<img class="icon" 
					alt="'.__('trash', 'cms-navigator').'" title="'.__('trash', 'cms-navigator').'" 
					src="'.$plugin_url.'images/delete.png">';
					
				if ($status == 'trash')	
				$txt .= '<img class="icon" 
					alt="'.__('trash', 'cms-navigator').'" title="'.__('trash', 'cms-navigator').'" 
					src="'.$plugin_url.'images/trash.png">';
					
				if ($password == 'password') 
				$txt .= '<img class="icon" 
					alt="'.__('password', 'cms-navigator').'" title="'.__('password', 'cms-navigator').'" 
					src="'.$plugin_url.'images/page_look.png">';
					
				if ($status == 'private') 
				$txt .= '<img class="icon" 
					alt="'.__('privately published', 'cms-navigator').'" title="'.__('privately published', 'cms-navigator').'"  
					src="'.$plugin_url.'images/page_private.png">';
					
			$txt .= '</td>';			
			
			$txt .= '<td align="center"><p>'.$page.'</p></td>';
			$txt .= '<td><p>'.__($status, 'cms-navigator').'</p></td>';	
				
			$txt .= '<td style="width:80px;">'.get_the_author_meta( 'display_name', get_post( $page ) ->post_author ).'</td>';
			$txt .= '<td>'.get_the_date( 'm.d.Y  H:i', $page ).'</td>';	
		
			//Permalink
			if ($status == 'publish') {
				$txt .= '<td><a alt="'.__('go to website', 'cms-navigator').'" title="'.__('go to website', 'cms-navigator').'" 
					href="'.get_permalink( $page ).'" target="_blank">'.get_permalink( $page ).'</a></td>';
			} else {
				$txt .= '<td></td>';
			}
			
			$txt .= '</tr>';
		}		
	}	
			$txt .= '</table>';
			$txt .= '</div>';

		echo $txt;	

}

class wa_cms_navigator_Excerpt_Walker extends Walker_Nav_Menu
{
    //function start_el(&$output, $item, $depth, $args)
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
		$output .= $item->ID.':'.$depth.';';	
    }
}


function wa_cms_navigator_cms_navigator_load_plugin_textdomain() {
	load_plugin_textdomain( 'cms-navigator', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wa_cms_navigator_cms_navigator_load_plugin_textdomain' );

	
	
//css datei einbinden
function wa_cms_navigator_load_custom_wp_admin_style() {
		$plugin_url = plugins_url( '/', __FILE__ );
        wp_register_style( 'wa_cms_navigator_custom_wp_admin_css', $plugin_url . 'style.css', false, '1.0.0' );
        wp_enqueue_style( 'wa_cms_navigator_custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'wa_cms_navigator_load_custom_wp_admin_style' );


require_once 'all_page.php';
?>