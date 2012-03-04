<?php

/**
 * Options for custom version of Options Framework.
 *
 * This sets the theme options and defaults.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */
function optionsframework_option_name() {

    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = get_theme_data(STYLESHEETPATH . '/style.css');
    $themename = $themename['Name'];
    $themename = preg_replace("/\W/", "", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */
function optionsframework_options() {
    $options = array();

    $options[] = array("name" => "Basic Settings",
        "type" => "heading");

    $options[] = array("name" => "Logo",
        "desc" => "Upload a logo for the theme.",
        "id" => "theme_logo",
        "type" => "upload");

    $options[] = array("name" => "Footer Text",
        "desc" => "Some text for the site footer.",
        "id" => "footer_text",
        "std" => "",
        "type" => "text");

    $options[] = array("name" => "Header Font",
        "desc" => "Select a font for headers on the site.",
        "id" => "header_font",
        "std" => "aller_light",
        "type" => "select",
        "options" => array(
            'aller_light' => 'Aller Light',
            'quicksand_light' => 'Quicksand Light',
            'gooddog' => 'Good Dog',
            'daniel' => 'Daniel',
            'journal' => 'Journal'
            ));

    $options[] = array("name" => "Cufon Headers",
        "desc" => "Check to use <a href='http://cufon.shoqolate.com/generate/' title='Cufon'>Cufon</a> text replacement.",
        "id" => "use_cufon",
        "std" => "1",
        "type" => "checkbox");

    $options[] = array("name" => "Show Sidebar In Galleries",
        "desc" => "Show the sidebar in galleries.",
        "id" => "show_sidebar",
        "std" => "1",
        "type" => "checkbox");

    $options[] = array("name" => "Show Attachments",
        "desc" => "Show attachment below content.",
        "id" => "show_attachments",
        "std" => "1",
        "type" => "checkbox");

    $options[] = array("name" => "Use Google Document View",
        "desc" => "Display compatible attachments in Google Documents.",
        "id" => "use_google_docs",
        "std" => "1",
        "type" => "checkbox");


    $frontpage_panels_menu = wp_get_nav_menu_object('Frontpage Panels');
    $options[] = array("name" => "Frontpage Layout",
        "desc" => "Show panels on the frontpage.<br /> <code>To set panels goto <a href='" . get_home_url() . "/wp-admin/nav-menus.php?action=edit&menu=" . $frontpage_panels_menu->term_id . "' target='_blank'>Appearance -&gt; Menus</a>. Each panel uses the post/page/gallery features image, if it is not set, a default image will be used.</code>",
        "id" => "frontpage_layout",
        "std" => "1",
        "type" => "checkbox");

    $options[] = array("name" => "Default Panel Image",
        "desc" => "Upload a default image for panels (300px wide).",
        "id" => "default_panel_image",
        "type" => "upload");

    $options[] = array("name" => "Image Panel Height",
        "desc" => "Set the hieght of the panel images.<code>If you have already uploaded images, to resize them properly please use 'Regenerate Thumbnails' plugin, <a href='" . get_home_url() . "/wp-admin/plugin-install.php?tab=search&type=term&s=regenerate+thumbnails&plugin-search-input=Search+Plugins' target='_blank'>click here</a> to install.</code>",
        "id" => "panel_height",
        "std" => "150",
        "type" => "text");

    $options[] = array("name" => "Gallery Settings",
        "type" => "heading");

    $options[] = array("name" => "Gallery Player Easing",
        "desc" => "Select easing for the gallery player.",
        "id" => "gallery_easing",
        "std" => "easeInOutQuad",
        "type" => "select",
        "options" => array(
            'swing' => 'swing',
            'easeInQuad' => 'easeInQuad',
            'easeOutQuad' => 'easeOutQuad',
            'easeInOutQuad' => 'easeInOutQuad',
            'easeInCubic' => 'easeInCubic',
            'easeOutCubic' => 'easeOutCubic',
            'easeInOutCubic' => 'easeInOutCubic',
            'easeInQuart' => 'easeInQuart',
            'easeOutQuart' => 'easeOutQuart',
            'easeInOutQuart' => 'easeInOutQuart',
            'easeInQuint' => 'easeInQuint',
            'easeOutQuint' => 'easeOutQuint',
            'easeInOutQuint' => 'easeInOutQuint',
            'easeInSine' => 'easeInSine',
            'easeOutSine' => 'easeOutSine',
            'easeInOutSine' => 'easeInOutSine',
            'easeInExpo' => 'easeInExpo',
            'easeOutExpo' => 'easeOutExpo',
            'easeInOutExpo' => 'easeInOutExpo',
            'easeInCirc' => 'easeInCirc',
            'easeOutCirc' => 'easeOutCirc',
            'easeInOutCirc' => 'easeInOutCirc',
            'easeInElastic' => 'easeInElastic',
            'easeOutElastic' => 'easeOutElastic',
            'easeInOutElastic' => 'easeInOutElastic',
            'easeInBack' => 'easeInBack',
            'easeOutBack' => 'easeOutBack',
            'easeInOutBack' => 'easeInOutBack',
            'easeInBounce' => 'easeInBounce',
            'easeOutBounce' => 'easeOutBounce',
            'easeInOutBounce' => 'easeInOutBounce',
            ));

    $options[] = array("name" => "Gallery Player Animation Time",
        "desc" => "Set the animation time for the player.",
        "id" => "gallery_timeout",
        "std" => "1000",
        "type" => "text");

    $options[] = array("name" => "Gallery Slider Top Offset",
        "desc" => "This will move the slider on gallery pages up (eg, -10) or down (eg, 10)",
        "id" => "slider_height_offset",
        "std" => "0",
        "type" => "text");

    $options[] = array("name" => "Next Gallery Panel Image",
        "desc" => "Upload an image for the next gallery slide.",
        "id" => "next_panel_image",
        "type" => "upload",
        'std' => get_template_directory_uri() . '/img/key_right.png');

    $options[] = array("name" => "Next Gallery Panel Text",
        "desc" => "Set some text for the next gallery link. (Use %s to insert the name of the next gallery)",
        "id" => "next_gallery_link",
        "std" => "Goto %s",
        "type" => "text");

    $options[] = array("name" => "Show Gallery Pagination",
        "desc" => "Show pagination in gallery pages.",
        "id" => "show_gallery_pagination",
        "std" => "1",
        "type" => "checkbox");

    $options[] = array("name" => "Colour Settings",
        "type" => "heading");

    $options[] = array("name" => "Background Colour",
        "desc" => "Background colour for the site",
        "id" => "background_colour",
        "std" => "#9ac069",
        "type" => "color");

    $options[] = array("name" => "Header Colour",
        "desc" => "Header colour for the site",
        "id" => "header_colour",
        "std" => "#6b8945",
        "type" => "color");

    $options[] = array("name" => "Text Colour",
        "desc" => "Text colour for the site",
        "id" => "text_colour",
        "std" => "#404040",
        "type" => "color");

    $options[] = array("name" => "Link Colour",
        "desc" => "Link colour for the site",
        "id" => "link_colour",
        "std" => "#9ac069",
        "type" => "color");

    $options[] = array("name" => "Link Hover Colour",
        "desc" => "Link hover colour for the site",
        "id" => "link_hover_colour",
        "std" => "#949599",
        "type" => "color");

    return $options;
}