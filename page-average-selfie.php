<?php get_header(); ?>

	<style>
		body {
			background: white;
			color: black;
		}
		.instagrams {
			display: flex;
			flex-wrap: wrap;
		}
		.instagrams .gram {
			width: 220px;
			position: relative;
		}
		.instagrams .gram img {
			/* opacity: 0; */
			position: relative;
			z-index: 0;
		}
		.instagrams .gram img.show {
			opacity: 1;
		}
		.instagrams .gram .rect {
	    border: 1px solid #a64ceb;
	    left: 0;
			top: 0;
	    position: absolute;
			z-index: 100;
	  }
		.average {
			height: 100vh;
			width: 100vw;
			position: fixed;
		}
		.average figure {
			height: 260px;
			width: 260px;
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate3d(-50%,-50%,0);
		}
	</style>

	<main role="main">

		<section class="instagrams">
			<?php
			global $results;
			// echo $results;
			// global $time_curr;
			// global $time_previous;
			// global $time_check;
			// echo $time_curr . '<br>';
			// echo $time_previous . '<br>';
			// echo $time_check . '<br>';
			$count = 0;
			foreach($results['data'] as $key => $value) { ?>
				<figure>
					<div class="gram">
						<!--
							$src = $item->images->standard_resolution->url;
					    $thumb = $item->images->thumbnail->url;
						-->
						<img id="img<?php echo $count++; ?>" src="<?php echo $value['images']['standard_resolution']['url']; ?>" />
					</div>
				</figure>
			<?php } ?>
		</section>

		<section class="average">

		</section>

	</main>

	<script>

		(function ($, root, undefined) {
			$(function () {
				'use strict';

				$(window).load(function() {

		      $("img").each(function(){

						var tracker = new tracking.ObjectTracker('face');
						var el = $(this);
						var id = "#" + $(el).attr("id");

						tracker.setStepSize(1.7);
			      tracking.track(id, tracker);

						tracker.on('track', function(event) {
							event.data.forEach(function(rect) {
								plot(rect.x, rect.y, rect.width, rect.height, el);
							});
						});

					});

				});

	      function plot(x, y, w, h, el) {

					var id = "#" + $(el).attr("id");
	        var rect = $('<div>');
					console.log(id);
	        $(el).parent().append(rect);
	        $(rect).addClass('rect');
	        $(rect).width(w);
	        $(rect).height(h);
					$(rect).css("left", x);
					$(rect).css("top", y);
					var $dupe = $(el).closest("figure").clone();
					$(".average").append($dupe);

	      }

			});
		})(jQuery, this);

	</script>

<?php get_footer(); ?>
