<?php if(defined('ENVIRONMENT') && constant('ENVIRONMENT') === 'live'): ?>
<?php  $link = is_single() || is_page() ? get_permalink() : get_bloginfo('url'); ?>
<div id="social" class="section">
	<div class="g-plusone" data-href="<?php echo $link; ?>" data-size="medium"></div>
	<div class="fb-like" data-href="<?php echo $link; ?>" data-layout="button_count" data-send="false" data-width="100" data-show-faces="false" data-font="arial"></div>
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $link; ?>">Tweet</a>
<!-- end of div #social -->
</div>
<?php endif; ?>