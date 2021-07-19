<?php
  /**
   * This creates an icon with a lumpy circle behind it
   * When a parent element with a class of `.morph-icon--hover` is hovered, the lumpy circle goes smooth
   * @param $icon - slug to render_svg
   */
?>
<div class="morph-icon">
  <div class="morph-icon--circle"></div>
  <?= render_svg($icon); ?>
</div>