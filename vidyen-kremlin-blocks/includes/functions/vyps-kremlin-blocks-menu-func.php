<?php

/*
*
*	Kremlin blocks functions for $WPDB pulls.
* I needed to functionalize this so didn't have to copy and paste all the damn time.
*
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Input id
vidyen_kremlin_blocks_input_point_id()
{
	global $wpdb; //Just in case needs to be set

	//the $wpdb stuff to find what the current name and icons are
	$table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	//Id needs to be set to one
	$first_row = 1;

	$input_point_id_query = "SELECT input_point_id FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
	$input_point_id_query_prepared = $wpdb->prepare( $input_point_id_query, $first_row );
	$input_point_id = $wpdb->get_var( $input_point_id_query_prepared );

	//Forcing the result into a integer
	$input_point_id = intval($input_point_id);

	//Checking to see if it returned a value greater than zero
	if ($input_point_id > 0)
	{
		return $input_point_id;
	}
	//If nothing is found it returns a zero you know
}

//Input id amount
vidyen_kremlin_blocks_input_point_amount()
{
	global $wpdb; //Just in case needs to be set

	//the $wpdb stuff to find what the current name and icons are
	$table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	 //Id needs to be set to one
	$first_row = 1;

	$input_point_amount_query = "SELECT input_point_amount FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
	$input_point_amount_query_prepared = $wpdb->prepare( $input_point_amount_query, $first_row );
	$input_point_amount = $wpdb->get_var( $input_point_amount_query_prepared );

	//Forcing the result into a integer
	$input_point_amount = intval($input_point_amount);

	//Checking to see if it returned a value greater than zero
	if ($input_point_amount > 0)
	{
		return $input_point_amount;
	}
	//If nothing is found it returns a zero you know
}

//Output id
vidyen_kremlin_blocks_output_point_id()
{
	global $wpdb; //Just in case needs to be set

	//the $wpdb stuff to find what the current name and icons are
	$table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	 //Id needs to be set to one
	$first_row = 1;

	$output_point_id_query = "SELECT output_point_id FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
  $output_point_id_query_prepared = $wpdb->prepare( $output_point_id_query, $first_row );
  $output_point_id = $wpdb->get_var( $output_point_id_query_prepared );

	//Forcing the result into a integer
	$output_point_id = intval($output_point_id);

	//Checking to see if it returned a value greater than zero
	if ($output_point_id > 0)
	{
		return $output_point_id;
	}
	//If nothing is found it returns a zero you know
}

//Win Multi
vidyen_kremlin_blocks_win_multi()
{
	global $wpdb; //Just in case needs to be set

	//the $wpdb stuff to find what the current name and icons are
	$table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	 //Id needs to be set to one
	$first_row = 1;

	$win_multi_query = "SELECT win_multi FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
	$win_multi_query_prepared = $wpdb->prepare( $win_multi_query, $first_row );
	$win_multi = $wpdb->get_var( $win_multi_query_prepared );

	//Forcing the result into a integer
	$win_multi = floatval($win_multi);

	//Checking to see if it returned a value greater than zero
	if ($output_point_id > 0)
	{
		return $output_point_id; //arguably winmulti could be 0 but... well... terrible idea unless admin need to shut it off
	}
	//If nothing is found it returns a zero you know
}
