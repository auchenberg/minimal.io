<?php
/*
Plugin Name: Automatic Post Scheduler
Plugin URI: http://tudorsandu.ro/
Description: A plugin that automatically schedules posts depending on a min/max threshold and the last post's publish date and time. 
Version: 0.9.1
Author: Tudor Sandu
Author URI: http://tudorsandu.ro/
License: GPL2
*/

//ini_set('display_errors', 1);
//error_reporting(E_ALL);


add_action( 'init', 'aps_init' );
function aps_init() {
	// Attach checkbox to publish box on edit post page
	add_action( 'post_submitbox_misc_actions', 'aps_publish_box' );
	
	// Actually delay/schedule post publishing
	add_filter( 'wp_insert_post_data', 'aps_check_and_schedule', 10, 2 );
}

/**
 * Filter handler for post status. Sets post_status and post_date, if needed
 * 
 * @since 0.9
 * @uses aps_get_nearest_open_time()
 * 
 * @param object $data Post object
 * @param object $postarr Raw HTTP POST data
 * @return object Filtered post object
 */
function aps_check_and_schedule( $data, $postarr ) {
	// don't autoschedule pages
	if( $data['post_type'] == 'page' )
		return $data;
	
	// check if scheduling should occur
	if( !( isset( $postarr['aps_schedule_post'] ) && $postarr['aps_schedule_post'] ) )
		return $data;
	
	// post_status is definately set, since we're using sanitized data. No check necessary
	$post_status = $data['post_status'];
	
	// only invoke upon publishing
	if ( $post_status != 'publish' )
		return $data;
	
	$time_of_post = aps_get_nearest_open_time();
	
	// publish immediately?
	if ( $time_of_post < current_time( 'timestamp' ) )
		return $data;
	
	$data['post_status'] = 'future';
	$time_of_post_gmt = $time_of_post - ( get_option( 'gmt_offset' ) * 3600 );
	$data['post_date'] = date( 'Y-m-d H:i:s', $time_of_post );
	$data['post_date_gmt'] = date( 'Y-m-d H:i:s', $time_of_post_gmt );
	
	return $data;
}

/**
 * Adds schedule checkbox on post page, in publish box (aka post submit box)
 * 
 * @since 0.9.1
 */
function aps_publish_box() {
	global $post;
	
	// only display for authorized users
	if ( !current_user_can( 'publish_posts' ) || in_array( $post->post_status, array( 'publish', 'future' ) ) )
		return;
	
	// don't display for pages
	if( $post->post_type == 'page' )
		return;
?>
<div class="misc-pub-section" id="aps_schedule_post">
	<label><input type="checkbox" id="aps_schedule_post" name="aps_schedule_post" <?php echo ( aps_current_user_disable_default() ) ? '' : 'checked="checked"'; ?>/> <?php _e( 'Schedule as soon as posible', 'autoscheduler' ); ?></label>
</div>
<?php
}

/**
 * Gets timestamp of a valid timestamp for a new public post based on the furthest published/scheduled post timestamp and interval options
 * 
 * @since 0.9
 * @uses aps_get_interval()
 * 
 * @return int Timestamp
 * 
 */
function aps_get_nearest_open_time() {
	// get furthest scheduled post
	$post = get_posts( array(
		'numberposts' => 1,
		'post_status' => 'future'
	) );
	// if no scheduled posts, get furthest published post
	if( empty( $post ) ) {
		$post = array_pop( get_posts( 'numberposts=1' ) );
	} else {
		$post = array_pop( $post );
	}
	
	return strtotime( $post->post_date ) + aps_get_interval();
}

/**
 * Returns a random interval between the min and max constraints
 * 
 * @since 0.9
 * 
 * @return integer Number of seconds 
 */
function aps_get_interval() {
	$interval = aps_get_interval_boundaries();
	
	return rand( $interval['min'], $interval['max'] );
}

require_once( 'settings.php' );