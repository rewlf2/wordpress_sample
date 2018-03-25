/**
 * Customizer custom js
 */

jQuery(document).ready(function() {
   jQuery('.wp-full-overlay-sidebar-content').prepend('<div class="ultra-ads"> <a href="http://phantomthemes.com/downloads/ultrabootstrap-premium-wordpress-theme/" class="button" target="_blank">{pro}</a></div>'.replace('{pro}',ultrabootstrap_customizer_js_obj.pro));
});