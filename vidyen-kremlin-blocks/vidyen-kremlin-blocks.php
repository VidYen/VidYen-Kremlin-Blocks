<?php
/*
 * Plugin Name: VidYen Kremlin Blocks
 * Plugin URI: http://vidyen.com
 * Description: A Tetris like game for the VidYen Point System
 * Author: VidYen, LLC
 * Version: 0.0.15
 * Author URI: http://vidyen.com
 * License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

register_activation_hook(__FILE__, 'vidyen_kremlin_blocks_sql_install');

//Install the SQL tables for VYPS.
function vidyen_kremlin_blocks_sql_install()
{
    global $wpdb;

		//I have no clue why this is needed. I should learn, but I wasn't the original author. -Felty
		$charset_collate = $wpdb->get_charset_collate();

		//NOTE: I have the mind to make mediumint to int, but I wonder if you get 8 million log transactios that you should consider another solution than VYPS.

		//vidyen_kremlin_blocks table creation
    $table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

    $sql = "CREATE TABLE {$table_name_kremlin} (
  		id mediumint(9) NOT NULL AUTO_INCREMENT,
  		input_point_id mediumint(9) NOT NULL,
      input_point_amount mediumint(9) NOT NULL,
      output_point_id mediumint(9) NOT NULL,
  		win_multi float(53) NOT NULL,
  		PRIMARY KEY  (id)
    ) {$charset_collate};";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php'); //I never did investigate why the original outsource dev used this.

    dbDelta($sql);

		//Default data
		$data_insert = [
				'input_point_id' => 1,
        'input_point_amount' => 1,
				'output_point_id' => 2,
				'win_multi' => 1,
		];

		$wpdb->insert($table_name_kremlin, $data_insert);
}

/*** Includes ***/

//Menu Include
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'vidyen-kremlin-blocks-menu.php'); //Menu include

//Shortcode include
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'includes/shortcodes/vidyen-kremlin-blocks-shortcode.php'); //Shortcode includes

//Functions include (I am not sure if this will cause problems)
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'includes/functions/vyps-kremlin-blocks-menu-func.php'); //function for menus
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'includes/functions/vyps-kremlin-blocks-balance-func.php'); //function for balance (comments may be redudant)
include_once( dirname(__FILE__) . DIRECTORY_SEPARATOR .  'includes/functions/vyps-ajax-kremlin-deduct.php'); //First ajax for deduct
