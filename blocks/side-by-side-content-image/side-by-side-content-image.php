<?php

/**
 * @param $content - content to display
 * @param $image - WP image ID to display
 * @param $fullbleed_image - whether the image should break the container and go full bleed or not
 * @param $image_position - should the image be on the left or right? 
 * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
 */

  if(empty($content) || empty($image)){
    return false;
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes = !empty($custom_padding) ? ' custom-padding' : '';  
?>
<section class="
  side-by-side-content-image 
  side-by-side-content-image--image-position-<?php echo $image_position; ?>
  side-by-side-content-image--<?php echo !empty($fullbleed_image) ? 'fullbleed' : 'no-bleed'; ?>
  <?= $classes ?>
  will-animate
" data-animation="fade-from-bottom" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <div class="side-by-side-content-image--content editor-content">
      <?php echo $content; ?>
    </div>
    <div class="side-by-side-content-image--image">
      <?php echo wp_image($image); ?>
    </div>
  </div>
</section>