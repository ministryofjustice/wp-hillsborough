<?php

/**
 * hillsborough functions and definitions
 *
 * @package hillsborough
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
  $content_width = 640;
} /* pixels */

if (!function_exists('hillsborough_setup')) :

  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  function hillsborough_setup() {

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on hillsborough, use a find and replace
     * to change 'hillsborough' to the name of your theme in all the template files
     */
    load_theme_textdomain('hillsborough', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    //add_theme_support( 'post-thumbnails' );
    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'hillsborough'),
        'footer' => __('Footer Menu', 'hillsborough')
    ));

    // Enable support for Post Formats.
    add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link'));

    // Setup the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('hillsborough_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));
  }

endif; // hillsborough_setup
add_action('after_setup_theme', 'hillsborough_setup');

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function hillsborough_widgets_init() {
  register_sidebar(array(
      'name' => __('Sidebar', 'hillsborough'),
      'id' => 'sidebar-1',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside>',
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
  ));
}

add_action('widgets_init', 'hillsborough_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function hillsborough_scripts() {
  wp_deregister_script('jquery');
  wp_register_script('jquery', 'https://code.jquery.com/jquery-1.10.2.min.js');

  wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  wp_register_style('hillsborough-jquery-ui', get_template_directory_uri() . '/css/jquery-ui.css');

  wp_enqueue_style('hillsborough-style', get_stylesheet_uri()); // Default underscores stylesheet
  wp_enqueue_style('hillsborough-style-custom', get_template_directory_uri() . '/css/custom.css'); // Custom stylesheet

  wp_enqueue_script('hillsborough-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true);

  wp_enqueue_script('hillsborough-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true);

  wp_enqueue_script('jquery-swipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js', array('jquery'));

  wp_enqueue_script('touch-punch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array('jquery', 'jquery-ui'), '', true);

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  if (is_page('hearings')) {
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_style('hillsborough-jquery-ui');
  }

  if (is_page('evidence') || is_post_type_archive('evidence')) {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('moment', get_template_directory_uri() . '/js/moment.min.js', array('jquery'), '20130115', true);
    wp_enqueue_script('combodate', get_template_directory_uri() . '/js/combodate.js', array('moment'), '20130115', true);
    wp_enqueue_style('jquery-ui');
  }

  if (is_page('about-the-inquests')) {
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('faq-accordion', get_template_directory_uri() . '/js/faqs.js', array('jquery-ui-accordion', 'jquery'), '20131213', true);
    wp_enqueue_style('hillsborough-jquery-ui');
  }
}

add_action('wp_enqueue_scripts', 'hillsborough_scripts');

/**
 * Enqueue the date picker
 */
function enqueue_admin_jquery() {
  wp_enqueue_script('field-date-js', get_template_directory_uri() . '/js/field_date.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), time(), true);
  //wp_enqueue_script('evidence-sort-js', get_template_directory_uri() . '/js/evidence-sort.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), time(), true);

  wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  wp_enqueue_style('jquery-ui');
}

add_action('admin_enqueue_scripts', 'enqueue_admin_jquery');

/**
 * Extra admin CSS
 */
function enqueue_admin_stylesheets() {
  wp_register_style('hillsborough-admin-style', get_template_directory_uri() . '/css/custom-admin.css');
  wp_enqueue_style('hillsborough-admin-style');
}

add_action('admin_enqueue_scripts', 'enqueue_admin_stylesheets');

// add ie conditional html5 shim to header
function add_ie_workarounds() {
  echo '<!--[if lt IE 9]>';
  echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>';
  echo '<script src="' . get_template_directory_uri() . '/js/IE8.js"></script>';
  echo '<![endif]-->';
}

add_action('wp_head', 'add_ie_workarounds');

/* Add AJAX to backend */

function ajax_load_scripts() {
  // load our jquery file that sends the $.post request
  wp_enqueue_script("hillsborough-ajax", get_template_directory_uri() . '/js/evidence-sort.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'));

  // make the ajaxurl var available to the above script
  wp_localize_script('hillsborough-ajax', 'custom_sort', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'ajax_load_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Template redirect rules (hooks to "template_redirect")
 */
require get_template_directory() . '/inc/template-redirects.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/* Custom Post Type declarations */
require get_template_directory() . '/inc/cpt/cpt-hearing.php';
require get_template_directory() . '/inc/cpt/cpt-evidence.php';
require get_template_directory() . '/inc/cpt/cpt-document.php';

/* Taxonomy declarations */
require get_template_directory() . '/inc/tax/tax-witness.php';

/* Admin AJAX functions */
require get_template_directory() . '/inc/custom-admin-ajax.php';

/* Adds OptionTree Theme Mode */
add_filter('ot_theme_mode', '__return_true');
add_filter('ot_show_pages', '__return_true');
add_filter('ot_show_pages', '__return_false');
require_once ('option-tree/ot-loader.php');
load_template(trailingslashit(get_template_directory()) . 'inc/theme-options.php');

/* Create custom slugs for hearings */
add_filter('wp_unique_post_slug', 'hearing_unique_post_slug', 10, 4);

function hearing_unique_post_slug($slug, $post_ID, $post_status, $post_type) {
  if ('hearing' == $post_type) {
    $slug = date('Y-m-d', strtotime(get_post_meta($post_ID, 'hearing_date', true))) . get_post_meta($post_ID, 'hearing_session', true);
  }
  return $slug;
}

/* Create custom title for hearings */
add_filter('wp_insert_post_data', 'change_title', 99, 2);

function change_title($data, $postarr) {
  // If it is our form has not been submitted, so we dont want to do anything
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // Change title if post type is hearing
  if (isset($_POST['post_type']) && $_POST['post_type'] == 'hearing') {
    $title = date('l j F Y', strtotime($postarr['hearing_date'])) . " " . strtoupper($postarr['hearing_session']) . " Session";
    $data['post_title'] = $title;

    $slug = date('Y-m-d', strtotime($postarr['hearing_date'])) . $postarr['hearing_session'];
    $data['post_name'] = $slug;
  }
  return $data;
}

// Get attachment ID from src(url)
function get_attachment_id_from_src($image_src) {
  global $wpdb;
  $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
  $id = $wpdb->get_var($query);
  return $id;
}

// Add new vars to possible query vars
add_filter('query_vars', 'hillsborough_queryvars');

function hillsborough_queryvars($qvars) {
  $qvars[] = 'hdate';
  return $qvars;
}

// Filter evidence by date from query_var
function meta_filter_evidence($query) {
  if (isset($_GET['hdate']) && $query->query['post_type'] == 'evidence') {
    // $query is the WP_Query object, set is simply a method of the WP_Query class that sets a query var parameter
    $query->set('meta_key', 'evidence_hearing_date');
    $query->set('meta_value', $_GET['hdate']);
  }
  return $query;
}

add_filter('pre_get_posts', 'meta_filter_evidence');

// Customise wp menu so that to make active menu items for archive and CPTs
add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

function special_nav_class($classes, $item) {
  // Access $post object
  global $post;
  // Add menu support for hearing CPT
  if (isset($post)) {
    if ($post->post_type == "hearing" && $item->title == 'Hearings') {
      $classes[] = 'current-menu-item';
    }
    // Add menu support for evidence CPT
    if (is_post_type_archive('evidence') && $item->title == 'Evidence') {
      $classes[] = 'current-menu-item';
    }
  }
  return $classes;
}

// Customise post list views for CPTs
// Hearings
add_filter('manage_hearing_posts_columns', 'hearing_custom_columns');
add_filter('manage_hearing_posts_custom_column', 'hearing_custom_content', 10, 2);

function hearing_custom_columns($defaults) {
  $defaults = array();
  $defaults['cb'] = '<input type="checkbox" />';
  $defaults['hearing_date'] = 'Hearing Date';
  $defaults['am_session'] = 'AM Session';
  $defaults['pm_session'] = 'PM Session';
  return $defaults;
}

function hearing_custom_content($column_name, $post_ID) {
  switch ($column_name) {
    case 'hearing_date':
      echo date('l j F Y', strtotime(get_post_meta($post_ID, 'hearing_date', true)));
      break;
    case 'am_session':
      if (get_post_meta($post_ID, 'hearing_session', true) == 'am') {
        edit_post_link('Edit', '', '', $post_ID);
      } else {
        echo "-";
      }
      break;
    case 'pm_session':
      if (get_post_meta($post_ID, 'hearing_session', true) == 'pm') {
        edit_post_link('Edit', '', '', $post_ID);
      } else {
        echo "-";
      }
      break;
    default:
      echo "other";
  }
}

// Register the column as sortable
function sort_column_register_sortable($columns) {
  $columns['hearing_date'] = 'hearing_date';
  return $columns;
}

add_filter('manage_edit-hearing_sortable_columns', 'sort_column_register_sortable');

add_filter('request', 'hearing_date_column_orderby');

function hearing_date_column_orderby($vars) {
  if (isset($vars['orderby']) && 'hearing_date' == $vars['orderby']) {
    $vars = array_merge($vars, array(
        'meta_key' => 'hearing_date',
        //'orderby' => 'meta_value_num', // does not work
        'orderby' => 'meta_value'
            //'order' => 'asc' // don't use this; blocks toggle UI
    ));
  }
  return $vars;
}

// Add filter to change query for hearing edit list screen in wp-admin
if (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'hearing') {
//    add_filter('posts_request', 'hearings_request');
}

function hearings_request($request) {
  $request = "Select Distinct
        99999999 As ID,
  hearing_dates.meta_value,
  am_sessions.post_id As am_id,
  pm_sessions.post_id
  From
  (Select Distinct
    hillsborough.wp_postmeta.meta_value,
    hillsborough.wp_postmeta.post_id
  From
    hillsborough.wp_postmeta
  Where
    hillsborough.wp_postmeta.meta_key = 'hearing_date') As hearing_dates
  Left Join
  (Select
    hillsborough.wp_postmeta.post_id,
    hillsborough.wp_postmeta.meta_value,
    wp_postmeta1.meta_value As am_hearing_date
  From
    hillsborough.wp_postmeta Inner Join
    hillsborough.wp_postmeta wp_postmeta1 On hillsborough.wp_postmeta.post_id =
      wp_postmeta1.post_id
  Where
    hillsborough.wp_postmeta.meta_value = 'am' And
    hillsborough.wp_postmeta.meta_key = 'hearing_session' And
    wp_postmeta1.meta_key = 'hearing_date') As am_sessions
    On hearing_dates.meta_value = am_sessions.am_hearing_date Left Join
  (Select
    hillsborough.wp_postmeta.post_id,
    hillsborough.wp_postmeta.meta_value,
    wp_postmeta1.meta_value As pm_hearing_date
  From
    hillsborough.wp_postmeta Inner Join
    hillsborough.wp_postmeta wp_postmeta1 On hillsborough.wp_postmeta.post_id =
      wp_postmeta1.post_id
  Where
    hillsborough.wp_postmeta.meta_value = 'pm' And
    hillsborough.wp_postmeta.meta_key = 'hearing_session' And
    wp_postmeta1.meta_key = 'hearing_date') As pm_sessions
    On hearing_dates.meta_value = pm_sessions.pm_hearing_date Inner Join
  hillsborough.wp_posts On hearing_dates.post_id = hillsborough.wp_posts.ID";
  return $request;
}

// Allow editors access to theme options
$_the_roles = new WP_Roles();
$_the_roles->add_cap('editor', 'edit_theme_options');

//Page Slug Body Class
function add_slug_body_class($classes) {
  global $post;
  if (isset($post)) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}

add_filter('body_class', 'add_slug_body_class');

function reset_hearing_slugs() {
  $hearings = new WP_Query(
          array(
      'post_type' => 'hearing',
      'orderby' => 'meta_value',
      'meta_key' => 'hearing_date',
      'posts_per_page' => -1,
      'post_status' => 'publish'
          )
  );
  while ($hearings->have_posts()) {
    $hearings->the_post();
    wp_update_post(array(
        'ID' => get_the_ID()
    ));
//    hearing_unique_post_slug(null,$hearing->ID,null,'hearing');
  }
}

if (isset($_GET['reset']) && $_GET['reset'] == 'hearing-slugs') {
  reset_hearing_slugs();
}
