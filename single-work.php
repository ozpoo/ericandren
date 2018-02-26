<?php get_header(); ?>

	<main role="main">

		<section class="single-work">

		<?php while (have_posts()) : the_post(); ?>

			<div class="top-title">
				<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
			</div>

			<div class="explore">
				<p><button>Explore</button></p>
			</div>

			<div class="title show">
				<?php the_title(); ?>
			</div>

			<div class="top-space">
				<?php $thumb = get_post_thumbnail_id(); ?>
				<div class="description">
					<div class="scroll-fade">
						<p><?php the_excerpt(); ?></p>
					</div>
				</div>
			</div>

			<!-- <div class="description">
				<?php the_content(); ?>
			</div> -->

			<section class="flky">
				<?php $image = get_post_thumbnail_id(); ?>
				<figure>
					<img
						draggable="false"
						alt=""
						src="<?php echo wp_get_attachment_image_src($image, 'micro')[0]; ?>"
						sizes="auto"
						data-srcset="<?php echo wp_get_attachment_image_srcset($image, 'large'); ?>"
						class="lazyload blur-up" />
				</figure>
				<?php $images = get_field('slideshow'); ?>
				<?php foreach( $images as $image ): ?>
					<figure>
						<img
							draggable="false"
							alt=""
							src="<?php echo wp_get_attachment_image_src($image[ID], 'micro')[0]; ?>"
							sizes="auto"
							data-srcset="<?php echo wp_get_attachment_image_srcset($image[ID], 'large'); ?>"
							class="lazyload blur-up" />
					</figure>
				<?php endforeach; ?>
			</section>

			<section class="flky-current-slide"></section>

			<section class="flky-arrows"><p><button class="flky-previous"></buttom><i class="far fa-arrow-alt-circle-left"></i><button class="flky-next"><i class="far fa-arrow-alt-circle-right"></i></buttom></section>

			<?php
				if(get_adjacent_post(false, '', true )){
						$nextPost = get_adjacent_post(false, '', true );
						$nextPost = get_the_permalink( $nextPost->ID );
				} else {
						$nextPost = new WP_Query('posts_per_page=1&order=ASC&post_type=work&orderby=menu_order');
						$nextPost->the_post();
						$nextPost = get_the_permalink( $nextPost->ID );
						wp_reset_query();
				}
				if(get_adjacent_post(false, '', false )){
						$prevPost = get_adjacent_post(false, '', false );
						$prevPost = get_the_permalink( $prevPost->ID );
				} else {
					$prevPost = new WP_Query('posts_per_page=1&order=DESC&post_type=work&orderby=menu_order');
					$prevPost->the_post();
					$prevPost = get_the_permalink( $prevPost->ID );
					wp_reset_query();
				}
			?>
			<div class="pagination">
				<div class="next">
					<p><a href="<?php echo $prevPost; ?>">P<span class="hover-text">revious</span></i></a></p>
				</div>
				<div class="previous">
					<p><a href="<?php echo $nextPost; ?>">N<span class="hover-text">ext</span></a></p>
				</div>
			</div>

		<?php endwhile; ?>

		</section>

		<script>

			(function ($, root, undefined) {
				$(function () {

					var loaded, flky, scrollTop, delta;

					$(document).ready(function() {
						init();
						initFlky();
					});

					$(window).load(function() {
						reveal();
						animate();
					});

					function init() {
						loaded = false;
						delta = 0;

						$(".explore button").on("click", function (ev) {
							$("html, body").animate({ scrollTop: $(window).height()*.25 }, 660);
						});
					}

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

					function setCurrentDisplay() {
						var current = pad(flky.selectedIndex+1 , 2) + " / " + pad(flky.slides.length, 2);
						$(".flky-current-slide").html("<p>"+current+"</p>");
					}

					var pad = function(num, size) {
				    var s = "000000000" + num;
				    return s.substr(s.length-size);
					}

					function reveal() {
						$(".title").removeClass("blue");
						setTimeout(function(){
							$(".title").removeClass("show");
							$(".top-title, .explore").addClass("show");
							setTimeout(function(){
								$(".description").addClass("show");
								setTimeout(function(){
									loaded = true;
								}, 660);
							}, 660);
						}, 1200);
					}

					function animate() {
						requestAnimationFrame( animate );
						scrollTop = $(document).scrollTop();

						if(loaded){
							if(scrollTop > 1) {
								$(".about-toggle, .next, .previous").addClass("show");
								$(".name, .menu-toggle, .top-title, .title, .explore").removeClass("show");
								if(scrollTop > ($(window).height()*.25)) {
									deltaO = 1 - ((scrollTop - ($(window).height()*.25)) * .002);
									$(".description .scroll-fade, .bg").css("opacity", deltaO);
								}
							} else {
								$(".description .scroll-fade, .bg").css("opacity", 1);
								$(".about-toggle, .next, .previous").removeClass("show");
								$(".name, .menu-toggle, .top-title, .explore").addClass("show");
							}
							scroll();

							if(scrollTop > $(document).height() - ($(window).height()*1.25) ) {
								$(".flky-current-slide, .flky-arrows, .flickity-page-dots").addClass("show");
							} else {
								$(".flky-current-slide, .flky-arrows, .flickity-page-dots").removeClass("show");
							}
						}

					}

					function scroll() {
						deltaY = scrollTop * .4;
						$(".description").css( "transform", "translate3d(0, "+deltaY+"px, 0)" );
					}

					(function() {
						var lastTime = 0;
						var vendors = ['ms', 'moz', 'webkit', 'o'];
						for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
							window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
							window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
						}

						if (!window.requestAnimationFrame) {
							window.requestAnimationFrame = function(callback, element) {
								var currTime = new Date().getTime();
								var timeToCall = Math.max(0, 16 - (currTime - lastTime));
								var id = window.setTimeout(function() { callback(currTime + timeToCall); },
								timeToCall);
								lastTime = currTime + timeToCall;
								return id;
							}
						}

						if (!window.cancelAnimationFrame) {
							window.cancelAnimationFrame = function(id) {
								clearTimeout(id);
							}
						}
					}());

				});
			})(jQuery, this);

		</script>

	</main>

<?php get_footer(); ?>
