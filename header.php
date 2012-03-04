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
wp_title('|', true, 'right');

// Add the blog name.
bloginfo('name');

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && ( is_home() || is_front_page() ))
    echo " | $site_description";

// Add a page number if necessary:
if ((isset($page) && $paged >= 2) || (isset($page) && $page >= 2))
    echo ' | ' . sprintf(__('Page %s', 'storyboardcomics'), max($paged, $page));
?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/fonts/<?php echo of_get_option('header_font', 'aller_light'); ?>/stylesheet.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
        <?php storyboardcomics_custom_css(); ?>
        <?php if (of_get_option('use_cufon', '1') == '1') { ?>
            <script src="<?php echo get_template_directory_uri(); ?>/js/cufon.js"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/fonts/<?php echo of_get_option('header_font', 'aller_light'); ?>.js"></script>
            <script>
                Cufon.replace('h1, h2, h3', {
                    fontFamily: '<?php echo of_get_option('header_font', 'aller_light'); ?>'
                });
            </script>
        <?php } ?>
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
            </style>
        <![endif]-->
        <?php wp_head(); ?>
        <script>
            var COLUMNS_GALLERIES = <?php echo (of_get_option('show_sidebar', '1') != '1') ? '3' : '2'; ?>;
        </script>
    </head>
    <body <?php body_class(); ?>>
        <div id="page" class="hfeed container">
            <div id="wrap" class="group">
                <header id="branding" role="banner">
                    <div class="row">
                        <div class="span6">
                            <h1 id="site-title"><span><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php
if ($logo_image = of_get_option('theme_logo', false)) {
    ?><img src="<?php echo $logo_image; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"><?php
                                    } else {
                                        bloginfo('name');
                                    }
?></a></span></h1>
                        </div>
                        <div class="span6">
                            <h2 id="site-description"><?php bloginfo('description'); ?>&nbsp;</h2>
                            <nav id="access" role="navigation" class="group">
                                <?php wp_nav_menu(array(
                                    'theme_location' => 'primary', 
                                    'depth' => 3, 
                                    'menu_class' => 'group', 
                                    'container' => '',
                                    'show_home' => true
                                    )); ?>
                            </nav><!-- #access -->
                        </div>
                    </div>
                </header><!-- #branding -->
                <div id="main" class="row">