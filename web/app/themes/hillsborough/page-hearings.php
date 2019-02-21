<?php
/**
 * Template for Hearings page/archive
 *
 * @package hillsborough
 */
get_header();
?>
<?php get_sidebar(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while (have_posts()) : the_post(); ?>

            <?php get_template_part('content', 'page'); ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
    <div id="main-hearings">
        <h2>Recent Hearings</h2>

        <div id="hearing-accordion">
            <?php
            $hearings = new WP_Query(
                    array(
                'post_type' => 'hearing',
                'orderby' => 'meta_value',
                'meta_key' => 'hearing_date',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                    'after'     => 'February 28th, 2014',
                    'inclusive' => true,
                    ),
                ),
                'post_status' => 'publish'
                    )
            );

            // Check var for to see if month/year has changed
            $cur_day = $cur_month = $cur_year = null;
            // Toggle var for applying row highlight
            $highlight = true;

            // Generate list of hearings, broken down by date
            while ($hearings->have_posts()) {
                $hearings->the_post();
                // Tracks if current hearing date has am/pm sessions
                $hearing_am = $hearing_pm = false;
                $next_post = $hearings->posts[$hearings->current_post + 1];
                // If the month/year has changed, print a new month/year heading
                if (($cur_month != date("F", strtotime(get_post_meta($post->ID, 'hearing_date', true)))) || ($cur_year != date("Y", strtotime(get_post_meta($post->ID, 'hearing_date', true))))) {
                    if ($cur_month != null)
                        echo "</div></div>";
                    $cur_month = date("F", strtotime(get_post_meta($post->ID, 'hearing_date', true)));
                    $cur_year = date("Y", strtotime(get_post_meta($post->ID, 'hearing_date', true)));
                    echo "<div class='hearing-month'>";
                    echo "<h3>" . $cur_month . " " . $cur_year . "</h3>";
                    echo "<div>";
                    // Make first entry for each month have a highlight
                    $highlight = true;
                }

                $session = get_post_meta($post->ID, "hearing_session", true);
                ${"hearing_$session"} = get_permalink($post->ID);
                if (get_post_meta($post->ID, 'hearing_date', true) == get_post_meta($next_post->ID, 'hearing_date', true)) {
                    $session = get_post_meta($next_post->ID, "hearing_session", true);
                    ${"hearing_$session"} = get_permalink($next_post->ID);
                    ?>
                    <div class='hearing-entry<?php echo ($highlight ? " shaded" : ""); ?>'>
                        <span class='hearing-date'><?php echo date('l j F Y', strtotime(get_post_meta($post->ID, 'hearing_date', true))); ?></span>
                        <span class='session-link'><a href="<?php echo $hearing_am; ?>">AM Session</a></span>
                        <span class='session-link'><a href="<?php echo $hearing_pm; ?>">PM Session</a></span>
                    </div>
                    <?php
                    $hearings->the_post();
                } else {
                    ?>
                    <div class='hearing-entry<?php echo ($highlight ? " shaded" : ""); ?>'>
                        <span class='hearing-date'><?php echo date('l j F Y', strtotime(get_post_meta($post->ID, 'hearing_date', true))); ?></span>
                        <span class='session-link'><?php if ($hearing_am) { ?><a href="<?php echo $hearing_am; ?>">AM Session</a><?php } else { ?>No AM session<?php } ?></span>
                        <span class='session-link'><?php if ($hearing_pm) { ?><a href="<?php echo $hearing_pm; ?>">PM Session</a><?php } else { ?>No PM session<?php } ?></span>
                    </div>
                    <?php
                }

                $highlight = !$highlight;
            }
            echo "</div></div>";
            ?>
        </div>
    </div>

    <div id="pre-inquest-hearings">
        <h2>Pre Inquest hearings</h2>
                <div id="hearing-accordion">
            <?php
            $hearings = new WP_Query(
                    array(
                'post_type' => 'hearing',
                'orderby' => 'meta_value',
                'meta_key' => 'hearing_date',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                    'before'     => 'March 1st, 2014',
                    'inclusive' => true,
                    ),
                ),
                'post_status' => 'publish'
                    )
            );

            // Check var for to see if month/year has changed
            $cur_day = $cur_month = $cur_year = null;
            // Toggle var for applying row highlight
            $highlight = true;

            // Generate list of hearings, broken down by date
            while ($hearings->have_posts()) {
                $hearings->the_post();
                // Tracks if current hearing date has am/pm sessions
                $hearing_am = $hearing_pm = false;
                $next_post = $hearings->posts[$hearings->current_post + 1];
                // If the month/year has changed, print a new month/year heading
                if (($cur_month != date("F", strtotime(get_post_meta($post->ID, 'hearing_date', true)))) || ($cur_year != date("Y", strtotime(get_post_meta($post->ID, 'hearing_date', true))))) {
                    if ($cur_month != null)
                        echo "</div></div>";
                    $cur_month = date("F", strtotime(get_post_meta($post->ID, 'hearing_date', true)));
                    $cur_year = date("Y", strtotime(get_post_meta($post->ID, 'hearing_date', true)));
                    echo "<div class='hearing-month'>";
                    echo "<h3>" . $cur_month . " " . $cur_year . "</h3>";
                    echo "<div>";
                    // Make first entry for each month have a highlight
                    $highlight = true;
                }

                $session = get_post_meta($post->ID, "hearing_session", true);
                ${"hearing_$session"} = get_permalink($post->ID);
                if (get_post_meta($post->ID, 'hearing_date', true) == get_post_meta($next_post->ID, 'hearing_date', true)) {
                    $session = get_post_meta($next_post->ID, "hearing_session", true);
                    ${"hearing_$session"} = get_permalink($next_post->ID);
                    ?>
                    <div class='hearing-entry<?php echo ($highlight ? " shaded" : ""); ?>'>
                        <span class='hearing-date'><?php echo date('l j F Y', strtotime(get_post_meta($post->ID, 'hearing_date', true))); ?></span>
                        <span class='session-link'><a href="<?php echo $hearing_am; ?>">AM Session</a></span>
                        <span class='session-link'><a href="<?php echo $hearing_pm; ?>">PM Session</a></span>
                    </div>
                    <?php
                    $hearings->the_post();
                } else {
                    ?>
                    <div class='hearing-entry<?php echo ($highlight ? " shaded" : ""); ?>'>
                        <span class='hearing-date'><?php echo date('l j F Y', strtotime(get_post_meta($post->ID, 'hearing_date', true))); ?></span>
                        <span class='session-link'><?php if ($hearing_am) { ?><a href="<?php echo $hearing_am; ?>">AM Session</a><?php } else { ?>No AM session<?php } ?></span>
                        <span class='session-link'><?php if ($hearing_pm) { ?><a href="<?php echo $hearing_pm; ?>">PM Session</a><?php } else { ?>No PM session<?php } ?></span>
                    </div>
                    <?php
                }

                $highlight = !$highlight;
            }
            echo "</div></div>";
            ?>
        </div>

    </div>
</div><!-- #primary -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#hearing-accordion > .hearing-month").accordion({ header: "h3", collapsible: true, heightStyle: "content", active: false});
        $("#hearing-accordion > .hearing-month").first().accordion({ header: "h3", collapsible: true, heightStyle: "content", active: 0});
    })
	$(document).bind('touchend', function(e) {
		$(e.target).trigger('click');
	});
</script>

<?php get_footer(); ?>