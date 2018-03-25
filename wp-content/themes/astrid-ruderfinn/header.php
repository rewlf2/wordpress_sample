<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrid
 */

?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php wp_head(); ?>
        <script type="text/javascript" src="/wp-admin/js/jquery.rwdImageMaps.js"></script>
        <!--<script type="text/javascript" src="/wp-admin/js/myjs.js"></script>-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46303130-3', 'auto');
  ga('send', 'pageview');

</script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?f968dffc9401e2973ae3fa11221143e7";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
    </head>

    <body <?php body_class(); ?>>
        <div class="preloader">
            <div class="preloader-inner">
                <ul>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
        <div id="page" class="site">
            <a class="skip-link screen-reader-text" href="#content">
                <?php esc_html_e( 'Skip to content', 'astrid' ); ?>
            </a>
            <header id="masthead" class="site-header <?php echo astrid_has_header(); ?>" role="banner">
                <div class="container">
                    <!--the following is for the top navigation in the header-->
                    <div id="top-navigation-container">
                        <div id="top-navigation">
                          <!--<i class="fa fa-twitter"></i> -->
                          <!--<i class="fa fa-youtube"></i> -->
                          <!--<i class="fa fa-facebook"></i> -->
                          <!--<i class="fa fa-linkedin"></i> -->
                          <!--<i class="fa fa-weibo"></i> -->
                          <span><a href="http://www.rfi-daylight.com/">RFI Daylight</a></span> &bull; <span><a href="http://www.ruderfinn.com/">Ruder Finn US</a></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="main-navigation">
                        <div class="site-branding col-md-4 col-sm-6 col-xs-12">
                            <!--?php astrid_branding(); ?-->
                            <a href="/" class="custom-logo-link" rel="home" itemprop="url"><img src="/wordpress/wp-content/uploads/2016/12/RFA-logo-800x152.jpg" class="custom-logo" alt="cropped-RFA-logo.jpg" itemprop="logo"></a>
                        </div>
                        <div class="btn-menu col-md-8 col-sm-6 col-xs-12"><i class="fa fa-navicon"></i></div>
                        <nav id="mainnav" class="main-navigation col-md-8 col-sm-6 col-xs-12" role="navigation">
                            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
                        </nav>
                        <!-- #site-navigation -->
                        <div class="clear"></div>
                    </div>
                </div>
            </header>
            <!-- #masthead -->
            <?php if ( astrid_has_header() == 'has-header' ) : ?>
                <div class="header-image">
                    <?php astrid_header_text(); ?> <img class="large-header" src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php bloginfo('name'); ?>">
                        <?php $mobile_default = get_template_directory_uri() . '/images/header-mobile.jpg'; ?>
                            <?php $mobile = get_theme_mod('mobile_header', $mobile_default); ?>
                                <?php if ( $mobile ) : ?> <img class="small-header" src="<?php echo esc_url($mobile); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php bloginfo('name'); ?>">
                                    <?php else : ?> <img class="small-header" src="<?php header_image(); ?>" width="1024" alt="<?php bloginfo('name'); ?>">
                                        <?php endif; ?>
                </div>
                <?php elseif ( astrid_has_header() == 'has-shortcode' ) : ?>
                    <div class="shortcode-area">
                        <?php $shortcode = get_theme_mod('astrid_shortcode'); ?>
                            <?php echo do_shortcode(wp_kses_post($shortcode)); ?>
                    </div>
                    <?php else : ?>
                        <div class="header-clone"></div>
                        <?php endif; ?>
                            <?php if ( !is_page_template('page-templates/page_widgetized.php') ) : ?>
                                <?php $container = 'container'; ?>
                                    <?php else : ?>
                                        <?php $container = 'home-wrapper'; ?>
                                            <?php endif; ?>
                                                <?php do_action('astrid_before_content'); ?>
                                                    <div id="content" class="site-content">
                                                        <div class="<?php echo $container; ?>">
                                                            <!--the following is for customizing pages-->
                                                            <?php //if($_SERVER['HTTP_HOST'] == "ruderfinn:8888"){
			// 	if($_SERVER['REQUEST_URI'] == "/"){
			// 		include "page/landing.php";
			// 	}elseif($_SERVER['REQUEST_URI'] == "/leadership/"){
			// 		include "page/leadership.php";
			// 	}elseif($_SERVER['REQUEST_URI'] == "/network/"){
			// 		include "page/network.php";
			// 	}elseif($_SERVER['REQUEST_URI'] == "/contact/") {
			// 		include "page/contact.php";
			// 	}
			// }else{
      //
			// } ?>