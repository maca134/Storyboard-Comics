<?php
/**
 * Template Name: Sidebar Template
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

get_header(); ?>
<div id="secondary" class="widget-area span4" role="complementary">
    <?php if (!dynamic_sidebar('sidebar')) : ?>

        <aside id="archives" class="widget">
            <h3 class="widget-title"><?php _e('Archives', 'storyboardcomics'); ?></h3>
            <ul>
                <?php wp_get_archives(array('type' => 'monthly')); ?>
            </ul>
        </aside>

        <aside id="meta" class="widget">
            <h3 class="widget-title"><?php _e('Meta', 'storyboardcomics'); ?></h3>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>

    <?php endif; // end sidebar widget area ?>
</div><!-- #secondary .widget-area -->
