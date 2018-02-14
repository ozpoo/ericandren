<?php get_header(); ?>

	<main role="main">
		<section class="list">
			<?php get_template_part('loop'); ?>
			<?php get_template_part('pagination'); ?>
		</section>
	</main>

<?php get_footer(); ?>
