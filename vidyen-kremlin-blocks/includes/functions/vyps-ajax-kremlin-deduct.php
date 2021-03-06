<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*** PHP Functions to handle AJAX request***/

//NOTE: I love to copy and paste poorly written code. -Felty

// register the ajax action for authenticated users
add_action('wp_ajax_vyps_kremlin_run_deduct_action', 'vyps_kremlin_run_deduct_action');

// handle the ajax request
function vyps_kremlin_run_deduct_action()
{
  global $wpdb; // this is how you get access to the database

  //$bet_cost = intval( $_POST['bet_amount'] ); //Avoding the ajax other than letting know php should run

  $bet_cost = vidyen_kremlin_blocks_input_point_amount(); //Didn't realize sessions were server side until recently

  $incoming_pointid_get = vidyen_kremlin_blocks_input_point_id(); //Now with not hardcoding

  // Shortcode additions.
  $atts = shortcode_atts(
    array(
        'outputid' => $incoming_pointid_get,
        'outputamount' => 1,
        'refer' => 0,
        'to_user_id' => get_current_user_id(),
        'comment' => '',
        'reason' => 'Video Poker Bet',
        'btn_name' => 'videobet',
        'raw' => TRUE,
        'cost' => 1,
        'pid' => $incoming_pointid_get,
        'firstid' => $incoming_pointid_get,
        'firstamount' => $bet_cost,
    ), $atts, 'vidyen-video-poker' );

  //Deduct. I figure there is a check when need to run.
  $deduct_results = vyps_deduct_func( $atts );

  echo json_encode($deduct_results);

  wp_die(); // this is required to terminate immediately and return a proper response
}
