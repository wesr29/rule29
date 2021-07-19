<?php 
  /**
   * @param $slides | array of image ids
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($slides)){
    return false;
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes = !empty($custom_padding) ? ' custom-padding' : '';
?>
<div class="gallery-slider will-animate <?php echo $classes; ?>" data-animation="fade-from-bottom" style="<?= $custom_padding ?>">
  <div class="gallery-slider--wrapper wrapper wrapper--med-small">
    <div class="gallery-slider--slides">
      <?php foreach($slides as $key => $slide): ?>
      <div class="gallery-slider--slide">
        <?php echo !empty($slide) ? wp_image($slide, 'full', [ 'class' => 'gallery-slider--photo not-lazy' ]) : ''; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <?php if(count($slides) > 1): ?>
    <div class="pagination-slider--wrapper wrapper wrapper--med-small">
      <div class="pagination-slider--slides">
        <?php foreach($slides as $key => $slide): ?>
        <div class="pagination-slider--slide" data-index="<?php echo $key; ?>">
          <div class="pagination-slider--slide--inner">
            <?php echo !empty($slide) ? wp_image($slide, 'full', [ 'data-index' => $key, 'class' => 'not-lazy pagination-slider--photo' ]) : ''; ?>
          </div>            
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  
</div>