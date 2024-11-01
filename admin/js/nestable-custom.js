jQuery(document).ready(function($){

	 // Function to Update Serialized value
	var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this sorting.');
        }
    };

    docDepth = 2;   
    // Nestable title list
	$('#nestable-document').nestable({
        group: 1,
        maxDepth:docDepth
    }).on('change', updateOutput);

    // output initial serialised data
    updateOutput($('#nestable-document').data('output', $('#list_serialized')));

    // Deleting Nested List
	$(document).on('click', '.delete-list', function( e ){	
		
		var id = $(this).attr('data-id');
		var msg = 'Are you sure to delete it';
		if($(this).closest('li').children('ol').html()){
			msg += " and all its children";
		}
		msg += '?';
		var c = confirm(msg);
		if(c == true){

			$('#document_title_'+id).remove(); // Removing title
			$('#documentation-list_'+id).remove(); // Removing Editor
			// output initial serialised data
    		updateOutput($('#nestable-document').data('output', $('#list_serialized')));
		}
		
	});

	// Show Add/Edit Title input box
	$(document).on('click','h3.itemTitle span.edit_title',function(e){
		$(this).hide();
		$(this).siblings('.section_title').show().focus().select();
	});

	$(document).on('blur','h3.itemTitle input.section_title',function(e){
		$(this).hide();
		$(this).siblings('span.edit_title').show();

	});

	$(document).on('keyup change', '.section_title', function( e ){
		var titleId = $(this).attr('data-id');
		var titleValue = ($(this).val())?$(this).val():"(no title)";
		$('#'+titleId ).text(titleValue);
	});


	// Adding New Nested List Item
	$(document).on('click ', '.wp-doc-add-new', function( e ){

		e.preventDefault();
		$('.message-add-new').slideUp('slow');
		$('.save-notice').html( "Please Publish / Update title before you go to contents section" ).slideDown('slow'); 

		var newDataId 	= get_max_data_id();  	
		var template 	= get_box_repeator(newDataId);
		$('ol.wp-doc-nested-sortable').append(template); 
		 updateOutput($('#nestable-document').data('output', $('#list_serialized')));

    });
		
	function get_max_data_id(){
		var dataId = 1;
	   	var maxDataId = 1;

	   	$('.itemTitle').each(function(i){
   			var availableDataIds = parseInt($(this).attr('data-id'));

   			if(availableDataIds > maxDataId)
   				maxDataId = availableDataIds;
	   	});
	   	if($('.itemTitle').length > 0)
	   		dataId = maxDataId + 1;
	   	return dataId;
	}

	function get_box_repeator(id){
		var htmltemplate = '';

		htmltemplate += '<li class="dd-item dd3-item" id="document_title_' + id + '" data-id="' + id + '">';
					
	    htmltemplate +=   '<div class="dd-handle dd3-handle"></div>';
		htmltemplate +=		'<div class="dd3-content">';
		htmltemplate +=			'<h3 class="itemTitle clearfix" data-id="' + id + '">';
		htmltemplate +=				'<span class="edit_title" id="title-' + id + '" title="Click Here To Add/Edits Title" >Click Here to add Title</span>';
		htmltemplate +=				'<input type="text" data-id="title-' + id + '" name="doc_title[' + id + ']" value="" placeholder="(no title)" class="section_title" style="display:none">';
		htmltemplate +=				'</span>' ;
		htmltemplate +=				'<i class="dashicons dashicons-no-alt delete-list delete-icon" data-id="' + id + '"></i>';
		htmltemplate +=			'</h3>';
		htmltemplate +=		'</div>';
		htmltemplate +=  '</li>';

		return htmltemplate;
   }

});