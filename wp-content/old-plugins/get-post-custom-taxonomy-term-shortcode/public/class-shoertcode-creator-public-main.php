<?php 
function post_listing($atts){
	ob_start(); 
	global $wpdb; 
	global $post; 
	$post_type_name = isset( $atts['post_name'] ) ? $atts['post_name'] :'';
	$taxonomy_type_name = isset( $atts['texonomy_name'] ) ? $atts['texonomy_name'] :'';
	$term_type_name = isset( $atts['term_name'] ) ? $atts['term_name'] : '';
	$display_view = isset( $atts['display_view'] ) ? $atts['display_view'] :'';
	if(isset($post_type_name) && isset($taxonomy_type_name) && isset($term_type_name) ){
	$PostListArgs = array( 
		'post_type'=> $post_type_name, 
		'tax_query' => array(
					array(
						'taxonomy' => $taxonomy_type_name,
						'field' => 'slug', 
						'terms' => $term_type_name
					)), 
				'orderby' => 'menu_order', 
				'order' => 'ASC', 
				'showposts' => -1
		); 
	}
	else if(isset($post_type_name) && isset($taxonomy_type_name) ){
		$PostListArgs = array( 
		'post_type'=> $post_type_name, 
		'tax_query' => array(
					array(
						'taxonomy' => $taxonomy_type_name,
						'field' => 'slug', 
					)), 
				'orderby' => 'menu_order', 
				'order' => 'ASC', 
				'showposts' => -1
		); 
	}
	else if(isset($post_type_name) ){
		$PostListArgs = array( 
			'post_type'=> $post_type_name, 
			'orderby' => 'menu_order', 
			'order' => 'ASC', 
			'showposts' => 100
		); 
	}
	$my_query = new WP_Query($PostListArgs); 
	if($display_view == 'first-view'){
		echo '<div class="shortcode-main '.$display_view.'">';
			if ($my_query->have_posts()) {
				while ($my_query->have_posts()) : $my_query->the_post();
				echo '<div class="row">';
				echo ' <div class="content-block-main">
					  <div class="site-title">'.get_the_title().'</div>
					 <div class="site-content">';
					if (empty($post->post_content)) {
						echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis sollicitudin sem, sed blandit diam porttitor at. Donec auctor, lacus id mollis gravida, tortor neque egestas justo, quis posuere velit neque nec augue. Suspendisse eget pulvinar sem.';
					}else {
							 the_content();
						}
					echo '</div></div></div>';
			endwhile; 
		}
		echo '</div>';
				
		 	
	}elseif($display_view == 'second-view'){
		
		echo '<div class="shortcode-main '.$display_view.'">';
			if ($my_query->have_posts()) {
				while ($my_query->have_posts()) : $my_query->the_post();
				echo '<div class="row">';
				echo ' <div class="content-block-main">
					  <div class="site-title">'.get_the_title().'</div>
					 <div class="site-content">';
					if (empty($post->post_content)) {
						echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis sollicitudin sem, sed blandit diam porttitor at. Donec auctor, lacus id mollis gravida, tortor neque egestas justo, quis posuere velit neque nec augue. Suspendisse eget pulvinar sem.';
					}else {
							 the_content();
						}
					echo '</div></div></div>';
			endwhile; 
		}
		echo '</div>';
	}elseif($display_view == 'third-view'){
		if ($my_query->have_posts()) {
			echo '<div class="shortcode-main '.$display_view.'">';
			while ($my_query->have_posts()) : $my_query->the_post();
			echo '<div class="row">';
				echo '<div class="site-thumbnail">' . get_the_post_thumbnail() .'</div>';
				echo '<div class="content-block-main"><div class="site-title">' . get_the_title() . '</div>';
				echo '<div class="site-content">';
						if (empty($post->post_content)) {
							echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis sollicitudin sem, sed blandit diam porttitor at. Donec auctor, lacus id mollis gravida, tortor neque egestas justo, quis posuere velit neque nec augue. Suspendisse eget pulvinar sem.</p>';
						} else {
							echo '<p>' . the_content() . '</p>';
						}
				echo '</div></div></div>';
			endwhile; 
		}
		echo '</div>';
	}else{
		if ($my_query->have_posts()) {
			$PostNumber = 1;
			while ($my_query->have_posts()) : $my_query->the_post();
				echo '<div id="content" class="site-content" role="main">
						<h2 class="entry-title">' . get_the_title() . '</h2>
					</div>';

				echo '<div class="entry-content">';
						if (empty($post->post_content)) {
							echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed iaculis sollicitudin sem, sed blandit diam porttitor at. Donec auctor, lacus id mollis gravida, tortor neque egestas justo, quis posuere velit neque nec augue. Suspendisse eget pulvinar sem.</p>';
						} else {
							echo '<p>' . the_content() . '</p>';
						}
				echo '</div><br/><br/>';
				$PostNumber++; 
			endwhile; 
		}
	}
		
		
		
		wp_reset_postdata();
	$post_data = ob_get_clean();
	return $post_data; 
}
add_shortcode( 'post_listing_data', 'post_listing');
