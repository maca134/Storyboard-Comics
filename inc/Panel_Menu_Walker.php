<?php
/**
 * Menu walker for showing panels on the frontpage
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

class Panel_Menu_Walker extends Walker_Nav_Menu {

    function start_el(&$output, $item, $depth, $args) {
        if ($item->type !== 'custom') {
            $image_id = get_post_thumbnail_id($item->object_id);
            $image_url = ($image_id != null) ? wp_get_attachment_image_src($image_id, 'storyboard_gallery_thumb') : array(of_get_option('default_panel_image', ''));

            $post = get_post($item->object_id);
            $description = (!empty($item->description)) ? $item->description : $post->post_excerpt;

            $title = apply_filters('the_title', $item->title, $item->ID);

            $item_output = storyboardcomics_gallery_posttype::forge()->panel($title, $description, esc_attr($item->url), $image_url[0], false);
            // Since $output is called by reference we don't need to return anything.
            $output .= apply_filters(
                    'walker_nav_menu_start_el'
                    , $item_output
                    , $item
                    , $depth
                    , $args
            );
        }
    }

}
