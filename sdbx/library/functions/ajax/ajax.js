/*function sdbx_ajax_call( vars ) {	
			
	jQuery.post(	
		SdbxAjax.ajaxurl,
		{
			action : 'sdbx-ajax-call',			
			post_var : vars['post_var'],
			ajaxNonce : SdbxAjax.ajaxNonce
		},
		function( response ) {
			alert( response );
		}	
	)	
}*/

function sdbx_update_option( vars ) {
	jQuery.post(	
		SdbxAjax.ajaxurl,
		{
			action : 'sdbx-update-option',			
			option_name : vars['option_name'],	
			option_value : vars['option_value'],
			ajaxNonce : SdbxAjax.ajaxNonce
		},
		function( response ) {
			//alert( response );
		}	
	)	
}
