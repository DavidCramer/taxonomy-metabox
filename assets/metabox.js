jQuery( function( $ ){

	
	$('[data-pull]').each( function( ){

		var space = $(this),
			panel = $( '#' + space.data('pull') );

		panel.appendTo( space );


	});

	// tab activations
	$(document).on('click', '.taxonomy-metabox-tab > li > a', function(e){
		
		e.preventDefault();
		var clicked = $(this),
			parent = clicked.closest('.inside');

		parent.find('.taxonomy-metabox-tab-body').hide();
		parent.find('.taxonomy-metabox-tab li').removeClass('active');
		
		$( clicked.attr('href') ).show();
		clicked.parent().addClass('active');
	});


} );