<?php

/**
 * Runs when theme is activated
 * 
 * Add a menu and attaches it to the frontpage menu.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
    add_action('admin_init', 'storyboardcomics_activation');
}

function storyboardcomics_activation() {
    global $wp_rewrite;
    $frontpage_panel_menu_name = 'Frontpage Panels';
    if (!is_nav_menu($frontpage_panel_menu_name)) {
        $menu = wp_create_nav_menu($frontpage_panel_menu_name);
        $nav_menu_locations = get_theme_mod('nav_menu_locations');
        $nav_menu_locations['frontpage'] = $menu;
        set_theme_mod('nav_menu_locations', $nav_menu_locations);
    }
    $wp_rewrite->flush_rules();
}
