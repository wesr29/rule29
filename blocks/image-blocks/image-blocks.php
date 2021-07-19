<?php 
  /**
   * @param $columns | required | radio, col class
   * @param $caption_color_override | string color
   * @param $blocks | required | array
   * @param $block['image'] | required | WP Image ID
   * @param $block['caption'] | optional | WYSIWYG html
   * @param $block['caption_position'] | optional | radio, left, right, or bottom
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($columns) && empty($blocks)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }

  $color = get_field('color') ?: '#002E5D'; //default to dark blue

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';  

  if(!empty($caption_color_override)){
    $color = $caption_color_override;
  }

  //fixing bad ACF data...
  $columns = str_replace('col-sm-4', 'col-md-4 col-sm-6', $columns);

  $text_color_class = is_color_dark($color) ? 'text-white' : 'text-dark-blue';
?>
<div class="image-blocks <?php echo $classes; ?> will-animate" data-animation="fade-from-bottom" style="<?= $custom_padding ?> <?= "--color:$color" ?>">
  <div class="wrapper">
    <div class="row">
      <?php if(!empty($blocks)): ?>
        <?php foreach($blocks as $block): ?>
          <div class="col <?php echo $columns; ?> col-xs-12 image-blocks--block relative <?php echo !empty($block['caption']) ? 'has-caption': ''; ?>">
            <div class="image-blocks--inner">
              <?php echo wp_image($block['image'], 'full', ['class' => 'image-blocks--image']); ?>
              <?php if(!empty($block['caption'])): ?>
                <div class="image-blocks--caption editor-content desktop-only <?php echo $block['caption_position']; ?> <?= $text_color_class ?>">
                  <?php echo $block['caption']; ?>
                </div>
              <?php endif; ?>
            </div>
            <?php if(!empty($block['caption'])): ?>
                <div class="image-blocks--caption editor-content mobile-only <?php echo $block['caption_position']; ?> <?= $text_color_class ?>">
                  <?php echo $block['caption']; ?>
                </div>
              <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif ?>
    </div>
  </div>
</div>