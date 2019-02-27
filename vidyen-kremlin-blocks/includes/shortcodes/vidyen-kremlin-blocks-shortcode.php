<?php

/*** Shortcode goes here like civilized nations do ***/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Shortcode function
function vidyen_kremlin_blocks_shortcode( $atts )
{

  if ( ! is_user_logged_in() )
  {
    $kremlin_html_output = ''; //This shows blank as it should. Place your own
    return($kremlin_html_output);
  }
  else // Just checking if they clicked conset or accepted a cookie prior.
  {
    $kremlin_html_output = '';
    $kremlin_html_output .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.css'>";
    $kremlin_html_output .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.jquery.js'></script>";
    $kremlin_html_output .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
    $kremlin_html_output .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';
    $kremlin_html_output .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css'>";
    $kremlin_html_output .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js'></script>";
    $kremlin_html_output .= "\n <div style='display:none' sbf_game_settings='blockrain'>".get_option('sfbg_sf_blockrain','1000:123456,5000:123456,10000:123456,20000:123456,30000:123456,40000:123456,50000:123456,70000:123456')."</div>";
    $kremlin_html_output .= "\n".'<div class="sfbg_br_game_wrap" style="min-width:400px; min-height:500px;"><center>'; //NOTE: You can change the height here.
    $kremlin_html_output .= "\n".'<div class="sfbg_br_game" style="width:250px; height:500px;"></div>';
    $kremlin_html_output .= "\n".'<div id="sfbg_br_faucet-TO-BE" style="display:none;min-width:400px;min-height:400px;"></div></center></div>';
    $kremlin_html_output .= "\n".'<script src="' . plugin_dir_url( __FILE__ ) . 'blockrain/starter.js"></script>';

    $kremlin_html_output .= '<table>
                              <tr>
                                <td>Input Balance</td>
                              </td>
                              <tr>
                                <td><div><span>'.vidyen_kremlin_blocks_input_point_icon().'</span><span id="input_blance">'.vidyen_kremlin_blocks_input_point_balance().'</span></div></td>
                              </tr>
                              <tr>
                                <td>Output Balance</td>
                              </td>
                              <tr>
                                <td><div><span>'.vidyen_kremlin_blocks_output_point_icon().'</span><span id="input_blance">'.vidyen_kremlin_blocks_output_point_balance().'</span></div></td>
                              </tr>
                            </table>';

    return($kremlin_html_output);
  }
}

//Call to add to WP
add_shortcode('vidyen-kremlin-blocks', 'vidyen_kremlin_blocks_shortcode');
