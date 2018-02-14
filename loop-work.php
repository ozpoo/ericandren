<?php if (have_posts()): ?>
	<?php while (have_posts()) : the_post(); ?>
		<?php $thumb = get_post_thumbnail_id(); ?>

		<article class="list-item" ]>
		<?php $categories = wp_get_post_terms($post->ID, 'work_category', array("fields" => "names")); ?>
			<span class="thumbnail">
				<a href="<?php the_permalink(); ?>" class="<?php echo $categories[0]; ?>" data-category="<?php echo $categories[0]; ?>" data-src-thumb="<?php echo wp_get_attachment_image_src( $thumb, 'small' )[0]; ?>">
					<?php the_post_thumbnail("small-ratio"); ?>
				</a>
			</span>
			<span class="title"><a href="<?php the_permalink(); ?>" data-category="<?php echo $categories[0]; ?>"><strong><?php the_title(); ?></strong></a></span>
			<span class="excerpt"><?php the_excerpt(); ?></span>
			<ul class="cat">
			 <?php $posttags = get_the_tags(); ?>
					<?php if ( ! empty( $categories ) ) { ?>
						<?php foreach( $categories as $category ) { ?>
							<li><button class="filter-button" data-category="<?php echo $category; ?>"><small><?php echo $category; ?></small></button></li>
						<?php } ?>
					<?php } ?>
				<!-- <?php if ( ! empty( $posttags ) ) { ?>
					<?php foreach( $posttags as $tag ) { ?>
						<li><button class="filter-button" data-category="<?php echo $tag->name; ?>"><small><?php echo $tag->name; ?></small></button></li>
					<?php } ?>
				<?php } ?> -->
			</ul>
		</article>

	<?php endwhile; ?>
<?php endif; ?>
