<?php

/*** Shortcode goes here like civilized nations do ***/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Shortcode function
function vidyen_russian_blocks_shortcode( $atts )
{
  $ret = '';
  $ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.css'>";
  $ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "blockrain/blockrain.jquery.js'></script>";
  $ret .= "\n".'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
  $ret .= "\n".'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';
  $ret .= "\n <link rel='stylesheet' href='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.css'>";
  $ret .= "\n <script src='" . plugin_dir_url( __FILE__ ) . "sbf_lib/sbf.js'></script>";
  $ret .= "\n <div style='display:none' sbf_game_settings='blockrain'>".get_option('sfbg_sf_blockrain','1000:123456,5000:123456,10000:123456,20000:123456,30000:123456,40000:123456,50000:123456,70000:123456')."</div>";
  $ret .= "\n <script>";
  $ret .= $this->blockrain_shortcode_localize();
  $ret .= "\n </script>";
  $ret .= "\n".'<div class="sfbg_br_game_wrap" style="min-width:400px; min-height:500px;"><center>';
  $ret .= "\n".'<div class="sfbg_br_game" style="width:250px; height:500px;"></div>';
  $ret .= "\n".'<div id="sfbg_br_faucet-TO-BE" style="display:none;min-width:400px;min-height:400px;"></div></center></div>';
  $ret .= "\n".'<script src="' . plugin_dir_url( __FILE__ ) . 'blockrain/starter.js"></script>';

  return($ret);
}

//Call to add to WP
add_shortcode('vidyen-russian-blocks', 'vidyen_russian_blocks_shortcode');
