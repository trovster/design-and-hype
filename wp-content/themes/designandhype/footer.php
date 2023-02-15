
	<!-- end of div #content -->
	</div>
	
	<?php wp_footer(); ?>

	<?php
	if(function_exists('yoast_analytics') && defined('ENVIRONMENT') && constant('ENVIRONMENT') === 'live') {
		yoast_analytics();
	}
	?>
	
	<!-- social media buttons -->
	<script type="text/javascript">
		window.___gcfg = {lang: 'en-GB'};

		(function(d, s) {
			var po = d.createElement(s), el; po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			el = d.getElementsByTagName(s)[0]; el.parentNode.insertBefore(po, el);
		})(document, 'script');
	</script>
	
	<script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
	
	<div id="fb-root"></div>
	<script type="text/javascript">
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=211175705609609";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	</body>
</html>