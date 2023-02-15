<?php get_header(); ?>

<?php get_sidebar('page'); ?>

<div id="content-primary">
	
	<?php while(have_posts()): the_post(); ?>
	<div class="hentry">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<h2><?php the_excerpt(); ?></h2>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
		
		<?php if($post->post_name === 'about'): ?>
		<?php //get_template_part('authors'); ?>
		<?php endif; ?>
	</div>
	<?php endwhile; ?>

<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>