<?php
/**
 * @package hillsborough
 */
?>

<?php
// Create query to pull in all hearings in hearing order (not order hearings were created,
// just in case they're created out of sequence
$hearings_results = new WP_Query(
        array(
    'post_type' => 'hearing',
    'orderby' => 'meta_value title',
    'order' => 'ASC',
    'meta_key' => 'hearing_date',
    'posts_per_page' => -1,
    'post_status' => 'publish'
        )
);

$x = 0;
foreach ($hearings_results->posts as $hearing) {
    if ($hearing->ID == $post->ID) {
        $hearing_session = get_post_meta($hearing->ID, 'hearing_session', true);
        $prev_link = get_permalink($hearings_results->posts[ $x - 1]->ID);
        $prev_date = get_the_title($hearings_results->posts[ $x - 1]->ID);
        $next_link = get_permalink($hearings_results->posts[ $x + 1]->ID);
        $next_date = get_the_title($hearings_results->posts[ $x + 1]->ID);
    }
    $x++;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if ($next_link != get_permalink()) { ?><div class="nav-next"><a href="<?php echo $next_link; ?>" title="<?php echo $next_date; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/arrow_sans_right.png"></a></div><?php } ?>
        <?php if ($prev_link != get_permalink()) { ?><div class="nav-prev"><a href="<?php echo $prev_link; ?>" title="<?php echo $prev_date; ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/arrow_sans_left.png"></a></div><?php } ?>
        <div class="title-container">
            <h1 class="entry-title">
                <span class="long-date">
                    <?php echo date('l j F Y', strtotime(substr(get_the_title(), 0, -10))); ?>
                </span>
                <span class="short-date">
                    <?php echo date('D j<\b\\r>M Y', strtotime(substr(get_the_title(), 0, -10))); ?>
                </span>
            </h1>
            <h3>
                <?php echo substr(get_the_title(), -10); ?> 
                <?php if (get_post_meta($post->ID, 'hearing_names', true)) { ?>
                <span class="names">Evidence in relation to <?php echo get_post_meta($post->ID, 'hearing_names', true); ?></span>
                <?php } else { ?>
                <?php } ?>  
            </h3>
        </div>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <div class="links-table">
            <div class="col-3">
                <h4>Witnesses</h4>
                <ul>
                    <?php
                    if ($witnesses = get_the_terms($post->ID, 'witness')) {
                        foreach ($witnesses as $witness) {
                            echo "<li><a href='" . add_query_arg('witness', $witness->slug, site_url('evidence')) . "'>" . $witness->name . "</a></li>";
                        }
                    } else {
                        echo "<li>No witnesses</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="col-3">
                <h4>Transcript</h4>
                <ul>
                    <?php
                    // Get PDF details
                    $pdf_url = get_post_meta($post->ID, 'hearing_pdf', true);
                    if ($pdf_url) {
                        $pdf_id = get_attachment_id_from_src($pdf_url);
                        $pdf_size = round(filesize(get_attached_file($pdf_id)) / 1024);
                        echo "<li><a href='" . $pdf_url . "' target='_blank'>Transcript of " . ($hearing_session == "am" ? "Morning" : "Afternoon") . " Hearing " . substr(get_the_title(), 0, -10) . " (pdf, " . $pdf_size . "kb)</a></li>";
                    } else {
                        echo "<li>PDF not yet available</li>";
                    }

                    // Get txt details
                    $txt_url = get_post_meta($post->ID, 'hearing_txt', true);
                    if ($txt_url) {
                        $txt_id = get_attachment_id_from_src($txt_url);
                        $txt_size = round(filesize(get_attached_file($txt_id)) / 1024);
                        echo "<li><a href='" . $txt_url . "' target='_blank'>Transcript of " . ($hearing_session == "am" ? "Morning" : "Afternoon") . " Hearing " . substr(get_the_title(), 0, -10) . " (txt, " . $txt_size . "kb)</a></li>";
                    } else {
                        echo "<li>TXT not yet available</li>";
                    }
                    ?>
                </ul>
            </div>
            <div class="col-3">
                <h4>Evidence</h4>
                <?php

                query_posts(array(
                    'post_type' => 'evidence',
                    'meta_query' => array(
                        array(
                            'key' => 'evidence_hearing_date',
                            'value' => get_post_meta($post->ID, 'hearing_date', true)
                        ),
                        array(
                            'key' => 'evidence_hearing_session',
                            'value' => get_post_meta($post->ID, 'hearing_session', true)
                        )
                    ),
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));

                get_template_part('list-evidence');

                wp_reset_query();

                ?>
            </div>
        </div>
    </div><!-- .entry-content -->

    <footer class="entry-meta">
    </footer><!-- .entry-meta -->
</article><!-- #post-## -->