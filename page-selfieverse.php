<?php get_header(); ?>

	<style>
		.threejs {
			height: 100vh;
			width: 100vw;
			overflow: hidden;
		}
	</style>

	<main role="main">

		<section class="threejs"></section>

	</main>

	<script>

		(function ($, root, undefined) {
			$(function () {
				'use strict';

				var camera, composer, controls, scene, renderer;

				init();
				animate();

				function init() {

					camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 10000 );

					controls = new THREE.FlyControls( camera );
					controls.movementSpeed = 1000;
					controls.domElement = $(".threejs");
					controls.rollSpeed = Math.PI / 24;
					controls.autoForward = false;
					controls.dragToLook = false;

					scene = new THREE.Scene();

					var light = new THREE.DirectionalLight( 0xffffff, 1 );
					light.position.set( 1, 1, 1 ).normalize();
					scene.add( light );

					var geometry = new THREE.BoxBufferGeometry( 40, 40, 40 );

					console.log(SELFIE_DATA);
					jQuery.each(SELFIE_DATA.data, function() {

						var texture = new THREE.TextureLoader().load( this.images.standard_resolution.url );
						texture.wrapS = THREE.RepeatWrapping;
						texture.wrapT = THREE.RepeatWrapping;
						texture.repeat.set( 1, 1 );

						var object = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { map: texture } ) );

						object.position.x = Math.random() * 800 - 400;
						object.position.y = Math.random() * 800 - 400;
						object.position.z = Math.random() * 800 - 400;

						object.rotation.x = Math.random() * 2 * Math.PI;
						object.rotation.y = Math.random() * 2 * Math.PI;
						object.rotation.z = Math.random() * 2 * Math.PI;

						// object.scale.x = Math.random() + 0.5;
						// object.scale.y = Math.random() + 0.5;
						// object.scale.z = Math.random() + 0.5;

						scene.add( object );

					});

					renderer = new THREE.WebGLRenderer( { alpha: true } );
					renderer.setClearColor( 0x000000, 0 );
					renderer.setPixelRatio( window.devicePixelRatio );
					renderer.setSize( window.innerWidth, window.innerHeight );

					$(".threejs").append( renderer.domElement );

					window.addEventListener( 'resize', onWindowResize, false );

					var renderModel = new THREE.RenderPass( scene, camera );
					var effectFilm = new THREE.FilmPass( 0.35, 0.75, 2048, false );

					effectFilm.renderToScreen = true;

					composer = new THREE.EffectComposer( renderer );

					composer.addPass( renderModel );
					composer.addPass( effectFilm );

				}

				function onWindowResize() {

					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
					renderer.setSize( window.innerWidth, window.innerHeight );
					composer.reset();

				}


				function animate() {

					requestAnimationFrame( animate );
					render();

				}

				function render() {

					renderer.render( scene, camera );

				}

			});
		})(jQuery, this);

	</script>

<?php get_footer(); ?>
