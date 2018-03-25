<?php
/*
CMS Navigator, Josef Leifeld, http://www.webdesign-leifeld.de
Seiten beim speichern der Nenus ordnen 
Seitenuebersicht um ID und Menu erweitern
License GPL2 https://www.gnu.org/licenses/gpl-2.0.html
*/

//Optionen abfragen
$page_option = get_option('cms_navigator');

#Alle Seiten ordnen
function wa_cms_navigator_auto_move_page() 
{
 global $wpdb;
 $debug = false;	
 $seiten = array();
	
	# ALLE Kategorien ermitteln
	$categories = get_terms( 'nav_menu', array( 'orderby' => 'term_id', 'order' => 'ASC', 'hide_empty' => true ) ); 	
	
	# Alle Kategorien ausgeben	
	$i=0;
	foreach($categories as $categorie)
	{
		$menu = wp_get_nav_menu_object ($categorie->term_id);
		$menu_items = wp_get_nav_menu_items($menu->term_id);

		if ($debug == true)
		{		
			echo '<pre style="margin-left:250px;">';
				print_r($menu_items);
			echo '</pre>';
		}
		
		//Alle Seite in Array schreiben
		foreach ($menu_items as $item) 
		{
			if (array_key_exists($item->object_id, $seiten) == false ) {
				if ($item->object == 'page' ) {	
					$seiten[$item->object_id] = array	(	
															'ID' => $item->ID,
															'object_id' => $item->object_id,
															'menu' => $categorie->name,
															'title' => $item->title,
															'menu_item_parent' => $item->menu_item_parent,
															'object' => $item->object,
															'menu_order' => $i
														);
				$i++;	
				}
			}
		}		
	}

	//Alle Seiten ermitteln
	$pages = get_pages( array('sort_column' => 'menu_order', 'sort_order' => 'asc', 'post_type' => 'page', 'post_status' => 'publish,private,draft') );

	foreach ( $pages as $page ) 
	{
		//Nur hinzuschreiben wenn Seiten keinem Menu zugeordnet sind
		if (array_key_exists($page->ID, $seiten) == false ) {

			$seiten[$page->ID] = array 	(	
											'ID' => 99999,
											'object_id' => $page->ID,
											'menu' => '',
											'title' => $page->post_title,
											'menu_item_parent' => 0,
											'object' => $page->post_type,
											'menu_order' => $i
										);
		$i++;						
		}	
	}
	
	//key neu schreiben
	$seiten = array_values ( $seiten );
	
	//array schreiben um post_parent zu ermitteln
	foreach ($seiten as $key => $seite) {
			$item_index[$seite['ID']] = $key;     
	}

	if ($debug == true)
	{	
		echo '<pre style="margin-left:250px;">';
			print_r($item_index);
		echo '</pre>';	
	}
	
	//
	foreach ($seiten as $key => $seite) 
	{

		if (array_key_exists($seite['menu_item_parent'], $item_index))
		{
			if ($debug == true)
				{
				echo '<pre style="margin-left:250px;">';
					//key $item_index: enthaelt Eltern ID
					$id = $seite['menu_item_parent'];
					print_r($id);
					
					//wert aus $item_index: entspricht ID aus $seite
					$parentkey = $item_index[$id];
					print_r($item_index[$id]);	

					$post_parent = $seiten[$parentkey]['object_id'];
					print_r($post_parent);
					
				echo '</pre>';	
				
				echo '<pre style="margin-left:250px;">';
					print_r('key: '.$key);
					print_r('post_id: '.$seite['object_id']);
					print_r('post_parent: '.$post_parent);
					print_r('nenu_order: '.$seite['menu_order']);			

				echo '</pre>';
			}
			
			//Tabelle post schreiben: Unterordner
			$post_parent = $seiten[$item_index[$seite['menu_item_parent']]]['object_id'];
			//wp_update_post( array( 'ID' => $seite['object_id'], 'post_parent' => $post_parent, 'menu_order' => $seite['menu_order'], ) );	
			if(!isset($seiten[$item_index[$seite['menu_item_parent']]]['object'])) 
			{
				//wenn Eltern Seite ein Individuellem Link (keine Seite) ist: menu_order => '0' (Permalinks fonktionieren sonst nicht)
				wp_update_post( array( 'ID' => $seite['object_id'], 'post_parent' => $post_parent, 'menu_order' => '0', ) );
			} else {			
				wp_update_post( array( 'ID' => $seite['object_id'], 'post_parent' => $post_parent, 'menu_order' => $seite['menu_order'], ) );	
			}			
		} else {
			//Tabelle post schreiben: Level0
			wp_update_post( array( 'ID' => $seite['object_id'], 'post_parent' => 0, 'menu_order' => $seite['menu_order'], ) );	
		}
	}	
}
$wa_cms_navigator_menuorder = $page_option[2]['menuorder'];
if ($wa_cms_navigator_menuorder == "true" and !function_exists ( 'wa1784_auto_move_page' )) add_action ('wp_update_nav_menu', 'wa_cms_navigator_auto_move_page', 10, 2);
 

//categories im Backend "Alle Seiten" sichtbar machen
function wa_cms_navigator_menu_title($defaults)
{
	$defaults['wa_cms_navigator_menu'] = __(__('Menu', 'cms-navigator'));
	return $defaults;
}

function wa_cms_navigator_menu_use($menu_column_name, $post_id)
{
	if($menu_column_name === 'wa_cms_navigator_menu'){
		echo wa_cms_navigator_categories_pagelist($post_id);		
	}
}


function wa_cms_navigator_categories_pagelist($id)
{
	# ALLE Kategorien ermitteln
	$categories = get_terms( 'nav_menu', array( 'orderby' => 'term_id', 'order' => 'ASC', 'hide_empty' => true ) ); 

	# Kategorien ausgeben
	$mnu = "";
	foreach($categories as $categorie)
	{
		$menu = wp_get_nav_menu_object ($categorie->term_id);
		$menu_items = wp_get_nav_menu_items($menu->term_id);
				
		foreach ($menu_items as $item) 
		{
			if ($item->object_id == $id) 
			{
				$mnu .= $categorie->name.', ';
			}
		}		
	}
	return substr($mnu, 0, -2);
}

$wa_cms_navigator_id_menutitle = $page_option[0]['checkmenu'];
if ($wa_cms_navigator_id_menutitle == "true" and !function_exists ( 'wa1784_menu_title' )) add_filter('manage_pages_columns', 'wa_cms_navigator_menu_title', 4);
if ($wa_cms_navigator_id_menutitle == "true" and !function_exists ( 'wa1784_menu_use' ))add_action('manage_pages_custom_column', 'wa_cms_navigator_menu_use', 4, 2);
//@categories...

//Page-IDs im Backend "Alle Seiten" sichtbar machen
function wa_cms_navigator_kb_posts_columns_id($defaults)
{
	$defaults['kb_wps_post_id'] = __('ID');
	return $defaults;
}

function wa_cms_navigator_kb_posts_custom_id_columns($kb_column_name, $id)
{
	if($kb_column_name === 'kb_wps_post_id'){
		echo $id;
	}
}
$wa_cms_navigator_id_pagetitle = $page_option[1]['checkid'];
if ($wa_cms_navigator_id_pagetitle == "true" and !function_exists ( 'wa1784_kb_posts_columns_id' )) add_filter('manage_pages_columns', 'wa_cms_navigator_kb_posts_columns_id', 4);
if ($wa_cms_navigator_id_pagetitle == "true" and !function_exists ( 'wa1784_kb_posts_custom_id_columns' )) add_action('manage_pages_custom_column', 'wa_cms_navigator_kb_posts_custom_id_columns', 4, 2);
//@Page-IDs...

?>