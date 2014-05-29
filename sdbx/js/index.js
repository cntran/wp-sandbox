function resizeFooter() {
	var browser_height = document.documentElement.clientHeight;
	var header = jQuery("#header").height();
	var main = jQuery("#main").height();
	var footer = jQuery("#footer").height();
	var site_height = header + main + footer;
	var padded_height = browser_height - site_height;
	var adjusted_footer_height = footer + padded_height;
	if ( padded_height > 0 ) {		
		jQuery("#footer").height( adjusted_footer_height );
	}
}
function scrollToElement(ele) {
    jQuery(window).scrollTop(ele.offset().top).scrollLeft(ele.offset().left);
}