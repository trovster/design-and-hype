<?php
$post_format		= get_post_format();
$category			= template_taxonomy_single_object('category', get_the_ID());
//$category_color	= get_taxonomy_colour($category);
//style="background-color: <?php echo $category_color;
//data-color="<?php echo $category_color;

$custom		= get_post_custom(get_the_ID());
$link		= template_get_custom_field($custom, 'url'); // '_format_' . $post_format . '_'
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-isotope-filter="<?php echo $category->slug; ?>" itemscope itemtype="http://schema.org/CreativeWork">
	
	<?php if(has_post_thumbnail()): ?>
	<section class="entry-photo">
		<?php the_post_thumbnail('listing', 'itemprop=image'); ?>
	</section>
	<?php endif; ?>
	
	<section class="entry-info">
		<header class="entry-header">
			<h2 class="entry-title" itemprop="name"><a href="<?php the_permalink(); ?>" rel="bookmark" class="url"><?php the_title(); ?></a></h2>
		</header>

		<footer class="entry-meta">
			<p class="entry-date date"><?php echo get_the_date(); ?></p>
			<?php $tags = get_the_term_list(0, 'post_tag', '', ', ', ''); ?>
			<?php if(!empty($tags) && is_string($tags)): ?>
			<p class="entry-tags"><strong>Tagged as </strong><?php echo $tags; ?></p>
			<?php endif; ?>
		</footer>
	</section>
	
	<?php if(function_exists('attachments_get_attachments')): ?>
	<?php
	$attachments	= attachments_get_attachments();
    $total			= count($attachments);
	?>
	<?php if($total > 0): ?>
	<section class="entry-content">
		<ul>
			<?php for($i = 0; $i < $total; $i++): ?>
			<li><?php echo wp_get_attachment_image($attachments[$i]['id'], 'listing'); ?></li>
			<?php endfor; ?>
		</ul>
	</section>
	<?php endif; ?>
	<?php endif; ?>
	
<!-- end of article #post-<?php the_ID(); ?> -->
</article>