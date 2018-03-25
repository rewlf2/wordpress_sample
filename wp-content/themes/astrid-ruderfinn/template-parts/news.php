<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrid
 */

?>
							<article id="post-<?php the_ID(); ?>" <?php //post_class(); ?>>
								<header class="news-header">
									<?php
										the_title( sprintf( '<h1 class="news-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h1>' );
										echo "<h2>".get_the_date('Y-m-d')."</h2>";
									?>
								</header><!-- .entry-header -->

								<div class="news-content">
									<?php
										the_excerpt();

										wp_link_pages( array(
											'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'astrid' ),
											'after'  => '</div>',
										) );
									?>
								</div><!-- .entry-content -->

							</article><!-- #post-## -->
