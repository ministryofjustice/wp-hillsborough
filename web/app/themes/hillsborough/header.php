<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package hillsborough
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>

        <?php if ($_SERVER["SERVER_NAME"] == "hillsboroughinquests.independent.gov.uk") { ?>
            <script>
                (function(i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function() {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-46373102-1', 'independent.gov.uk');
                ga('send', 'pageview');

            </script>
        <?php } ?>
    </head>

    <body <?php body_class(); ?>>
        <div id="page" class="hfeed site">
            <?php do_action('before'); ?>
            <nav id="site-navigation" class="main-navigation" role="navigation">
                <h1 class="menu-toggle"></h1>
                <a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'hillsborough'); ?></a>

                <?php //wp_nav_menu( array( 'theme_location' => 'primary' ) );  ?>
            </nav><!-- #site-navigation -->
            <header id="masthead" class="site-header" role="banner">
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/img/logo-460x84.gif" alt="<?php bloginfo('name'); ?>"></a>
                </div>
            </header><!-- #masthead -->
            <div id="breadcrumbs-wrapper">
                <div id="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">Home</a>
                        </li>
                        <?php
                        if (!is_home()) {
                            foreach (get_post_ancestors($post->ID) as $ancestor) {
                                echo "<li class='breadcrumb-child'>" . get_the_title($ancestor) . "</li>";
                            }
                            if (is_archive()) {
                                echo "<li class='breadcrumb-child'><a href='" . get_post_type_archive_link($wp_query->query['post_type']) . "'>" . post_type_archive_title('', false) . "</a></li> ";
                                if (is_post_type_archive('evidence') && get_query_var('witness')) {
                                    $witness = get_term_by('slug', get_query_var("witness"), "witness");
                                    echo "<li class='breadcrumb-child'><a href='" . get_permalink(get_page_by_title('evidence')) . "?witness=" . get_query_var('witness') . "'> Witness: " . $witness->name . "</a></li>";
                                } elseif (is_post_type_archive('evidence') && get_query_var('hdate')) {
                                    echo "<li class='breadcrumb-child'><a href='" . get_permalink(get_page_by_title('evidence')) . "?hdate=" . get_query_var('hdate') . "'> Date: " . date('l j F Y', strtotime($_GET['hdate'])) . "</a></li>";
                                }
                            }
                            else if (is_search()) {
                                echo "<li class='breadcrumb-child'>Search Results for: " . get_search_query() . "</li>";
                            } else {
                                if (is_singular('hearing')) {
                                    echo "<li class='breadcrumb-child'><a href='" . get_permalink(get_page_by_title('hearings')) . "'>Hearings</a> </li>";
                                }
                                echo "<li class='breadcrumb-child'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div id="content" class="site-content">
