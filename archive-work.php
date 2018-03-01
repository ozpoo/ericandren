<?php get_header(); ?>

	<main role="main">

		<section class="project-modal">
			<div class="info">
				<div class="title"></div>
			</div>

			<section class="carousel">
				<div class="flky"></div>
				<!-- <div class="flky-current-slide"></div> -->
				<!-- <div class="flky-arrows"><p><button class="flky-previous"></buttom><i class="far fa-arrow-alt-circle-left"></i><button class="flky-next"><i class="far fa-arrow-alt-circle-right"></i></buttom></div> -->
			</section>

			<div class="content"></div>
			<div class="close"><p><button>C<span class="hover-text">lose</span></button></p></div>

			<div class="pagination">
				<div class="next">
					<p><a href="<?php echo $prevPost; ?>">P<span class="hover-text">revious</span></i></a></p>
				</div>
				<div class="previous">
					<p><a href="<?php echo $nextPost; ?>">N<span class="hover-text">ext</span></a></p>
				</div>
			</div>
		</section>

		<section class="filter">
			<!-- <ul>
				<li><button class="filter-button" data-category="All"><small>All</small></button></li>
				<?php $terms = get_terms(['taxonomy' => 'work_category']); ?>
				<?php foreach($terms as $term): ?>
					<li><button class="filter-button" data-category="<?php echo $term->name; ?>"><small><?php echo $term->name; ?></small></button></li>
				<?php endforeach; ?>
			</ul> -->
		</section>

		<section class="list">
			<?php get_template_part('loop-work'); ?>
			<?php get_template_part('pagination'); ?>
		</section>

		<script>

			(function ($, root, undefined) {

				$(function () {

					var flky;
					var transitionSplit, transitionTime, transitionCount, transitionBuffer;
					var cache = [];
					var lastX, lastY, offsetLeft, offsetTop;

					$(window).load(function(){
						init();
						animate();
					});

					var init = function() {
						$("body").mousemove(onMouseMove);

						$(document).on('mouseenter', '.thumbnail', function () {
							$(this).addClass('hover');
						});

						$(document).on('mouseleave', '.thumbnail', function () {
							$(this).removeClass('hover');
						});

						$(".filter button").each(function() {
							var button = $(this);
							var category = $(this).attr("data-category");
							if(category == "All") {
								var length = $(".list .thumbnail a").length;
								var index = Math.floor(Math.random() * length + 1);
								var url = $(".list .thumbnail a").eq(index).attr("data-src-thumb");
								$('<img src="'+ url +'">').load(function() {
								  $(this).appendTo(button);
								});
							} else {
								var element = ".list .thumbnail a." + category;
								console.log(url);
								var url = $(element).attr("data-src-thumb");
								$('<img src="'+ url +'">').load(function() {
								  $(this).appendTo(button);
								});
							}
						});

						$(document).on("click", ".filter-button", function() {
							hideItems();
							setTimeout(function(el){
								var term = $(el).attr("data-category");
								filterItems(term);
								showItems();
							}, transitionTime, $(this));
							transitionTime = transitionCount * transitionSplit + transitionBuffer;
							setTimeout(function(){
								$(".name, .menu-toggle").addClass("show");
							}, 880);
						});

						$(".list-item").each(function() {
							cache.push(this);
						});

						setTimeout(function(el){
							showItems();
						}, 440);
					}

					var hideItems = function() {
						var i = 0;
						$(".list-item").each(function() {
							setTimeout(function(el){
								$(el).removeClass("show");
							}, transitionSplit*i++ ,$(this));
						});
					}

					var showItems = function() {
						$(document).scrollTop(0);
						transitionCount = $(".list-item:visible").size();
						transitionSplit = 40;
						transitionBuffer = 1000;
						transitionTime = transitionCount * transitionSplit + transitionBuffer;
						var i = 0;
						$(".list-item").each(function() {
							setTimeout(function(el){
								$(el).addClass("show");
							}, transitionSplit*i++ ,$(this));
						});
					}

					var filterItems = function(term) {
						var full = (term == "All");
						$(".list").empty();
						$(cache).each(function(){
							if($(this).find("a").attr("data-category") == term || full) {
								$(".list").append($(this));
							}
			      }, term);
					}

					var animate = function(time) {
						requestAnimationFrame( animate );
						var x = lastX - ($(window).width()/2);
						var y = lastY - ($(window).height()/2);

						if($(".thumbnail.hover").size()) {
							var $thumb = $(".thumbnail.hover");
							var offset = $($thumb).offset();
							var width = $($thumb).width();
							var height = $($thumb).height();
							var centerX = offset.left + width / 2 - ($(window).width()/2);
							var centerY = offset.top + height / 2 - ($(window).height()/2);

							var deltaX = centerX - x;
							var deltaY = centerY - y;

							if($($thumb).closest(".list-item").is(":first-child") && $(window).width() > 1120) {
								deltaX = -deltaX*.02;
								deltaY = deltaY*.04;
							} else {
								deltaX = -deltaX*.05;
								deltaY = deltaY*.1;
							}

							// $($thumb).css("transform", "rotateX("+deltaY+"deg) rotateY("+deltaX+"deg) translateZ(30px)");
						} else {
							$(".thumbnail").css("transform", "none");
						}
					}

					var getCursorPosition = function(e) {
		        if(offsetLeft == undefined) {
	            offsetLeft = 0;
	            for(var node=$("body")[0]; node; node = node.offsetParent) {
	              offsetLeft += node.offsetLeft;
	            }
		        }
		        if(offsetTop == undefined) {
	            offsetTop = 0;
	            for(var node=$("body")[0]; node; node = node.offsetParent) {
	              offsetTop += node.offsetTop;
	            }
		        }

		        var x = e.pageX - offsetLeft;
		        var y = e.pageY - offsetTop;

		        return { x: x, y: y };
			    }

					var onMouseMove = function(e) {
		        var pos = getCursorPosition(e);
		        lastX = pos.x;
		        lastY = pos.y;
			    }

					var requestAnimationFrame = (function() {
					 return  window.requestAnimationFrame       ||
									 window.webkitRequestAnimationFrame ||
									 window.mozRequestAnimationFrame    ||
									 window.oRequestAnimationFrame      ||
									 window.msRequestAnimationFrame     ||
									 function(callback, element){
											 window.setTimeout(callback, 1000 / 60);
									 };
					 })();

					 $(document).on( 'click', '.ajax-project', function ( e ) {
				    e.preventDefault();
						var id = $(this).attr("data-id");
				    $.ajax( {
				      url: '/ericandren/wp-json/wp/v2/work/'+id+'?_embed',
				      success: function ( data ) {
								$("body").addClass("noScroll");
				        var post = data;
				        $('.project-modal .title').append($("<p/>").append(post.title.rendered));
								var img = $("<img/>");
								console.log(post._embedded);
								var src = post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['02']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['02']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['03']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['03']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['04']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['04']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['05']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['05']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['06']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['06']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['07']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['07']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['08']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['08']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['09']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['09']['width'] + "w, ";
								src += post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['10']['source_url'] + " " + post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['10']['width'] + "w";
								$(img).attr("src", post._embedded['wp:featuredmedia']['0']['media_details']['sizes']['micro']['source_url']);
								$(img).attr("draggable", "false");
								$(img).attr("sizes", "auto");
								$(img).attr("data-srcset", src);
								$(img).addClass("lazyload blur-up");
								$('.project-modal .flky').append($("<figure/>").append(img));

								for(var i in post.acf.slideshow) {
									var img = $("<img/>");
									var src = post.acf.slideshow[i]['sizes']['02'] + " " + post.acf.slideshow[i].sizes['02-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['03']+ " " + post.acf.slideshow[i].sizes['03-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['04'] + " " + post.acf.slideshow[i].sizes['04-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['05'] + " " + post.acf.slideshow[i].sizes['05-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['06'] + " " + post.acf.slideshow[i].sizes['06-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['07'] + " " + post.acf.slideshow[i].sizes['07-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['08'] + " " + post.acf.slideshow[i].sizes['08-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['09'] + " " + post.acf.slideshow[i].sizes['09-width'] + "w, ";
									src += post.acf.slideshow[i]['sizes']['10'] + " " + post.acf.slideshow[i].sizes['10-width'] + "w";
									$(img).attr("src", post.acf.slideshow[i].sizes.micro);
									console.log(post.acf.slideshow[i]);
									$(img).attr("draggable", "false");
									$(img).attr("sizes", "auto");
									$(img).attr("data-srcset", src);
									$(img).addClass("lazyload blur-up");
									$('.project-modal .flky').append($("<figure/>").append(img));
								}
								$('.project-modal .content').append(post.content.rendered);
								initFlky();
								$('.project-modal').addClass("show");
								setTimeout(function(){
									$(".flky-current-slide, .flky-arrows, .flickity-page-dots").addClass("show");
									$(".next, .previous").addClass("show");
								}, 880);
				      },

				      cache: false
				    });
				  });

					$(document).on( 'click', '.close button', function () {
						$('.project-modal').removeClass("show");
						$("body").removeClass("noScroll");
						setTimeout(function(){
							$('.project-modal .title').html("");
							$('.project-modal .content').html("");
							destroyFlky();
						}, 880);
					});

					function initFlky() {
						flky = new Flickity( '.flky', {
							accessibility: true,
							adaptiveHeight: true,
							autoPlay: false,
							cellAlign: 'center',
							cellSelector: undefined,
							contain: false,
							draggable: true,
							dragThreshold: 3,
							freeScroll: false,
							selectedAttraction: 0.1,
							friction: 1,
							groupCells: false,
							initialIndex: 0,
							lazyLoad: false,
							percentPosition: true,
							prevNextButtons: false,
							pageDots: true,
							resize: true,
							rightToLeft: false,
							setGallerySize: false,
							watchCSS: false,
							wrapAround: false
						});

						flky.on( 'select', function( progress ) {
							setCurrentDisplay();
						});
						setCurrentDisplay();

						$(".flky-next").on("click", function (){
							flky.next();
						});

						$(".flky-previous").on("click", function (){
							flky.previous();
						});
					}

					function destroyFlky() {
						flky.destroy();
						$(".flky-next").off("click");
						$(".flky-previous").off("click");
						$(".flky-current-slide, .flky-arrows, .flickity-page-dots").removeClass("show");
						$(".next, .previous").removeClass("show");
						$('.project-modal .flky').html("");
					}

					function setCurrentDisplay() {
						var current = pad(flky.selectedIndex+1 , 2) + " / " + pad(flky.slides.length, 2);
						$(".flky-current-slide").html("<p>"+current+"</p>");
					}

					var pad = function(num, size) {
				    var s = "000000000" + num;
				    return s.substr(s.length-size);
					}

				});

			})(jQuery, this);

		</script>

	</main>

<?php get_footer(); ?>
