<?php
/**
 * Plugin Name: salf-tracking
 * Plugin URI:  https://www.seaaireandland.com
 * Description: Container Tracking Application.
 * Version:     1.0.0
 * Author:      Nick White
 * Author URI:  https://seaaireandland.com
 */

define( 'SALF_TRACKING_VERSION', '1.0.0' );

add_shortcode( 'salf_tracking', 'salf_tracking_output' );

function salf_tracking_output() {
  // You might need to change this if its different in your react app.
  return '<div id="root"></div>';
}


add_action( 'wp_enqueue_scripts', 'enqueue_SALF_TRACKING', PHP_INT_MAX );

function enqueue_SALF_TRACKING() {
		global $post;

		if ( has_shortcode( $post->post_content, 'salf_tracking' ) ) {
			$build_directory                = plugin_dir_path( __FILE__ ) . '/build';
			$includes_directory_uri         = plugin_dir_url( __FILE__ );
			$build_directory_uri            = $includes_directory_uri . '/build';
			$asset_manifest                 = json_decode( file_get_contents( $build_directory . '/asset-manifest.json' ) );

			foreach ( $asset_manifest->entrypoints as $entrypoint ) {
				$resource_uri = $build_directory_uri . '/' . $entrypoint;
				if ( str_ends_with( $entrypoint, '.css' ) ) {
					wp_enqueue_style( 'custom_application_styles', $resource_uri, [], SALF_TRACKING_VERSION );
				}
				if ( str_ends_with( $entrypoint, '.js' ) ) {
					wp_enqueue_script( 'custom_application_script', $resource_uri, [], SALF_TRACKING_VERSION, true );
				}
			}
		}
	}