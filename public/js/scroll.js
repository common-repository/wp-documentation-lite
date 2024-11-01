(function ( $ ) {
 
    $.fn.wen_scroll = function(defaults) {
        var settings = $.extend({            
            offsetTop 		: 30,
            defaultOffset 	: 100,
            smoothScroll	: true,
            backToTop		: true
        }, 
        defaults );

    	var offset_top 		= settings.offsetTop;
		var default_offset 	= settings.defaultOffset;

		// back to top button
		if(settings.backToTop){
			
			$('body').append('<a class="cd-top" href="#0" >Top</a>');

			var backToTopOffset = ($('.wp-doc-theme').offset().top) - offset_top;
			var offset = backToTopOffset + 300;

			$back_to_top = $('.cd-top');
			//smooth scroll to top
			$back_to_top.on('click', function(event){
				event.preventDefault();
				$('body,html').animate({
				  scrollTop: backToTopOffset ,
				  }, scroll_top_duration
				);
			}); 
		}

		top_nav_height();
		// nav menu Scroll
		function top_nav_height(){
			//console.log($('.top-menu').height() + " " + $(window).height());
			if($('.top-menu').height() > ( $( window ).height() - offset_top - default_offset ) ){
				$('.top-menu').height($( window ).height() - offset_top - default_offset).css({'overflow':'scroll'});
			}
		}
		

		$(window).on("resize scroll",function(e){
			wsOnScroll(offset_top,default_offset);
			//console.log(offset_top);
			var navToShow = $('.nav-inner-wrapper'); //  nav wrap
			var navOffsetTop = navToShow.offset().top;
			fixNavPos(navOffsetTop,offset_top,navToShow);
		});

		// scroll Event
        $(document).on('scroll', window, function () {        	
	        
			
			// scroll document
			wsOnScroll(offset_top,default_offset);
			if(settings.backToTop)
				backToTop(offset);

			var navToShow = $('.nav-inner-wrapper'); //  nav wrap
			var navOffsetTop = navToShow.offset().top;
			fixNavPos(navOffsetTop,offset_top,navToShow);
	    });
		
		
	    // Scroll on click
		$('#document-toc a[href*="#"]:not([href="#"])').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			  var target = $(this.hash);
			  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			  if (target.length) {
				if(settings.smoothScroll){
					$('html, body').animate({
				      scrollTop: (target.offset().top-offset_top-default_offset+1)
				    }, 1000);
				}
				else{
					$(window).scrollTop(target.offset().top-offset_top-default_offset+1);
				}
			    return false;
			  }
			}
		});

		$('.ws-wp-docs-categories span.toc-has-child').on('click',function(e){
			e.preventDefault();
			var childElement = $(this).closest('li').children('ul');
			
			if(childElement.html()){
				var iconElement = $(this);
				if(iconElement.hasClass('dashicons-arrow-right')){
					
					iconElement.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
					childElement.slideDown();
				}

				else{				
					iconElement.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
					childElement.slideUp();
				}			
			}
			top_nav_height();
		});

		function wsOnScroll(offset_top,default_offset){
			// Position Fix Toc after Scroll
	        var docTotalHeight	= $('.ws-wp-docs-content').height();
	        var docOffsetTop	= $(".ws-wp-docs-content").offset().top; 
	        var tocOffsetTop	= $(".document-toc").offset().top; 
	        var winH	 		= $(window).height();
	        var winScrollTop 	= $( window ).scrollTop();
	        
			if(  winScrollTop >= ( tocOffsetTop - offset_top ) ) {   

				$('#document-toc').css({
				  'position': 'fixed',
				  'top':offset_top,
				  'width': $('.document-toc').width()
				});

				// Fix toc at the end of content scroll
				var bottomPos = 10;
				
				var scrollUpto	= docTotalHeight +  tocOffsetTop - winH  - bottomPos;
				var tocGap		= docOffsetTop + docTotalHeight + offset_top - winH - winScrollTop - bottomPos;
				if($(window).scrollTop() >= scrollUpto  && tocGap <= 0){
					
					$('#document-toc').css({
						'position' 	: 'absolute',
						'top'		: 'inherit',
						'bottom'	: bottomPos
					});
				}
			}
			else{
				$('#document-toc').css({
				  'position': 'relative',
				  'top':0,
				});
			}
		    

		    var scrollPos = $(document).scrollTop();

		    $('#document-toc a').each(function (i) {
		        var currLink = $(this);
		        var refElement = $(currLink.attr("href"));
		        if (refElement.offset().top <= scrollPos+offset_top+default_offset ) {

		            $('#document-toc .doc-list-item-wrapper').removeClass("active");
		            currLink.parent('span').addClass("active");

	            	if(currLink.siblings('span').hasClass('toc-has-child')){
		            	currLink.closest('li').children('ul').slideDown();
		            	currLink.closest('li').find('span.dashicons').removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');		            	
		            }
		        }
		        else{
		            currLink.parent('span').removeClass("active");
		        }

		    });
		}


		function backToTop(offset){

			offset_opacity = 1200,
			//duration of the top scrolling animation (in ms)
			scroll_top_duration = 700,
			//grab the "back to top" link
			$back_to_top = $('.cd-top');

			
			//hide or show the "back to top" link
			( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
			if( $(this).scrollTop() > offset_opacity ) { 
			  $back_to_top.addClass('cd-fade-out');
			}

		}

		function fixNavPos(navOffsetTop,offsetTop,navToShow){

			var css_top = 0;
			if(parseInt($(window).width()) > 1024){
				css_top = offsetTop;
			}
			
			if($(window).scrollTop() > (navOffsetTop) ){
				$('.ws-visible').css({
					'position'	:'fixed',
					'top'		: css_top,
					'width'		: $('.nav-inner-wrapper').width()									
				});
			}
			else{
				$('.ws-visible').css({
					'position'	:'relative',				
				});
			}
		}
    };
 
}( jQuery ));