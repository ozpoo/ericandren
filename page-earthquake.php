<?php get_header(); ?>

<style>
	.info {
		position: fixed;
		top: 20vh;
		left: 0px;
		pointer-events: none;
	}
	.info p {
		padding: .6vh 2vw;
	}
</style>
	<main role="main">

		<section id="container" class="three"></section>

		<div class="info">
			<div><p>Total: <span class="count"></span></p></div>
			<div><p>Max: <span class="max"></span></p></div>
			<div><p>Min: <span class="min"></span></p></div>
			<div><p>Average: <span class="average"></span></p></div>
		</div>

	</main>

	<script>
		( function ( $, root, undefined ) {
			$( function () {
				'use strict';

				var container, globe, string, compiled, data;
				var count, max, min, average;

				container = document.getElementById('container');
				globe = new DAT.Globe( container );
				string = '';
				count = 0;
				max = 0;
				min = 10000000000000000;
				average = 0;

				$.each(EARTHQUAKE_DATA.features, function (key, val) {
					// console.log(val.properties.mag);
					// console.log(val.properties.place);
					// console.log(val.properties.time);
					// console.log(val.properties.title);
					// console.log(val.geometry.coordinates[0]);
					// console.log(val.geometry.coordinates[1]);
					// console.log(val.geometry.coordinates[2]);
					string += val.geometry.coordinates[0] + "," + val.geometry.coordinates[1] + "," + val.properties.mag + ",";
					count ++;
					if(val.properties.mag > max) { max = val.properties.mag; }
					if(val.properties.mag < min) { min = val.properties.mag; }
					average += val.properties.mag;
				});

				average = average / count;

				string = string.replace(/,\s*$/, "");
				compiled = '[["1990",[' + string + ']]]';
				data = JSON.parse( compiled );

        window.data = data;
        for (var i=0;i<data.length;i++) {
          globe.addData(data[i][1], {format: 'magnitude', name: data[i][0], animated: true});
        }

        globe.createPoints();
        globe.animate();

				$(".count").html(count);
				$(".max").html(max);
				$(".min").html(min);
				$(".average").html(average);

			});
		})(jQuery, this);
	</script>

<?php get_footer(); ?>
