<?php 
  /**
   * @param $quote | required | WYSYWYG html
   * @param $attribution_line_1 | optional | string html to display
   * @param $attribution_line_2 | optional | string html to display
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($quote)){
    return false;
  }

  if(empty($classes)){
    $classes = '';
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';
?>
<div class="testimonial-block <?php echo $classes; ?> will-animate" data-animation="fade-from-bottom" style="<?= $custom_padding ?>">
  <div class="testimonial-block--inner">
    <div class="wrapper">
      <div class="editor-content text-center">
        <blockquote>
          <div class="testimonial-block--quote">
            <?php echo $quote; ?>
          </div>
          <?php if(!empty($attribution_line_1) || !empty($attribution_line_2)): ?>
            <footer class="testimonial-block--attribution">
              <p>
                <?php echo !empty($attribution_line_1) ? '<span class="testimonial-block--attribution--line2">'.$attribution_line_1.'</span>' : ''; ?>
                <?php echo !empty($attribution_line_2) ? $attribution_line_2 : ''; ?>
              </p>
            </footer>
          <?php endif; ?>
        </blockquote>
      </div>
    </div>
  </div>
</div>