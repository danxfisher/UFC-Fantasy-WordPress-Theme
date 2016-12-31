/*
 *
 * Additional administrative scripts
 *
 */

// Hide metabox based on page template selection
// change #postbox-container-2 to metabox id that needs to be hidden upon selection
jQuery(document).ready(function($){'use strict',

	$('#page_template').change(checkTemplate);

	function checkTemplate(){
        $('#postbox-container-2').show();
		var selecetedTemplate = $('#page_template option:selected').attr('value');
        
        
        if (selecetedTemplate == 'collections-page.php')
        {
            $('#postbox-container-2').hide();
        }
	}

	$(window).load(function(){'use strict',
		checkTemplate();
	})

});