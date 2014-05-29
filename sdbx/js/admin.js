function sdbx_select_page_header_type( sender ) {
	
	switch ( sender.value ) {
		case 'featured_image' :
			jQuery('.featured-image').show();
			break;
		
		default:
			jQuery('.featured-image').hide();
			break;
			
	}
}
