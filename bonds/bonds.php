<?php 
//SBF_DB_ stuff
//show bonds_box - user,admin
//fetch bonds - admin
//add bond  - admin
//delete bond - admin
//redeem  bond code - user,admin


add_action('wp_ajax_SBF_DB_code_action', 'SBF_DB_code_redeem_callback'); //admin
add_action('wp_ajax_nopriv_SBF_DB_code_action', 'SBF_DB_code_redeem_callback'); //user

add_action('wp_ajax_SBF_DB_code_manage_action', 'SBF_DB_code_manage_callback'); //admin only
//ajax, params by POST



function SBF_DB_code_redeem_callback() //ajax. returns message
{
	$btc_address =  trim($_POST['R_BTC_ADDRESS']) ;
	$code = trim($_POST['R_CODE']);
	$api_key = get_option('sfbg_bonds_api_key','');

	$code_index = -1;
	$amount = -1;
	$bonds_array = get_transient( 'SBF_DB_BONDS' );

	if( (strlen($code) == 0) || (strlen($btc_address) == 0) )
	{
		$ret = __( 'Bond code and recipient address may not be empty', 'simple-bitcoin-faucets' );
		echo($ret);
		wp_die();
	}	
	
	for($i=0; $i<count($bonds_array); $i++)
	{	
		$item = $bonds_array[$i];
		if( ($item['code'] == $code) && (!isset($item['redeemed'])) )
		{
			$code_index = $i;
			$amount = $item['amount'];
			break;
		}
	}		
		
	if($code_index == -1)
	{
		$ret = __( 'There is no outstanding bond with the code', 'simple-bitcoin-faucets' ) . ' ' . $code;
		echo($ret);
		wp_die();
	}
//if we here we did find the bond code 	


	$fields = array(
		'api_key'=> $api_key,
		'to'=>$btc_address,
		'amount'=>$amount
	);
	
	$response = wp_remote_post( 'https://cryptoo.me/api/v1/send', array(
		'method' => 'POST', 
		'body' => $fields)  );
	$resp_body = wp_remote_retrieve_body( $response );
	$resp_code = wp_remote_retrieve_response_code( $response );

	if($resp_code != 200)
	{
		$ret = __( 'Unknown error', 'simple-bitcoin-faucets' );
		echo($ret);
		wp_die();	
	}
	
//if we here, request succeeded	
	$body = json_decode($resp_body);
	if($body->status == 200) //we ok
	{
		$msg = 'OK'.__( 'Bond', 'simple-bitcoin-faucets' ) . ' ' . __( 'has been redeemed', 'simple-bitcoin-faucets' ) . ',<br>'; 
		$msg .= $amount . ' ' . __( 'satoshi', 'simple-bitcoin-faucets' ) . ' ' . __( 'sent to', 'simple-bitcoin-faucets' ) . ' '; 
		$msg .= '<a target=_blank href="' . __( 'https://cryptoo.me/check/', 'simple-bitcoin-faucets' ) .$btc_address.'">' . $btc_address . '</a>.';
		$bonds_array[$code_index]['redeemed'] = time();
		$bonds_array[$code_index]['receiver'] = $btc_address;
		$bonds_array[$code_index]['IP'] = $_SERVER['REMOTE_ADDR'];
		set_transient( 'SBF_DB_BONDS', $bonds_array, 0 ); //0 - never expires	
	}
	else
	{
		$msg = $body->message;
	}
//if we here - it was not 200, error happened
	echo($msg);
	
	wp_die();
}

function SBF_DB_show_bond_item($item, $date_time_str,$div_extra_style='') // ajax. show, add, delete. returns 'OK', or error message
{
	$c = '';
	$ret = '';
	$c .= "<b>Code:</b> " . $item['code'] . "<br>\n";
	$c .= "<b>Amount:</b> " . $item['amount'] . " satoshi<br>\n";
	$c .= "<b>Created:</b> " . strftime( $date_time_str , $item['created']) . "<br>\n";
	if(isset($item['redeemed']))
	{
		$c .= "<b>Redeemed:</b> ".  strftime( $date_time_str , $item['redeemed']) . "<br>\n";
		$c .= "<b>To:</b> " . $item['receiver'] . " (" . $item['IP'] . ")<br>\n";
	}
	$ret .= "<div id='SBF_DB_BOND_DIV_".$item['code']."'  class='SBF_DB_BOND_DIV' style='border:1px solid black;width:90%;padding:5px;margin:5px;$div_extra_style'>\n";
	$ret .= "<button type='button' onclick='SBF_DB_do_delete_bond(\"".$item['code']."\");'style='float:right;' class='delete_bond' id='delete_bond_".$item['code']."' >". __( 'Delete Bond', 'simple-bitcoin-faucets' ) ."</button>\n";
	$ret .= $c."\n";
	$ret .= "</div>\n";
	return $ret;
}


function SBF_DB_code_manage_callback() // ajax. show, add, delete. returns 'OK', or error message
{
	if( !is_admin() ){ //manage only via admin interface
		wp_die();
	}
	
	$bond_command =  $_POST['B_COMMAND'] ;
	$bond_param =  $_POST['B_PARAM'] ;
	
	$bonds_array = get_transient( 'SBF_DB_BONDS' );
//	$date_format = get_option( 'date_format' );
//	$time_format = get_option( 'time_format' );	
//	$date_time_str = $date_format . ' ' . $time_format;
//some day http://php.net/manual/en/function.strftime.php#96424

	$date_time_str = '%d %b %Y %H:%M:%S' ; //$date_format . ' ' . $time_format;

	if($bond_command == 'ADD')
	{
		if(!is_array($bonds_array))
		{	
			$bonds_array = array();
		}
		$new_record = array();
		$new_record['created'] = time();	
		$new_record['code'] = md5(rand());	//go guess..	
		$new_record['amount'] = $bond_param;
		array_unshift($bonds_array,$new_record); //insert first
		set_transient( 'SBF_DB_BONDS', $bonds_array, 0 ); //0 - never expires	
		$time_str = strftime( $date_time_str , $new_record['created']);
		echo(SBF_DB_show_bond_item($new_record, $date_time_str,'display:none;'));
	}
	if($bond_command == 'DELETE') //$bond_param is bond code here
	{
		for($i=0; $i<count($bonds_array); $i++)
		{	
			$item = $bonds_array[$i];
			if($item['code'] == $bond_param)
			{
				array_splice($bonds_array, $i, 1);
			}
		}		
		set_transient( 'SBF_DB_BONDS', $bonds_array, 0 ); //0 - never expires	
		echo("SBF_DB_BOND_DIV_" . $bond_param);
	}	
	if($bond_command == 'SHOW')
	{
		if(!is_array($bonds_array))
		{	
			_e( 'No Bonds yet', 'simple-bitcoin-faucets' );
		}	
		else
		{
			$ret = '';
			for($i=0; $i<count($bonds_array); $i++)
			{	
				$item = $bonds_array[$i];
				$ret .= SBF_DB_show_bond_item($item, $date_time_str);
			}
			echo($ret);
		}
	}	
	wp_die();
}

function SBF_DB_render_bonds_box()
{
	static $i = 0; //so we can use several shortcodes in one page. fool-proof 
	$i++;
	$ret = "
	<table id='SBF_DB_table_$i'  style='width:500px;'>
		<tr>
			<td style='text-align:right;'>".__( 'Bond Code:', 'simple-bitcoin-faucets' )."</td>
			<td style='text-align:left;'><input id='SBF_DB_redeem_code_$i' type='text' style='width:100%;'></input></td>
		</tr>
		<tr>
			<td style='text-align:right;'>".__( 'Bitcoin Address:', 'simple-bitcoin-faucets' )."</td>
			<td style='text-align:left;'><input id='SBF_DB_redeem_BTC_address_$i' type='text' style='width:100%;'></input></td>
		</tr>
		<tr>
			<td colspan=2 style='text-align:center;'>
				<div id='SBF_DB_msg_$i' style='display:none;margin:5px;font-weight:bold;'></div>
				<button type='button' id='SBF_DB_use_code_$i' onclick='SBF_DB_do_redeem_$i();return false'>".__( 'Redeem Bond', 'simple-bitcoin-faucets' )."</button>
			</td>
		</tr>	
	</table>
	<script>
		var lsA_$i = localStorage.getItem('BTC_address');
		if(lsA_$i != null){
			jQuery('#SBF_DB_redeem_BTC_address_$i').val(lsA_$i);
		}
		jQuery(document).on('change keyup paste', '#SBF_DB_redeem_BTC_address_$i , #SBF_DB_redeem_code_$i', function (event) {
			if(event.handled !== true){ // This will prevent event triggering more then once
				event.handled = true;
			}else{
				return;
			}
			var c = jQuery('#SBF_DB_redeem_code_$i').val();
			var a = jQuery('#SBF_DB_redeem_BTC_address_$i').val();
			if((a.length > 10) && (c.length > 10) ){
				 jQuery('#SBF_DB_use_code_$i').prop('disabled', false);
			}
		})

		SBF_DB_do_redeem_$i = function(){
			jQuery('#SBF_DB_msg_$i').slideUp('fast');
			var data = {
				action: 'SBF_DB_code_action',
				R_BTC_ADDRESS: jQuery('#SBF_DB_redeem_BTC_address_$i').val(),
				R_CODE: jQuery('#SBF_DB_redeem_code_$i').val()
			};

			jQuery.post( '".get_site_url()."/wp-admin/admin-ajax.php', data, function(response) {
					if(response.indexOf('OK') == 0)	{
						response = response.substr(2);
						jQuery('#SBF_DB_redeem_code_$i').val('');
						jQuery('#SBF_DB_use_code_$i').prop('disabled', true);
						localStorage.setItem('BTC_address',jQuery('#SBF_DB_redeem_BTC_address_$i').val());
						if(typeof bonds_update_list === 'function'){
							bonds_update_list();
						}
					}
					jQuery('#SBF_DB_msg_$i').html(response).slideDown('fast');	
			});			
			return false;
		}
	</script>	
	";
	return $ret;
}

