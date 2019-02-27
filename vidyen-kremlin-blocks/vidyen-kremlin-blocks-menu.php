<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//adding menues
add_action('admin_menu', 'vidyen_kremlin_blocks_menu');

function vidyen_kremlin_blocks_menu()
{
    $parent_page_title = "VidYen Kremlin Blocks";
    $parent_menu_title = 'VY Kremlin Blocks';
    $capability = 'manage_options';
    $parent_menu_slug = 'vidyen_kremlin_blocks';
    $parent_function = 'vidyen_kremlin_blocks_menu_page';
    add_menu_page($parent_page_title, $parent_menu_title, $capability, $parent_menu_slug, $parent_function);
}

//The actual menu
function vidyen_kremlin_blocks_menu_page()
{
	global $wpdb;

	if (isset($_POST['input_point_id']))
	{
		//As the post is the only thing that edits data, I suppose this is the best place to the noce
		$vyps_nonce_check = $_POST['vypsnoncepost'];
		if ( ! wp_verify_nonce( $vyps_nonce_check, 'vyps-nonce' ) )
    {
				// This nonce is not valid.
				die( 'Security check' );
		}

		//I have no idea why I kept the old comments.
		$input_point_id = abs(intval($_POST['input_point_id'])); //Even though I am in the believe if an admin sql injects himself, we got bigger issues, but this has been sanitized.

		//Input amount
		$input_point_amount = abs(intval($_POST['input_point_amount']));

    //Output point from post
    $output_point_id = abs(intval($_POST['output_point_id']));

		//Should I ever use ajax for this
		$win_multi = abs(floatval($_POST['win_multi']));

    $table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	    $data = [
					'input_point_id' => $input_point_id,
          'input_point_amount' => $input_point_amount,
          'output_point_id' => $output_point_id,
          'win_multi' => $win_multi,
	    ];

			$wpdb->update($table_name_kremlin, $data, ['id' => 1]);
	    //$data_id = $wpdb->update($table_name_kremlin , $data);

	    //I forget thow this works
	    $message = "Added successfully.";
	}

	//the $wpdb stuff to find what the current name and icons are
	$table_name_kremlin = $wpdb->prefix . 'vidyen_kremlin_blocks';

	$first_row = 1; //Note sure why I'm setting this.

	//input_point_id pull
	$input_point_id_query = "SELECT input_point_id FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
	$input_point_id_query_prepared = $wpdb->prepare( $input_point_id_query, $first_row );
	$input_point_id = $wpdb->get_var( $input_point_id_query_prepared );

  //input_point_amount pull
  $input_point_amount_query = "SELECT input_point_amount FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
  $input_point_amount_query_prepared = $wpdb->prepare( $input_point_amount_query, $first_row );
  $input_point_amount = $wpdb->get_var( $input_point_amount_query_prepared );

  //output_point_amount pull
  $output_point_id_query = "SELECT output_point_id FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
  $output_point_id_query_prepared = $wpdb->prepare( $output_point_id_query, $first_row );
  $output_point_id = $wpdb->get_var( $output_point_id_query_prepared );

	//multi pull
	$win_multi_query = "SELECT win_multi FROM ". $table_name_kremlin . " WHERE id= %d"; //I'm not sure if this is resource optimal but it works. -Felty
	$win_multi_query_prepared = $wpdb->prepare( $win_multi_query, $first_row );
	$win_multi = $wpdb->get_var( $win_multi_query_prepared );


	//Just setting to 1 if nothing else I suppose, but should always be something
	if ($input_point_id == '')
	{
		$input_point_id = 1;
	}

	//Just setting to 1 if nothing else I suppose, but should always be something
	if ($win_multi == '')
	{
		$win_multi = 1;
	}

	//It's possible we don't use the VYPS logo since no points.
  $vyps_logo_url = plugins_url( 'includes/images/logo.png', __FILE__ );
	$vidyen_kremlin_blocks_logo_url = plugins_url( 'includes/images/vyvp-logo.png', __FILE__ );

	//Adding a nonce to the post
	$vyps_nonce_check = wp_create_nonce( 'vyps-nonce' );

	//Static text for the base plugin
	$vidyen_kremlin_blocks_menu_html_ouput =
	'<br><br><img src="' . $vidyen_kremlin_blocks_logo_url . '">
	<h1>VidYen Kremlin Blocks Sub-Plugin</h1>
	<p>The Video poker!</p>
	<table>
		<form method="post">
			<tr>
				<th>Input Point ID</th>
				<th>Input Point Amount</th>
        <th>Output Point Amount</th>
				<th>Win Multi</th>
				<th>Submit</th>
			</tr>
			<tr>
				<td><input type="number" name="input_point_id" type="number" id="input_point_id" min="1" step="1" value="' . $input_point_id .  '" required="true">
				<input type="hidden" name="vypsnoncepost" id="vypsnoncepost" value="'. $vyps_nonce_check . '"/></td>
				<td><input type="number" name="input_point_amount" type="number" id="input_point_amount" min="1" max="1000000" step="1" value="' . $input_point_amount . '" required="true"></td>
        <td><input type="number" name="output_point_id" type="number" id="output_point_id" min="1" step="1" value="' . $output_point_id .  '" required="true">
				<td><input type="number" name="win_multi" type="number" id="win_multi" min="0.01" max="10" step=".01" value="' . $win_multi . '" required="true"></td>
				<td><input type="submit" value="Submit"></td>
			</tr>
		</form>
	</table>
	<h2>Shortcode</h2>
	<p><b>[vidyen-kremlin-blocks]</b></p>
	<p>Simply put the shortcode <b>[vidyen-kremlin-blocks]</b> on a page and let it run with the point id from the VidYen point system.</p>
	<p>Point ID is the point ID from the VidYen System. Found in Manage Points section of VYPS</p>
	<p>Max bet is how much you want to let them bet in a single hand. Requires session refresh.</p>
	<p>Win Multi is if you want to increase rewards with 2 for 2x the winnings.</p>
	<p>NOTE: If you change this settings while a game is in play, they must close browser or tab and reload page as is server session based.</p>
	<p>Requires the <a href="https://wordpress.org/plugins/vidyen-point-system-vyps/" target="_blank">VidYen Point System</a> for any point record keeping.</p>
	<br><br><a href="https://wordpress.org/plugins/vidyen-point-system-vyps/" target="_blank"><img src="' . $vyps_logo_url . '"></a>
	';

  echo $vidyen_kremlin_blocks_menu_html_ouput;
}
