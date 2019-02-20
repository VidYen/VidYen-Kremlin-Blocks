<?php
?>
<script>
<?php echo($this->main_js_shortcode_localize()); ?>
</script>
<?php 
//echo($this->referral_shortcode_localize()); 
//echo($this->referral_shortcode_top()) 
?> 

<hr>
<?php _e( 'Satoshi Bonds can be used as prizes, gifts, or sold as digital goods in stores/marketplaces' ); ?>.
<?php _e( 'Person who has the Bond Code can redeem it for satoshi on the website issued the Bond', 'simple-bitcoin-faucets' ); ?>.
<br>
<?php _e( 'Use Shortcode', 'simple-bitcoin-faucets' ); ?>  <code>[SBFG_BOND_REDEEM]</code>
 <?php _e( 'where you want the Bond Redeem form to appear', 'simple-bitcoin-faucets' ); ?> , 
 <?php _e( 'or', 'simple-bitcoin-faucets' ); ?>
 <a href="#" onclick="window.open(top.location.href+'&shortcode=[SBFG_BOND_REDEEM]&name=Redeem%Satoshi%Bonds');return false;"><?php _e('Generate test Page', 'simple-bitcoin-faucets' ); ?></a>


<br><a href="javascript:document.getElementById('bonds_hints').scrollIntoView();"><b><?php _e( 'Scroll to Hints', 'simple-bitcoin-faucets' ); ?></b></a>


<hr>
		
<div id="sfbg_referral_settings" style="float: left; padding:10px;">	
	
	<?php _e( 'Cryptoo.me <b>API Key</b>', 'simple-bitcoin-faucets' ); ?>:
	<input type="text" id='sfbg_bonds_api_key' name='sfbg_bonds_api_key' maxlength="40" 
	value='<?php echo esc_attr( get_option('sfbg_bonds_api_key','') ); ?>' >
	</input> 
	<div class='bonds_comments'>
		<?php _e( 'Get the API Key for free at', 'simple-bitcoin-faucets' ); ?> 
		<a target=_blank href='<?php _e( 'https://cryptoo.me/applications/', 'simple-bitcoin-faucets' ); ?>'  >cryptoo.me</a>.
	</div>

	<div class="vp_trof_must_save" style='background:red;color:yellow;display:none'><?php _e( 'Please save', 'simple-bitcoin-faucets' ); ?></div>
	<?php submit_button(); ?>
</div>


<div id="sfbg_bonds_example" style="float: left; padding:10px; max-width:50%; padding-left:100px;">

<?php _e( 'Redeem Bonds form example', 'simple-bitcoin-faucets' ); ?>:
<br><br>
<div style='border:1px solid gray;padding:5px;margin:5px;'><?php echo(SBF_DB_render_bonds_box()); ?></div>

</div>

<div id='bonds_hints' style="clear:both;">
<hr><hr>
<?php _e( 'New Bond for', 'simple-bitcoin-faucets' )  ?>
<input  id='SBF_DB_bond_ammount' type='text'></input> <?php _e( 'satoshi', 'simple-bitcoin-faucets' )  ?>
&nbsp;&nbsp;&nbsp;<button type='button' disabled id='SBF_DB_create_code' onclick='this.disabled=true; SBF_DB_do_add_bond();return false'><?php _e( 'Create Bond', 'simple-bitcoin-faucets' ) ?></button>
<script>
		SBF_DB_do_add_bond = function(){
			var data = {
				action: 'SBF_DB_code_manage_action',
				B_COMMAND: 'ADD',
				B_PARAM: jQuery('#SBF_DB_bond_ammount').val(),
			};

			jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
				jQuery('#bonds_list_wrap').html(response + jQuery('#bonds_list_wrap').html());
				jQuery('.SBF_DB_BOND_DIV').slideDown("fast",function(){jQuery('#SBF_DB_create_code').prop('disabled',false);});
			});			
			return false;
		}
		
		SBF_DB_do_delete_bond = function(bond_code){
			var conv_text1='<?php _e( 'Bond', 'simple-bitcoin-faucets' )  ?>';
			var conv_text2='<?php _e( 'is going to be permanently deleted!', 'simple-bitcoin-faucets' )  ?>';
			var conv_text3='<?php _e( 'Are you sure?', 'simple-bitcoin-faucets' )  ?>';
			if(!confirm(conv_text1 + " " + bond_code + " " + conv_text2 + "\n\n" + conv_text3 ))
			{
				return;
			}
			var data = {
				action: 'SBF_DB_code_manage_action',
				B_COMMAND: 'DELETE',
				B_PARAM: bond_code,
			};		
			jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
				jQuery('#' + response).slideUp("fast",function(){jQuery('#' + response).remove();});
			});			
		}

</script>
</div>

<hr><hr>
<h1><?php _e( 'Bonds', 'simple-bitcoin-faucets' ); ?></h1>

<div id='bonds_list_wrap'>
</div>



<div id='bonds_hints' style="clear:both;">
<hr>
<b><?php _e( 'Hints', 'simple-bitcoin-faucets' ); ?>:</b><br>
&nbsp;-&nbsp;
<?php _e('Reputation is everything. Always make sure you have enough funds to cover all outstanding bonds', 'simple-bitcoin-faucets'); ?>.
<hr>&nbsp;-&nbsp;
<?php _e('Same API Key can be used for registrations and page visits. However, using seperate Keys will simplify the track of performance, and increase the exposure of your website in the <a href="https://cryptoo.me/rotator/">App List</a> ', 'simple-bitcoin-faucets' ); ?>.
 <?php _e('History of the payments is available in the <a href="https://cryptoo.me/applications/">Application Manager</a> under "Payouts" link of your Application', 'simple-bitcoin-faucets' ); ?>.

<hr>

</div>

 
<script>

function bonds_tab_activated()
{
//do nothing for now
}

function bonds_check_api_key(selector)
{
	var o = jQuery(selector);
	var b_pref = '0';
	if(o.val().length < 40)
	{
		b_pref = '1';
	}
	o.css('border',b_pref + 'px solid red');
}

function bonds_update_list()
{
	var data = {
			action: 'SBF_DB_code_manage_action',
			B_COMMAND: 'SHOW',
			B_PARAM: '',
		};

	jQuery.post( '<?php echo(get_site_url()); ?>/wp-admin/admin-ajax.php', data, function(response) {
		jQuery('#bonds_list_wrap').html(response);
	});	
}

jQuery(document).ready(function () {
	bonds_check_api_key('#sfbg_bonds_api_key');


	jQuery("#sfbg_bonds_api_key").on('change keyup paste', function () {
		referral_check_api_key('#sfbg_bonds_api_key');
	});
	
	
	jQuery("#SBF_DB_bond_ammount").on('change keyup paste', function () {
		var s = jQuery(this).val();
		var n = s.replace(/[^0-9]/g,'');
		jQuery(this).val(n);	
		if(n.length == 0)
		{
			jQuery(this).val('0');
		}
		jQuery(this).val(parseInt(jQuery(this).val()));	
		jQuery('#SBF_DB_create_code').prop('disabled', (jQuery(this).val() == 0) );
	});
	
	bonds_update_list();

});

</script>		
<?php

?>