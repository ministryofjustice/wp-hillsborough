<?php

function hillsborough_ctp_evidence_init() {
    $labels = array(
        'name' => 'Evidence',
        'singular_name' => 'Evidence',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Evidence',
        'edit_item' => 'Edit Evidence',
        'new_item' => 'New Evidence',
        'all_items' => 'All Evidence',
        'view_item' => 'View Evidence',
        'search_items' => 'Search Evidence',
        'not_found' => 'No evidence found',
        'not_found_in_trash' => 'No evidence found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Evidence'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'evidence'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title')
    );

    register_post_type('evidence', $args);
}

add_action('init', 'hillsborough_ctp_evidence_init');

/**
 * Initialize the meta boxes. 
 */
function hillsborough_add_evidence_meta_boxes() {
    // Time/date metabox
    $evidence_metabox_hearing = array(
        'id' => 'evidence_metabox_hearing',
        'title' => 'Hearing Date & Time',
        'pages' => array('evidence'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'id' => 'evidence_hearing_date',
                'label' => 'Hearing Date',
                'type' => 'text',
                'class' => 'datepicker',
            ),
            array(
                'id' => 'evidence_hearing_session',
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
    ot_register_meta_box($evidence_metabox_hearing);

    $evidence_metabox_media = array(
        'id' => 'evidence_metabox_media',
        'title' => 'Evidence Media',
        'pages' => array('evidence'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'id' => 'evidence_video',
                'desc' => 'Note that YouTube ID takes preference over the URL below. If you want to link to a file/page, please leave the YouTube ID blank.',
                'label' => 'YouTube ID',
                'type' => 'text',
            ),
            array(
                'id' => 'evidence_url',
                'label' => 'Evidence URL',
                'type' => 'upload'
            ),

        )
    );
    ot_register_meta_box($evidence_metabox_media);
}

add_action('admin_init', 'hillsborough_add_evidence_meta_boxes');
