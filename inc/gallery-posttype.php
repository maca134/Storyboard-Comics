<?php
/**
 * Gallery Post Type
 * 
 * Sets up the post type with all the needed features contained with in a singleton class, so accessing the meta data is easier
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */

class storyboardcomics_gallery_posttype {

    protected static $instances = null;

    public static function forge() {
        if (self::$instances !== null) {
            return self::$instances;
        }
        self::$instances = new self();
        return self::$instances;
    }

    private $post_type = 'storyboard_gallery';
    private $meta_fields = array(
        'storyboard_gallery_type' => 'list',
        'storyboard_gallery_thumb' => array(),
        'storyboard_gallery_caption' => array()
    );

    private function __construct() {
        register_post_type($this->post_type, array(
            'labels' => array(
                'name' => _x('Galleries', 'post type general name', 'storyboardcomics'),
                'singular_name' => _x('Gallery', 'post type singular name', 'storyboardcomics'),
                'add_new' => _x('Add New', 'product item', 'storyboardcomics'),
                'add_new_item' => __('Add New Gallery', 'storyboardcomics'),
                'edit_item' => __('Edit Gallery', 'storyboardcomics'),
                'new_item' => __('New Gallery', 'storyboardcomics'),
                'view_item' => __('View Galleries', 'storyboardcomics'),
                'search_items' => __('Search Galleries', 'storyboardcomics'),
                'not_found' => __('Nothing found', 'storyboardcomics'),
                'not_found_in_trash' => __('Nothing found in Trash', 'storyboardcomics'),
                'parent_item_colon' => ''
            ),
            'public' => true,
            'menu_icon' => get_stylesheet_directory_uri() . '/img/image.png',
            'show_ui' => true, // UI in admin panel
            'capability_type' => 'post',
            'hierarchical' => true,
            'rewrite' => array('slug' => 'galleries'), // Permalinks
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes', 'comments') // Let's use custom fields for debugging purposes only
        ));
        add_image_size('storyboard_gallery_admin_thumb', 100, 100, true);
        add_image_size('storyboard_gallery_thumb', 300, of_get_option('panel_height', '150'), true);

        $this->actions();
    }

    private function actions() {
        add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        add_action('save_post', array(&$this, 'save_post'));
        add_action('admin_print_styles-post.php', array(&$this, 'load_styles'));
        add_action('admin_print_scripts-post.php', array(&$this, 'load_scripts'));
        add_action('admin_print_styles-post-new.php', array(&$this, 'load_styles'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'load_scripts'));
        add_filter('manage_edit-' . $this->post_type . '_columns', array(&$this, 'edit_columns'));
        add_action('manage_pages_custom_column', array(&$this, 'custom_columns'));
        if (is_admin()) {
            add_action('wp_ajax_storyboard_gallery_get_thumbnail', array(&$this, 'ajax_get_thumbnail'));
            add_action('wp_ajax_storyboard_gallery_get_all_thumbnail', array(&$this, 'ajax_get_all_attachments'));
        }
    }

    function edit_columns($columns) {
        $columns = array(
            "cb" => "<input type=\"checkbox\" />",
            "title" => "Title",
            "type" => "Type",
            "date" => 'Date'
        );
        return $columns;
    }

    function custom_columns($column) {
        global $post;
        $gallery_type = $this->get('storyboard_gallery_type', $post->ID);
        switch ($column) {
            case "type":
                echo ucwords($gallery_type);
                break;
        }
    }

    public function remove_row_actions($actions) {
        //if (get_post_type() === $this->post_type) {
        unset($actions['edit']);
        unset($actions['view']);
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
        //}
        return $actions;
    }

    public function get($key, $post_id = 0) {
        if ($post_id == 0) {
            global $post;
            $post_id = $post->ID;
        }
        if (!isset($this->meta_fields[$key]))
            return false;
        $meta = get_post_meta($post_id, $key, true);
        return ($meta !== '') ? $meta : $this->meta_fields[$key];
    }

    public function load_styles() {
        wp_register_style('storyboardcomics_gallery_style', get_stylesheet_directory_uri() . '/css/admin.css', array(), STORYBOARD_COMICS_VERSION);
        wp_enqueue_style('storyboardcomics_gallery_style');
    }

    public function load_scripts() {
        wp_register_script('storyboardcomics_gallery_script', get_stylesheet_directory_uri() . '/js/admin.js', array('jquery'), STORYBOARD_COMICS_VERSION, true);
        wp_enqueue_script('storyboardcomics_gallery_script');
    }

    public function add_meta_boxes() {
        add_meta_box(
                'storyboard_gallery_settings_meta_box', __('Settings', 'storyboardcomics'), array(&$this, 'settings_meta_box'), $this->post_type, 'side'
        );
        add_meta_box(
                'storyboard_gallery_thumb_meta_box', __('Images', 'storyboardcomics'), array(&$this, 'thumb_meta_box'), $this->post_type, 'normal'
        );
    }

    public function settings_meta_box($post) {
        $gallery_type = $this->get('storyboard_gallery_type', $post->ID);
        ?>
        <script>
            jQuery(function($) {
                var storyboard_gallery_list = $('#storyboard_gallery_list');
                var storyboard_gallery_thumb_meta_box = $('#storyboard_gallery_thumb_meta_box');
                var storyboard_gallery_player = $('#storyboard_gallery_player');
                var editor_container = $('#postdivrich');
                if (storyboard_gallery_list.attr('checked') == 'checked') { 
                    storyboard_gallery_thumb_meta_box.hide();
                    editor_container.show();
                } else {
                    storyboard_gallery_thumb_meta_box.show();
                    editor_container.hide();
                }
                storyboard_gallery_list.on('click', function () {
                    storyboard_gallery_thumb_meta_box.slideUp();
                    editor_container.slideDown();
                });
                storyboard_gallery_player.on('click', function () {
                    storyboard_gallery_thumb_meta_box.slideDown();
                    editor_container.slideUp();
                });  
            });
        </script>
        <h4>Page Type</h4>
        <div class="storyboard_options group">
            <p>
                <input type="radio" id="storyboard_gallery_list" name="storyboard_gallery_type" value="list"<?php if ($gallery_type == 'list') { ?> checked="checked"<?php } ?> />
                <label for="storyboard_gallery_list">
                    List<br /><img width="100" height="100" src="<?php echo get_stylesheet_directory_uri(); ?>/img/panels.png">
                </label>
            </p>
            <p>
                <input type="radio" id="storyboard_gallery_player" name="storyboard_gallery_type" value="player"<?php if ($gallery_type == 'player') { ?> checked="checked"<?php } ?> />
                <label for="storyboard_gallery_player">
                    Player<br /><img width="100" height="100" src="<?php echo get_stylesheet_directory_uri(); ?>/img/player.png">
                </label>
            </p>
        </div>
        <?php
        wp_nonce_field(plugin_basename(__FILE__), $this->post_type . '_noncename');
    }

    public function thumb_meta_box($post) {
        $gallery = $this->get('storyboard_gallery_thumb', $post->ID);
        $caption = $this->get('storyboard_gallery_caption', $post->ID);
        ?>
        <script type="text/javascript">
            var POST_ID = <?php echo $post->ID; ?>;
        </script>
        <input id="storyboard_gallery_upload_button" class="button" type="button" value="<?php echo __('Upload Image', 'storyboardcomics'); ?>" rel="" />
        <input id="storyboard_add_attachments_button" class="button" type="button" value="<?php echo __('Add All Attachments', 'storyboardcomics'); ?>" rel="" />
        <div id="storyboard_gallery_gallery_container">
            <ul id="storyboardcomics_thumbs" class="group"><?php
        if (is_array($gallery) && count($gallery) > 0) {
            foreach ($gallery as $i => $attachment_id) {
                echo $this->admin_thumb($attachment_id, (isset($caption[$i]) ? $caption[$i] : ''));
            }
        }
        ?></ul>
        </div>
        <?php
    }

    public function save_post($post) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times

        if (isset($_POST[$this->post_type . '_noncename']) && !wp_verify_nonce($_POST[$this->post_type . '_noncename'], plugin_basename(__FILE__)))
            return;

        // Check permissions
        if (isset($_POST['post_type']) && $this->post_type == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post))
                return;
        }

        if (isset($_POST['post_type']) && $this->post_type == $_POST['post_type'] && !isset($_POST['_inline_edit'])) {
            // Loop through the POST data
            foreach ($this->meta_fields as $key => $default) {
                $value = (isset($_POST[$key]) && !empty($_POST[$key])) ? $_POST[$key] : $default;
                update_post_meta($post, $key, $value);
            }
        }
    }

    public function ajax_get_thumbnail() {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }

    public function ajax_get_all_attachments() {
        $post_id = $_POST['post_id'];
        $included = (isset($_POST['included'])) ? $_POST['included'] : array();

        $attachments = get_children(array(//do only if there are attachments of these qualifications
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_mime_type' => 'image', //MIME Type condition
                )
        );

        if (count($attachments) > 0) {
            foreach ($attachments as $a) {
                if (!in_array($a->ID, $included)) {
                    echo $this->admin_thumb($a->ID);
                }
            }
        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        die;
    }

    private function admin_thumb($attachment_id, $caption = '') {
        $image = wp_get_attachment_image_src($attachment_id, 'storyboard_gallery_admin_thumb', true);
        ?>
        <li>
            <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" /><a href="#" class="storyboard_gallery_remove">Remove</a>
            <input type="hidden" name="storyboard_gallery_thumb[]" value="<?php echo $attachment_id; ?>" />
            <textarea name="storyboard_gallery_caption[]"><?php echo $caption; ?></textarea>
        </li>
        <?php
    }

    public function gallery_list($parent_id = 0) {
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        global $wp_query;
        $temp = $wp_query;
        $wp_query = null;
        $wp_query = new WP_Query(array(
                    'post_type' => 'storyboard_gallery',
                    'post_parent' => $parent_id,
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                    'paged' => $paged
                ));
        if ($wp_query->have_posts()) {
            ?>
            <ul id="galleries-panels" class="group panel-container">
                <?php
                while ($wp_query->have_posts()) {
                    $wp_query->the_post();
                    $image_id = get_post_thumbnail_id();
                    $theme_default_panel = of_get_option('default_panel_image', '');
                    $theme_default_panel = (empty($theme_default_panel)) ? 'http://placehold.it/300x150' : $theme_default_panel;
                    $image_url = ($image_id != null) ? wp_get_attachment_image_src($image_id, 'storyboard_gallery_thumb') : array($theme_default_panel);
                    echo $this->panel(get_the_title(), get_the_excerpt(), get_permalink(), $image_url[0]);
                }
                ?>
            </ul>
            <?php storyboardcomics_paginate($parent_id); ?>
            <?php
        }
        $wp_query = null;
        $wp_query = $temp;
        wp_reset_query();
    }

    public function panel($title, $excerpt, $permalink, $image, $last_li = true) {
        $image_style = 'background-image: url(' . $image . ')';
        ob_start();
        ?>
        <li style="<?php echo $image_style; ?>" class="image-panel">
            <h2><a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h2>
            <div class="storyboard_gallery_overlay">
                <?php echo (!empty($excerpt)) ? '<p>' . $excerpt . '</p>' : ''; ?>
                <a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>" class="corner"><span>+</span></a>
            </div>
        
        <?php
        if ($last_li) echo '</li>';
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

}