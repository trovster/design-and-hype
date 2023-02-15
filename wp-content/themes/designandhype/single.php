<?php get_header(); ?>

<?php $post_type = template_get_post_type(); ?>
	
<?php get_sidebar($post_type); ?>

<div id="content-primary">
	
	<div id="<?php echo $post_type; ?>" class="single hatom">
		<?php rewind_posts(); ?>
		<?php if(have_posts()): ?>
			<?php while(have_posts()): the_post(); ?>
				<?php get_template_part('content', 'single'); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	<!-- end of div #<?php echo $post_type; ?> -->
	</div>
	
	<a href="/" class="back">Back</a>
	
<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>