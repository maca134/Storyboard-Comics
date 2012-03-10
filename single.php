<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

get_header(); ?>

<div id="primary" class="span8">
    <div id="content" role="main">

        <?php while (have_posts()) : the_post(); ?>



            <?php get_template_part('content', 'single'); ?>
            <nav id="nav-single" class="group">
                <h3 class="assistive-text"><?php _e('Post navigation', 'storyboardcomics'); ?></h3>
                <span class="nav-previous"><?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous', 'storyboardcomics')); ?></span>
                <span class="nav-next"><?php next_post_link('%link', __('Next <span class="meta-nav">&rarr;</span>', 'storyboardcomics')); ?></span>
            </nav><!-- #nav-single -->
            <?php comments_template('', true); ?>

        <?php endwhile; // end of the loop. ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>