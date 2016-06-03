jQuery(function($){

	$('.post-listing').append( '<span class="load-more">Click here to load earlier stories</span>' );
	var button = $('.post-listing .load-more');
	var page = 1;
	var loading = false;

	$('.load-more').on('click', function(){
		if( ! loading ) {
			loading = true;
			var data = {
				action: 'be_ajax_load_more',
				nonce: beloadmore.nonce,
				page: page,
				query: beloadmore.query,
			};
			$.post(beloadmore.url, data, function(res) {
				if( res.success) {
					$('.post-listing').append( res.data );
					$('.post-listing').append( button );
					page = page + 1;
					loading = false;
				} else {
					// console.log(res);
				}
			}).fail(function(xhr, textStatus, e) {
				// console.log(xhr.responseText);
			});
		}
	});
		
});