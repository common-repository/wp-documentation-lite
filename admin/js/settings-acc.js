jQuery(document).ready(function($){

	// setting option accordian
    $(document).on('click','.option-title a',function(e){
		e.preventDefault();		
		$(this).parent().next("div.setting-options").slideToggle();
		
		if($(this).hasClass('showing')){
			$(this).removeClass('showing').children('i').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');				
		}
		else{
			$(this).addClass('showing').children('i').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');				
		}
	}); 


	// content dimension	
	$(document).on('keyup change','.wp_documentation_settings-dimension',function(e){		
		var total = 0;
		$('.wp_documentation_settings-dimension').each(function(i){
			total += parseFloat($(this).val());
		});
		
		if(total > 100){
			$('.dimension-error').css({'margin-left':'20px','visibility':'visible'});
			$('#submit').prop("disabled",true);
		}
		else{
			$('.dimension-error').css({'visibility':'hidden'});
			$('#submit').prop("disabled",false);
		}
		
	});
	
});