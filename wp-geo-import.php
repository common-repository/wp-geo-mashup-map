<?php
/*
Plugin Name: WP Geo - Import from Geo Mashup
Plugin URI: http://status301.net/wordpress-plugins/wp-geo-mashup-map/
Description: Import geo information from Geo Mashup database tables. Runs once on plugin activation, scanning for Geo Mashup tables and convert the geo information to WP Geo readable post meta entries. You can deactivate this plugin right after it has done its job. NOTE: It does NOT create or remove any database tables or remove old Geo Mashup data.
Version: 0.6
Author: RavanH
Author URI: http://status301.net/
*/

if( !function_exists('wpgeo_import') ) {

 function wpgeo_import( $query, $options = null ) {
	
	// Constants
	if ( !defined( 'WPGEO_LATITUDE_META' ) )
		define( 'WPGEO_LATITUDE_META',     '_wp_geo_latitude' );
	if ( !defined( 'WPGEO_LONGITUDE_META' ) )
		define( 'WPGEO_LONGITUDE_META',    '_wp_geo_longitude' );
	if ( !defined( 'WPGEO_TITLE_META' ) )
		define( 'WPGEO_TITLE_META',        '_wp_geo_title' );

	global $wpdb;

	// NOT EXISTS doesn't work in MySQL 4, use left joins instead
	$unconverted_select = "SELECT *
		FROM {$wpdb->prefix}geo_mashup_location_relationships";

	$wpdb->query( $unconverted_select );

	if ($wpdb->last_error) {
		update_option( 'wpgeo_import_log', $wpdb->last_error );
		return false;
	}

	$unconverted_data = $wpdb->last_result;
	//update_option( 'wpgeo_import_query', $unconverted_data );
	if ( $unconverted_data ) {
		foreach ( $unconverted_data as $postdata ) {
			$post_id = $postdata->object_id;
			$loc_id = $postdata->location_id;
			$location = wpgeo_import_location($loc_id);
			$result[] = $location;
			add_post_meta( $post_id, WPGEO_LATITUDE_META, $location->lat, false );
			add_post_meta( $post_id, WPGEO_LONGITUDE_META, $location->lng, false );
			add_post_meta( $post_id, WPGEO_TITLE_META, $location->saved_name, false );
		}
	}
	//update_option( 'wpgeo_import_result', $result );

 }

 function wpgeo_import_location( $object_id ) {
	global $wpdb;

	$select_string = "SELECT * 
		FROM {$wpdb->prefix}geo_mashup_locations
		WHERE id = {$object_id}";
	$location = $wpdb->get_row( $select_string );

	return $location;
 }

}

register_activation_hook( __FILE__, 'wpgeo_import' );
