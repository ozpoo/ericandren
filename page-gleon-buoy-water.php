<?php get_header(); ?>

<style>
	body {
		/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#232323+0,191919+100 */
		background: #232323; /* Old browsers */
		background: -moz-linear-gradient(top, #232323 0%, #191919 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(top, #232323 0%,#191919 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom, #232323 0%,#191919 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#232323', endColorstr='#191919',GradientType=0 ); /* IE6-9 */
	}
	.three{
		height: 100vh;
		width: 100vw;
	}
</style>

	<main role="main">

		<section class="three">

		</section>

	</main>

	<script>

		( function ( $, root, undefined ) {
			$( function () {
				'use strict';

				var lastTime = (new Date()).getTime();
				var renderer, camera, scene, controls, ocean;

				console.log(GLEON_DATA);

				$(window).load(function(){
					init();
					animate();
				});

				$(window).resize(function () {
					camera.aspect = $(window).width() / $(window).height();
					camera.updateProjectionMatrix();
					renderer.setSize($(window).width(), $(window).height());
				});

				function init() {

					scene = new THREE.Scene();
					camera = new THREE.PerspectiveCamera(55.0, $(window).width() / $(window).height(), 0.5, 300000);
					renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });

					renderer.setPixelRatio( window.devicePixelRatio );
					renderer.setSize( window.innerWidth, window.innerHeight );
					renderer.context.getExtension('OES_texture_float');
					renderer.context.getExtension('OES_texture_float_linear');

					camera.position.set(450, 350, 450);
					camera.lookAt(new THREE.Vector3());

					controls = new THREE.OrbitControls(camera, renderer.domElement);
					controls.userPan = false;
					controls.userPanSpeed = 0.0;
					controls.minDistance = 0;
					controls.maxDistance = 2000.0;
					controls.minPolarAngle = 0;
					controls.maxPolarAngle = Math.PI * 0.495;

					var gsize = 512;
					var res = 1024;
					var gres = res / 2;
					var origx = -gsize / 2;
					var origz = -gsize / 2;

					ocean = new THREE.Ocean(renderer, camera, scene, {
						USE_HALF_FLOAT : true,
						INITIAL_SIZE : 256.0,
						INITIAL_WIND : [10.0, 10.0],
						INITIAL_CHOPPINESS : 1.5,
						CLEAR_COLOR : [1.0, 1.0, 1.0, 0.0],
						GEOMETRY_ORIGIN : [origx, origz],
						SUN_DIRECTION : [-1.0, 1.0, 1.0],
						OCEAN_COLOR: new THREE.Vector3(0.004, 0.016, 0.047),
						SKY_COLOR: new THREE.Vector3(3.2, 9.6, 12.8),
						EXPOSURE : 0.35,
						GEOMETRY_RESOLUTION: gres,
						GEOMETRY_SIZE : gsize,
						RESOLUTION : res
					});

					ocean.materialOcean.uniforms.u_projectionMatrix = { value: camera.projectionMatrix };
					ocean.materialOcean.uniforms.u_viewMatrix = { value: camera.matrixWorldInverse };
					ocean.materialOcean.uniforms.u_cameraPosition = { value: camera.position };

					scene.add(ocean.oceanMesh);

					$(".three").append(renderer.domElement);

				}

				function animate() {
					requestAnimationFrame( animate );
					update();
				}

				function update() {

					var currentTime = new Date().getTime();
					ocean.deltaTime = (currentTime - lastTime) / 1000 || 0.0;
					lastTime = currentTime;
					ocean.render(ocean.deltaTime);
					ocean.overrideMaterial = ocean.materialOcean;

					if (ocean.changed) {
						ocean.materialOcean.uniforms.u_size.value = ocean.size;
						ocean.materialOcean.uniforms.u_sunDirection.value.set( ocean.sunDirectionX, ocean.sunDirectionY, ocean.sunDirectionZ );
						ocean.materialOcean.uniforms.u_exposure.value = ocean.exposure;
						ocean.changed = false;
					}

					ocean.materialOcean.uniforms.u_normalMap.value = ocean.normalMapFramebuffer.texture;
					ocean.materialOcean.uniforms.u_displacementMap.value = ocean.displacementMapFramebuffer.texture;
					ocean.materialOcean.uniforms.u_projectionMatrix.value = camera.projectionMatrix;
					ocean.materialOcean.uniforms.u_viewMatrix.value = camera.matrixWorldInverse;
					ocean.materialOcean.uniforms.u_cameraPosition.value = camera.position;
					ocean.materialOcean.depthTest = true;
					renderer.render(scene, camera);

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

<?php get_footer(); ?>
