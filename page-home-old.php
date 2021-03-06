<?php get_header(); ?>

	<main role="main">

		<?php while (have_posts()) : the_post(); ?>
			<section class="projects">

				<div class="left-icon">
					<p>
						<svg version="1.1"
							 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
							 x="0px" y="0px" width="27.3px" height="53.2px" viewBox="0 0 27.3 53.2" style="enable-background:new 0 0 27.3 53.2;"
							 xml:space="preserve">
							<polygon class="st0" points="26.6,0 27.3,0.7 1.4,26.6 27.3,52.5 26.6,53.2 0,26.6 	"/>
						</svg>
					</p>
				 </div>

				<div class="right-icon">
					<p>
						<svg version="1.1"
							 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
							 x="0px" y="0px" width="27.3px" height="53.2px" viewBox="0 0 27.3 53.2" style="enable-background:new 0 0 27.3 53.2;"
							 xml:space="preserve">
							<polygon class="st0" points="0.7,53.2 0,52.5 25.9,26.6 0,0.7 0.7,0 27.3,26.6 	"/>
						</svg>
					</p>
				 </div>

				<div class="filter">
					<div class="modal-filter">
						<?php
							$terms = get_terms( array(
								'taxonomy' => 'cat_work',
								'hide_empty' => false,
							) );
						?>
						<ul>
							<?php foreach ( $terms as $term ): ?>
				        <li><button><?php echo $term->name; ?><span class="count"><?php echo $term->count; ?></span></button></li>
					    <?php endforeach; ?>
						</ul>
					</div>
					<div class="toggle-filter">
						<p>
							<button>Filter</button>
						</p>
					</div>
				 </div>

				<div class="current-slide">
					<p class="number"></p>
				</div>

				<div class="slider">
					<?php $count = 0; ?>
					<?php
					$args = array(
			    'post_type'=> 'work',
			    'order'    => 'ASC',
					'posts_per_page' => -1
			    );
					$the_query = new WP_Query( $args );
					if($the_query->have_posts() ) : while ( $the_query->have_posts() ) :
						$the_query->the_post();
						$title = get_the_title();
						$description = get_the_excerpt();
						$thumb = get_post_thumbnail_id();
					?>
						<div class="project">

							<?php
								if(get_the_title() == "Sounds of Twitter") {
									$image = wp_get_attachment_image_src( $thumb, 'full' )[0];
								} else {
									$image = wp_get_attachment_image_src( $thumb, 'medium' )[0];
								}
							?>
							<figure data-index="<?php echo $count++; ?>" data-url="<?php echo wp_get_attachment_image_src( $thumb, 'holder' )[0]; ?>" data-full="<?php echo $image; ?>">
								<a href="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>">
									<picture>
										<img src="/">
									</picture>
								</a>
							</figure>

						</div>
						<?php endwhile; endif; ?>
					<?php $the_query->rewind_posts(); ?>
				</div>

				<div class="slider">
					<?php $count = 0; ?>
					<?php
					$args = array(
			    'post_type'=> 'work',
			    'order'    => 'ASC',
					'posts_per_page' => -1
			    );
					$the_query = new WP_Query( $args );
					if($the_query->have_posts() ) : while ( $the_query->have_posts() ) :
						$the_query->the_post();
						$title = get_the_title();
						$description = get_the_excerpt();
						$thumb = get_post_thumbnail_id();
					?>
						<div class="project">

							<?php
								if(get_the_title() == "Sounds of Twitter") {
									$image = wp_get_attachment_image_src( $thumb, 'full' )[0];
								} else {
									$image = wp_get_attachment_image_src( $thumb, 'medium' )[0];
								}
							?>
							<figure data-index="<?php echo $count++; ?>" data-url="<?php echo wp_get_attachment_image_src( $thumb, 'holder' )[0]; ?>" data-full="<?php echo $image; ?>">
								<a href="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>">
									<picture>
										<img src="/">
									</picture>
								</a>
							</figure>

						</div>
						<?php endwhile; endif; ?>
					<?php $the_query->rewind_posts(); ?>
				</div>

				<div class="frames">
					<?php $count = 0; ?>
					<?php if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : ?>
						<?php $the_query->the_post(); ?>
						<div class="frame" data-index="<?php echo $count++; ?>" data-title="<?php the_title(); ?>">
							<div class="wrap">
								<p class="title"><?php the_title() ?></p>
								<div class="description"><p><?php echo the_excerpt(); ?></p></div>
								<p class="click-bait">Click to explore</p>
							</div>
						</div>
					<?php endwhile; endif; ?>
					<?php wp_reset_query(); ?>
				</div>

			</section>

		<?php endwhile; ?>

	</main>

	<script>

		(function ($, root, undefined) {

			$(function () {

				var flky, offsetLeft, offsetTop, lastX, lastY, index;

				$(window).load(function(){

					init();
					animate();

				});
				var glitchme = false;
				var init = function() {

					index = Cookies.get('index');
					if (index == "") {
						index = 0;
						Cookies.set('index', '0');
			    }

					flky = new Flickity( '.projects .slider', {
						accessibility: true,
						adaptiveHeight: false,
						autoPlay: false,
						cellAlign: 'center',
						cellSelector: undefined,
						contain: false,
						draggable: false,
						dragThreshold: 3,
						freeScroll: false,
						selectedAttraction: 0.12,
						friction: 1,
						groupCells: false,
						initialIndex: index,
						lazyLoad: false,
						percentPosition: true,
						prevNextButtons: false,
						pageDots: true,
						resize: true,
						rightToLeft: false,
						setGallerySize: false,
						watchCSS: false,
						wrapAround: true
					});

					flky.on( 'select', function( progress ) {
						Cookies.set("index", flky.selectedIndex);
						$(".frame, .left-icon, .right-icon").removeClass("show");
						$(".project figure").removeClass("set-back");
						$(".flickity-page-dots").removeClass("hide");
						resetSlides();
					});

					flky.on( 'settle', function( progress ) {

					});

					$(".project a").on("click", function (ev) {
						if(!$(this).closest(".project").hasClass("is-selected")){
							ev.preventDefault();
							var index = $(this).closest("figure").data("index");
							flky.select(index);
						} else {
							ev.preventDefault();
							$("body").removeClass("show");
							window.location.href = $(this).attr("href");
						}
					});

					$(".project figure").on("mouseenter", function (ev) {
						if($(this).closest(".project").hasClass("is-selected")){
							var thing = this;
							var current = $(this).data("index");
							$(".frame").eq(current).addClass("show");
							$(this).addClass("set-back");
							$(".flickity-page-dots").addClass("hide");
							$(".name, .menu-toggle").addClass("hide");
							// glitcher.glitch($(this).find("img").attr("src"), function () {
							// 	$(thing).find("a").append(glitcher.canvas);
							// });
						 // glitchme = true;
						} else if($(this).closest(".project").hasClass("left")) {
							// $(".left-icon").addClass("show");
						} else if($(this).closest(".project").hasClass("right")) {
							// $(".right-icon").addClass("show");
						}
					});

					$(".project figure").on("mouseleave", function (ev) {
						$(".frame, .left-icon, .right-icon").removeClass("show");
						$(".project figure").removeClass("set-back");
						$(".flickity-page-dots").removeClass("hide");
						$(".name, .menu-toggle").removeClass("hide");
						// $(".project canvas").remove();
						// glitchme = false;
					});

					$(".filter button").on("mouseenter", function (ev) {
						$(".filter").addClass("toggle");
					});

					$(".filter").on("mouseleave", function (ev) {
						$(".filter").removeClass("toggle");
					});

					$("body").mousemove(onMouseMove);

					resetSlides();

					$(".projects").addClass("show");

				}

				var resetSlides = function() {

					// $(".projects").css("pointer-events", "none");

					$(".project").removeClass("is-ready left pleft right pright");

					if((flky.selectedIndex-1) < 0){
						$(".project").eq(flky.slides.length-1).addClass("left");
						$(".project").eq(flky.slides.length-2).addClass("pleft");
					} else {
						$(".project").eq(flky.selectedIndex-1).addClass("left");
						if((flky.selectedIndex-2) < 0) {
							$(".project").eq(flky.slides.length-1).addClass("pleft");
						} else {
							$(".project").eq(flky.selectedIndex-2).addClass("pleft");
						}
					}

					if((flky.selectedIndex+1) > (flky.slides.length-1)) {
						$(".project").eq(0).addClass("right");
						$(".project").eq(1).addClass("pright");
					} else {
						$(".project").eq(flky.selectedIndex+1).addClass("right");
						if((flky.selectedIndex+2) > (flky.slides.length-1)) {
							$(".project").eq(0).addClass("pright");
						} else {
							$(".project").eq(flky.selectedIndex+2).addClass("pright");
						}
					}

					$(".project.left, .project.pleft, .project.right, .project.pright, .project.is-selected").find("img").each(function(){
						$(this).attr("src", $(this).closest("figure").attr("data-full"));
					});
					$(".project").not(".left, .pleft, .right, .pright, .is-selected").find("img").each(function(){
						$(this).attr("src", $(this).closest("figure").attr("data-url"));
					});

					var current = pad(flky.selectedIndex+1 , 2) + "/" + pad(flky.slides.length, 2);
					$(".current-slide .number").html(current);

					setTimeout(function(index){
						// $(".projects").css("pointer-events", "all");
						$(".project").eq(index).addClass("is-ready");
					}, 880, flky.selectedIndex);

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

				var requestAnimationFrame = (function(){
				 return  window.requestAnimationFrame       ||
								 window.webkitRequestAnimationFrame ||
								 window.mozRequestAnimationFrame    ||
								 window.oRequestAnimationFrame      ||
								 window.msRequestAnimationFrame     ||
								 function(callback, element){
										 window.setTimeout(callback, 1000 / 60);
								 };
				 })();

			  var throttle = 0;
				var animate = function(time) {
					requestAnimationFrame( animate );
					var x = lastX - ($(window).width()/2);
					var y = lastY - ($(window).height()/2);

					var proj = $(".project.is-selected");
					var $this = $(proj);
					var offset = $(proj).offset();
					var width = $(proj).width();
					var height = $(proj).height();
					var centerX = offset.left + width / 2 - ($(window).width()/2);
					var centerY = offset.top + height / 2 - ($(window).height()/2);

					var deltaX = centerX - x;
					var deltaY = centerY - y;
					deltaX = -deltaX*.01;
					deltaY = deltaY*.02;

					$(".project.is-selected").css("transform", "rotateX("+deltaY+"deg) rotateY("+deltaX+"deg) translateZ(30px)");

					if(glitchme){
						if(throttle++ > 3) {
							glitcher.options = {
									color: {
											red: 1,
											green: 0.8,
											blue: 0.58
									},
									stereoscopic: {
											red: 10 * randomRange(1, 3),
											green: 5 * randomRange(1, 3),
											blue: 30 * randomRange(1, 3)
									},
									lineOffset: {
											value: 5 * randomRange(1, 3),
											lineHeight: 10 * randomRange(1, 3)
									}
							};
							glitcher.process();
							throttle = 0;
						}
					}

				}

				var onMouseMove = function(e) {
	        var pos = getCursorPosition(e);
	        lastX = pos.x;
	        lastY = pos.y;
		    }

				var pad = function(num, size) {
			    var s = "000000000" + num;
			    return s.substr(s.length-size);
				}

			});

		})(jQuery, this);

	</script>

<?php get_footer(); ?>
