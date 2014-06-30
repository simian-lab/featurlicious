(function ($) {
	"use strict";
	$(function () {

		$("#search-posts").on("click", function(event){

			event.preventDefault();

			$('#search-result').empty();

			var site = window.location.origin;
			var searchTerm = $('#input-search').val();

			if(searchTerm) {
				jQuery.ajax({
					type : "post",
					dataType : "json",
					url : site + "/wp-admin/admin-ajax.php",
					data : {action: "sim_search", search : searchTerm},
					success: function(response) {
						var postsFound = Object.keys(response).length;
						for(var i=0; i<postsFound; i++) {
							var post = $("<div/>", {"id": response[i].the_id});
							$(post).draggable();
							var title = $("<h4/>", {html: response[i].the_title});
							$(title).appendTo(post);
							$(post).appendTo('#search-result');
						}
					},
					error: function(response) {
						console.log('Hubo un error con la petición.');
					}
				});
			}
		});		
	});
}(jQuery));