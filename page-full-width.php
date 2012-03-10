<?php 
/**
 * Template Name: Full Width
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

get_header(); ?>

<div id="primary" class="span12">
    <div id="content" role="main">

        <?php while (have_posts()) : the_post(); ?>

            <?php get_template_part('content', 'page'); ?>

            <?php comments_template('', true); ?>

        <?php endwhile; // end of the loop. ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>