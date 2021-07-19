<?php 
  /**
   * @param $title | optional | string html
   * @param $content | optional | WYSIWYG string html
   * @param $image | required | WP Image ID
   * @param $color | required | string hex color
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($image)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }

  $color = !empty($color) ? $color : (get_field('color') ?: '#002E5D');

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';  
?>
<div class="client-divider will-animate <?php echo $classes; ?>" style="<?= $custom_padding ?> <?= "--color:$color" ?>" data-animation="fade-from-bottom">
  <div class="client-divider--inner">
    <div class="wrapper">
      <div class="client-divider--content bg-<?php echo $color; ?>">
        <?php if(!empty($title) && !empty($content)): ?>
        <div class="editor-content">
          <?php echo !empty($title) ? '<h2 class="client-divider--title">'.$title.'</h2>' : ''; ?>
          <?php echo !empty($content) ? $content : ''; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="client-divider--image">
        <?php echo wp_image($image); ?>
      </div>
    </div>
  </div>
</div>