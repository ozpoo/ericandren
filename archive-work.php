<?php get_header(); ?>

	<main role="main">

		<section class="filter">
			<ul>
				<li><button class="filter-button" data-category="All"><small>All</small></button></li>
				<?php $terms = get_terms(['taxonomy' => 'work_category']); ?>
				<?php foreach($terms as $term): ?>
					<li><button class="filter-button" data-category="<?php echo $term->name; ?>"><small><?php echo $term->name; ?></small></button></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<section class="list">
			<?php get_template_part('loop-work'); ?>
			<?php get_template_part('pagination'); ?>
		</section>

		<script>

			(function ($, root, undefined) {

				$(function () {

					var transitionSplit, transitionTime, transitionCount, transitionBuffer;
					var cache = [];

					$(window).load(function(){
						init();
						animate();
					});

					var init = function() {
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
						transitionSplit = 24;
						transitionBuffer = 880;
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

					var animate = function(time) {
						requestAnimationFrame( animate );
					}

				});

			})(jQuery, this);

		</script>

	</main>

<?php get_footer(); ?>
