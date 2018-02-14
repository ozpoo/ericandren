<?php get_header(); ?>

	<main role="main">

		<section class="three"></section>

	</main>

	<script>
		( function ( $, root, undefined ) {
			$( function () {
				'use strict';

				if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

				console.log(TOPO_IMAGE);

				var renderer;
				var scene;
				var camera;
				var controls;
				var scale = chroma.scale(['blue', 'green', 'red']).domain([0, 50]);

				$(window).load(function(){
					init();
					animate();
				});

				function init() {

				    scene = new THREE.Scene();
				    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 10000);
				    renderer = new THREE.WebGLRenderer({ antialias: false });
				    renderer.setClearColor(0x000000, 1.0);
				    renderer.setSize(window.innerWidth, window.innerHeight);

				    var light = new THREE.DirectionalLight();
				    light.position.set(1200, 1200, 1200);

				    scene.add(light);

				    camera.position.x = 1200;
				    camera.position.y = 500;
				    camera.position.z = 1200;

				    camera.lookAt(scene.position);

				    $(".three").append(renderer.domElement);

						controls = new THREE.TrackballControls( camera );

						controls.rotateSpeed = 1.0;
						controls.zoomSpeed = 1.2;
						controls.panSpeed = 0.8;
						controls.noZoom = false;
						controls.noPan = false;
						controls.staticMoving = true;
						controls.dynamicDampingFactor = 0.3;
						controls.keys = [ 65, 83, 68 ];

						controls.addEventListener( 'change', render );

				    createGeometryFromMap();

						window.addEventListener( 'resize', onWindowResize, false );

				    render();
				}

				function createGeometryFromMap() {

				    var depth = 512;
				    var width = 512;
				    var spacingX = 3;
				    var spacingZ = 3;
				    var heightOffset = 2;
				    var canvas = document.createElement('canvas');

				    canvas.width = TOPO_IMAGE.width;
				    canvas.height = TOPO_IMAGE.height;
				    var ctx = canvas.getContext('2d');
				    var img = new Image();
				    img.src = TOPO_IMAGE.src;

				    img.onload = function () {

			        ctx.drawImage(img, 0, 0);
			        var pixel = ctx.getImageData(0, 0, width, depth);
			        var geom = new THREE.Geometry;
			        var output = [];
			        for (var x = 0; x < depth; x++) {
		            for (var z = 0; z < width; z++) {
	                var yValue = pixel.data[z * 4 + (depth * x * 4)] / heightOffset;
	                var vertex = new THREE.Vector3(x * spacingX, yValue, z * spacingZ);
	                geom.vertices.push(vertex);
		            }
			        }

			        for (var z = 0; z < depth - 1; z++) {
		            for (var x = 0; x < width - 1; x++) {
	                var a = x + z * width;
	                var b = (x + 1) + (z * width);
	                var c = x + ((z + 1) * width);
	                var d = (x + 1) + ((z + 1) * width);
	                var face1 = new THREE.Face3(a, b, d);
	                var face2 = new THREE.Face3(d, c, a);
	                face1.color = new THREE.Color(scale(getHighPoint(geom, face1)).hex());
	                face2.color = new THREE.Color(scale(getHighPoint(geom, face2)).hex())
	                geom.faces.push(face1);
	                geom.faces.push(face2);
		            }
			        }

			        geom.computeVertexNormals(true);
			        geom.computeFaceNormals();
			        geom.computeBoundingBox();

			        var zMax = geom.boundingBox.max.z;
			        var xMax = geom.boundingBox.max.x;
			        var mesh = new THREE.Mesh(geom, new THREE.MeshLambertMaterial({
		            vertexColors: THREE.FaceColors,
		            color: 0x666666,
		            shading: THREE.NoShading
			        }));

			        mesh.translateX(-xMax / 2);
			        mesh.translateZ(-zMax / 2);
			        scene.add(mesh);
			        mesh.name = 'valley';

				    };
				}

				function getHighPoint(geometry, face) {

			    var v1 = geometry.vertices[face.a].y;
			    var v2 = geometry.vertices[face.b].y;
			    var v3 = geometry.vertices[face.c].y;
			    return Math.max(v1, v2, v3);

				}

				function onWindowResize() {

					camera.aspect = window.innerWidth / window.innerHeight;
					camera.updateProjectionMatrix();
					renderer.setSize( window.innerWidth, window.innerHeight );
					controls.handleResize();

					render();

				}

				function animate() {

					requestAnimationFrame( animate );
					controls.update();

				}

				function render() {

					renderer.render( scene, camera );

				}

			});
		})(jQuery, this);
	</script>

<?php get_footer(); ?>
