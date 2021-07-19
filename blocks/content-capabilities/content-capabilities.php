<?php 
  /**
   * @param $content | required | string html to display
   * @param $capabilities | required | string html to display
   * @param $color | required | color
   * @param $classes | optional | string of extra classes for this section
   */

  if(empty($content) && empty($capabilities)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }

?>
<div class="content-capabilities <?php echo $classes; ?>">
  <div class="wrapper">
    <div class="content-capabilities--capabilities" style="background-color: <?php echo $color; ?>">
      <h2 class="content-capabilities--capabilities--title">Capabilities</h2>
      <?php echo $capabilities; ?>
    </div>
    <?php block('content', [
      'classes' => 'no-top-padding',      
      'columns' => 'one',
      'content' => $content,
      'content_2' => '',
      'content_3' => '',
    ]);
    ?>
  </div>
</div>