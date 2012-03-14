<?php
/**
 * Storyboard Comics functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
define('STORYBOARD_COMICS_VERSION', '0.7');

require 'inc/theme-activation.php';
require 'inc/gallery-posttype.php';
require 'inc/Panel_Menu_Walker.php';

if (!isset($content_width))
    $content_width = 960;

if (!function_exists('optionsframework_init')) {
    if (STYLESHEETPATH == TEMPLATEPATH) {
        define('OPTIONS_FRAMEWORK_URL', TEMPLATEPATH . '/admin/');
        define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
    } else {
        define('OPTIONS_FRAMEWORK_URL', STYLESHEETPATH . '/admin/');
        define('OPTIONS_FRAMEWORK_DIRECTORY', get_stylesheet_directory_uri() . '/admin/');
    }
    require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');
}


// Add gallery post type
global $storyboardcomics_gallery_posttype;
$storyboardcomics_gallery_posttype = storyboardcomics_gallery_posttype::forge();

// Displays post date, category, etc
function theme_posted_on() {
    printf(__('<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'storyboardcomics'), esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'storyboardcomics'), get_the_author())), get_the_author()
    );
}

// Run storyboardcomics_setup once theme is setup
add_action('after_setup_theme', 'storyboardcomics_setup');
add_action('wp_enqueue_scripts', 'storyboardcomics_scripts');

function storyboardcomics_setup() {
    // Add default posts and comments RSS feed links to <head>.
    add_theme_support('automatic-feed-links');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 'storyboardcomics'));
    register_nav_menu('footer', __('Footer Menu', 'storyboardcomics'));
    register_nav_menu('frontpage', __('Frontpage Panels', 'storyboardcomics'));
    register_nav_menu('gallery_player', __('Gallery Player', 'storyboardcomics'));

    // Add thumbnails to post and pages
    add_theme_support('post-thumbnails');

    // Sets custom thumbnail sizes
    add_image_size('large-feature', 620, 200, true);
    add_image_size('full-feature', 940, 200, true);
    add_image_size('small-feature', 300, of_get_option('panel_height', '150'), true);
}

function storyboardcomics_scripts() {
    wp_enqueue_script('jquery-easing', get_stylesheet_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'), '1.3', true);
    wp_enqueue_script('jquery-pagination', get_stylesheet_directory_uri() . '/js/jquery.pagination.js', array('jquery'), '1.3', true);
    wp_enqueue_script('storyboardcomics-slider', get_stylesheet_directory_uri() . '/js/comicslider.js', array('jquery', 'jquery-hotkeys'), '0.1', true);
    wp_enqueue_script('storyboardcomics', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), STORYBOARD_COMICS_VERSION, true);
}

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function storyboardcomics_excerpt_length($length) {
    return 30;
}

add_filter('excerpt_length', 'storyboardcomics_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 */
function storyboardcomics_continue_reading_link() {
    return ' <a href="' . esc_url(get_permalink()) . '">' . __('Continue reading <span class="meta-nav">&rarr;</span>', 'storyboardcomics') . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and storyboardcomics_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function storyboardcomics_auto_excerpt_more($more) {
    return ' &hellip;'/* . storyboardcomics_continue_reading_link() */;
}

add_filter('excerpt_more', 'storyboardcomics_auto_excerpt_more');

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 */
function storyboardcomics_widgets_init() {
    register_sidebar(array(
        'name' => __('Main Sidebar', 'storyboardcomics'),
        'id' => 'sidebar',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'storyboardcomics_widgets_init');

function storyboardcomics_custom_css() {
    $gallery_cols = (of_get_option('show_sidebar', '1') != '1') ? '3' : '2';
    $panel_height = of_get_option('panel_height', '150');
    ?>
    <style>
        body {
            background: <?php echo of_get_option('background_colour', '#9ac069'); ?>;
            color: <?php echo of_get_option('text_colour', '#404040'); ?>;
        }
        h1, h2, h3, h4, h5, h6 {
            color: <?php echo of_get_option('header_colour', '#6b8945'); ?>;
            font-family: '<?php echo of_get_option('header_font', 'aller_light'); ?>', "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        a {
            color: <?php echo of_get_option('link_colour', '#9ac069'); ?>;
        }
        a:hover {
            color: <?php echo of_get_option('link_hover_colour', '#949599'); ?>;
        }  
        #site-description {
            color: <?php echo of_get_option('background_colour', '#6b8945'); ?>;
        }
        #access a {
            color: <?php echo of_get_option('link_hover_colour', '#949599'); ?>;
            font-family: '<?php echo of_get_option('header_font', 'aller_light'); ?>', "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        #access a:hover {
            color:  <?php echo of_get_option('link_colour', '#9ac069'); ?>;
        }
        .entry-header .comments-link a:hover,
        .entry-header .comments-link a:focus,
        .entry-header .comments-link a:active {
            background-color: <?php echo of_get_option('link_colour', '#9ac069'); ?>;
            color: #fff;
            color: rgba(255,255,255,0.8);
        }
        .entry-meta .edit-link a, 
        .commentlist .edit-link a,
        a.comment-reply-link {
            background: <?php echo of_get_option('background_colour', '#6b8945'); ?>;
        }
        .entry-meta .edit-link a:hover,
        .commentlist .edit-link a:hover,
        a.comment-reply-link:hover {
            background: <?php echo of_get_option('header_colour', '#6b8945'); ?>;
        }
        #respond input#submit {
            background: <?php echo of_get_option('background_colour', '#6b8945'); ?>;
        }
        #respond input#submit:active, #respond input#submit:hover {
            background: <?php echo of_get_option('header_colour', '#6b8945'); ?>;
        }
        #story-board-pagination span.current {
            background-color: <?php echo of_get_option('link_colour', '#9ac069'); ?>;
            color: #fff;
        }
        #access li:hover > a,
        #access ul ul :hover > a,
        #access a:focus {
            background: <?php echo of_get_option('background_colour', '#6b8945'); ?>;
        }
        #access li:hover > a,
        #access a:focus {
            background: <?php echo of_get_option('background_colour', '#6b8945'); ?>; /* Show a solid color for older browsers */
            color: <?php echo of_get_option('header_colour', '#949599'); ?>;
        }
        #access ul ul a {
            color: <?php echo of_get_option('link_colour', '#9ac069'); ?>;
        }
        #galleries-panels .image-panel:nth-child(<?php echo $gallery_cols; ?>n+1) {
            margin-left: 0;
        }
        #galleries-panels .image-panel:nth-child(<?php echo $gallery_cols; ?>n+<?php echo $gallery_cols; ?>) {
            margin-right: 0;
        }

        .image-panel {
            height: <?php echo $panel_height; ?>px;
            background: <?php echo of_get_option('link_hover_colour', '#949599'); ?>;
        }
        /* #frontpage_panels .storyboard_gallery_overlay, #storyboard_gallery_container .storyboard_gallery_overlay {
             height: <?php echo $panel_height - 20; ?>px;
         }*/
        /*.image-panel .storyboard_gallery_overlay {
            height: <?php echo $panel_height - 76; ?>px;
        }*/

        #story-board div {
            border-right: 1px solid <?php echo of_get_option('link_colour', '#9ac069'); ?>;
        }

    </style>
    <?php
}

function storyboardcomics_dashboard_widget_function() {
    // Display whatever it is you want to show
    ?>
    <img src="<?php echo get_template_directory_uri() ?>/img/dashboard-logo.png" width="154" height="44">
    <p>Thank you for choosing Storyboard Comic Theme.</p>
    <h4>Links</h4>
    <p>
        <a href="<?php echo get_template_directory_uri() ?>/theme-tutorial/" title="Theme Help" target="_blank">Theme Help</a><br />
        <a href="http://maca134.co.uk" title="Maca134" target="_blank">Maca134</a><br />
        <a href="http://transverseuniverse.com" title="Transverse Universe" target="_blank">Transverse Universe</a>
    </p>
    <h4 class="heading">Please Donate</h4>
    <p>A lot of time and effort went into making this plugin, all donations are hugely appreciated.</p>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LDZBJ2YP5GBRE" title="Please Donate" target="_blank">
        <img src="http://maca134.co.uk/plugins/donate.gif">
    </a>
    <?php
}

// Create the function use in the action hook
function storyboardcomics_add_dashboard_widgets() {
    wp_add_dashboard_widget('storyboardcomics_dashboard', 'Storyboard Comic Theme', 'storyboardcomics_dashboard_widget_function');

    // Globalize the metaboxes array, this holds all the widgets for wp-admin

    global $wp_meta_boxes;

    // Get the regular dashboard widgets array 
    // (which has our new widget already but at the end)

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashbaord widget from the end of the array

    $example_widget_backup = array('storyboardcomics_dashboard' => $normal_dashboard['storyboardcomics_dashboard']);
    unset($normal_dashboard['storyboardcomics_dashboard']);

    // Merge the two arrays together so our widget is at the beginning

    $sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

    // Save the sorted array back into the original metaboxes 

    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'storyboardcomics_add_dashboard_widgets');

function storyboardcomics_attachment_icons($echo = true) {
    $files = array();
    if ($pdf_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/pdf', //MIME Type condition
            ))) {
        $files['pdf'] = $pdf_files;
    }

    if ($doc_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/msword', //MIME Type condition
            ))) {
        $files['doc'] = $doc_files;
    }
    if ($ppt_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/vnd.ms-powerpoint', //MIME Type condition
            ))) {
        $files['ppt'] = $ppt_files;
    }

    if ($xls_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/vnd.ms-excel', //MIME Type condition
            ))) {
        $files['xls'] = $xls_files;
    }

    if ($zip_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/zip', //MIME Type condition
            ))) {
        $files['zip'] = $zip_files;
    }
    if ($rar_files = get_children(array(//do only if there are attachments of these qualifications
        'post_parent' => get_the_ID(),
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_mime_type' => 'application/rar', //MIME Type condition
            ))) {
        $files['rar'] = $rar_files;
    }

    $output = '';
    if (count($files) > 0) {
        $output = '<div class="attachment-container"><h3>Attachments</h3>';
        foreach ($files as $type => $list) {
            foreach ($list as $attachment) {
                //$link = 'http://docs.google.com/viewer?url=' . urlencode(wp_get_attachment_link($attachment->ID));
                $output .= '<div class="attachment-type-' . $type . '">';
                $output .= '<a href="' . get_attachment_link($attachment->ID) . '" title="View ' . $attachment->post_title . '">' . $attachment->post_title . '</a>';
                $output .= '</div>';
            }
        }
        $output .= '</div>';
    }
    if ($echo == true) {
        echo $output;
    } else {
        return $output;
    }
}

function storyboardcomics_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type) :
        case 'pingback' :
        case 'trackback' :
            ?>
            <li class="post pingback">
                <p><?php _e('Pingback:', 'storyboardcomics'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('Edit', 'storyboardcomics'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <article id="comment-<?php comment_ID(); ?>" class="comment">
                <footer class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        $avatar_size = 68;
                        if ('0' != $comment->comment_parent)
                            $avatar_size = 39;
                        echo get_avatar($comment, $avatar_size);
                        /* translators: 1: comment author, 2: date and time */
                        printf(__('%1$s on %2$s <span class="says">said:</span>', 'storyboardcomics'), sprintf('<span class="fn">%s</span>', get_comment_author_link()), sprintf('<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                                        /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s', 'storyboardcomics'), get_comment_date(), get_comment_time())
                                )
                        );
                        ?>
                        <?php edit_comment_link(__('Edit', 'storyboardcomics'), '<span class="edit-link">', '</span>'); ?>
                    </div><!-- .comment-author .vcard -->
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'storyboardcomics'); ?></em>
                        <br />
                    <?php endif; ?>
                </footer>
                <div class="comment-content"><?php comment_text(); ?></div>
                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply <span>&darr;</span>', 'storyboardcomics'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div><!-- .reply -->
            </article><!-- #comment-## -->
            <?php
            break;
    endswitch;
}

function storyboardcomics_paginate($id) {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
        'base' => @add_query_arg('page', '%#%'),
        'format' => '',
        'total' => $wp_query->max_num_pages,
        'current' => $current,
        'show_all' => true,
        'type' => 'array',
        'prev_text' => __('&lt;', 'storyboardcomics'),
        'next_text' => __('&gt;', 'storyboardcomics'),
    );
    if ($wp_rewrite->using_permalinks())
        $pagination['base'] = user_trailingslashit(trailingslashit(get_permalink($id)) . '%#%/', 'paged');
    if (!empty($wp_query->query_vars['s']))
        $pagination['add_args'] = array('s' => get_query_var('s'));
    $pagination_arr = paginate_links($pagination);
    if ($pagination_arr !== null) {
        echo '<div class="pagination"><ul>';
        foreach ($pagination_arr as $p) {
            if (strstr($p, 'span') !== false) {
                echo '<li class="active"><a href="#">' . $p . '</a></li>';
            } else {
                echo '<li>' . $p . '</li>';
            }
        }
        echo '</ul></div>';
    }
}