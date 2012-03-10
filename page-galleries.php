<?php
/**
 * Template Name: Galleries Index
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
get_header();
?>
<div id="primary" class="<?php echo (of_get_option('show_sidebar', '1') != '1') ? 'span12' : 'span8'; ?>">
    <div id="content" role="main">
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('content', 'storyboard_gallery'); ?>
            <?php storyboardcomics_gallery_posttype::forge()->gallery_list(); ?>
            <?php comments_template('', true); ?>
        <?php endwhile; // end of the loop. ?>
    </div><!-- #content -->
</div><!-- #primary -->
<?php if (of_get_option('show_sidebar', '1') == '1') {
    get_sidebar();
} ?>
<?php get_footer(); ?>