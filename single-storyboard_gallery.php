<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

$page_type = storyboardcomics_gallery_posttype::forge()->get('storyboard_gallery_type');
get_template_part('gallery', $page_type);
?>