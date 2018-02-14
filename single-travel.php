<?php get_header(); ?>

	<main role="main">

		<section class="single-travel">

		<?php while (have_posts()) : the_post(); ?>

			<div class="top-title">
				<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
			</div>

			<div class="explore">
				<p><button>Explore</button></p>
			</div>

			<div class="title blue show">
				<?php the_title(); ?>
			</div>

			<div class="top-space">
				<?php $thumb = get_post_thumbnail_id(); ?>
				<div class="bg" data-url="<?php echo wp_get_attachment_image_src( $thumb, 'medium' )[0]; ?>">
					<figure>
						<picture>
							<source media="(max-width: 1800px)" srcset="<?php echo wp_get_attachment_image_src( $thumb, 'medium' )[0]; ?>">
								<img src="<?php echo wp_get_attachment_image_src( $thumb, 'medium' )[0]; ?>">
						</picture>
					</figure>
				</div>
				<div class="description">
					<div class="scroll-fade">
						<p><?php the_excerpt(); ?></p>
					</div>
				</div>
			</div>

			<div class="stream">

				<div class="poster" data-url="<?php echo wp_get_attachment_image_src( $thumb, 'micro' )[0]; ?>">
					<figure>
						<picture>
							<source media="(max-width: 1800px)" srcset="<?php echo wp_get_attachment_image_src( $thumb, 'small' )[0]; ?>">
								<img src="<?php echo wp_get_attachment_image_src( $thumb, 'medium' )[0]; ?>">
						</picture>
					</figure>
				</div>

				<?php the_content(); ?>
			</div>

			<?php
				if(get_adjacent_post(false, '', true )){
						$nextPost = get_adjacent_post(false, '', true );
						$nextPost = get_the_permalink( $nextPost->ID );
				} else {
						$nextPost = new WP_Query('posts_per_page=1&order=ASC&post_type=travel&orderby=menu_order');
						$nextPost->the_post();
						$nextPost = get_the_permalink( $nextPost->ID );
						wp_reset_query();
				}
				if(get_adjacent_post(false, '', false )){
						$prevPost = get_adjacent_post(false, '', false );
						$prevPost = get_the_permalink( $prevPost->ID );
				} else {
					$prevPost = new WP_Query('posts_per_page=1&order=DESC&post_type=travel&orderby=menu_order');
					$prevPost->the_post();
					$prevPost = get_the_permalink( $prevPost->ID );
					wp_reset_query();
				}
			?>
			<div class="pagination">
				<div class="next">
					<!-- <p><?php next_post_link(); ?></p> -->
					<p><a href="<?php echo $prevPost; ?>">previous post</i></a></p>
				</div>
				<div class="previous">
					<!-- <p><?php previous_post_link(); ?></p> -->
					<p><a href="<?php echo $nextPost; ?>">next post</a></p>
				</div>
			</div>

		<?php endwhile; ?>

		</section>

		<script>

			(function ($, root, undefined) {

				$(function () {

					var loaded = false;
					var scrollTop;

					$(document).ready(function() {
						setTimeout(function(){
							$("html, body").scrollTop(0);
						}, 100);
					});

					$(window).load(function() {
						init();
						animate();
					});

					function init() {
						$("body").addClass("show");

						// glitcher.glitch($(".poster").data("url"), function () {
						// 		$(".poster figure").append(glitcher.canvas);
						// });
						// glitcher.glitch($(".bg").data("url"), function () {
						// 	$(".bg figure").append(glitcher.canvas);
						// });

						$(".return a").on("click", function (ev) {
							ev.preventDefault();
							$("body").removeClass("show");
							window.location.href = $(this).attr("href");
						});

						$(".explore button").on("click", function (ev) {
							$("html, body").animate({ scrollTop: $(window).height()*.25 }, 660);
						});

						setTimeout(function(){
							$(".title").removeClass("blue");
							setTimeout(function(){
								$(".title").removeClass("show");
								$(".top-title, .explore").addClass("show");
								setTimeout(function(){
									$(".description").addClass("show");
									setTimeout(function(){
										$("html, body").animate({ scrollTop: $(window).height()*.25 }, 660);
										loaded = true;
									}, 660);
								}, 660);
							}, 1200);
						}, 1200);
					}

					var delta = 0;

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
						}

						// glitch();

					}

					function scroll() {
						deltaY = scrollTop * .4;
						$(".description").css( "transform", "translate3d(0, "+deltaY+"px, 0)" );
						// $(".poster").css({"transform": "translate3d(0, "+deltaY+"px, 0)", "opacity": deltaO });

						// deltaO = (scrollTop * .0014);
						// $(".poster").css( "opacity", deltaO );
						// $("img").each(function(){
						// 	deltaY = scrollTop * $(this).data("delta");
						// 	$(this).css("transform","translate3d(0, "+deltaY+"px, 0)");
						// });
					}

					function glitch() {
						if(delta++ == 3) {
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
							delta = 0;
						}
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
