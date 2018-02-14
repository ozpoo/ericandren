(function ($, root, undefined) {

	$(function () {

		'use strict';

		$(document).ready(function(){

			$("body").addClass("show");

		});

		$(".menu-toggle").on("click", function (){
			$(".menu-modal").toggleClass("show");
		});

		var jsonData = function( form ) {
			var arrData = form.serializeArray(),
				objData = {};
			$.each( arrData, function( index, elem ) {
				objData[elem.name] = elem.value;
			});
			console.log(JSON.stringify( objData ));
			return JSON.stringify( objData );
		};

		$( 'form' ).on( 'submit', function( e ) {
			e.preventDefault();
			$.ajax({
				url: 'http://localhost:8888/ericandren/wp-json/wp/v2/selfies',
				method: 'POST',
				data: jsonData( $(this) ),
				crossDomain: true,
				contentType: 'application/json',
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', POST_SUBMITTER.nonce );
				},
				success: function( data ) {
					console.log( data );
				},
				error: function( error ) {
					console.log( error );
				}
			});
		});

	});

})(jQuery, this);
