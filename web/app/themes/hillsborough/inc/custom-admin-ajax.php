<?php

add_action('wp_ajax_reorder_evidence', 'reorder_evidence');

function reorder_evidence() {
    // first check if data is being sent and that it is the data we want
    $evidence_ids = $_POST['values'];
    parse_str($evidence_ids);
    if ($evidence_ids) {
        // Turn serialised array back into array and loop through, extracting IDs and updating menu_order value with next position
        $position = 1;
        foreach ($post as $evidence_post) {
            wp_update_post(array(
                'ID' => $evidence_post,
                'menu_order' => $position
            ));
            $position++;
        }
        die();
    }
}
