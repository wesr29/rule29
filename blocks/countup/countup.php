<?php 
  /**
   * @param $stats | required | array of number and text
   * @param $stat['number'] | required | number
   * @param $stat['text'] | optional | string html to display
   * @param $classes | optional | string of extra classes for this section
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($stats)){
    return false;
  }

  if(empty($classes)){
    $classes = '';
  }
  
  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';
?>
<div class="countup-block <?php echo $classes; ?>" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <div class="row">
      <?php foreach($stats as $stat): ?>
        <div class="col col-sm-4 col-xs-12 countup-block--stat text-center">
          <div class="editor-content">
            <h2 class="animate data-point-number flex justify-center">
              <?= !empty($stat['prefix']) ? $stat['prefix'] : '' ?>
              <span class="data-point-number--actual" data-number="<?php echo $stat['number']; ?>"></span>
              <?= !empty($stat['suffix']) ? $stat['suffix'] : '' ?>
            </h2>
            <?php echo !empty($stat['text']) ? '<p>'.$stat['text'].'</p>' : ''; ?>  
          </div>          
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>