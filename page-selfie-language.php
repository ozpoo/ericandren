<?php get_header(); ?>

	<style>
		body {
			background: white;
			color: black;
		}
		section {
			display: flex;
			flex-wrap: wrap;
		}
		figure {
			width: 220px;
		}
		figure img {
		}
	</style>

	<main role="main">

		<section class="instagrams">

			<?php
			global $results;
			// var_dump($results);
			// global $time_curr;
			// global $time_previous;
			// global $time_check;
			// echo $time_curr . '<br>';
			// echo $time_previous . '<br>';
			// echo $time_check . '<br>';

			$terms = get_terms([
				'taxonomy' => 'cat_selfies',
				'hide_empty' => false,
			]);

			foreach($results['data'] as $key => $value) { ?>

				<?php
					$raw = json_encode(current($results['data']));
					$raw = utf8_encode($raw);
				?>

				<form name="post-form" id="post-form">
					<figure>
						<img src="<?php echo $value['images']['standard_resolution']['url']; ?>" />
						<input type="hidden" name="title" id="title" value="<?php echo $value['id']; ?>" >
						<input type="hidden" name="status" id="status" value="publish" >
						<input type="hidden" name="content" id="content" value="<?php echo htmlspecialchars($raw); ?>" >
						<select name="cat_selfies" id="cat_selfies">
							<option value="" selected disabled>Cat</option>
						  <?php foreach($terms as $term) { ?>
				        <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
						  <?php } ?>
						</select>
					</figure>
					<input type="submit" name="submit" value="Submit">
				</form>

			<?php } ?>

		</section>

	</main>

	<script>

		(function ($, root, undefined) {
			$(function () {
				'use strict';

			});
		})(jQuery, this);

	</script>

<?php get_footer(); ?>
