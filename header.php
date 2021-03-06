<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

		<script>
			document.createElement( "picture" );
		</script>

	</head>
	<body <?php body_class(); ?>>

		<header class="header">
			<div class="name">
				<p><a href="<?php echo site_url( '/', 'http' ); ?>">E<span class="hover-text">ric </span>A<span class="hover-text">ndren</span></a></p>
			</div>
			<div class="menu-toggle">
				<p><button>M<span class="hover-text">enu</span></button></p>
				<!-- <ul>
					<?php foreach ( get_post_types( '', 'names' ) as $post_type ): ?>
						<li><a href=""><?php echo $post_type; ?></a></li>
					<?php endforeach; ?>
				</ul> -->
			</div>
		</header>
