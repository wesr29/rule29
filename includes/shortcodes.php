<?php

//adds a class of button to a link
add_shortcode('button', function ($args, $content) {
  $color = !empty($args['color']) ? $args['color'] : 'normal';
  $style = !empty($args['style']) ? $args['style'] : 'normal';
  return str_replace('<a ', "<a class='button button--{$color} button--{$style}' ", $content);
});

//returns current year
add_shortcode('year', function () {
  return date('Y');
});

//Adds desktop/mobile only classes
add_shortcode('desktop-only', function ($args, $content) {
  return '<span class="desktop-only">' . do_shortcode($content) . '</span>';
});
add_shortcode('mobile-only', function ($args, $content) {
  return '<span class="mobile-only">' . do_shortcode($content) . '</span>';
});

//adds click to tweet link
add_shortcode('tweet', function($args){
  if(empty($args['quote'])){
    return false;
  }
  $currentPostID = get_the_id();
  if(is_home()){
    $currentPostID = get_option('page_for_posts');
  }
  $quote = $args['quote'];
  $linkQuote = urlencode(html_entity_decode($args['quote'], ENT_COMPAT, 'UTF-8'));
  $link = get_the_permalink($currentPostID);
  $html = "<a href='https://twitter.com/intent/tweet?url=$link&text=$linkQuote&via=rule29&related=rule29' rel='noopener noreferrer' target='_blank' class='tweet-this'>";
    $html .= "<p class='tweet-this--quote'>$quote</p>";
    $html .= "<div class='tweet-this--bird-button'>".render_svg('twitter')." Tweet This</div>";
    $html .= "<div class='tweet-this--desktop-button'>".render_svg('share')."</div>";
  $html .= "</a>";

  return $html;
});

//returns site map shortcode
add_shortcode('site-map', function ($args, $content) {

  if (!empty($args['showall'])) {
  	$html = '<nav class="site-map editor-content">';

  	$post_types_we_want = [];
  	$post_types = get_post_types();

  	// EXCLUDES
		$exclude_list = [
			'attachment'
		];

		foreach ($exclude_list as $key => $exclude) {
			unset($post_types[$exclude]);
		}

		// map in data
		foreach ($post_types as $key => $post_type) {

			$post_type = get_post_type_object($post_type);

			if ($post_type->publicly_queryable || $post_type->name == 'page') {
				$post_types_we_want[] = [
					'title' => $post_type->label,
					'posts' => get_posts([
						'post_type' => $post_type->name,
						'numberposts' => -1,
						'orderby' => 'post_title',
						'order' => 'ASC'
					])
				];
			}
		}
	
		foreach($post_types_we_want as $post_type){
			if (!empty($post_type['posts'])) {
		  	$html .= '<h2>'.$post_type['title'].'</h2>';
		  		$html .= '<ul>';
		  		foreach($post_type['posts'] as $single){
				    $html .= '<li><a href="'.get_the_permalink($single).'">'.$single->post_title.'</a></li>';
			  	}
		  		$html .= '</ul>';
		  }
		}
		$html .= '</nav>';

  } else {
    $html =  wp_nav_menu([
      'theme_location'  => 'site-map',
      'container'       => false,
      'echo'      => false,
    ]);
  }

  return $html;
});