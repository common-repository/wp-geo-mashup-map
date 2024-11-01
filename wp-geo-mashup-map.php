<?php
/*
Plugin Name: WP Geo - Mashup Shortcode Compat
Plugin URI: http://status301.net/wordpress-plugins/wp-geo-mashup-map/
Description: Extends WP Geo plugin to allow you to continue using the Geo Mashup shortcodes [geo_mashup_map] and [geo_mashup_show_on_map_link] or the old shortcode [wp_geo_mashup] that are in your posts and pages already where you want the map to appear.
Version: 0.6
Author: RavanH
Author URI: http://status301.net/
*/

if( !function_exists('wp_geo_mashup_map_init') ) {
 function wp_geo_mashup_map_init() {
	if (function_exists('shortcode_wpgeo_mashup')) {
		add_shortcode( 'wp_geo_mashup', 'shortcode_wpgeo_mashup' );
		// Geo Mashup compatibility
		add_shortcode( 'geo_mashup_map', 'shortcode_wpgeo_mashup' );
	} else {
		add_shortcode( 'wp_geo_mashup', 'shortcode_wpgeo_mashup_map' );
		// Geo Mashup compatibility
		add_shortcode( 'geo_mashup_map', 'shortcode_wpgeo_mashup_map' );
	}
	add_shortcode( 'geo_mashup_show_on_map_link', 'shortcode_wpgeo_map_link' );
 }

 function get_wpgeo_mashup_map( $query, $options = null ) {
	
	global $wpgeo_map_id;
	$wpgeo_map_id++;
	$id = 'wpgeo_map_id_' . $wpgeo_map_id;

	$wp_geo_options = get_option('wp_geo_options');
	if (is_archive())
		global $posts;
	else
		$posts = get_posts( $query );
	
	$output = '
		<div id="' . $id . '" class="wpgeo_map" style="width:' . $query['width'] . '; height:' . $query['height'] . ';float:' . $query['align'] . '"></div>
		<script type="text/javascript">
		<!--
		jQuery(window).load( function() {
			if ( GBrowserIsCompatible() ) {
				var bounds = new GLatLngBounds();
				map_' . $id . ' = new GMap2(document.getElementById("' . $id . '"));
				map_' . $id . '.setUIToDefault();
				map_' . $id . '.setMapType(' . $query['type'] . ');
				map_' . $id . '.addMapType(G_SATELLITE_3D_MAP);
				';
	if( $query['overview'] == 'Y' )
		$output .= 'map_' . $id . '.addControl(new GOverviewMapControl());
				';

	$latest_post = true;
	if( $posts ) : foreach ( $posts as $post ) {
		$latitude = get_post_meta($post->ID, WPGEO_LATITUDE_META, true);
		$longitude = get_post_meta($post->ID, WPGEO_LONGITUDE_META, true);
		if ( is_numeric($latitude) && is_numeric($longitude) ) {
			if ($latest_post) {
				$icon = apply_filters( 'wpgeo_marker_icon', 'wpgeo_icon_'.$query['lastmarker'], $post, 'wpgeo_map' );
				$latest_post = false;
			} else {
				$icon = apply_filters( 'wpgeo_marker_icon', 'wpgeo_icon_'.$query['markers'], $post, 'wpgeo_map' );
			}
			$polyline_coords_js .= 'new GLatLng(' . $latitude . ', ' . $longitude . '),';
			$title = get_wpgeo_title($post->ID, false);
			if ($title == null)
				$title = get_the_title($post->ID);
			$output .= '
				var center = new GLatLng(' . $latitude . ',' . $longitude . ');
				var marker = new wpgeo_createMarker2(map_' . $id . ', center, ' . $icon . ', \'' . addslashes( $title ) . '\', \'' . get_permalink($post->ID) . '\');
				bounds.extend(center);
				';
		}
	}
	if ( $query['polylines'] == 'Y' ) {
					$output .= 'map_' . $id . '.addOverlay(wpgeo_createPolyline([' . $polyline_coords_js . '], "' . $query['polyline_colour'] . '", 2, 0.50));';
				}
	$output .= '
				zoom = map_' . $id . '.getBoundsZoomLevel(bounds);
				map_' . $id . '.setCenter(bounds.getCenter(), zoom);';
	else : 
	$output .= '
				map_' . $id . '.setCenter(new GLatLng(' . $wp_geo_options['default_map_latitude'] . ', ' . $wp_geo_options['default_map_longitude'] . '), ' . $wp_geo_options['default_map_zoom'] . ');';
	endif;
	$output .= '		}
		} );
		-->
		</script>
		';
	
	return $output;
	
 }

}

if( !function_exists('shortcode_wpgeo_mashup_map') ) {
 function shortcode_wpgeo_mashup_map( $atts, $content = null ) {
	global $wpgeo;

	$wp_geo_options = get_option( 'wp_geo_options' );

	// Default attributes
	$map_atts = array(
		'numberposts' => -1,
		'category_name' => '',
		'cat' => '',
		'post_type' => 'any',
		'post_status' => 'publish',
		'orderby' => 'post_date',
		'order' => 'DESC',
		'width' => $wp_geo_options['default_map_width'],
		'height' => $wp_geo_options['default_map_height'],
		'align' => 'none',
		'type' => $wp_geo_options['google_map_type'],
		'polylines' => $wp_geo_options['show_polylines'],
		'polyline_colour' => $wp_geo_options['polyline_colour'],
		'markers' => 'small',
		'lastmarker' => 'large',
		'overview' => 'Y'
	);
	$atts = shortcode_atts( $map_atts, $atts );

	if ( !is_feed() && isset($wpgeo) && $wpgeo->show_maps() && $wpgeo->checkGoogleAPIKey() ) {
		return get_wpgeo_mashup_map( $atts );
	} else {
		return '';	
	}
 }
}

if (!is_admin())
	add_filter('widget_text', 'do_shortcode', 11);

add_action('init', 'wp_geo_mashup_map_init', 99 );

