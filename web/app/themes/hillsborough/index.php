<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package hillsborough
 */
get_header();
?>

<?php get_sidebar(); ?>

<div id="primary" class="content-area">
    <div id="main-boxout">
        <h2>Latest News</h2>
        <?php
        if (function_exists('ot_get_option')) {
            echo ot_get_option('boxout_text');
        }
        ?>
    </div>
    <main id="main" class="site-main" role="main">
        <div id="main-content">

            <h1>Hillsborough Inquests</h1>

            <div class="highlighted-box">
                <?php
                if (function_exists('ot_get_option')) {
                    echo ot_get_option('main_text');
                }
                ?>
            </div>

        </div>
    </main><!-- #main -->
    <div id="main-boxout" class="responsive">
        <h2>Latest News</h2>
        <?php
        if (function_exists('ot_get_option')) {
            echo ot_get_option('boxout_text');
        }
        ?>
    </div>

</div><!-- #primary -->

<?php get_footer(); ?>
