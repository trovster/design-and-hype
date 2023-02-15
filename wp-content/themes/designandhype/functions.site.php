<?php

/*   
Component: Site
Description: Site specific functions. Most functions only need to be tweaked
Author: Surface / Trevor Morris
Author URI: http://www.madebysurface.co.uk
Version: 0.0.1
*/

/**
 * thumbnails
 * @desc	General thumbnail sizes. For individual post types, see relavant functions file
 * @hook	add_image_size
 */
add_theme_support('post-thumbnails');
set_post_thumbnail_size(800, 2000, false); // Normal post thumbnails
add_image_size('listing', 800, 260, false);
//add_theme_support('post-formats', array('link', 'image')); // http://codex.wordpress.org/Post_Formats

/**
 * template_is_page
 * @desc	Check what page is set
 * @global	object	$post
 * @param	string	$page
 * @return	boolean 
 */
function template_is_page($page) {
	global $post;
	
	if(is_search()) {
		return false; // no section is active
	}
	
	switch(strtolower($page)) {
		case 'homepage':
		case 'home':
			return is_front_page();
			break;
	}
	
	return false;
}

/**
 * site_new_excerpt_length
 * @hook	add_filter('excerpt_length');
 * @param	int $length
 * @return	int
 */
function site_new_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'site_new_excerpt_length');

/**
 * Add excerpts to pages
 */
add_post_type_support('page', 'excerpt');

/**
 * register_javascript_css
 * @desc	Register the JavaScript and CSS
 * @hook	add_action('template_redirect');
 */
function site_register_javascript_css() {
	$post_type = get_post_type();

	if(!is_admin()) {
		wp_deregister_script('jquery');
		wp_deregister_script('NextGEN');
		wp_deregister_script('thickbox');
		wp_deregister_script('shutter');
		wp_deregister_script('swfobject');
		wp_deregister_script('jquery-cycle');
		wp_dequeue_style('NextGEN');
		wp_dequeue_style('shutter');
		wp_dequeue_style('thickbox');
		wp_dequeue_style('ngg-slideshow');
		
		$cdn_url = str_replace(constant('WP_SITEURL'), constant('WP_CDN'), get_bloginfo('template_directory'));

		// javascript
		wp_register_script('jquery', $cdn_url . '/js/jquery/1.7.1.js', false, '1.7.1', true);
		wp_register_script('isotope', $cdn_url . '/js/jquery/plugin/isotope-1.5.07.js', false, '1.5.07', true);
		wp_register_script('lettering', $cdn_url . '/js/jquery/plugin/lettering-0.6.1.js', false, '0.6.1', true);
		wp_register_script('infinitescroll', $cdn_url . '/js/jquery/plugin/infinitescroll-2.0.js', false, '2.0', true);
		
		wp_register_script('app', $cdn_url . '/js/app.js', array('jquery'), '1.0', true);
		wp_register_script('site', $cdn_url . '/js/site.js', array('jquery', 'app'), '1.0', true);

		wp_enqueue_script('jquery');
		wp_enqueue_script('isotope');
		wp_enqueue_script('lettering');
		wp_enqueue_script('infinitescroll');

		wp_enqueue_script('app');
		wp_enqueue_script('site');

		// css
		wp_enqueue_style('font_open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,800,300', false, false);
		wp_enqueue_style('normalize', $cdn_url . '/css/normalize.css', false, false);
		wp_enqueue_style('screen', $cdn_url . ' /css/screen.css', false, false);
	}
}
add_action('template_redirect', 'site_register_javascript_css');

/**
 * site_body_classes
 * @desc	Add extra information to the body class
 * @hook	add_filter('body_class');
 * @param	array	$classes
 * @return	array
 */
function site_body_classes($classes) {
	global $post;
	
	$post_type	= template_get_post_type();
	$taxonomy	= 'category';

	if(!is_array($classes)) {
		$classes = (array) $classes;
	}
	
	if(is_search()) {
		 // no section is active
		return $classes;
	}
	if(is_404()) {
		 $classes[] = 'page';
	}

	if(is_page('contact')) {
		$classes[] = 'contact';
	}
	if(template_is_page('homepage')) {
		$classes[] = 'homepage';
	}

	return $classes;
}
add_filter('body_class', 'site_body_classes');

/**
 * register_navigation
 * @desc	Registering custom navigations with WordPress
 * @hook	add_action('init');
 */
function register_navigation() {
	register_nav_menus(array(
		'main'	=> __('Main Navigation'),
	));
}
add_action('init', 'register_navigation');

/**
 * extra_category_fields
 * @return	array
 */
function extra_category_fields() {
	return array(
		'category_colour'	=> 'Hex code for the colour of the link (eg #ff0011)'
	);
}

/**
 * extra_category_fields_add
 * @param	object	$tag 
 */
function extra_category_fields_add($tag) {
	$fields = extra_category_fields();
	
	foreach($fields as $field => $description) {
		$value	= get_metadata($tag->taxonomy, $tag->term_id, $field, true);
		$label	= str_replace('_', ' ', $field);
		$label	= ucwords($label);
		$id		= $field;
		$name	= 'custom_' . $field;
		$type	= 'text';
		
		echo '<div class="form-field">';
		echo '<label for="' . $id . '">' . $label . '</label>';
		echo '<input style="width:30%;" type="' . $type . '" id="' . $id . '" name="' . $name . '" value="' . $value . '"' . (!empty($class) ? ' class="' . $class . '"' : '') . ' />';
		echo '<p class="description">' . $description . '</p>';
		echo '</div>';
	}
}
add_action('category_add_form_fields', 'extra_category_fields_add');

/**
 * extra_category_fields_edit
 * @param	object	$tag 
 */
function extra_category_fields_edit($tag) {
	$fields		= extra_category_fields();
	
	foreach($fields as $field => $description) {
		$value	= get_option($tag->taxonomy . '_' . $tag->term_id . '_' . $field);
		$label	= str_replace('_', ' ', $field);
		$label	= ucwords($label);
		$id		= $field;
		$name	= $field;
		$type	= 'text';
		
		echo '<tr class="form-field">';
		echo '<th scope="row" valign="top"><label for="' . $id . '">' . $label . '</label></th>';
		echo '<td>';
		echo '<input style="width:30%;" type="' . $type . '" id="' . $id . '" name="custom_category[' . $name . ']" value="' . $value . '"' . (!empty($class) ? ' class="' . $class . '"' : '') . ' />';
		echo '<p class="description">' . $description . '</p>';
		echo '</td>';
		echo '</tr>';
	}
}
add_action('edit_category_form_fields', 'extra_category_fields_edit');

/**
 * save_extra_category_fields
 * @param	int	$term_id 
 */
function save_extra_category_fields($term_id) {
	if(!empty($_POST['custom_category'])) {
		$taxonomy = $_POST['taxonomy'];
		foreach($_POST['custom_category'] as $key => $value) {
			update_option($taxonomy . '_' . $term_id . '_' . $key, $value);
		}
	}
}
add_action('add_category', 'save_extra_category_fields');
add_action('edited_category', 'save_extra_category_fields');


/**
 * Create HTML list of categories, with Isotope data attribute
 * @uses Walker_Category
 */
class Walker_Category_Isotope_With_Colour extends Walker_Category {
	function start_el(&$output, $category, $depth, $args) {
		extract($args);
		
		// @amend Colour option
		$cat_colour = get_option($category->taxonomy . '_' . $category->term_id . '_category_colour');
		if(!empty($cat_colour)) {
			$cat_colour = strpos($cat_colour, '#') === 0 ? $cat_colour : '#' . $cat_colour;
		}

		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$link = '<a href="' . esc_attr( get_term_link($category) ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) )
			$link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s' ), $cat_name) ) . '"';
		else
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
			
		if(!empty($cat_colour)) {
//			$link .=  ' style="color: ' . $cat_colour . '"';
//			$link .=  ' data-color="' . $cat_colour . '"';
		}
		
		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( !empty($feed_image) || !empty($feed) ) {
			$link .= ' ';

			if ( empty($feed_image) )
				$link .= '(';

			$link .= '<a href="' . get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) . '"';

			if ( empty($feed) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$title = ' title="' . $feed . '"';
				$alt = ' alt="' . $feed . '"';
				$name = $feed;
				$link .= $title;
			}

			$link .= '>';

			if ( empty($feed_image) )
				$link .= $name;
			else
				$link .= "<img src='$feed_image'$alt$title" . ' />';

			$link .= '</a>';

			if ( empty($feed_image) )
				$link .= ')';
		}

		if ( !empty($show_count) )
			$link .= ' (' . intval($category->count) . ')';

		if ( !empty($show_date) )
			$link .= ' ' . gmdate('Y-m-d', $category->last_update_timestamp);

		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$class = 'cat-item cat-item-' . $category->term_id;
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
			}
			$output .=  ' class="' . $class . '"';
			$output .=  ' data-isotope-filter="' . $category->slug . '"'; // @amend
			if(!empty($cat_colour)) {
//				$output .=  ' data-color="' . $cat_colour . '"';
//				$output .=  ' style="color: ' . $cat_colour . '"';
			}
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}
}

/**
 * site_post_custom_fields
 * @desc	Assigning the custom fields
 * @global	int	$user_ID
 * @hook	add_action('admin_init');
 */
function site_post_custom_fields() {
	add_meta_box('specific', 'Post Specifics', 'site_post_custom_fields_specific', 'post', 'normal', 'high');
}
add_action('admin_init', 'site_post_custom_fields');

/**
 * site_post_custom_fields_specific
 * @global	object	$post
 */
function site_post_custom_fields_specific() {
	global $post;

	$custom			= get_post_custom($post->ID);
	$fields			= array('url', 'via');

	foreach($fields as $field) {
		$value	= template_get_custom_field($custom, $field);
		$label	= str_replace('_', ' ', $field);
		$label	= ucwords($label);
		$label	= $field === 'url' ? strtoupper($label) : $label;
		$label	= $field === 'via' ? strtoupper($label) : $label;
		$name	= 'custom_' . $field;
		$id		= $field;
		echo template_custom_field($id, $name, $label, $value);
	}
}

/**
 * site_get_posts_nav_link
 * @global	object	$wp_query
 * @param	string	$sep
 * @param	string	$prelabel
 * @param	string	$nxtlabel
 * @return	string 
 */
function site_get_posts_nav_link($sep = '', $prelabel = '', $nxtlabel = '') {
	global $wp_query;

	$return = '';

	if(!is_singular()) {
		$defaults = array(
			'sep'		=> ' &#8212; ',
			'prelabel'	=> __('&laquo; Previous Page'),
			'nxtlabel'	=> __('Next Page &raquo;'),
		);
		$args = array_filter(compact('sep', 'prelabel', 'nxtlabel'));
		$args = wp_parse_args($args, $defaults);

		$max_num_pages = $wp_query->max_num_pages;
		$paged = get_query_var('paged');

		//only have sep if there's both prev and next results
		if($paged < 2 || $paged >= $max_num_pages) {
			$args['sep'] = '';
		}

		if($max_num_pages > 1) {
			$previous	= get_previous_posts_link($args['prelabel']);
			$next		= get_next_posts_link($args['nxtlabel']);
			$separator	= preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $args['sep']);
			
			$return .= !empty($previous) || !empty($next) ? '<ul class="previous-next">' : '';
			$return .= !empty($previous) ? '<li class="previous">' . $previous . '</li>' : '';
			//$return .= $separator;
			$return .= !empty($next) ? '<li class="next">' . $next . '</li>' : '';
			$return .= !empty($previous) || !empty($next) ? '</ul>' : '';
		}
	}
	
	return $return;
}

/**
 * custom_show_extra_profile_fields
 * @param	object	$user 
 */
function custom_show_extra_profile_fields($user) {
	$extra_fields	= array(
		'custom_twitter'	=> array(
			'label'			=> 'Twitter',
			'description'	=> 'Please enter your Twitter username <strong>excluding</strong> the @ symbol.'
		)
	);
	?>

	<h3>Extra profile information</h3>

	<table class="form-table">
		<?php foreach($extra_fields as $field => $info): ?>
		<tr>
			<th><label for="<?php echo $field; ?>"><?php echo $info['label']; ?></label></th>
			<td>
				<input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo esc_attr(get_the_author_meta($field, $user->ID)); ?>" class="regular-text" /><br />
				<span class="description"><?php echo $info['description']; ?></span>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php
}
add_action('show_user_profile', 'custom_show_extra_profile_fields');
add_action('edit_user_profile', 'custom_show_extra_profile_fields');

/**
 * custom_save_extra_profile_fields
 * @param	int	$user_id
 * @return	mixed 
 */
function custom_save_extra_profile_fields( $user_id ) {

	if(!current_user_can('edit_user', $user_id)) {
		return false;
	}

	// authentication passed, save data
	// cycle through each posted meta item and save
	// by default only saves custom fields which are prefixed with custom_
	foreach($_POST as $key => $value) {
		if(strpos($key, 'custom_') !== false) {
			update_usermeta($user_id, $key, $value);
		}
	}
}
add_action('personal_options_update', 'custom_save_extra_profile_fields');
add_action('edit_user_profile_update', 'custom_save_extra_profile_fields');

/**
 * add_attachments_to_content
 * @desc	Modify the_content to include attachments
 *			which means it also appears in RSS feed
 * @param	string	$content
 * @return	string 
 */
function add_attachments_to_content($content) {
	if(function_exists('attachments_get_attachments')) {
		$attachments_html	= '';
		$attachments		= attachments_get_attachments();
		$total				= count($attachments);
	
		$attachments_html .= '<ul style="list-style: none; padding: 0; margin: 0;">' . "\r\n";
		
		if(has_post_thumbnail()) {
			$attachments_html .= '<li>' . get_the_post_thumbnail(null, 'post-thumbnail', 'itemprop=image') . '</li>' . "\r\n";
		}
		
		for($i = 0; $i < $total; $i++) {
			$attachments_html .= '<li>' . wp_get_attachment_image($attachments[$i]['id'], 'post-thumbnail') . '</li>' . "\r\n";
		}
		
		$attachments_html .= '</ul>' . "\r\n";
		
		$content .= $attachments_html;
	}
	
	return $content;
}
//add_filter('the_content', 'add_attachments_to_content');
add_filter('the_content_feed', 'add_attachments_to_content');
add_filter('the_excerpt_rss', 'add_attachments_to_content');