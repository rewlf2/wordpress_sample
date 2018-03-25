<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrid
 */

?>
<style>
    .arc-post-holder1{
        width: 25%;
        clear: none;
        float: left;
        padding: 0px -10px;
        height: 600px;
        display: table;
    }
    
    .arc-entry-title{
        font-size: large;
        
    }
    
    .arc-entry-content{
        font-size: x-small;
    }
    
    .arc-entry-summary{
        font-size: x-small;
    }
</style>
<div class="cozy arc-post-holder">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<header class="arc-entry-header">
		<?php
			if ( is_single() ) {
				the_title( '<h1 class="arc-entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="arc-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}

		if ( 'post' === get_post_type() && get_theme_mod('hide_meta') != 1 ) : ?>
		<div class="arc-entry-meta">
<!--			<?php astrid_posted_on(); ?>-->
		</div><!-- .entry-meta -->
		<?php
		endif; ?>
	</header><!-- .entry-header -->	

	<?php if ( has_post_thumbnail() && ( get_theme_mod( 'featured_image' ) != 1 ) ) : ?>
		<?php if ( is_single() ) : ?>
		<div class="arc-single-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('astrid-large-thumb'); ?></a>
		</div>	
		<?php else : ?>
		<div class="arc-entry-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('astrid-medium-thumb'); ?></a>
		</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_single() ) : ?>
	<div class="arc-entry-content">
		<?php the_content(); ?>
	</div>
	<?php else : ?>
	<div class="arc-entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<div class="arc-read-more clearfix">
		<a class="arc-button post-button" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php esc_html_e('Read more', 'astrid'); ?></a>
	</div>
	<?php endif; ?>

	<?php
		wp_link_pages( array(
			'before' => '<div class="arc-page-links">' . esc_html__( 'Pages:', 'astrid' ),
			'after'  => '</div>',
		) );
	?>
		
	<?php if ( is_single() && get_theme_mod('hide_meta') != 1 ) : ?>
	<footer class="arc-entry-footer">
		<?php astrid_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	<?php endif; ?>
</article>
</div><!-- #post-## -->
