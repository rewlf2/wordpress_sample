<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astrid
 */

?>
                    <li class="cat-post-item">
                        <a href="<?php echo get_permalink() ?>">
                            <div class="post-item-wrapper">
                                <div class="thumb-wrapper">
                                    <div class="thumb-color1"><?php echo get_the_post_thumbnail() ?></div>
                                </div>
                                <div class="post-title cat-post-title" rel="bookmark"><?php echo get_the_title() ?></div>
                            </div>
                        </a>
                    </li>
