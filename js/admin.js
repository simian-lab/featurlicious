(function ($) {
	"use strict";
	$(function () {

		/*jQuery(document).ready( function() {
			jQuery('.postbox h3').prepend('<a class="togbox">+</a> ');
			if(jQuery("textarea[@name=event_notes]").val()!="") {
				jQuery("textarea[@name=event_notes]").parent().parent().removeClass('closed');
			}
			jQuery('.postbox h3').click( function() {
				jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
			});
});*/

$("#search-posts").on("click", function(event) {

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

				if(postsFound) {
					for(var i=0; i<postsFound; i++) {
						var post = $("<div/>", {"id": response[i].the_id, 'class': 'post-id'});
						var postId = $('<span/>', {html: response[i].the_id});
						var title = $("<h4/>", {html: response[i].the_title, 'class': 'title'});
						var permalink = $('<p/>', {html: response[i].the_permalink, 'class': 'permalink'});
						permalink.hide();
						$(postId).appendTo(post);
						$(title).appendTo(post);
						$(permalink).appendTo(post);
						$(post).draggable();
						$(post).appendTo('#search-result');
					}
				} else {
					var notFound = $('<label/>', {html: "I didn't found anything :("});
					$(notFound).appendTo('#search-result');
				}			
			},
			error: function(response) {
				console.log('Hubo un error con la petición.');
			}
		});
	}
});

$("#create-area").on("click", function(event) {

	var areaName = $('#area-name').val();
	if(areaName) {
		setTimeout(function() {
			location.reload(true);
		}, 100);
	} else {
		event.preventDefault();
		var error = $('<div/>', {'class': 'error'});
		var message = $('<p/>', {html: 'You must set a name.'});
		$(message).appendTo(error);
		$(error).appendTo('.error-area');
	}
});

$('[name="add-post"]').on('click', function(event) {

	event.preventDefault();

	var site = window.location.origin;
	// TODO: Use parents() to avoid multiple parent() calls.
	var postId = $(this).parent().parent().children('#input-id').val();
	var areaId = $(this).parent().parent().parent().attr('id');

	if(postId) {

		var post = $('#search-result').children('#'+postId);
		var postTitle = $(post).children('.title').text();
		var postPermalink = $(post).children('.permalink').text();

		if(postTitle) {
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : site + "/wp-admin/admin-ajax.php",
				data : {action: "sim_update_area", title: postTitle, permalink: postPermalink, id: areaId, postId: postId},
				success: function(response) {
					location.reload();
				},
				error: function(response) {
					console.log('Hubo un error con la petición.');
				}
			});
		}
	}
});

$('.remove-link').on('click', function() {

	event.preventDefault();

	var site = window.location.origin;
	var areaId = $(this).parents('.postbox').attr('id');
	var postId = $(this).parent().children('.post-title').attr('id');			

	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : site + "/wp-admin/admin-ajax.php",
		data : {action: "sim_remove_post", areaId: areaId, postId: postId},
		success: function(response) {
		},
		error: function(response) {
			console.log('Hubo un error con la petición.');
		}
	});

	$(this).parent().remove();
});

$('.delete-area').on('click', function() {
	
	event.preventDefault();

	var site = window.location.origin;
	var areaId = $(this).parents('.postbox').attr('id');

	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : site + "/wp-admin/admin-ajax.php",
		data : {action: "sim_remove_area", areaId: areaId},
		success: function(response) {
		},
		error: function(response) {
			console.log('Hubo un error con la petición.');
		}
	});

	$(this).parents('.postbox').remove();
});
});
}(jQuery));