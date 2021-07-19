<?php

/**
 * These are helpful functions to use within the theme
 * They will be used all the time, so get to know them all
 */

/**
 * Returns an string of html5 picture using next gen images when available
 * @param $name - name of image located in public/images/ WITHOUT the extention
 * @param $args - alt/title/etc to add to the image
 */
function theme_image($name, $args = []){
  if(!function_exists('get_image_source')){
    function get_image_source($name, $ext){
      return file_exists(get_template_directory() . '/public/images/' . $name . '.' . $ext) ? 
                get_template_directory_uri() . '/public/images/' . $name . '.' . $ext :
                false;
    }
  }

  $found_images = array_filter([
    'webp' => get_image_source($name, 'webp'),
    'jpeg' => get_image_source($name, 'jpeg'),
    'jpg'  => get_image_source($name, 'jpg'),
    'png'  => get_image_source($name, 'png'),
    'gif'  => get_image_source($name, 'gif'),
    'svg'  => get_image_source($name, 'svg'),
  ]);

  $extra_tags = '';
  foreach($args as $key => $value){
    $extra_tags .= " $key='$value'";
  }

  $html = '<picture>';
    foreach($found_images as $ext => $image){
      //jpg svg png work on any browser, so make those default
      if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'svg' || $ext == 'gif'){
        $html .= "<img srcset='$image'$extra_tags>";
      } else {
        $html .= "<source srcset='$image' type='image/$ext'>";
      }
    }
  $html .= '</picture>';

  return $html;
}

/**
 * This is just a for wp_get_attachment_image that makes it html5 and next gen images
 */
function wp_image($image_id, $size = 'large', $args = []){
  return convert_wp_image_html_to_html5(wp_get_attachment_image($image_id, $size, '', $args));
}

/**
 * Converts a youtube or vimeo video to work with iframes
 * Vimeo example: https://player.vimeo.com/video/ID
 * Youtube example: https://www.youtube.com/embed/ID
 * @param $url 
 * @param $options | these are things like mute, background, loop, etc.
 */
function convert_video_link_for_iframe($url, $options = []){
  if(strpos($url, 'vimeo')){
    $url = 'https://player.vimeo.com/video/' . preg_replace('/[^0-9]/', '', $url);
  }

  if(strpos($url, 'you')){
    preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $id);
    $id = !empty($id[0]) ? $id[0] : null;
    $url = 'https://www.youtube.com/embed/' . $id;
  }


  if(!empty($options)){
    $url .= '?';
    foreach($options as $key => $value){
      if($key == 'loop' && $value == 1 && !empty($id)){
        $url .= 'playlist=' . $id . '&';
      }

      $url .= $key . '=' . $value . (count($options) - 1 !== $key ? '&' : '');
    }
  }

  return trim($url);
}

/**
 * This returns a single category, it prioritizes yoast's primary categories, if one isn't set, it just grabs the first one.
 * @param $id - post ID
 * @param $term - term_slug (if using this for a custom taxonomy)
 */
function get_primary_category($id, $term = 'category'){
  $primary_cat = '';

  $category = get_the_terms($id, $term);
  // If post has a category assigned.
  if ($category){
    if ( class_exists('WPSEO_Primary_Term') ) {
      // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
      $wpseo_primary_term = new WPSEO_Primary_Term( $term, $id );
      $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
      $term = get_term( $wpseo_primary_term );

      if (is_wp_error($term)) {
        // Default to first category (not Yoast) if an error is returned
        $primary_cat = $category[0];
      } else {
        // Yoast Primary category
        $primary_cat = $term;
      }
    } else {
      // Default, display the first category in WP's list of assigned categories
      $primary_cat = $category[0];
    }
  }

  return $primary_cat;
}

// converts a phone number to a tel: link
function phone_to_link($num){
  $number_only = preg_replace('/[^0-9]/', '', $num);
  return '<a class="link" href="tel:'.$number_only.'">'.$num.'</a>';
}


// converts email address to a mailto: link
function email_to_link($email){
  return '<a class="link" href="mailto:'. antispambot( $email ).'">'.esc_html( antispambot( $email ) ) .'</a>';
}


/**
  * converts ACF link array to pretty 
  * @param ACF link array 
*/
function acf_link($link, $classes='') {
  if(empty($link['target'])){
    $target = '_self';
  }
  else{
    $target = $link['target'];
  }
  if(empty($link['title'])){
    $title = "Learn More";
  }
  else{
    $title = $link['title'];
  }
  return '<a class="'.$classes.'" target="'.$target.'" href="'.$link['url'].'">'.$title.'</a>';
}

/**
 * get an arbritrary numbers of words from some text
 * @param $text - string to get some words from
 * @param $count - int | # of words you want
 * @return String of text
 */
function get_words($text, $count){
  return implode(' ', array_slice(explode(' ', $text), 0, $count - 1));
}

/**
 * Wraps a PHP variable to pass to vue via a prop
 * @param $var
 * @return escaped JSON var
 */
function vue_data($var){
  return htmlspecialchars(json_encode($var), ENT_QUOTES, 'UTF-8', true);
}


/**
  * returns a WP page obj by template
  * @param $template (string) - which template are we looking for
  * @param $id_only (bool)    - should we return the WP obj or the ID?
  * @param $return_all (bool) - should we return all or just the first?
  */  
function get_page_by_template($template, $id_only = false, $return_all = false){
  $pages = get_posts([
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'meta_value' => $template,
    'numberposts' => -1
  ]);
  
  if(empty($pages)){
    return false;
  } else {
    if(!$id_only && $return_all){
      return $pages;
    } else if($id_only && $return_all){
      $page_ids = [];
      foreach($pages as $page){
        $page_ids[] = $page->ID;
      }
      return $page_ids;
    } else if($id_only && !$return_all){
      return $pages[0]->ID;
    } else {
      return $pages[0];
    }
  }
}

/**
 * Checks if we are on devbucket... thats it!
 */
function is_devbucket(){
  return !empty($_SERVER['SERVER_NAME']) && strpos($_SERVER['SERVER_NAME'], 'devbucket.net') !== false;
}