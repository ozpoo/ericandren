<?php if (have_posts()): ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php $thumb = get_post_thumbnail_id(); ?>

		<article class="list-item" ]>
		<?php $categories = wp_get_post_terms($post->ID, 'travel_category', array("fields" => "names")); ?>
			<span class="thumbnail">
				<a href="<?php the_permalink(); ?>" class="<?php echo $categories[0]; ?>" data-category="<?php echo $categories[0]; ?>" data-src-thumb="<?php echo wp_get_attachment_image_src( $thumb, 'small' )[0]; ?>">
					<?php the_post_thumbnail("small-ratio"); ?>
				</a>
			</span>
			<span class="title"><a href="<?php the_permalink(); ?>" data-category="<?php echo $categories[0]; ?>"><strong><?php the_title(); ?></strong></a></span>
			<span class="excerpt"><?php the_excerpt(); ?></span>
			<span class="map">
				<div class="acf-map">
					<div class="marker" data-lat="<?php get_field("latitude"); ?>" data-lng="<?php get_field("longitude"); ?>"></div>
				</div>
			</span>
			<ul class="cat">
			 <?php $posttags = get_the_tags(); ?>
					<?php if ( ! empty( $categories ) ) { ?>
						<?php foreach( $categories as $category ) { ?>
							<li><button class="filter-button" data-category="<?php echo $category; ?>"><small><?php echo $category; ?></small></button></li>
						<?php } ?>
					<?php } ?>
				<!-- <?php if ( ! empty( $posttags ) ) { ?>
					<?php foreach( $posttags as $tag ) { ?>
						<li><button class="filter-button" data-category="<?php echo $tag->name; ?>"><small><?php echo $tag->name; ?></small></button></li>
					<?php } ?>
				<?php } ?> -->
			</ul>
		</article>

	<?php endwhile; ?>
<?php endif; ?>

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
