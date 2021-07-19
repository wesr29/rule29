<?php
  /**
   * @param $content - string HTML
   */

  if(empty($content)){
    return false;
  }
?>
<section class="notification p-t-20 p-b-20" style="display:none">
  <div class="wrapper flex justify-center">
    <div class="editor-content"><?= $content ?></div>
    <button class="notification--close x"><span class="sr-only">Close Notification</span>&times;</button>
  </div>
</section>