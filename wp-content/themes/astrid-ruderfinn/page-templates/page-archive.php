<?php /*Template Name: Archives*/ ?>

<?php get_header(); ?>

<style>
ul.bycategories {
margin: 0;
padding: 0;
}
ul.bycategories li {
list-style: none; 
list-style-type: none;
margin: 0; 
padding: 0;
}
ul.bycategories li a {
list-style: none; 
list-style-type: none;
margin: 0 20px 15px 0; 
float: left; 
background: #eee; 
color: #464646; 
padding: 5px 10px;
border-radius: 5px; 
-moz-border-radius: 5px; 
-webkit-border-radius: 5px;
}
ul.bycategories li a:hover{
text-decoration: none; 
background: #ff6200; 
color: #fff;
}
.clear{clear: both;}
</style>

<div id="primary" class="site-content">
<div id="content" role="main">

<?php while ( have_posts() ) : the_post(); ?>
				
<h1 class="entry-title"><?php the_title(); ?></h1>

<div class="entry-content">

<?php the_content(); ?>

<!--/* Custom Archives Functions Go Below this line */-->

<p><strong>Categories:</strong></p>
<ul class="bycategories">
<?php wp_list_categories('title_li='); ?>
</ul>
<div class="clear"></div>

<!--/* Custom Archives Functions Go Above this line */-->

</div><!-- .entry-content -->

<?php endwhile; // end of the loop. ?>

</div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>