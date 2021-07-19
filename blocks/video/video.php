<?php
  /**
   * TODO: Update/fix this block with styles and JS
   *
   * @param $classes - string - extra classes to add
   * @param $image - int - WP image ID to overlay
   * @param $video - string - Youtube or Vimeo URL
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */
  
  if(empty($video)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';
?>
<div class="video <?php echo $classes; ?> will-animate" data-animation="fade-from-bottom" style="<?= $custom_padding ?>">
  <div class="wrapper relative">
    <?php if(!empty($image)): ?>
      <?php echo wp_get_attachment_image($image, 'large', '', ['class' => 'video--image-overlay']); ?>
      <div class="video--play-button morph-icon--hover">
        <div><?php block('morph-icon', [ 'icon' => 'play' ]); ?></div>
      </div>      
    <?php endif; ?>
    <iframe src="<?php echo convert_video_link_for_iframe($video); ?>" allow="autoplay" class="video--iframe"></iframe>
  </div>
  
</div>