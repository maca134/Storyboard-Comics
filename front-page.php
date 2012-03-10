<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
?>
<?php
if (of_get_option('frontpage_layout', '1') == '1') {
    get_header();
    ?>
    <div id="primary" class="span12">
        <div id="content" role="main">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'frontpage',
                'depth' => 1,
                'fallback_cb' => false,
                'container' => 'div',
                'menu_class' => 'panel-container group',
                'menu_id' => 'frontpage-panels',
                'walker' => new Panel_Menu_Walker));
            ?>
        </div><!-- #content -->
    </div><!-- #primary -->
    <?php
    get_footer();
} else {
    get_header();
    $custom_fields = get_post_custom_values('_wp_page_template', $post->ID);
    $page_template = $custom_fields[0];
    ?>
    <div id="primary" class="<?php echo ($page_template === 'page-full-width.php') ? 'span12' : 'span8'; ?>">
        <div id="content" role="main">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('content', get_post_format()); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <article id="post-0" class="post no-results not-found">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php _e('Nothing Found', 'storyboardcomics'); ?></h1>
                    </header><!-- .entry-header -->
                    <div class="entry-content">
                        <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'storyboardcomics'); ?></p>
                        <?php get_search_form(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-0 -->
            <?php endif; ?>
        </div><!-- #content -->
    </div><!-- #primary -->
    <?php
    if ($page_template !== 'page-full-width.php')
        get_sidebar();
    get_footer();
}
?>