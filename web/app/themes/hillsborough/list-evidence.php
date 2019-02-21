<ul>
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            $evidence_url = get_post_meta(get_the_ID(), 'evidence_url', true);
            $evidence_video = get_post_meta(get_the_ID(), 'evidence_video', true);
            if ($evidence_video) {
                echo "<li><a href='' class='popup-video' data-video-id='" . $evidence_video . "'>" . get_the_title() . " (video)</a></li>";
            } elseif ($evidence_url) {
                $evidence_id = get_attachment_id_from_src($evidence_url);
                $evidence_size = round(filesize(get_attached_file($evidence_id)) / 1024);
                echo "<li><a href='" . $evidence_url . "' target='_blank'>" . get_the_title() . " (" . substr($evidence_url, -3) . ", " . $evidence_size . "kb)</a></li>";
            }
        }
    } else {
        echo "<li>No evidence available for this hearing</li>";
    }
    ?>
</ul>