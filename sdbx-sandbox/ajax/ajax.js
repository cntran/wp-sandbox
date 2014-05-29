function sdbx_ajax_call( vars ) {	

	jQuery.post(	
		SdbxAjax.ajaxurl,
		{
			action : 'sdbx-ajax-call',			
			post_var : vars['post_var'],
			ajaxNonce : SdbxAjax.ajaxNonce
		},
		function( response ) {
			alert( response.success );
		}	
	)	
}
