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

				var container;
				var camera, scene, renderer;
				var group;
				var temp = new Array();
				var controls, particle, background;

				$(window).load(function(){
					init();
					animate();
				});

				$(window).resize(function(){
					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
					renderer.setSize( window.innerWidth, window.innerHeight );
					controls.handleResize();
				});

				function init() {

					scene = new THREE.Scene();
					camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 10000);
					renderer = new THREE.WebGLRenderer({ antialias: false });
					renderer.setClearColor(0x000000, 1.0);
					renderer.setSize(window.innerWidth, window.innerHeight);

					camera.position.x = 1200;
					camera.position.y = 1200;
					camera.position.z = 1200;

					controls = new THREE.TrackballControls( camera );
					controls.rotateSpeed = 0.1;
					controls.zoomSpeed = 1.2;
					controls.panSpeed = 0.8;
					controls.noZoom = false;
					controls.noPan = false;
					controls.staticMoving = true;
					controls.dynamicDampingFactor = 0.3;
					controls.keys = [ 65, 83, 68 ];

					particle = new THREE.Object3D();
					scene.add(particle);

					for(var i = 0; i < GLEON_DATA.list.length; i++) {
				    var group = GLEON_DATA.list[i];
				    // console.log(group.id);
						// console.log(group.name);
						if(group.name.toUpperCase() === "WEST LAKE OKOBOJI"){
							console.log("1");
							for(var l = 0; l < group.sites.length; l++) {
						    var site = group.sites[l];
								if(site.name.toUpperCase() === "OKOBOJI LAKE"){
									console.log("2");
							    // console.log(site.id);
									// console.log(site.name);
									temp.push([site.data[6].value, 0, site.data[10].value]);   //10
							    temp.push([site.data[13].value, 10, site.data[12].value]); //10
									temp.push([site.data[16].value, 16, site.data[15].value]); //16
									temp.push([site.data[19].value, 23, site.data[18].value]); //23
									temp.push([site.data[22].value, 30, site.data[21].value]); //30
									temp.push([site.data[25].value, 36, site.data[24].value]); //36
									temp.push([site.data[28].value, 43, site.data[27].value]); //43
									temp.push([site.data[31].value, 49, site.data[30].value]); //49
									temp.push([site.data[34].value, 56, site.data[33].value]); //56
									temp.push([site.data[37].value, 62, site.data[36].value]); //62
									temp.push([site.data[40].value, 69, site.data[39].value]); //69
									temp.push([site.data[43].value, 75, site.data[42].value]); //75
									temp.push([site.data[46].value, 82, site.data[45].value]); //82

									var ambientLight = new THREE.AmbientLight(0x999999 );
								  scene.add(ambientLight);

								  var lights = [];
									lights[0] = new THREE.DirectionalLight( 0xffffff, 1 );
									lights[0].position.set( 1, 0, 0 );
									lights[1] = new THREE.DirectionalLight( 0x11E8BB, 1 );
									lights[1].position.set( 0.75, 1, 0.5 );
									lights[2] = new THREE.DirectionalLight( 0x8200C9, 1 );
									lights[2].position.set( -0.75, -1, 0.5 );
									scene.add( lights[0] );
									scene.add( lights[1] );
									scene.add( lights[2] );

									var geometry = new THREE.SphereGeometry( 1, 60, 60 );
									var material = new THREE.MeshBasicMaterial({
								    color: 0xffffff,
								    flatShading: THREE.FlatShading
								  });

									for (var i = 0; i < 1200; i++) {
										var circle = new THREE.Mesh( geometry, material );
								    circle.position.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).normalize();
								    circle.position.multiplyScalar(90 + (Math.random() * 800));
								    particle.add(circle);
								  }
								}
							}
						}
					}

					renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
					renderer.setPixelRatio( window.devicePixelRatio );
					renderer.setSize( window.innerWidth, window.innerHeight );
					$(".three").append( renderer.domElement );

				}

				function animate() {
					requestAnimationFrame( animate );
					particle.rotation.x -= 0.0000;
					particle.rotation.y -= 0.0040;
					controls.update();
					renderer.render( scene, camera );
				}

				// console.log(GLEON_DATA);
        //
				// for(var i = 0; i < GLEON_DATA.list.length; i++) {
			  //   var group = GLEON_DATA.list[i];
			  //   console.log(group.id);
				// 	console.log(group.name);
				// 	for(var y = 0; y < group.sites.length; y++) {
				//     var site = group.sites[y];
				//     // console.log(site.id);
				// 		// console.log(site.name);
				// 		for(var n = 0; n < site.data.length; n++) {
				// 	    var data = site.data[n];
				// 			console.log(data.name);
				// 			console.log(data.value);
				// 			console.log(data.unit);
				// 		}
				// 	}
				// }

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

				Number.prototype.map = function (in_min, in_max, out_min, out_max) {
				  return (this - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
				}

			});
		})(jQuery, this);

	</script>

<?php get_footer(); ?>
