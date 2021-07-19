<?php 
  /**
   * @param $columns | required | radio, how many columns
   * @param $content | required | string html to display
   * @param $content_2 | optional | string html to display
   * @param $content_3 | optional | string html to display
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   * @param $text_color | optional | string color class name
   * @param $background_color | optional | string color class name
   */

  if(empty($content)){
    return false;
  }

  if(empty($classes)){
    $classes = '';
  }
  if(empty($columns)){
    $columns = 'one';
  }

  if(!empty($text_color)){
    $classes .= ' text-' . $text_color;
  }

  if(!empty($background_color)){
    $classes .= ' bg-' . $background_color;
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';
?>
<div class="content-block will-animate <?php echo $classes; ?> <?php echo $columns; ?>-column" data-animation="fade-from-bottom" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <?php if($columns == 'one'): ?>
    <div class="editor-content">
      <?php echo $content; ?>
    </div>
    <?php elseif($columns == 'two' && !empty($content_2)): ?>
    <div class="row">
      <div class="col-sm-6 col-xs-12 col">
        <div class="editor-content">
          <?php echo $content; ?>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12 col">
        <div class="editor-content">
          <?php echo $content_2; ?>
        </div>
      </div>
    </div>
    <?php elseif($columns == 'three' && !empty($content_2) && !empty($content_3)): ?>
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12 col">
        <div class="editor-content">
          <?php echo $content; ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 col">
        <div class="editor-content">
          <?php echo $content_2; ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 col">
        <div class="editor-content">
          <?php echo $content_3; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>