<?php
/*
Plugin Name: WP Geo - Detect Coordinates
Plugin URI: http://status301.net/wordpress-plugins/wp-geo-mashup-map/
Description: Extends WP Geo to detect coordinates in post/page content when publishing via the WP backend, Postie or during Import and converts found coordinates to WP Geo readable post meta entries.
Version: 0.6
Author: RavanH
Author URI: http://status301.net/
*/

if( !function_exists('wp_geo_detect_coords') ) {

 function wp_geo_detect_coords( $post_ID, $post = NULL ) {

	// get post if no post data 
	// !! seems to be always necessary in spite of 
	// both $post = get_post($post_ID) and
	// do_action('wp_insert_post', $post_ID, $post) 
	// in post.php --> BUG ?
	if ( !$post )
		$post = get_post($post_ID);
	
	// WP Geo constants
	if ( !defined( 'WPGEO_LATITUDE_META' ) )
		define( 'WPGEO_LATITUDE_META',     '_wp_geo_latitude' );
	if ( !defined( 'WPGEO_LONGITUDE_META' ) )
		define( 'WPGEO_LONGITUDE_META',    '_wp_geo_longitude' );

	// recognize coordinate patterns like 44:00.813S, 149:54.04E
	$lat_pattern = "/[0-9]{1,2}\:[0-9]{2}(\.|\:)[0-9]+(S|N)/";
	$lng_pattern = "/[0-9]{1,3}\:[0-9]{2}(\.|\:)[0-9]+(E|W)/";
	
	// search for the first geo latitude
	if ( preg_match ( $lat_pattern, $post->post_content, $matches ) ) {
		if (strstr($matches[0],'S'))
			$latneg = '-';
		$matches[0] = str_replace(array('N','S',':'), array('','','.'), $matches[0]);
		$lat_arr = explode( '.', $matches[0] );
		$latitude = (int)$lat_arr[0] + ( floatval( $lat_arr[1] . '.' . $lat_arr[2] ) / 60 ) ;
		add_post_meta( $post_ID, WPGEO_LATITUDE_META, $latneg . number_format($latitude, 7), true );
		//error_log(' deg='.$lat_arr[0].' min='.$lat_arr[1].' dec='.$lat_arr[2].' -> lat='.$latitude.' id='.$post_ID);
	}
	
	// search for the first geo latitude
	if ( preg_match ( $lng_pattern, $post->post_content, $matches ) ) {
		if (strstr($matches[0],'W'))
			$lngneg = '-';
		$matches[0] = str_replace(array('E','W',':'), array('','','.'), $matches[0]);
		$lng_arr = explode( '.', $matches[0] );
		$longitude = (int)$lng_arr[0] + ( floatval( $lng_arr[1] . '.' . $lng_arr[2] ) / 60 ) ;
		add_post_meta( $post_ID, WPGEO_LONGITUDE_META, $lngneg . number_format($longitude, 7), true );
		//error_log(' deg='.$lng_arr[0].' min='.$lng_arr[1].' dec='.$lng_arr[2].' -> lng='.$longitude.' id='.$post_ID);
	}

	return true;
 }

}

add_action('wp_insert_post', 'wp_geo_detect_coords', '99' );
