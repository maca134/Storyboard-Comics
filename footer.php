<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Storyboard_Comics
 * @since Storyboard Comics 0.1
 */
?>
</div><!-- #main -->
</div><!-- #wrap -->
<footer id="colophon" role="contentinfo">
    <div class="row">
        <div class="span6">
            <p class="copyright"><?php echo of_get_option('footer_text'); ?>&nbsp;</p>
        </div>
        <div class="span6">
            <nav id="footer-access" role="navigation">
                <?php wp_nav_menu(array('theme_location' => 'footer', 'depth' => 1)); ?>
            </nav><!-- #access -->
        </div>
    </div>
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>