<?php

/**
 * Put any functions specific to this theme here
 */

/**
 * Make any ACF named `icon` automatically populate with the available SVGs
 */
add_filter('acf/load_field/name=icon', function($field){
  $field['choices'] = [
    'bar-graph'     => 'Bar Graph',
    'trademark'     => 'Trademark',
    'website'       => 'Website',
    'paper-layout'  => 'Paper Layout',
    'video'         => 'Video', 
    'identity-card' => 'Identity Card', 
    'open-box'      => 'Open Box',
    'globe'         => 'Globe',
    'briefcase'     => 'Briefcase'
  ];
  return $field;
});

/*
   Connect ajax load more to relevanssi
   https://connekthq.com/plugins/ajax-load-more/docs/filter-hooks/#alm_query_args
*/
function my_alm_query_args_relevanssi($args){
   $args = apply_filters('alm_relevanssi', $args);
   return $args;
}
add_filter( 'alm_query_args_relevanssi', 'my_alm_query_args_relevanssi');

/**
 * Adds custom padding to blocks for easy admin control
 */
function build_custom_padding_str($padding = []){
  $str = '';

  if(isset($padding['desktop_top'])){
    $str .= "--desktop-padding-top:{$padding['desktop_top']}px;";
  }
  if(isset($padding['desktop_bottom'])){
    $str .= "--desktop-padding-bottom:{$padding['desktop_bottom']}px;";
  }
  if(isset($padding['mobile_top'])){  
    $str .= "--mobile-padding-top:{$padding['mobile_top']}px;";
  }
  if(isset($padding['mobile_bottom'])){  
    $str .= "--mobile-padding-bottom:{$padding['mobile_bottom']}px;";
  }

  return $str;
}

/**
 * Redirect tag links to a generic search
 */
add_filter('term_link', function($url, $term, $taxonomy){
  return get_home_url() . "?s={$term->slug}";
}, 10, 3);

/**
 * Filter tags specifically on the search page
 */
// add_filter('pre_get_posts', function($query){
//   if(!empty($_GET['type']) && $_GET['type'] == 'tag' && !is_admin() && is_search() && !empty($query->query['s'])){
//     $tags = get_terms('post_tag');
//     $found_tag = array_filter($tags, function($tag) use ($query){ return $tag->slug == $query->query['s']; });

//     if($found_tag){
//       $found_tag = array_values($found_tag)[0];
//       unset($query->query['s']);
//       $query->query_vars['tag__in'] = [ $found_tag->term_id ];
//     }
//   }

//   return $query;
// });

/**
 * Checks if a color is dark or not
 */
function is_color_dark($hex){
  $rgb = HTMLToRGB($hex);
  $hsl = RGBToHSL($rgb);
  return $hsl->lightness < 150;
}

function HTMLToRGB($htmlCode) {
  if($htmlCode[0] == '#')
    $htmlCode = substr($htmlCode, 1);

  if (strlen($htmlCode) == 3)
  {
    $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
  }

  $r = hexdec($htmlCode[0] . $htmlCode[1]);
  $g = hexdec($htmlCode[2] . $htmlCode[3]);
  $b = hexdec($htmlCode[4] . $htmlCode[5]);

  return $b + ($g << 0x8) + ($r << 0x10);
}

function RGBToHSL($RGB) {
  $r = 0xFF & ($RGB >> 0x10);
  $g = 0xFF & ($RGB >> 0x8);
  $b = 0xFF & $RGB;

  $r = ((float)$r) / 255.0;
  $g = ((float)$g) / 255.0;
  $b = ((float)$b) / 255.0;

  $maxC = max($r, $g, $b);
  $minC = min($r, $g, $b);

  $l = ($maxC + $minC) / 2.0;

  if($maxC == $minC)
  {
    $s = 0;
    $h = 0;
  }
  else
  {
    if($l < .5)
    {
      $s = ($maxC - $minC) / ($maxC + $minC);
    }
    else
    {
      $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
    }
    if($r == $maxC)
      $h = ($g - $b) / ($maxC - $minC);
    if($g == $maxC)
      $h = 2.0 + ($b - $r) / ($maxC - $minC);
    if($b == $maxC)
      $h = 4.0 + ($r - $g) / ($maxC - $minC);

    $h = $h / 6.0; 
  }

  $h = (int)round(255.0 * $h);
  $s = (int)round(255.0 * $s);
  $l = (int)round(255.0 * $l);

  return (object) Array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
}

class MegaMenuWalker extends Walker_Nav_Menu {
  // they dont want this anymore...
  // function start_lvl(&$output, $depth = 0, $args = []){
  //   $output .= '<div class="sub-menu"><div class="wrapper"><ul>';
  // }

  // function end_lvl(&$output, $depth = 0, $args = []){
  //   $output .= '</ul></div></div>';
  // }  
}

/**
 * Create SharpSpring Connection info
 * Not written by us - this was given to us as a copy/paste in
 */
add_action( 'gform_after_submission', 'post_to_third_party', 10, 2 );
function post_to_third_party( $entry, $form ){
  $body = [];
  
  function dupeCheck($fieldName, $bodyData) {
    $cleanLabel = substr(preg_replace("/[^a-zA-Z0-9]+/", "", $fieldName), 0, 24);
    for ($x = 0; $x <= 20; $x++) {
      if(array_key_exists($cleanLabel, $bodyData)) {
        $cleanLabel = $cleanLabel . $x;
      } else { 
        break; 
      }
    }
    return $cleanLabel;
  }

  $formFields = $form['fields'];

  foreach($formFields as $formField):
    if($formField['label'] == 'sharpspring_base_uri') {
      $base_uri = rgar( $entry, $formField['id']);
      $sendToSharpSpring = true;
    } elseif($formField['label'] == 'sharpspring_endpoint') {
      $post_endpoint = rgar( $entry, $formField['id']);
    } elseif($formField['type'] == 'multiselect') {
      $fieldNumber = $formField['id'];
      $fieldLabel = dupeCheck($formField['label'], $body);
      $tempValue = rgar ( $entry, strval($fieldNumber) );
      $trimmedValue = str_replace('[', '', $tempValue);
      $trimmedValue = str_replace(']', '', $trimmedValue);
      $trimmedValue = str_replace('"', '', $trimmedValue);
      $body[preg_replace("/[^a-zA-Z0-9]+/", "", $fieldLabel)] = $trimmedValue;
    } elseif($formField['inputs']) {
      if($formField['type'] == 'checkbox') {
        $fieldNumber = $formField['id'];
        $fieldLabel = dupeCheck($formField['label'], $body);
        $checkBoxField = GFFormsModel::get_field( $form, strval($fieldNumber) );
        $tempValue = is_object( $checkBoxField ) ? $checkBoxField->get_value_export( $entry ) : '';
        $trimmedValue = str_replace(', ', ',', $tempValue);
        $body[preg_replace("/[^a-zA-Z0-9]+/", "", $fieldLabel)] = $trimmedValue;
      } elseif($formField['type'] == 'time') {
        $fieldNumber = $formField['id'];
        $fieldLabel = dupeCheck($formField['label'], $body);
        $body[preg_replace("/[^a-zA-Z0-9]+/", "", $fieldLabel)] = rgar( $entry, strval($fieldNumber) );
      } else {
        foreach($formField['inputs'] as $subField):
          $fieldLabel = dupeCheck($subField['label'], $body);
          $fieldNumber = $subField['id'];
          $body[preg_replace("/[^a-zA-Z0-9]+/", "", $fieldLabel)] = rgar( $entry, strval($fieldNumber) );
        endforeach;
      } 
    } else {
      $fieldNumber = $formField['id'];
      $fieldLabel = dupeCheck($formField['label'], $body);
      $body[preg_replace("/[^a-zA-Z0-9]+/", "", $fieldLabel)] = rgar( $entry, strval($fieldNumber) );
    };
  endforeach;
  $body['form_source_url'] = $entry['source_url'];
  $body['trackingid__sb'] = $_COOKIE['__ss_tk']; //DO NOT CHANGE THIS LINE... it collects the tracking cookie to establish tracking
  $post_url = $base_uri . $post_endpoint;
  if($sendToSharpSpring) {
    $request = new WP_Http();
    $response = $request->post( $post_url, array( 'body' => $body ) );
  }
}