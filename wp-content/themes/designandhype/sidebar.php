<aside id="content-secondary">
	
	<ul class="categories section">
		<?php
		wp_list_categories(array(
			'title_li'			=> '',
			'show_option_all'	=> 'All',
			'echo'				=> 1,
			'depth'				=> 0,
			'hide_empty'		=> 1,
			'taxonomy'			=> 'category',
			'exclude'			=> array(1),
			'walker'			=> new Walker_Category_Isotope_With_Colour
		));
		?>
	</ul>
	
	<?php get_sidebar('social'); ?>
	
	<?php get_sidebar('credits'); ?>
	
<!-- end of aside #content-secondary -->
</aside>