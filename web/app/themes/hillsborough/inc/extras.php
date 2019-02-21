<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package hillsborough
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function hillsborough_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'hillsborough_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hillsborough_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() )
		$classes[] = 'group-blog';

	return $classes;
}
add_filter( 'body_class', 'hillsborough_body_classes' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function hillsborough_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'hillsborough' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'hillsborough_wp_title', 10, 2 );

function sort_hearings_by_hearing_date($postA, $postB, $dateSort = 'asc') {
	$dateA = get_post_meta($postA->ID, 'hearing_date', true);
	$dateB = get_post_meta($postB->ID, 'hearing_date', true);

	$dateA = new DateTime($dateA);
	$dateB = new DateTime($dateB);

	if ($dateA > $dateB) {
		if ($dateSort == 'asc') { return 1; }
		else                    { return -1; }
	} else if ($dateA < $dateB) {
		if ($dateSort == 'asc') { return -1; }
		else                    { return 1; }
	}

	$timeA = get_post_meta($postA->ID, 'hearing_session', true);
	$timeB = get_post_meta($postB->ID, 'hearing_session', true);

	$timeA = ( $timeA == 'am' ) ? 0 : 1;
	$timeB = ( $timeB == 'am' ) ? 0 : 1;

	if ($timeA > $timeB) {
		return 1;
	} else if ($timeA == $timeB) {
		return 0;
	} else {
		return -1;
	}
}

function sort_hearings_by_hearing_date_desc($postA, $postB) {
	return sort_hearings_by_hearing_date($postA, $postB, 'desc');
}

function relevanssi_sort_hearings($hits) {
	$allHitsAreHearings = true;
	$i = 0;
	$iCount = count($hits[0]);
	while ($i < $iCount && $allHitsAreHearings) {
		if ($hits[0][$i]->post_type !== 'hearing') {
			$allHitsAreHearings = false;
		}
		$i++;
	}

	if ($allHitsAreHearings) {
		usort($hits[0], 'sort_hearings_by_hearing_date_desc');
	}

	return $hits;
}
add_filter('relevanssi_hits_filter', 'relevanssi_sort_hearings');