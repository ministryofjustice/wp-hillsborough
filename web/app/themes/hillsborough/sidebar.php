<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package hillsborough
 */
?>
<div id="secondary" class="widget-area" role="complementary">
    <div class="twitter">
        <a href="//twitter.com/hboroinquests" target="_blank">
            <img src="<?php echo get_template_directory_uri(); ?>/img/bird_blue_32.png" alt="Twitter">
        </a>
        <a href="//twitter.com/hboroinquests" target="_blank">
            Follow us on Twitter
        </a>
    </div>
    <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
    <?php get_search_form(); ?>
</div><!-- #secondary -->
