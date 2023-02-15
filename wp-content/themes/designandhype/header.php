<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"> <!--<![endif]-->
<?php $classes = array(); ?>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		
                                                                         <!--:-.>
                                                                       .+syyyyssyyo.
                                                                      -yyyyys`.syyys`
                                                                      /yyyyyo `osys:
              .-//+++/:-``-----------.   .-------. --------- `-///:..-oyyyyyo---```.://+++//:-`        `-:/+++/:-`      `-:////:-.
            :syyyyyssyyyyyyysyyyyyyyy/  `oyyyyyyyo`syyyyyyyy+ys+syyysyyyyyyyyyys-+sys+//+syyyyyo.    -oyyyyyyyyyyyo. `:syyyyyyyyyys/`
           /yyyyys.   `-+yyy+.-syyyyy/   .-oyyyyyo ..:yyyyyyy-`+yyyyy-oyyyyyo-.-oyyyyo.   -yyyyys   +yyyyys-` ./syyy/syyyys-``.+yyyyy:
           oyyyyyy+:-.`  .::.  oyyyyy/     +yyyyyo   `syyyyy/ `oyyyy/ +yyyyyo   :syyys.   .yyyyyy` /yyyyyy.  -yyyyyyyyyyyy:    `syyyyy.
           :yyyyyyyyyyyso/-`   oyyyyy/     +yyyyyo   `syyyyy:   .--`  +yyyyyo    `.--.-:/oyyyyyyy` syyyyyo   .syyyyyyyyyyyssssssyyyyyy/
            ./oyyyyyyyyyyyys/  oyyyyy/     +yyyyyo   `syyyyy:         +yyyyyo   ./osyyyyo/syyyyyy``syyyyyo    `-:-./yyyyyy+///////////-
           ://- .-:/osyyyyyyy: oyyyyy/     +yyyyyo   `syyyyy:         +yyyyyo  -yyyyo/.` :yyyyyyy` syyyyys`     -::+yyyyyy-      `----`
           oyyy/`     :yyyyyy: +yyyyys`   -syyyyyo   .syyyyy:        `oyyyyyo` +yyyyy+.`.syyyyyyy..syyyyyy+`   .syys+yyyyyo.    .oyyy+
           oyyyyys+++osyyyys/  -syyyyyyooss+yyyyyyssssyyyyyyyss/   :ssyyyyyyysssyyyys/sooyysyyyyyyyysoyyyyysoooyyys- -syyyyysoosyyys/
           /oo--/osyyyyso+:`    ./osyyso+-`.+++++++o+++++++++++/   -++++++++++++-/oyyyyso:``:oyyyys/` .:+syyyyys+-`    ./osyyyysa-->


		<!-- www.phpied.com/conditional-comments-block-downloads/ -->
		<!--[if IE]><![endif]-->

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<!-- site created by... -->
		<meta name="author" lang="en" content="Surface / Aaron Tolley" />
		<meta name="language" content="en" />
		<meta name="robots" content="index,follow" />
		<meta name="revisit-after" content="1 days" />
		<meta name="viewport" content="width=device-width; initial-scale=1;" />
		<meta http-equiv="imagetoolbar" content="false" />

		<!-- title, description, etc... -->
		<?php $desc = get_bloginfo('description'); ?>
		<title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?><?php echo (!empty($desc)) ? ' - ' . $desc : ''; ?></title>

		<script type="text/javascript">
			document.getElementsByTagName('html')[0].className += ' js';
		</script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/modernizr-2.0.6.js"></script>

		<link href="<?php bloginfo('template_directory'); ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="<?php bloginfo('template_directory'); ?>/apple-touch-icon.png" rel="apple-touch-icon" />
		
		<?php wp_head(); ?>
		
		<?php $categories = get_categories(array(
			'depth'				=> 0,
			'hide_empty'		=> 1,
			'taxonomy'			=> 'category',
			'exclude'			=> array(1),
		)); ?>
		<style type="text/css">
			<?php foreach($categories as $category): ?>
			<?php $colour = get_taxonomy_colour($category); ?>
			<?php if(!empty($colour)): echo "\r\n"; ?>
				aside .categories .cat-item-<?php echo $category->term_id; ?> a {color: <?php echo $colour; ?>;}
				aside .categories .cat-item-<?php echo $category->term_id; ?> a::before,
				aside .categories .cat-item-<?php echo $category->term_id; ?> a::after,
				#content-primary article.category-<?php echo $category->slug; ?> .entry-info {background-color: <?php echo $colour; ?>;}
			<?php endif; ?>
			<?php endforeach; ?>
		</style>
		<script type="text/javascript">
			var current_page = <?php echo max(1, get_query_var('paged')); ?>
		</script>
	</head>

	<body id="designandtype" <?php body_class($classes); ?>>
			
	<header>
		<h1 class="fn org"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>

		<nav role="navigation">
			<?php wp_nav_menu(array(
				'theme_location'	=> 'main',
				'container_class'	=> '',
				'container_id'		=> '',
				'menu_class'		=> '',
				'menu_id'			=> '',
				'exclude'			=> '' // 
			));  ?>
		</nav>

		<aside>
			<ul>
				<li class="twitter"><a href="http://twitter.com/design_hype">Twitter</a></li>
				<li class="facebook"><a href="http://www.facebook.com/pages/Design-Hype/174902239283513">Facebook</a></li>
				<li class="feed"><a href="<?php bloginfo('rss_url'); ?>">RSS Feed</a></li>
			</ul>
		</aside>
	</header>

	<div id="content" class="content">