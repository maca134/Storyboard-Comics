<?php
/**
 * Gallery Player Custom Page
 *
 * Displays a gallery player which is independant from the rest of the site. 
 * This could present a small issue that some plugins dont work with this page but its suppose to have no distractions.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
global $page, $paged;

wp_title('|', true, 'right');

// Add the blog name.
bloginfo('name');

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && ( is_home() || is_front_page() ))
    echo " | $site_description";

// Add a page number if necessary:
if ($paged >= 2 || $page >= 2)
    echo ' | ' . sprintf(__('Page %s', 'storyboardcomics'), max($paged, $page));
?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/fonts/<?php echo of_get_option('header_font', 'aller_light'); ?>/stylesheet.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
        <?php storyboardcomics_custom_css(); ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />  
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <style>
                #storyboard_gallery_container .storyboard_gallery_overlay {
                display: none;
                }
                #storyboard_gallery_container li:hover .storyboard_gallery_overlay {
                display: block;
                }
        #episode_jump_list ul {
                margin-top: 16px;
                }
            </style>
        <![endif]-->
        <?php wp_head(); ?>
        <style>
            html, body {
                overflow: hidden;
                height: 100%;
            }
        </style>
        <script>
<?php $next_post = get_next_post(); ?>
    jQuery(function($) {
        comicslider.easing = '<?php echo of_get_option('gallery_easing', 'linear') ?>';
        comicslider.animateTime = <?php echo of_get_option('gallery_timeout', '1000') ?>;
        comicslider.loadingGif = '<?php echo get_template_directory_uri(); ?>/img/loading.gif';
        comicslider.nextPost = '<?php echo ($next_post !== '' && $post->post_parent != $next_post->ID && $post->post_parent == $next_post->post_parent) ? get_permalink($next_post->ID) : ''; ?>';
        comicslider.offset = <?php echo of_get_option('slider_height_offset', '0') ?>;
        
        comicslider.init();
        $('#episode_jump_list li').removeClass('nojs').hover(function() {
            $(this).find('ul').stop().fadeIn(500, function () {
                $(this).css({opacity: 1});
            });
        }, function () {
            $(this).find('ul').stop().fadeOut();
        });
        
        $("#story-board-pagination").pagination(comicslider.totalSlides - 1, {
            items_per_page:1,
            load_first_page: false,
            next_text: '&raquo;',
            prev_text: '&laquo;',
            num_edge_entries: 2,
            callback: function (new_page_index, pagination_container) {
                comicslider.slideTo(new_page_index + 1);
                return false;
            }
        });
        var storyboard_pagination = $("#story-board-pagination");
        
        $('#story-board img').on('click', function () {
            var s = jQuery(this).data('id');
            if ((s - comicslider.currentSlide) == -1) {
                storyboard_pagination.trigger('prevPage');
            } else {
                storyboard_pagination.trigger('nextPage');
            }
        });
<?php if (of_get_option('show_gallery_pagination', '1') !== '1') { ?>
            storyboard_pagination.hide();
<?php } ?>
        
        $(document).bind('keydown', function (e) {
            if (comicslider.visible == true) {
                if (e.keyCode == 39) {
                    storyboard_pagination.trigger('nextPage');
                }
                if (e.keyCode == 37) {
                    storyboard_pagination.trigger('prevPage');
                }
            }
        });
        $('#wpadminbar').hide();
    });
    var COLUMNS_GALLERIES = <?php echo (of_get_option('show_sidebar', '1') != '1') ? '3' : '2'; ?>;
        </script>
    </head>
    <body <?php body_class(); ?>>
        <div id="loading_overlay"></div>
        <div id="story-board-container">
            <div id="story-board-top" class="group">
                <div class="left-col">
                    <?php if ($post->post_parent > 0) { ?><a href="<?php echo get_permalink($post->post_parent); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'storyboardcomics'), get_the_title($post->post_parent)); ?>" rel="bookmark"><?php echo __('Back to', 'storyboardcomics'); ?> <?php echo get_the_title($post->post_parent); ?></a> /<?php } ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php echo esc_attr(get_bloginfo('name', 'display')); ?></a>
                </div>
                <div class="middle-col">
                    <?php wp_nav_menu(array('theme_location' => 'gallery_player', 'depth' => 1, 'fallback_cb' => false)); ?>
                </div>
                <div class="right-col">
                    <h1><?php the_title(); ?></h1>
                    <?php
                    $storyboards = new WP_Query('post_type=storyboard_gallery&post_parent=' . $post->post_parent . '&orderby=menu_order&order=ASC');
                    if ($storyboards->have_posts()) {
                        ?>
                        <ul id="episode_jump_list">
                            <li class="nojs"><a href='#' title='Episodes' class="no-click gallery-episodes">Episodes</a><ul>
                                    <?php
                                    while ($storyboards->have_posts()) {
                                        $storyboards->the_post();
                                        $page_type = storyboardcomics_gallery_posttype::forge()->get('storyboard_gallery_type', get_the_ID());
                                        if ($page_type == 'player') {
                                            ?>
                                            <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                                            <?php
                                        }
                                    }
                                    ?></ul>
                            </li>
                        </ul>
                        <?php
                    }
                    wp_reset_query();
                    ?>      
                </div>
            </div>
            <div id="story-board-banner">
                <?php if (have_posts()) { ?>
                    <?php
                    while (have_posts()) {
                        the_post();
                        ?>
                        <div id="story-board" class="group">
                            <?php
                            $gallery = storyboardcomics_gallery_posttype::forge()->get('storyboard_gallery_thumb', $post->ID);
                            $caption = storyboardcomics_gallery_posttype::forge()->get('storyboard_gallery_caption', $post->ID);

                            foreach ($gallery as $i => $g) {
                                $c = $caption[$i];
                                $image = wp_get_attachment_image_src($g, 'full', true);
                                ?>
                                <div>
                                    <img src="<?php echo $image[0]; ?>" title="<?php echo $c; ?>" data-id="<?php echo $i; ?>">
                                    <span><?php echo nl2br($c); ?></span>
                                </div>
                                <?php
                            }
                            if ($next_post !== '' && $post->post_parent != $next_post->ID && $post->post_parent == $next_post->post_parent) {
                                ?>
                                <div id="goto-next-post">
                                    <h1><?php printf(of_get_option('next_gallery_link', 'Goto %s'), $next_post->post_title); ?></h1>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div id="story-board-pagination" ></div>
        </div>
    </body>
    <?php wp_footer(); ?>
</html>