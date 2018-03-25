<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrid
 */

?>
    </div>
    </div>
    <!-- #content -->
    <div class="footer-wrapper">
        <div id="footer" class="container">
            <div class="flex-container">
                <div id="footer1" class="flex-item flex-2 section"> <img class="favicon" src="/wordpress/wp-content/themes/astrid-ruderfinn/bak/img/cropped-Ficon.png"> </div>
                <div id="footer2" class="flex-item flex-1 section">
		<?php if(get_locale() == "en_US"){ ?>
			<?php echo do_shortcode( '[do_widget id=nav_menu-3 class=footer-widget]' ); ?>
		<?php }else{ ?>
			<?php echo do_shortcode( '[do_widget id=nav_menu-15 class=footer-widget]' ); ?>
		<?php } ?>
                </div>
                <div id="footer3" class="flex-item flex-1 section">
		<?php if(get_locale() == "en_US"){ ?>
			<?php echo do_shortcode( '[do_widget id=nav_menu-4]' ); ?>
		<?php }else{ ?>
			<?php echo do_shortcode( '[do_widget id=nav_menu-11]' ); ?>
		<?php } ?>
                </div>
                <div id="footer4" class="flex-item flex-2">
                    <div class="footer4-sub flex-container">
                        <div id="footer5" class="flex-item section">
			<?php if(get_locale() == "en_US"){ ?>
				<?php echo do_shortcode( '[do_widget id=nav_menu-5]' ); ?><?php echo do_shortcode('[do_widget id=nav_menu-6]'); ?>
			<?php }else{ ?>
				<?php echo do_shortcode( '[do_widget id=nav_menu-12]' ); ?><?php echo do_shortcode('[do_widget id=nav_menu-13]'); ?>
			<?php } ?>
                        </div>
                        <div id="footer6" class="flex-item section">
			<?php if(get_locale() == "en_US"){ ?>
				<?php echo do_shortcode( '[do_widget id=nav_menu-2]' ); ?>
			<?php }else{ ?>
				<?php echo do_shortcode( '[do_widget id=nav_menu-16]' ); ?>
			<?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div id="footer4">
			<?php if(get_locale() == "en_US"){ ?>
				<div class="pull-right hidden-sm hidden-xs" style="padding:20px"> <img src="">
				<p>&copy;<?php echo date('Y'); ?> Ruder Finn Asia. All rights reserved</p><br>
				</div>
				<div class="text-center hidden-lg hidden-md"> <img src="">
				<p>&copy;<?php echo date('Y'); ?> Ruder Finn Asia. All rights reserved</p><br>
				</div>
			<?php }else{ ?>
				<div class="pull-right hidden-sm hidden-xs" style="padding:20px"> <img src="">
				<p>&copy;<?php echo date('Y'); ?> 年，罗德亚洲版权所有。</p><br>
				</div>
				<div class="text-center hidden-lg hidden-md"> <img src="">
				<p>&copy;<?php echo date('Y'); ?> 年，罗德亚洲版权所有。</p><br>
				</div>
			<?php } ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- #page -->
    <?php wp_footer(); ?><?php hoverfeaturedimg(); ?>
        </body>

        </html>