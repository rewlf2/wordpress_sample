<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrid
 */

?>
			        <div class="cozy">
                <aside id="bcn_widget-3" class="widget widget_breadcrumb_navxt amr_widget">
                    <span property="itemListElement" typeof="ListItem">
                    	<a property="item" typeof="WebPage" title="Go to Ruderfinn Asia." href="http://www2.ruderfinnasia.com" class="home">
                    		<span property="name">Ruderfinn Asia</span>
                    	</a>
                    </span>
                    &gt;&gt; 
                    <span property="itemListElement" typeof="ListItem"><a href="/whats-new">What's New</a></span>
                    &gt;&gt;
                    <span property="itemListElement" typeof="ListItem"><span property="name"><?php the_title() ?></span>
									</span>
                </aside>
			        </div>

    					<div class="news-article-wrapper">
								<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
									<div class="panel-grid-cell">
										<header class="news-header">
											<?php
												the_title( '<h1>', '</h1>' );
												echo "<h2>".get_the_date('Y-m-d')."</h2>";
											?>
										</header><!-- .entry-header -->

										<div class="news-content">
											<?php
												the_content();

												wp_link_pages( array(
													'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'astrid' ),
													'after'  => '</div>',
												) );
											?>
										</div><!-- .entry-content -->
									</div>
								</article><!-- #post-## -->
							</div>