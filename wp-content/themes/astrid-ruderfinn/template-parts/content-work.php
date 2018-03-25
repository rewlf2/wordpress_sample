<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrid
 */

?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header fullspan headline">
            <?php
      $id = get_the_ID();
      $cat = get_the_category($id)[0]->name;
    ?>
                <p class="entry-category">Works |
                    <?php echo $cat ?>
                </p>
                <?php
			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}

		if ( 'post' === get_post_type() && get_theme_mod('hide_meta') != 1 ) : ?>
                    <div class="entry-meta">
                        <?php astrid_posted_on(); ?>
                    </div>
                    <!-- .entry-meta -->
                    <?php
		endif; ?>
        </header>
        <!-- .entry-header -->
        <div class="cozy">
                <aside id="bcn_widget-3" class="widget widget_breadcrumb_navxt amr_widget">
                    <!-- Breadcrumb NavXT 5.5.2 --><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Go to Ruderfinn Asia." href="http://www2.ruderfinnasia.com" class="home"><span property="name">Ruderfinn Asia</span></a>
                    </span> &gt;&gt; <span property="itemListElement" typeof="ListItem">
                    <?php
$categories = get_the_category();
if ( ! empty( $categories ) ) {

    $child = get_category($categories[0]->term_id);
    $parent_name = get_category($child->parent);

    echo '<a href="' . get_field('category_path', "category_".$parent_name->term_id) . '">' . esc_html( $parent_name->name ) . '</a>';
}?>
                    </span> &gt;&gt; <span property="itemListElement" typeof="ListItem">
                    <?php $categories = get_the_category();
if ( ! empty( $categories ) ) {
    echo '<a href="' . get_field('category_path', "category_".$categories[0]->term_id) . '">' . esc_html( $categories[0]->name ) . '</a>';
}?>
                    </span> &gt;&gt; <span property="itemListElement" typeof="ListItem"><span property="name"><?php the_title() ?></span> </span>
                </aside>
        </div>
        <?php if ( has_post_thumbnail() && ( get_theme_mod( 'featured_image' ) != 1 ) ) : ?>
            <?php if ( is_single() ) : ?>
                <div class="single-thumb">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_post_thumbnail('astrid-large-thumb'); ?>
                    </a>
                </div>
                <?php else : ?>
                    <div class="entry-thumb">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_post_thumbnail('astrid-medium-thumb'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                        <?php endif; ?>
                            <?php if ( is_single() ) : ?>
                                <div class="entry-content cozy">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <?php the_content(); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php echo do_shortcode("[do_widget id=nav_menu-3] [do_widget id=nav_menu-4]"); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php else : ?>
                                    <div class="entry-summary">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="read-more clearfix">
                                        <a class="button post-button" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <?php esc_html_e('Read more', 'astrid'); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                        <?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'astrid' ),
			'after'  => '</div>',
		) );
	?>
                                            <?php if ( is_single() && get_theme_mod('hide_meta') != 1 ) : ?>
                                                <footer class="entry-footer">
                                                    <?php astrid_entry_footer(); ?>
                                                </footer>
                                                <!-- .entry-footer -->
                                                <?php endif; ?>
    </article>
    <!-- #post-## -->