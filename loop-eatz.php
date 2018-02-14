<?php if (have_posts()): ?>
<?php while (have_posts()) : the_post(); ?>
<?php
	$title = get_the_title();
	$description = get_the_excerpt();
	$thumb = get_post_thumbnail_id();
	$background = get_field("background");
?>

	<article class="list-item">
		<?php $categories = wp_get_post_terms($post->ID, 'eatz_category', array("fields" => "names")); ?>
		<a href="<?php the_permalink(); ?>" data-category="<?php echo $categories[0]; ?>">
			<span class="thumbnail"><?php the_post_thumbnail("small-ratio"); ?></span>
			<span class="title"><strong><?php the_title(); ?></strong></span>
			<span class="excerpt"><?php the_excerpt(); ?></span>
			<span class="cat">
				<?php $posttags = get_the_tags(); ?>
				<small>
					<?php
						$separator = ' ';
						$output = '';
						if ( ! empty( $categories ) ) {
							foreach( $categories as $category ) {
									$output .= $category . $separator;
							}
							echo trim( $output, $separator );
						}
					?>
					<?php
						$separator = ' ';
						$output = '';
						if ( ! empty( $posttags ) ) {
							foreach( $posttags as $tag ) {
									$output .= $tag->name . $separator;
							}
							echo trim( $output, $separator );
						}
					?>
				 </small>
			</span>
		</a>
	</article>

<?php endwhile; ?>
<?php endif; ?>
