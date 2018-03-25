<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 26/10/2016
 * Time: 11:45 AM
 */


function my_theme_enqueue_styles() {

  $parent_style = 'parent-style';

  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style',
    get_stylesheet_directory_uri() . '/style.css',
    array( $parent_style ),
    wp_get_theme()->get('Version')
  );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function hoverfeaturedimg(){

	$nth_image = $dynamic_featured_image -> get_all_featured_images( [$postId (291)] );
}

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
    return sprintf( ' <a class="read-more" href="%1$s">%2$s</a>',
        get_permalink( get_the_ID() ),
        __( '..read more', 'textdomain' )
    );
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

/* added by Terry on 20161207 */
function news_archive_rewrite_rules( $wp_rewrite ) {
    $new_rules1 = array('en/news/([0-9]{4})' => 'year='.$wp_rewrite->preg_index(1).'&lang=en&cat=33');
    $new_rules2 = array('en/news/([0-9]{4})/page/([2-9][0-9]*)' => 'year='.$wp_rewrite->preg_index(1).'&lang=en&cat=33&paged='.$wp_rewrite->preg_index(2));
    $wp_rewrite->rules = $new_rules2 + $new_rules1 + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'news_archive_rewrite_rules');

?>