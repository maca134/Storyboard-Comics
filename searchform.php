<?php
/**
 * The template for displaying search forms in Storyboard Comics
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="s" class="assistive-text"><?php _e('Search', 'storyboardcomics'); ?></label>
    <input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e('Search', 'storyboardcomics'); ?>" />
    <input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e('Search', 'storyboardcomics'); ?>" />
</form>