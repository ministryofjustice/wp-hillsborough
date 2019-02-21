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
    <div id="main-evidence">
        <div id="evidence-by-date">
            <h4>By Date</h4>
            <div class="datepicker"></div>
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
                foreach ($witnesses as $witness) {
                    // Clean and split witness' name
                    $name = $witness->name;
                    $name = preg_replace('/\(.*?\)/', '', $name); // Remove anything in brackets
                    $name = preg_replace('/ {2,}/', ' ', $name); // Collapse multiple spaces into one
                    $name = trim($name); // Trim trailing whitespace
                    $split = explode(" ", $name);

                    // Get the first letter of the last word in the string
                    $first_letter = strtoupper(substr($split[count($split) - 1], 0, 1));
                    $letters[$first_letter] = true;
                    $witnesses_formatted .= "<div class='evidence-nav-witness " . $first_letter . "-witness'>" . $witness->name . "</div>";
                }
                foreach (range('A', 'Z') as $char) {
                    echo "<span id='" . $char . "-filter' class='evidence-nav-letter " . ($letters[$char] ? "" : " disabled") . "'>" . $char . "</span>";
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
</div><!-- #primary -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Display datepicker for date nav
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    // Interaction for by-letter nav
    $(document).on("click", ".evidence-nav-letter:not(.disabled)", function() {
        clickedOn = $(this).attr("id").replace("-filter", "");
        $(".results-header").show().html(clickedOn + " Results");
        if (clickedOn == "all") {
            $(".evidence-nav-witness").show();
        } else {
            $(".evidence-nav-witness").hide();
            $(".evidence-nav-witness." + clickedOn + "-witness").show();
        }
    });
</script>

<?php get_footer(); ?>