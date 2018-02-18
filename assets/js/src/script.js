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
			console.log($allVideos);
			$allVideos.each(function() {
				var url = $(this).attr("src");
        $(this).attr("src",url+"&amp;wmode=transparent");
			  $(this)
			    .data('aspectRatio', this.height / this.width)
			    .removeAttr('height')
			    .removeAttr('width');
			});
		};

		var setVideos = function() {
			$allVideos.each(function() {
		    var $el = $(this);
				var newWidth = $el.parent().width();
		    $el
		      .width(newWidth)
		      .height(newWidth * $el.data('aspectRatio'));
		  });
		};

		var jsonData = function( form ) {
			var arrData = form.serializeArray(),
				objData = {};
			$.each( arrData, function( index, elem ) {
				objData[elem.name] = elem.value;
			});
			console.log(JSON.stringify( objData ));
			return JSON.stringify( objData );
		};

		var $q = function(q, res){
			if (document.querySelectorAll) {
				res = document.querySelectorAll(q);
			} else {
				var d = document;
				var a = d.styleSheets[0] || d.createStyleSheet();
				a.addRule(q,'f:b');
				for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
					l[b].currentStyle.f && c.push(l[b]);

				a.removeRule(0);
				res = c;
			}
			return res;
		};

		var addEventListener = function(evt, fn) {
			window.addEventListener ? this.addEventListener(evt, fn, false) : (window.attachEvent) ? this.attachEvent('on' + evt, fn) : this['on' + evt] = fn;
		};

		var _has = function(obj, key) {
			return Object.prototype.hasOwnProperty.call(obj, key);
		};

		function loadImage (el, fn) {
			var img = new Image();
			var src = el.getAttribute('data-src');
			img.onload = function() {
				if (!! el.parent) {
					el.parent.replaceChild(img, el);
				} else {
					el.src = src;
				}
				fn ? fn() : null;
			};
			img.src = src;
		}

		function elementInViewport(el) {
			var rect = el.getBoundingClientRect();

			return (rect.top >= 0 && rect.left >= 0 && rect.top <= (window.innerHeight || document.documentElement.clientHeight));
		}

		var images = [];
		var query = $q('img.lazy');
		var processScroll = function() {
			for (var i = 0; i < images.length; i++) {
				if (elementInViewport(images[i])) {
					loadImage(images[i], function () {
						images.splice(i, i);
					});
				}
			}
		};
		// Array.prototype.slice.call is not callable under our lovely IE8
		for (var i = 0; i < query.length; i++) {
			images.push(query[i]);
		}

		processScroll();
		addEventListener('scroll', processScroll);

	});

})(jQuery, this);
