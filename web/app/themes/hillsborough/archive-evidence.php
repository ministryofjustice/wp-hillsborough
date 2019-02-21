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

        <header class="page-header">
            <h1 class="page-title">Evidence</h1>
        </header>

        <?php
        if (get_query_var("witness") || get_query_var("hdate")) {
            $witness = get_term_by('slug', get_query_var("witness"), "witness");
            ?>

            <h4>You searched for</h4>
            <div class="search-criteria">
                <?php if (isset($_GET['hdate'])) { ?>
                    <div class="col-3"><strong>Date:</strong></div>
                    <div class="col-3">
                        <span class="long-date">
                            <?php echo date('l j F Y', strtotime($_GET['hdate'])); ?>
                        </span>
                        <span class="short-date">
                            <?php echo date('D j<\b\\r>M Y', strtotime($_GET['hdate'])); ?>
                        </span>
                    </div>
                    <div class="col-3"><a href="<?php echo site_url('/evidence/'); ?>">Remove</a></div>
                <?php } ?>
                <?php if (isset($_GET['witness'])) { ?>
                    <div class="col-3"><strong>Witness:</strong></div>
                    <div class="col-3"><?php echo $witness->name; ?></div>
                    <div class="col-3"><a href="<?php echo site_url('/evidence/'); ?>">Remove</a></div>
                <?php } ?>
            </div>

            <h4>Results</h4>
            <div class="search-results">
                <?php if (have_posts()) { ?>
                    <?php $prev_hearing_ID = false; ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $evidence_url = get_post_meta($post->ID, 'evidence_url', true);
                        if ($evidence_url) {
                            $evidence_id = get_attachment_id_from_src($evidence_url);
                            $evidence_size = round(filesize(get_attached_file($evidence_id)) / 1024);

                            $hearing = new WP_Query(array(
                                'post_type' => 'hearing',
                                'meta_query' => array(
                                    array(
                                        'key' => 'hearing_date',
                                        'value' => get_post_meta($post->ID, 'evidence_hearing_date', true),
                                    ),
                                    array(
                                        'key' => 'hearing_session',
                                        'value' => get_post_meta($post->ID, 'evidence_hearing_session', true),
                                    ),
                                ),
                            ));

                            if ($hearing->have_posts()) {
                                $hearing = $hearing->posts[0];
                                $hearing_date = get_post_meta($hearing->ID, 'hearing_date', true);
                                $hearing_timestamp = strtotime($hearing_date);
                                $hearing_session = get_post_meta($hearing->ID, 'hearing_session', true);
                                $hearing_url = get_the_permalink($hearing->ID);
                            } else {
                                $hearing = false;
                            }

                            ?>
                            <div class="results-line">
                                <div class="col-3">
                                    <?php if ($hearing && $hearing->ID !== $prev_hearing_ID): ?>
                                        <span class="long-date">
                                            <a href="<?php echo $hearing_url; ?>">
                                                <?php echo date('l j F Y', $hearing_timestamp); ?>
                                                <?php echo strtoupper($hearing_session); ?>
                                            </a>
                                        </span>
                                        <span class="short-date">
                                            <a href="<?php echo $hearing_url; ?>">
                                                <?php echo date('D j<\b\\r>M Y', $hearing_timestamp); ?>
                                                <?php echo strtoupper($hearing_session); ?>
                                            </a>
                                        </span>
                                    <?php else: // For evidence which doesn't have a hearing date assigned. ?>
                                        &nbsp;
                                    <?php endif; ?>
                                </div>
                                <div><?php echo "<a href='" . $evidence_url . "' target='_blank'>" . get_the_title() . " (" . substr($evidence_url, -3) . ", " . $evidence_size . "kb)</a>"; ?></div>
                            </div>
                            <?php
                            if ($hearing) {
                                $prev_hearing_ID = $hearing->ID;
                            } else {
                                $prev_hearing_ID = false;
                            }
                            ?>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php } else { ?>
                    No evidence for this <?php echo (isset($_GET['hdate'])?'date':'witness'); ?>
                <?php } ?>
            </div>
            <?php
        } else {
            ?>

            <div class="entry-content">
                <?php echo apply_filters('the_content', get_post_field('post_content', get_page_by_title("evidence")->ID)); ?>
            </div>

        </main><!-- #main -->
        <div id="main-evidence">
            <div id="evidence-by-date">
                <h4>By Date</h4>
                <div class="datepicker"></div>
                <form id="mobile-date-form" method="get" action="<?php echo site_url("evidence"); ?>">
                    <input type="text" name="hdate" id="hdate" data-format="YYYY-MM-DD" data-template="DD / MM / YYYY" value="<?php echo date('Y-m-j'); ?>"></input>
                    <input type="submit">
                </form>
            </div>
            <div id="evidence-by-witness">
                <h4>By Witness</h4>
                <div class="nav">
                    <?php
                    $letters = array();
                    foreach (range('A', 'Z') as $char) {
                        $letters[$char] = false;
                    }
                    $witnesses = get_terms('witness', array(
                        'hide_empty' => false
                    ));
                    $witnesses_formatted = '';
                    foreach ($witnesses as $witness) {
                        $witness_meta = get_option("taxonomy_" . $witness->term_id);
                        if (!empty($witness_meta["letter"])) {
                            $first_letter = strtoupper($witness_meta['letter']);
                        } else {
                            // Clean and split witness' name
                            $name = $witness->name;
                            $name = preg_replace('/\(.*?\)/', '', $name); // Remove anything in brackets
                            $name = preg_replace('/ {2,}/', ' ', $name); // Collapse multiple spaces into one
                            $name = trim($name); // Trim trailing whitespace
                            $split = explode(" ", $name);

                            // Get the first letter of the last word in the string
                            $first_letter = strtoupper(substr($split[count($split) - 1], 0, 1));
                        }
                        $letters[$first_letter] = true;
                        $witnesses_formatted .= "<div class='evidence-nav-witness " . $first_letter . "-witness'><a href='?witness=" . $witness->slug . "'>" . $witness->name . "</a></div>";
                    }
                    foreach (range('A', 'Z') as $char) {
                        echo "<span id='" . $char . "-filter' class='evidence-nav-letter" . ($letters[$char] ? "" : " disabled") . "'>" . $char . "</span>";
                    }
                    echo "<span id='no-filter' class='evidence-nav-letter'></span><span id='all-filter' class='evidence-nav-letter'>ALL</span>";
                    ?>
                </div>
                <div class="results">
                    <h4 class="results-header"></h4>
                    <?php echo $witnesses_formatted; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div><!-- #primary -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Display datepicker for date nav
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(date) {
                document.location = UpdateQueryString("hdate", date);
            }
        });

		$('#mobile-date-form #hdate').combodate();

    });

    // Interaction for by-letter nav
    $(".evidence-nav-letter:not(.disabled)").on("click touchend", function() {
        clickedOn = $(this).attr("id").replace("-filter", "");
        $(".results-header").show().html(clickedOn + " Results");
        $(".evidence-nav-letter.active").removeClass("active");
        $(this).addClass("active");
        if (clickedOn == "all") {
            $(".evidence-nav-witness").show();
        } else {
            $(".evidence-nav-witness").hide();
            $(".evidence-nav-witness." + clickedOn + "-witness").show();
        }
    });


    function UpdateQueryString(key, value, url) {
        if (!url)
            url = window.location.href;
        var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");

        if (re.test(url)) {
            if (typeof value !== 'undefined' && value !== null)
                return url.replace(re, '$1' + key + "=" + value + '$2$3');
            else {
                var hash = url.split('#');
                url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
                return url;
            }
        }
        else {
            if (typeof value !== 'undefined' && value !== null) {
                var separator = url.indexOf('?') !== -1 ? '&' : '?',
                        hash = url.split('#');
                url = hash[0] + separator + key + '=' + value;
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
                return url;
            }
            else
                return url;
        }
    }
</script>

<?php get_footer(); ?>
