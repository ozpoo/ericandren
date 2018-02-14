<?php get_header(); ?>

<style>
	html {
		background: #161616;
	}
	body {
		color: white;
	}
	a{
	 color: white;
	 border-bottom: 1px white solid;
	 text-decoration: none;
	}
	a:hover {
	 border-bottom: 1px transparent solid;
	}
	.email {
		position: fixed;
		bottom: 0px;
		left: 50%;
		transform: translate3d(-50%, 0, 0);
		z-index: 2;
	}
	.callout {
		position: fixed;
		top: 50%;
		left: 50%;
		font-size: calc(20px + 7vw);
		transform: translate3d(-50%, -50%, 0);
		z-index: 2;
		width: 100%;
		text-align: center;
		pointer-events: none;
		font-weight: 600;
		font-family: 'BlackRock Free';
	}
	.threejs {
		position: fixed;
		top: 0px;
		left: 0px;
		height: 100vh;
		width: 100vw;
		z-index: 1;
		filter: brightness(.8);
	}
	.threejs {
		opacity: 0;
	  transition: opacity 660ms cubic-bezier(.165, .84, .44, 1);
	  will-change: opacity;
		backface-visibility: hidden;
	}
	.threejs.show {
		opacity: 1;
	}
	small {
		font-size: .9em;
	}
</style>

	<main role="main" class="main">

		<section class="threejs"></section>

		<section class="email">
			<p><small><a href="mailto:hello@ericandren.com">hello@ericandren.com</a></small></p>
		</section>

		<section class="callout">Coming Soon</section>

	</main>

	<script>

	(function ($, root, undefined) {

		$(function () {

			var camera, scene, renderer, plane;
			var video, videoTexture,videoMaterial;
			var composer;
			var shaderTime = 0;
			var badTVParams, badTVPass;
			var staticParams, staticPass;
			var rgbParams, rgbPass;
			var filmParams, filmPass;
			var renderPass, copyPass;
			var pnoise, globalParams;

			$(window).load(function(){
				init();
				animate();
				setTimeout(function(){
					$(".threejs").addClass("show");
				}, 880)
			});

			function init() {

				camera = new THREE.PerspectiveCamera(55, 1080/ 720, 20, 3000);
				camera.position.z = 1000;
				scene = new THREE.Scene();

				video = document.createElement( 'video' );
				video.loop = true;
				video.src = "<?php echo get_template_directory_uri(); ?>/assets/video/me.webm";
				video.play();

				//Use webcam
				// video = document.createElement('video');
				// video.width = 320;
				// video.height = 240;
				// video.autoplay = true;
				// video.loop = true;
				// //Webcam video
				// window.URL = window.URL || window.webkitURL;
				// navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
				// //get webcam
				// navigator.getUserMedia({
				// 	video: true
				// }, function(stream) {
				// 	//on webcam enabled
				// 	video.src = window.URL.createObjectURL(stream);
				// }, function(error) {
				// 	prompt.innerHTML = 'Unable to capture WebCam. Please reload the page.';
				// });

				videoTexture = new THREE.Texture( video );
				videoTexture.minFilter = THREE.LinearFilter;
				videoTexture.magFilter = THREE.LinearFilter;
				videoMaterial = new THREE.MeshBasicMaterial( {
					map: videoTexture
				} );

				var planeGeometry = new THREE.PlaneGeometry( 1080, 720,1,1 );
				plane = new THREE.Mesh( planeGeometry, videoMaterial );
				scene.add( plane );
				plane.z = 0;

				setScale();

				renderer = new THREE.WebGLRenderer();
				renderer.setSize( 800, 600 );

				$(".threejs").append( renderer.domElement );

				renderPass = new THREE.RenderPass( scene, camera );
				badTVPass = new THREE.ShaderPass( THREE.BadTVShader );
				rgbPass = new THREE.ShaderPass( THREE.RGBShiftShader );
				filmPass = new THREE.ShaderPass( THREE.FilmShader );
				staticPass = new THREE.ShaderPass( THREE.StaticShader );
				copyPass = new THREE.ShaderPass( THREE.CopyShader );

				filmPass.uniforms.grayscale.value = 0;

				badTVParams = {
					mute:true,
					show: true,
					distortion: 3.0,
					distortion2: 1.0,
					speed: 0.3,
					rollSpeed: 0.1
				};

				staticParams = {
					show: true,
					amount:0.5,
					size:4.0
				};

				rgbParams = {
					show: true,
					amount: 0.005,
					angle: 0.0,
				};

				filmParams = {
					show: true,
					count: 800,
					sIntensity: 0.9,
					nIntensity: 0.4
				};

				onToggleShaders();
				onToggleMute();
				onParamsChange();

				window.addEventListener('resize', onResize, false);
				renderer.domElement.addEventListener('click', randomizeParams, false);
				setInterval(function(){ randomizeParams(); console.log('rando'); }, 2400);

				onResize();
				randomizeParams();

			}

			function onParamsChange() {

				badTVPass.uniforms[ 'distortion' ].value = badTVParams.distortion;
				badTVPass.uniforms[ 'distortion2' ].value = badTVParams.distortion2;
				badTVPass.uniforms[ 'speed' ].value = badTVParams.speed;
				badTVPass.uniforms[ 'rollSpeed' ].value = badTVParams.rollSpeed;
				staticPass.uniforms[ 'amount' ].value = staticParams.amount;
				staticPass.uniforms[ 'size' ].value = staticParams.size;
				rgbPass.uniforms[ 'angle' ].value = rgbParams.angle*Math.PI;
				rgbPass.uniforms[ 'amount' ].value = rgbParams.amount;
				filmPass.uniforms[ 'sCount' ].value = filmParams.count;
				filmPass.uniforms[ 'sIntensity' ].value = filmParams.sIntensity;
				filmPass.uniforms[ 'nIntensity' ].value = filmParams.nIntensity;

			}

			function randomizeParams() {

				if (Math.random() <0.2) {

					badTVParams.distortion = 0.1;
					badTVParams.distortion2 =0.1;
					badTVParams.speed =0;
					badTVParams.rollSpeed =0;
					rgbParams.angle = 0;
					rgbParams.amount = 0;
					staticParams.amount = 0;

				} else {

					badTVParams.distortion = Math.random()*10+0.1;
					badTVParams.distortion2 =Math.random()*10+0.1;
					badTVParams.speed =Math.random()*0.4;
					badTVParams.rollSpeed =Math.random()*0.2;
					rgbParams.angle = Math.random()*2;
					rgbParams.amount = Math.random()*0.03;
					staticParams.amount = Math.random()*0.2;

				}

				onParamsChange();

			}

			function onToggleMute() {
				video.volume  = badTVParams.mute ? 0 : 1;
			}

			function onToggleShaders() {

				composer = new THREE.EffectComposer( renderer);
				composer.addPass( renderPass );

				if (filmParams.show) {
					composer.addPass( filmPass );
				}

				if (badTVParams.show) {
					composer.addPass( badTVPass );
				}

				if (rgbParams.show) {
					composer.addPass( rgbPass );
				}

				if (staticParams.show) {
					composer.addPass( staticPass );
				}

				composer.addPass( copyPass );
				copyPass.renderToScreen = true;

			}

			function animate() {

				shaderTime += 0.1;
				badTVPass.uniforms[ 'time' ].value =  shaderTime;
				filmPass.uniforms[ 'time' ].value =  shaderTime;
				staticPass.uniforms[ 'time' ].value =  shaderTime;

				if ( video.readyState === video.HAVE_ENOUGH_DATA ) {
					if ( videoTexture ) videoTexture.needsUpdate = true;
				}

				requestAnimationFrame( animate );
				composer.render( 0.1 );
			}

			function onResize() {
				setScale();
				renderer.setSize(window.innerWidth, window.innerHeight);
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
			}

			function setScale() {
				var scale = $(window).width()/$(window).height();
				if(scale < 1.45) {
					scale = 1.45;
				}
				plane.scale.x = plane.scale.y = scale;
			}

		});

	})(jQuery, this);

	</script>

<?php get_footer(); ?>
