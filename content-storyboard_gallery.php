<?php
/**
 * The template for displaying posts in the Aside Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if ($post->post_parent == 0) { ?>
            <h1 class="entry-title"><?php echo get_the_title(); ?></h1>
        <?php } else { ?>
            <h1 class="entry-title"><a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'storyboardcomics'), get_the_title($post->post_parent)); ?>" rel="bookmark"><?php echo get_the_title($post->post_parent); ?></a></h1>
            <h2><?php the_title(); ?></h2>
        <?php } ?>
        <?php if (comments_open() && !post_password_required()) : ?>
            <div class="comments-link">
                <?php comments_popup_link('<span class="leave-reply">' . __('Reply', 'storyboardcomics') . '</span>', _x('1', 'comments number', 'storyboardcomics'), _x('%', 'comments number', 'storyboardcomics')); ?>
            </div>
        <?php endif; ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
        <?php the_content(__('Continue reading <span class="meta-nav">&rarr;</span>', 'storyboardcomics')); ?>
        <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'storyboardcomics') . '</span>', 'after' => '</div>')); ?>
    </div><!-- .entry-content -->
    <footer class="entry-meta group">
        <?php edit_post_link(__('Edit', 'storyboardcomics'), '<span class="edit-link">', '</span>'); ?>
    </footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
