(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */	


jQuery(document).ready(function($){

	
	removeStyle();

	function removeStyle(){
		if($(window).width() <=640 ){
			$('.ws-wp-docs-content').removeAttr('style');
		}
	}
	
	$(window).on("resize scroll",function(e){	
		removeStyle();
	});

	// Slide Toggle for mobile menu
	$(document).on('click','.ws-navbar-toggle',function(e){
		$('div.top-menu').slideToggle();
	});

	$(document).on('click','.ws-visible div.top-menu a',function(e){
		$('.ws-visible div.top-menu').slideToggle();
	});


	 // Copy Text
	$(document).on('click','#copyButton',function(e){
		e.preventDefault();
		var permalinkVal= $(location).attr('href');
		var confirmVal = prompt("'Cltr + C' to copy ", permalinkVal);
	});


	$(document).on('click','a.copy-title-link',function(e){
		e.preventDefault();
		var permalinkVal= $(this).siblings('input.doc-hash-link').val();
		var confirmVal = prompt("'Cltr + C' to copy ", permalinkVal);
	});

	// Document Print
	$(document).on('click','#wp-documentation-single-print',function(e){
		e.preventDefault(); 			
		$('.ws-wp-docs').printElement();
	});


	// Show / Hide Suggestion Box
	$('.ws-form-show, .ws-form-close').click(function(e){
		e.preventDefault();
		$('.ws-feedback').slideToggle();
	});


	function wp_doc_scroll_bar(){
		$('#wp-doc-toc-list').slimScroll({
			color: wp_documentation.theme.primary_color,
			size: '7px',
			height: '500px',
			opacity:1,
			borderRadius:'0px',
			distance: '-1px',
			railVisible: true,
			railColor: '#ccc',
			railOpacity: 1,
			wheelStep: 10,
		});
	}

	$(window).on("load",function(){
		wp_doc_scroll_bar();
	});

	
});
	
})( jQuery );

	