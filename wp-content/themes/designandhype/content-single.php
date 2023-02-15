<?php
$post_format		= get_post_format();
$category			= template_taxonomy_single_object('category', get_the_ID());
$category_color		= get_taxonomy_colour($category);
//style="background-color: <?php echo $category_color;
//data-color="<?php echo $category_color;

$custom		= get_post_custom(get_the_ID());
$link		= template_get_custom_field($custom, 'url'); // '_format_' . $post_format . '_'
$via		= template_get_custom_field($custom, 'via'); // '_format_' . $post_format . '_'
$tags		= get_the_term_list(0, 'post_tag', '', ', ', '');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/CreativeWork">
	
	<header class="entry-header">
		<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1>
		<h2><?php printf('Posted on %s by <a href="%s" class="fn">%s</a> in <a href="%s" class="category" style="color: %s;">%s</a>', get_the_date(), get_author_posts_url(get_the_author_meta('ID')), get_the_author_meta('display_name'), get_category_link($category), $category_color, $category->name); ?></h2>
	</header>
	
	<section class="entry-content" itemprop="description">
		<?php the_content(); ?>
		<?php echo add_attachments_to_content(''); ?>
	</section>
	
	<footer class="entry-meta">
		<?php if(!empty($tags) && is_string($tags)): ?>
		<p class="entry-tags"><strong>Tagged as </strong><?php echo $tags; ?></p>
		<?php endif; ?>
		<?php if(!empty($link)): ?>
		<p class="entry-permalink">Visit: <a href="<?php echo $link; ?>" class="url" rel="bookmark" style="color: <?php echo $category_color; ?>;"><?php echo template_short_url($link); ?></a></p>
		<?php endif; ?>
		<?php if(!empty($via)): ?>
		<p class="entry-via">Via: <a href="<?php echo $via; ?>"><?php echo template_short_url($via); ?></a></p>
		<?php endif; ?>
	</footer>
	
<!-- end of article #post-<?php the_ID(); ?> -->
</article>