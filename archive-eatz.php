<?php get_header(); ?>

	<main role="main">

		<section class="filter">
			<ul>
				<li><button class="filter-button" data-category="All"><small>All</small></button></li>
				<!-- <?php $terms = get_terms(['taxonomy' => 'work_category']); ?>
				<?php foreach($terms as $term): ?>
					<li><button class="filter-button" data-category="<?php echo $term->name; ?>"><small><?php echo $term->name; ?></small></button></li>
				<?php endforeach; ?> -->
			</ul>
		</section>

		<section class="list">
			<?php get_template_part('loop-eatz'); ?>
			<?php get_template_part('pagination'); ?>
		</section>

		<script>

			(function ($, root, undefined) {

				$(function () {

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

				});

			})(jQuery, this);

		</script>

	</main>

<?php get_footer(); ?>
