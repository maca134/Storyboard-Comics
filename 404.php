<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

get_header(); ?>
<div id="primary" class="span8">
    <div id="content" role="main">

        <article id="post-0" class="post error404 not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'storyboardcomics'); ?></h1>
            </header>

            <div class="entry-content">
                <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'storyboardcomics'); ?></p>

                <?php get_search_form(); ?>
                
            </div><!-- .entry-content -->
        </article><!-- #post-0 -->

    </div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>