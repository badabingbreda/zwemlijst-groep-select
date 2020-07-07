( function($){

	$(document).ready( function() {

		$('.group_add').each( function( item ) {
			$item = $(this);
			$item.select2({
  				ajax: {
    				url: mbmrs.adminUrl,
    				dataType: 'json',
    				data: function(params) { var query = { page: params.page || 1 , action: 'get_groups'}; return query; },
  				}
			});
		});


		$('.group_add').on('select2:select', function (e) {
	  		// Do something

			$.ajax(
				{
					url: mbmrs.adminUrl,
					dataType: 'json',
					method: 'GET',
					data : {
						action: 'get_group_data',
						'groupid' : e.params.data.id,
					}
				}
			).done( function (data) {
				$( '.group_add_action' ).attr( 'data-students' , JSON.stringify(data) );
			});
		});

		$( '.group_add_action[data-students]' ).on( 'click' , function() {

			$this = $(this);

			$item = $('#multirelation');	// the ID of the multirelationships we want to change

			if ( $this.attr( 'data-students' ) ) {

				data = JSON.parse( $this.attr( 'data-students' ) );
				console.log( data );

				for ( let i = 0; i < data.length ; i++  ) {

					listItem = mbMultirelationship.insertItem( { id: data[i].id , label: data[i].text } , $item );
					$item.find( '.items' ).append( listItem );

				}
				// clear the group_add button
				$('.group_add').val(null).trigger('change');
				// clear the data so the action-button is hidden again
				$('.group_add_action').attr( 'data-students' , '' );
				// collect the data and update
				let itemsdata = mbMultirelationship.collectData( $item.attr( 'id' ));
				mbMultirelationship.updateData( $item , itemsdata );

			}
		} );

	});

})(jQuery);