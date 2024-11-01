jQuery(document).ready(function($) {
    
    // Tab
	$(".tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });


	// Edit link beside Title
	$('.edit-doc-page').click(function(e){
		e.preventDefault();
		var showContent = $(this).attr('href');
		$('#tab-1').hide();
		$('#tab-2').show();

		var tabContainer = $(showContent).closest('div#tabs-container');

		tabContainer.children('ul').children('li:first').removeClass('current');
		tabContainer.children('ul').children('li:eq(1)').addClass('current');
		
		$(showContent).show();
		$(showContent).prev().find('i.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up')
		.closest('div.documentation-title').next('.documentation-content').addClass('showing');
		var elemTopOffset = $(showContent).offset().top;
		$(window).scrollTop(elemTopOffset-150);
		
	});


	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars()
	{
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}

	var qs = getUrlVars()["doc_fhl"];
	if(qs == 'wp_documentation'){
			
		var showContent = '#'+getUrlVars()["doc_fhl_id"];
		$('#tab-1').hide();
		$('#tab-2').show();

		var tabContainer = $(showContent).closest('div#tabs-container');

		tabContainer.children('ul').children('li:first').removeClass('current');
		tabContainer.children('ul').children('li:eq(1)').addClass('current');
		
		$(showContent).show();
		$(showContent).prev().find('i.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up')
		.closest('div.documentation-title').next('.documentation-content').addClass('showing');
		var elemTopOffset = $(showContent).offset().top;
	;
		$(window).scrollTop(elemTopOffset-150);
	}

 	// Settings Page Option Show Hide
 	$(document).on('click','.show_option',function(){
 		var inputType = $(this).attr('type');
 		
 		if(inputType){
 			var opt = $(this).closest('.wpdp-form-row').next('.wpdp-form-row');
 			switch(inputType){
 				case 'radio': 					
 					
 					if($(this).val() === "default")
 						
 						opt.fadeOut('slow');
 					else
 						opt.fadeIn('slow');
 					
 				break;

 				case 'checkbox':
 					if($(this).is(':checked'))
 						opt.fadeIn('slow');
 					else
 						opt.fadeOut('slow');

 				break;

 				default:
 				break;
 			}
 		}
 		
 	});

	// Documentation Content Accordian
	$(document).on('click','.documentation-title h3',function(e){
		e.preventDefault();				
		var content = $(this).parent().next('.documentation-content');
		content.slideToggle();

		if(content.hasClass('showing')){
			content.removeClass('showing');	
			$(this).children('i').addClass('dashicons-arrow-down').removeClass('dashicons-arrow-up');				
		}
		else{
			content.addClass('showing');
			$(this).children('i').addClass('dashicons-arrow-up').removeClass('dashicons-arrow-down');
		}

	});
		

});