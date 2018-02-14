<?php get_header(); ?>

	<main role="main">

		<div class="acf-map">
			<div class="marker" data-lat="47.5832657" data-lng="-115.5865147"></div>
		</div>

		<section class="filter">
			<ul>
				<li><button class="filter-button" data-category="All"><small>All</small></button></li>
				<?php $terms = get_terms(['taxonomy' => 'travel_category']); ?>
				<?php foreach($terms as $term): ?>
					<li><button class="filter-button" data-category="<?php echo $term->name; ?>"><small><?php echo $term->name; ?></small></button></li>
				<?php endforeach; ?>
			</ul>
		</section>

		<section class="list">
			<?php get_template_part('loop-travel'); ?>
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

	<style type="text/css">

	.acf-map {
		width: 100%;
		height: 400px;
		border: #ccc solid 1px;
		margin: 20px 0;
	}

	/* fixes potential theme css conflict */
	.acf-map img {
		 max-width: inherit !important;
	}

	</style>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBloBEVpchbc6AquIFEQHSDhLy6QlsNlKQ"></script>
	<script type="text/javascript">
	(function($) {

	/*
	*  new_map
	*
	*  This function will render a Google Map onto the selected jQuery element
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	$el (jQuery element)
	*  @return	n/a
	*/

	function new_map( $el ) {

		// var
		var $markers = $el.find('.marker');


		// vars
		var args = {
			zoom		: 16,
			center		: new google.maps.LatLng(0, 0),
			mapTypeId	: google.maps.MapTypeId.ROADMAP
		};


		// create map
		var map = new google.maps.Map( $el[0], args);


		// add a markers reference
		map.markers = [];


		// add markers
		$markers.each(function(){

				add_marker( $(this), map );

		});


		// center map
		center_map( map );


		// return
		return map;

	}

	/*
	*  add_marker
	*
	*  This function will add a marker to the selected Google Map
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	$marker (jQuery element)
	*  @param	map (Google Map object)
	*  @return	n/a
	*/

	function add_marker( $marker, map ) {

		// var
		var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

		// create marker
		var marker = new google.maps.Marker({
			position	: latlng,
			map			: map
		});

		// add to array
		map.markers.push( marker );

		// if marker contains HTML, add it to an infoWindow
		if( $marker.html() )
		{
			// create info window
			var infowindow = new google.maps.InfoWindow({
				content		: $marker.html()
			});

			// show info window when marker is clicked
			google.maps.event.addListener(marker, 'click', function() {

				infowindow.open( map, marker );

			});
		}

	}

	/*
	*  center_map
	*
	*  This function will center the map, showing all markers attached to this map
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	map (Google Map object)
	*  @return	n/a
	*/

	function center_map( map ) {

		// vars
		var bounds = new google.maps.LatLngBounds();

		// loop through all markers and create bounds
		$.each( map.markers, function( i, marker ){

			var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

			bounds.extend( latlng );

		});

		// only 1 marker?
		if( map.markers.length == 1 )
		{
			// set center of map
				map.setCenter( bounds.getCenter() );
				map.setZoom( 16 );
		}
		else
		{
			// fit to bounds
			map.fitBounds( bounds );
		}

	}

	/*
	*  document ready
	*
	*  This function will render each map when the document is ready (page has loaded)
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	// global var
	var map = null;

	$(document).ready(function(){

		$('.acf-map').each(function(){

			// create map
			map = new_map( $(this) );

		});

	});

	})(jQuery);
	</script>

	<style type="text/css">

	.acf-map {
		width: 100%;
		height: 400px;
		border: #ccc solid 1px;
		margin: 20px 0;
	}

	/* fixes potential theme css conflict */
	.acf-map img {
		 max-width: inherit !important;
	}

	</style>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBloBEVpchbc6AquIFEQHSDhLy6QlsNlKQ"></script>
	<script type="text/javascript">
	(function($) {

	/*
	*  new_map
	*
	*  This function will render a Google Map onto the selected jQuery element
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	$el (jQuery element)
	*  @return	n/a
	*/

	function new_map( $el ) {

		// var
		var $markers = $el.find('.marker');


		// vars
		var args = {
			zoom		: 16,
			center		: new google.maps.LatLng(0, 0),
			mapTypeId	: google.maps.MapTypeId.ROADMAP
		};


		// create map
		var map = new google.maps.Map( $el[0], args);


		// add a markers reference
		map.markers = [];


		// add markers
		$markers.each(function(){

				add_marker( $(this), map );

		});


		// center map
		center_map( map );


		// return
		return map;

	}

	/*
	*  add_marker
	*
	*  This function will add a marker to the selected Google Map
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	$marker (jQuery element)
	*  @param	map (Google Map object)
	*  @return	n/a
	*/

	function add_marker( $marker, map ) {

		// var
		var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

		// create marker
		var marker = new google.maps.Marker({
			position	: latlng,
			map			: map
		});

		// add to array
		map.markers.push( marker );

		// if marker contains HTML, add it to an infoWindow
		if( $marker.html() )
		{
			// create info window
			var infowindow = new google.maps.InfoWindow({
				content		: $marker.html()
			});

			// show info window when marker is clicked
			google.maps.event.addListener(marker, 'click', function() {

				infowindow.open( map, marker );

			});
		}

	}

	/*
	*  center_map
	*
	*  This function will center the map, showing all markers attached to this map
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	4.3.0
	*
	*  @param	map (Google Map object)
	*  @return	n/a
	*/

	function center_map( map ) {

		// vars
		var bounds = new google.maps.LatLngBounds();

		// loop through all markers and create bounds
		$.each( map.markers, function( i, marker ){

			var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

			bounds.extend( latlng );

		});

		// only 1 marker?
		if( map.markers.length == 1 )
		{
			// set center of map
				map.setCenter( bounds.getCenter() );
				map.setZoom( 16 );
		}
		else
		{
			// fit to bounds
			map.fitBounds( bounds );
		}

	}

	/*
	*  document ready
	*
	*  This function will render each map when the document is ready (page has loaded)
	*
	*  @type	function
	*  @date	8/11/2013
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	// global var
	var map = null;

	$(document).ready(function(){

		$('.acf-map').each(function(){

			// create map
			map = new_map( $(this) );

		});

	});

	})(jQuery);
	</script>

<?php get_footer(); ?>
