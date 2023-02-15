<?php $authors = get_users(array(
	'exclude'	=> array(1)
)); ?>
<section class="authors">
	<h2>Authors</h2>
	
	<?php foreach($authors as $author): ?>
	<?php
	$name			= $author->display_name;
	$url			= $author->user_url;
	$description	= get_the_author_meta('user_description', $author->ID);
	$twitter		= get_the_author_meta('twitter', $author->ID);
	$avatar			= get_avatar($author->ID, 44, '', $name);
	?>
	<article class="vcard">
		<?php echo $avatar; ?>
		<h3 class="fn"><?php echo $name; ?></h3>
		<?php if(!empty($description)): ?>
		<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
		<?php if(!empty($url)): ?>
		<p class="url"><a href="<?php echo $url; ?>"><?php echo template_short_url($url, false); ?></a></p>
		<?php endif; ?>
	</article>
	<?php endforeach; ?>
<!-- end section authors -->
</section>