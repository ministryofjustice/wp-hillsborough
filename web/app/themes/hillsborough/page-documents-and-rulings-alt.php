<?php
/**
 * Template for Documents and Rulings page
 * 
 * This template has been retained in case it is decided to switch to the alternative
 * format which lists documents and rulings automatically (as opposed to free text)
 *
 * @package hillsborough
 */
get_header();
?>
<?php get_sidebar(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main documents-main" role="main">

        <h1>Rulings</h1>

        <h4>Ruling date</h4>
        <?php
        $rulings = new WP_Query(
                array(
            'post_type' => 'document',
            'orderby' => 'meta_value',
            'meta_key' => 'document_date',
            'meta_query' => array(
                array(
                    'key' => 'document_type',
                    'value' => 'ruling'
                )
            )
                )
        );
        while ($rulings->have_posts()) {
            $rulings->the_post();
            $document_url = get_post_meta($post->ID, 'document_url', true);
            if ($document_url) {
                $evidence_id = get_attachment_id_from_src($document_url);
                $evidence_size = round(filesize(get_attached_file($evidence_id)) / 1024);
                ?>
                <div class="results-line">
                    <div class="col-3">
                        <span class="long-date">
                            <?php echo ($last_date != get_post_meta($post->ID, "document_date", true) ? date('l j F Y', strtotime(get_post_meta($post->ID, "document_date", true))) : "&nbsp;"); ?>
                        </span>
                        <span class="short-date">
                            <?php echo ($last_date != get_post_meta($post->ID, "document_date", true) ? date('D j M Y', strtotime(get_post_meta($post->ID, "document_date", true))) : "&nbsp;"); ?>
                        </span>
                    </div>
                    <div><?php echo "<a href='" . $document_url . "' target='_blank'>" . get_the_title() . " (" . substr($document_url, -3) . ", " . $evidence_size . "kb)</a>"; ?></div>
                </div>

                <?php $last_date = get_post_meta($post->ID, "document_date", true); ?>
                <?php
            }
        }
        ?>

        <h1>Documents</h1>

        <h4>Document date</h4>
        <?php
        $rulings = new WP_Query(
                array(
            'post_type' => 'document',
            'orderby' => 'meta_value',
            'meta_key' => 'document_date',
            'meta_query' => array(
                array(
                    'key' => 'document_type',
                    'value' => 'document'
                )
            )
                )
        );
        while ($rulings->have_posts()) {
            $rulings->the_post();
            $document_url = get_post_meta($post->ID, 'document_url', true);
            if ($document_url) {
                $evidence_id = get_attachment_id_from_src($document_url);
                $evidence_size = round(filesize(get_attached_file($evidence_id)) / 1024);
                ?>
                <div class="results-line">
                    <div class="col-3">
                        <span class="long-date">
                            <?php echo ($last_date != get_post_meta($post->ID, "document_date", true) ? date('l j F Y', strtotime(get_post_meta($post->ID, "document_date", true))) : "&nbsp;"); ?>
                        </span>
                        <span class="short-date">
                            <?php echo ($last_date != get_post_meta($post->ID, "document_date", true) ? date('D j M Y', strtotime(get_post_meta($post->ID, "document_date", true))) : "&nbsp;"); ?>
                        </span>
                    </div>
                    <div><?php echo "<a href='" . $document_url . "' target='_blank'>" . get_the_title() . " (" . substr($document_url, -3) . ", " . $evidence_size . "kb)</a>"; ?></div>
                </div>

                <?php $last_date = get_post_meta($post->ID, "document_date", true); ?>
                <?php
            }
        }
        ?>

    </main>
</div>

<?php get_footer(); ?>