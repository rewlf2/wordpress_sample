<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astrid
 */

	get_header();

?>
    <div id="primary" class="content-area news">
			<main id="main" class="site-main" role="main">

        <div class="panel" style="background-color: #00B047; margin-bottom:0px;">
            <div class="headline" style="color: black;"><?php echo get_the_title( 395 ); ?></div>
        </div>

				<div class="cozy">
					<aside id="bcn_widget-3" class="widget widget_breadcrumb_navxt amr_widget">
						<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Ruderfinn Asia." href="http://www2.ruderfinnasia.com" class="home"><span property="name">Ruderfinn Asia</span></a></span>
						&gt;&gt; 
						<span property="itemListElement" typeof="ListItem"><a href="/whats-new">What's New</a></span>
						&gt;&gt;
						<span property="itemListElement" typeof="ListItem"><span property="name"><?php echo get_the_time("Y") ?></span> </span>
					</aside>
				</div>

				<div class="row">
					<div class="col-md-9">

						<div class="news-wrapper">
<?php

// The Query
if (get_query_var('paged')) {
    if (get_query_var('paged') == 0) {
        $paged = 1;
    } else {
        $paged = get_query_var('paged');
    }
} else {
    $paged = 1;
}
$the_query = new WP_Query( array( 'date_query' => array(array('year' => get_the_time('Y'))), 'category_name' => 'news', 'paged' => $paged) );

// The Loop
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        get_template_part( 'template-parts/news' );
    }

?>
							<div class="page-navigation">
<?php
    if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1 
        if ($paged > 1) {
//            echo "previous";
            echo get_previous_posts_link( '&lt;');
        }
        if ($paged < $the_query->max_num_pages) {
//            echo "next";
            echo get_next_posts_link( '&gt;', $the_query->max_num_pages );
        }
    }
?>
							</div>
<?php

} else {
    echo "no post";
    // no posts found
}
?>
						</div>

          </div>
          <div class="col-md-3"><?php echo do_shortcode("[do_widget id=wp-yearly-archive-2]"); ?></div>
        </div>

			</main><!-- #main -->
		</div><!-- #primary -->

<?php

	get_footer();

?>
