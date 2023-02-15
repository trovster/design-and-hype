<?php get_header(); ?>

<?php $post_type = template_get_post_type(); ?>
	
<?php get_sidebar($post_type); ?>

<div id="content-primary">
	
	<div id="<?php echo $post_type; ?>" class="archive hatom">
		<?php rewind_posts(); ?>
		<?php if(have_posts()): ?>
			<?php while(have_posts()): the_post(); ?>
				<?php get_template_part('content', get_post_format()); ?>
			<?php endwhile; ?>
		<?php endif; ?>
	<!-- end of div #<?php echo $post_type; ?> -->
	</div>
	
	<nav id="pagination">
		<?php echo site_get_posts_nav_link('', 'Previous', 'Next'); ?>
	</nav>
	
<!-- end of div id #content-primary -->
</div>

<?php get_footer(); ?>