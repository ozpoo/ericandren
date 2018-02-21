(function ($, root, undefined) {

	$(function () {

		var $allVideos;

		$(document).ready(function(){
			init();
		});

		$(window).load(function() {
			reveal();
		});

		$(window).resize(function() {
		  setVideos();
		});

		var init = function() {
			document.addEventListener("touchstart", function() {}, true);

			initVideos();
			setVideos();

			$(".menu-toggle").on("click", function (){
				$(".menu-modal").toggleClass("show");
			});

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
		};

		var reveal = function() {
			$("body").addClass("show");
			$(".name, .menu-toggle, .filter").addClass("show");
		};

		var initVideos = function() {
			$allVideos = $("iframe[src*='youtube.com']");
			$allVideos.each(function() {
				var url = $(this).attr("src");
        $(this).attr("src",url+"&amp;wmode=transparent");
			  $(this)
			    .attr('data-aspectRatio', this.height / this.width)
			    .removeAttr('height')
			    .removeAttr('width');
			});
		};

		var setVideos = function() {
			$allVideos.each(function() {
		    var $el = $(this);
				var newWidth = $el.parent().innerWidth();
		    $el
		      .width(newWidth)
		      .height(newWidth * $el.attr('data-aspectRatio'));
		  });
		};

	});

})(jQuery, this);
