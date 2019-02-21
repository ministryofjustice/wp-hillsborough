<?php

function hillsborough_ctp_hearing_init() {
    $labels = array(
        'name' => 'Hearings',
        'singular_name' => 'Hearing',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Hearing',
        'edit_item' => 'Edit Hearing',
        'new_item' => 'New Hearing',
        'all_items' => 'All Hearings',
        'view_item' => 'View Hearing',
        'search_items' => 'Search Hearings',
        'not_found' => 'No hearings found',
        'not_found_in_trash' => 'No hearings found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Hearings'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'hearings'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );

    register_post_type('hearing', $args);
}

add_action('init', 'hillsborough_ctp_hearing_init');

/**
 * Initialize the meta boxes. 
 */
function hillsborough_add_hearing_meta_boxes() {
    // Time/date metabox
    $hearing_metabox_time = array(
        'id' => 'hearing_metabox_time',
        'title' => 'Hearing Date & Time',
        'pages' => array('hearing'),
        'context' => 'side',
        'priority' => 'default',
        'fields' => array(
            array(
                'id' => 'hearing_date',
                'label' => 'Hearing Date',
                'type' => 'text',
                'class' => 'datepicker',
            ),
            array(
                'id' => 'hearing_session',
                'label' => 'Hearing Session',
                'type' => 'select',
                'choices' => array(
                    array(
                        'value' => 'am',
                        'label' => 'AM'
                    ),
                    array(
                        'value' => 'pm',
                        'label' => 'PM'
                    )
                )
            )
        )
    );
    ot_register_meta_box($hearing_metabox_time);

    // Transcript uploads and witnesses
    $hearing_metabox_details = array(
        'id' => 'hearing_metabox_details',
        'title' => 'Hearing Details',
        'pages' => array('hearing'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
//            array(
//                'id' => 'hearing_video',
//                'label' => 'YouTube ID',
//                'type' => 'text',
//            ),
            array(
                'id' => 'hearing_pdf',
                'label' => 'Transcript (PDF)',
                'type' => 'upload',
            ),
            array(
                'id' => 'hearing_txt',
                'label' => 'Transcript (txt)',
                'type' => 'upload',
            ),
            array(
                'id' => 'hearing_names',
                'label' => 'Names of the deceased',
                'type' => 'text',
            ),
        )
    );
    ot_register_meta_box($hearing_metabox_details);
}

add_action('admin_init', 'hillsborough_add_hearing_meta_boxes');

function add_sort_metabox() {
    add_meta_box('hearing_evidence_sort_metabox', 'Custom Evidence Sort Order', 'display_hearing_evidence_sort_metabox', 'hearing', 'normal', 'core');
}

add_action('add_meta_boxes', 'add_sort_metabox');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function display_hearing_evidence_sort_metabox($post) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field('display_hearing_evidence_sort_metabox', 'display_hearing_evidence_sort_metabox_nonce');

    // Get evidence
    $evidence = new WP_Query(
            array(
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
        'orderby' => 'menu_order'
            )
    );

//  echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field" value="' . esc_attr( $value ) . '" size="25" />';

    if ($evidence->have_posts()) {
        echo "<ul class='sortable'>";
        foreach ($evidence->posts as $evidence_item) {
            $evidence_url = get_post_meta($evidence_item->ID, 'evidence_url', true);
            if ($evidence_url) {
                $evidence_id = get_attachment_id_from_src($evidence_url);
                $evidence_size = round(filesize(get_attached_file($evidence_id)) / 1024);
                echo "<li id='post-" . $evidence_item->ID . "'>" . $evidence_item->post_title . " (" . substr($evidence_url, -3) . ", " . $evidence_size . "kb)</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<ul><li>No evidence available for this hearing</li></ul>";
    }

    echo "<button class='refresh-sortable'>Refresh List</button>";
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function myplugin_save_postdata($post_id) {

    /*
     * We need to verify this came from the our screen and with proper authorization,
     * because save_post can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['myplugin_inner_custom_box_nonce']))
        return $post_id;

    $nonce = $_POST['myplugin_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'myplugin_inner_custom_box'))
        return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // Check the user's permissions.
    if ('page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } else {

        if (!current_user_can('edit_post', $post_id))
            return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    $mydata = sanitize_text_field($_POST['myplugin_new_field']);

    // Update the meta field in the database.
    update_post_meta($post_id, '_my_meta_value_key', $mydata);
}

add_action('save_post', 'myplugin_save_postdata');
