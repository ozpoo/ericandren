<?php get_header(); ?>

	<style>

		.name {
			position: fixed;
			top: 0px;
			left: 0px;
		}
		.info {
			position: fixed;
			top: 0px;
			right: 0px;
		}
		.wrap {
			position: fixed;
			top: 0px;
			left: 0px;
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 0;
			height: 100vh;
			width: 100vw;
		}
		#visualization{
			height: 420px;
			width: 420px;
			overflow: hidden;
			/* border-radius: 50%; */
		}
		#visualization canvas{
			overflow: hidden;
			border: 4px solid rgb(0, 0, 255);
		}
		.overlay {
			height: 100vh;
			width: 100vw;
			position: fixed;
			top: 0px;
			left: 0px;
			overflow: hidden;
			z-index: 10;
			display: flex;
			justify-content: center;
			align-items: center;
			mix-blend-mode: screen;
			display: none;
		}

		.overlay img {
			height: 100%;
			width: 100%;
			object-fit: cover;
			object-position: center;
		}

	</style>

	<main role="main">

		<section class="name"><p>GLEON</p></section>
		<section class="info"><p id="state"></p></section>

		<div class="wrap">
			<section id="visualization">
				<section class="overlay">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/texture/texture_02.jpg" />
				</section>
			</section>
		</div>

	</main>

	<script>

		( function ( $, root, undefined ) {
			$( function () {

				var s = function( sketch ) {

				  let container, canvas, temp, state, orientation
					let name, latitude, longitude
					let air_temperature, humidity, barometric_pressure, wind_speed, wind_direction, rain, conductivity, ph, carbon_dioxide

				  sketch.setup = function() {
						sketch.temp = []
						sketch.container = $("#visualization")
						sketch.initState()
						sketch.setOrientation(true)
						sketch.strokeWeight(1)

						sketch.dt = 0.01
						sketch.yoff = 0.0
						for(let i = 0; i < GLEON_DATA.list.length; i++) {
					    let group = GLEON_DATA.list[i];
					    // console.log(group.id);
							// console.log(group.name);
							if(group.name.toUpperCase() === "WEST LAKE OKOBOJI") {
								for(let l = 0; l < group.sites.length; l++) {
							    let site = group.sites[l]
									if(site.name.toUpperCase() === "OKOBOJI LAKE") {
								    // console.log(site.id)
										// console.log(site.name)
										sketch.name = site.name
										sketch.latitude = site.latitude
										sketch.longitude = site.longitude

										sketch.air_temperature = site.data[0].value
										sketch.humidity = site.data[1].value
										sketch.barometric_pressure = site.data[2].value
										sketch.wind_speed = site.data[3].value
										sketch.wind_direction = site.data[4].value
										sketch.rain = site.data[5].value
										sketch.conductivity = site.data[7].value
										sketch.conductivity = 460
										sketch.ph = site.data[8].value
										sketch.ph = 84.3
										sketch.carbon_dioxide = site.data[11].value
										sketch.carbon_dioxide = -499.8

										// Temperature DO DOS
										sketch.temp[0] = [site.data[10].value, site.data[9].value, site.data[6].value] //0
										sketch.temp[0] = [56.5, 9.28, 88.9] //0
								    sketch.temp[1] = [site.data[14].value, site.data[13].value, site.data[12].value] //10
										sketch.temp[1] = [56.23, 9.06, 86.8] //10
										sketch.temp[2] = [site.data[17].value, site.data[16].value, site.data[15].value] //16
										sketch.temp[2] = [56.23, 9.17, 87.1] //16
										sketch.temp[3] = [site.data[20].value, site.data[19].value, site.data[18].value] //23
										sketch.temp[3] = [56.19, 8.67, 83.1] //23
										sketch.temp[4] = [site.data[23].value, site.data[22].value, site.data[21].value] //30
										sketch.temp[4] = [56.16, 9.06, 86.8] //30
										sketch.temp[5] = [site.data[26].value, site.data[25].value, site.data[24].value] //36
										sketch.temp[5] = [56.23, 8.83, 84.7] //36
										sketch.temp[6] = [site.data[29].value, site.data[28].value, site.data[27].value] //43
										sketch.temp[6] = [56.17, 9.06, 86.8] //43
										sketch.temp[7] = [site.data[32].value, site.data[31].value, site.data[30].value] //49
										sketch.temp[7] = [56.17, 8.95, 85.7] //49
										sketch.temp[8] = [site.data[35].value, site.data[34].value, site.data[33].value] //56
										sketch.temp[8] = [56.21, 9.02, 86.5] //56
										sketch.temp[9] = [site.data[38].value, site.data[37].value, site.data[36].value] //62
										sketch.temp[9] = [56.08, 8.84, 84.6] //62
										sketch.temp[10] = [site.data[41].value, site.data[40].value, site.data[39].value] //69
										sketch.temp[10] = [56.15, 8.73, 85.6] //69
										sketch.temp[11] = [site.data[44].value, site.data[43].value, site.data[42].value] //75
										sketch.temp[11] = [56.21, 9.02, 86.5] //75
										sketch.temp[12] = [site.data[47].value, site.data[46].value, site.data[45].value] //82
										sketch.temp[12] = [55.85, 8.73, 86.5] //82
									}
								}
							}
						}
				  }

				  sketch.draw = function() {
						switch(sketch.state) {
							case "1":
								sketch.draw01()
								break
							case "2":
								sketch.draw02()
								break
							case "3":
								sketch.draw03()
								break
							case "4":
								sketch.draw04()
								break
							case "5":
								sketch.draw05()
								break
							case "6":
								sketch.draw06()
								break
							case "7":
								sketch.draw07()
								break
							default:
						}
				  }

					sketch.windowResized = function() {
						sketch.setOrientation()
					}

					sketch.draw01 = function() {
						// sketch.rotateY(sketch.radians(sketch.frameCount))
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/2, -sketch.height/2)
					  sketch.noStroke()
						sketch.fill(0, 0, 255)
						sketch.ellipse(sketch.width/2, sketch.height/2, 400, 400, 300)
					}

					let time , dt, yoff

					sketch.draw02 = function() {
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/2, -sketch.height/2)
						sketch.noFill()
						sketch.stroke(0,0,255)
						sketch.time += sketch.dt
						let y = sketch.mouseY
						sketch.beginShape()
						for(let x = 0; x <= sketch.width; x += 1){
							let drift = (sketch.noise(x / 100, y / 300, sketch.time) - 0.5) * 300
							sketch.vertex(x, y + drift)
						}
						sketch.endShape()
					}

					sketch.draw03 = function() {
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/2, -sketch.height/2)
						sketch.noFill()
						sketch.stroke(0, 0, 255)

						// Temperature
						sketch.beginShape()
						let step = sketch.height / (sketch.temp.length - 1)
						for(let i = 0; i < sketch.temp.length; i++) {
							let val = sketch.map(sketch.temp[i][0], 55, 57, 0, sketch.width)
							let depth = step * i
							sketch.vertex(val, depth)
						}
						sketch.endShape()

						// DO
						sketch.beginShape()
						step = sketch.height / (sketch.temp.length - 1)
						for(let i = 0; i < sketch.temp.length; i++) {
							let val = sketch.map(sketch.temp[i][1], 8, 10, 0, sketch.width)
							let depth = step * i
							sketch.vertex(val, depth)
						}
						sketch.endShape()

						// DOS
						sketch.beginShape()
						step = sketch.height / (sketch.temp.length - 1)
						for(let i = 0; i < sketch.temp.length; i++) {
							let val = sketch.map(sketch.temp[i][2], 82, 89, 0, sketch.width)
							let depth = step * i
							sketch.vertex(val, depth)
						}
						sketch.endShape()
					}

					sketch.draw04 = function() {
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/2, -sketch.height/2)
						sketch.noFill()
						sketch.stroke(0, 0, 255)
						let step = sketch.height / (sketch.temp.length + 1)

						// Temperature
						for(let i = 0; i < sketch.temp.length; i++) {
							sketch.beginShape()
							let val = sketch.map(sketch.temp[i][0], 55, 57, 0, sketch.width)
							let depth = (step * i) + step - (step * .3)
							sketch.vertex(0, depth)
							sketch.vertex(val, depth)
							sketch.endShape()
						}

						// DO
						for(let i = 0; i < sketch.temp.length; i++) {
							sketch.beginShape()
							let val = sketch.map(sketch.temp[i][1], 8, 10, 0, sketch.width)
							let depth = (step * i) + step
							sketch.vertex(0, depth)
							sketch.vertex(val, depth)
							sketch.endShape()
						}

						// DOS
						for(let i = 0; i < sketch.temp.length; i++) {
							sketch.beginShape()
							let val = sketch.map(sketch.temp[i][2], 82, 89, 0, sketch.width)
							let depth = (step * i) + step + (step * .3)
							sketch.vertex(0, depth)
							sketch.vertex(val, depth)
							sketch.endShape()
						}
					}

					sketch.draw05 = function() {
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/2, -sketch.height/2)
						sketch.noFill()
						sketch.noStroke()
						let c1, c2
						let color = tinycolor("blue")
						let lastColor = sketch.color(color.spin(sketch.map(sketch.temp[0][0], 55, 57, 0, 40)).toString())
						let step = sketch.height / sketch.temp.length

						for(let i = 0; i < sketch.temp.length; i++) {
							let y = step * i
							color = tinycolor("blue")
							c1 = lastColor
							c2 = sketch.color(color.spin(sketch.map(sketch.temp[i][0], 55, 57, 0, 40)).toString())
							sketch.setGradient(0, y, sketch.windowWidth, step, c1, c2, "Y")
							lastColor = c2
						}
					}

					sketch.draw06 = function() {
						sketch.clear()
						// sketch.rotateY(sketch.radians(sketch.frameCount))
						sketch.colorMode(sketch.RGB, 255, 255, 255)
						// sketch.translate(-sketch.width/4, -sketch.height/2)
						sketch.noFill()
						sketch.stroke(0, 0, 255)
					  sketch.beginShape()
					  let xoff= 0
					  for (let x = 0; x <= sketch.width+10; x += 10) {
					    let y = sketch.map(sketch.noise(xoff, sketch.yoff), 0, 1, 0, sketch.height)
					    sketch.vertex(x, y)
					    xoff += 0.05
					  }
					  sketch.yoff += 0.008
					  // sketch.vertex(sketch.width/2, sketch.height/2)
					  // sketch.vertex(0, sketch.height/2)
					  sketch.endShape()
					}

					sketch.draw07 = function() {
						
					}

					sketch.initState = function() {
						let cookieState = Cookies.get("state")
						if(!cookieState || isNaN(cookieState)) {
							sketch.setState("1")
						} else {
							sketch.setState(cookieState)
						}
					}

					sketch.setState = function(newState) {
						sketch.state = newState.toString()
						Cookies.set("state", sketch.state)
						$("#state").html(sketch.state)
						console.log(sketch.state)
					}

					sketch.getOrientation = function() {
						if(window.innerWidth > window.innerHeight) {
							return "landscape"
						} else {
							return "portrait"
						}
					}

					sketch.setOrientation = function(init = false) {
						sketch.orientation = sketch.getOrientation()
						if(init) {
							if(sketch.orientation == "portrait") {
								// sketch.canvas = sketch.createCanvas($(sketch.container).width(), $(sketch.container).height(), sketch.WEBGL)
								sketch.canvas = sketch.createCanvas($(sketch.container).width(), $(sketch.container).height())
							} else {
								// sketch.canvas = sketch.createCanvas($(sketch.container).width(), $(sketch.container).height(), sketch.WEBGL)
								sketch.canvas = sketch.createCanvas($(sketch.container).width(), $(sketch.container).height())
							}
						} else {
							if(sketch.orientation == "portrait") {
								sketch.resizeCanvas($(sketch.container).width(), $(sketch.container).height())
							} else{
								sketch.resizeCanvas($(sketch.container).width(), $(sketch.container).height())
							}
						}
					}

					sketch.setGradient = function(x, y, w, h, c1, c2, axis) {
					  sketch.noFill();
					  if(axis == "Y") {  // Top to bottom gradient
					    for(let i = y; i <= y+h; i++) {
					      let inter = sketch.map(i, y, y+h, 0, 1);
					      let c = sketch.lerpColor(c1, c2, inter);
					      sketch.stroke(c)
					      sketch.line(x, i, x+w, i)
					    }
					  }
					  else if (axis == "X") {  // Left to right gradient
					    for(let j = x; j <= x+w; j++) {
					      let inter2 = sketch.map(j, x, x+w, 0, 1)
					      let d = sketch.lerpColor(c1, c2, inter2)
					      sketch.stroke(d)
					      sketch.line(j, y, j, y+h)
					    }
					  }
					}

					sketch.keyPressed = function() {
						sketch.clear()
						var newState
					  if (sketch.keyCode === sketch.UP_ARROW) {
					    newState = parseInt(sketch.state) + 1
					  } else if (sketch.keyCode === sketch.DOWN_ARROW) {
							newState = parseInt(sketch.state) - 1
							if(parseInt(newState) < 1) {
								newState = 1
							}
					  }
						sketch.setState(newState)
					}

				}

				var myp5 = new p5(s, 'visualization');

			});
		})(jQuery, this);

	</script>

<?php get_footer(); ?>
